<?php

namespace App\Http\Controllers;

use Throwable;
use Validator;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\KategoriProduk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;

class MenuAdminController extends Controller
{
    public function add_kategori_produk(Request $request){
       
        // Periksa apakah kategori sudah ada sebelumnya
        $Kategori = KategoriProduk::where('toko_id', $request->toko_id)->where('kategori', $request->kategori)->first();
        
        KategoriProduk::create([
            'kategori' => $request->kategori,
            'status' => 'active',
            'toko_id' => $request->toko_id,
        ]);
        
        return redirect()->route('admin.kategori-produk')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function profil_toko(){
        $id = Auth::user()->id;
        $toko = Toko::with('user')->where('user_id',$id)->first();
        // $provinsi = Province::all();
        // $getProvinsibyToko = DB::table('tokos')
        // ->leftJoin('provinces','tokos.province_id','=','provinces.id')
        // ->select('provinces.provinsi')
        // ->where('tokos.user_id',$id)
        // ->first();
        // $getCitybyToko = DB::table('tokos')
        // ->leftJoin('cities','tokos.city_id','=','cities.id')
        // ->select('cities.city_name')
        // ->where('tokos.user_id',$id)
        // ->first();
        $encryptId = Crypt::encrypt($toko->user->id);
        return view('profile/profil_toko',compact('toko','encryptId'));
    }

    public function formedit_akun_toko($encryptId){
        $userId = Crypt::decrypt($encryptId);
        $toko = Toko::with('user')->where('user_id',$userId)->first();
        return view('profile/edit',compact('toko'));
    }

    public function search_map($value){
        $data_request = Http::withHeaders([
            'Authorization' => 'biteship_test.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiZGV2V2luSWQiLCJ1c2VySWQiOiI2NWFhMTVhMDc4YTI1NzAwMTJkMTFiOWEiLCJpYXQiOjE3MDU3MjU4NDB9.0rxl8ArGyldLWj3m0puYKg6Crbnclr57d8fLMKN_C3Y'
        ])->get('https://api.biteship.com/v1/maps/areas?countries=ID&input='.$value.'&type=single');
        // return response()->json($data_request);
        return json_decode($data_request);
    }

    public function update_akun_toko(Request $request){
       

        $validator = Validator::make($request->all(), [
            'password' => 'nullable|min:10',
            'password_confirmation' => 'nullable|same:password',
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048', // Tambahkan validasi untuk logo
        ],
        [
            'password'=>'password minimal 10 karakter',
            'password_confirmation'=>'konfirmasi password tidak cocok',
            'logo'=>'ukuran file logo maksimal 2mb',
        ]
        );
        if($validator->fails()){
            $messages = $validator->messages();
            $alertMessage = $messages->first();
          
            return back()->with(['error'=>$alertMessage]);
        }
        try {
            DB::beginTransaction();
            $id = Auth::user()->id;
            $toko = Toko::where('user_id',$id)->first();
            $user = User::where('id',$id)->first();
            $dataToko = [
                'toko' => $request->toko,
                'no_telp' => $request->no_telp,
                'detail_alamat' => $request->detail_alamat,
            ];
            if ($request->alamat != null) {
                $arrayAlamat = explode(', ',$request->alamat);
                $lokasiArray = explode('. ',end($arrayAlamat));
        
                $arrayAlamat[count($arrayAlamat)-1] = $lokasiArray[0];
                $arrayAlamat[] = $lokasiArray[1];
                $dataToko['kecamatan'] = $arrayAlamat[0];
                $dataToko['kota'] = $arrayAlamat[1];
                $dataToko['kecamatan'] = $arrayAlamat[2];
                $dataToko['postal_code'] = $arrayAlamat[3];
            }

            // Cek apakah password baru dimasukkan
            if ($request->password != null) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
            }
        
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $dataToko['logo'] = $logo->hashName();
                $logo->storeAs('public/image', $dataToko['logo']);
            }
        
            $toko->update($dataToko); 
            $user->update([
                'name' => $request->name,
               
            ]); 
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            dd($th);
            return redirect()->route('admin.profil-toko')->with('error', 'Data Gagal DiEdit!');
        }
    
        return redirect()->route('admin.profil-toko')->with('success', 'Data Berhasil DiEdit!');
    }
}
