<?php

namespace App\Http\Controllers\Api;

use Validator;
use Carbon\Carbon;
use App\Models\Npl;
use Illuminate\Support\Str;
use App\Models\PembelianNpl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiNplController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/lelang/npl/add-npl",
     *      tags={"Npl"},
     *      summary="npl",
     *      description="",
     *      operationId="npl",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="event_lelang_id", type="string"),
     *                  @OA\Property(property="harga_npl", type="integer"),
     *                  @OA\Property(property="jumlah_tiket", type="integer"),
     *                  @OA\Property(property="nominal_transfer", type="integer"),
     *                  @OA\Property(property="no_rekening", type="integer"),
     *                  @OA\Property(property="nama_pemilik_rekening", type="integer"),
     *                  @OA\Property(property="bukti", type="file", format="binary"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */

     public function add_npl(Request $request){
        $validator = Validator::make($request->all(), [
            'event_lelang_id'     => 'required|integer|min:1',
            'no_rekening'     => 'required',
            'nama_pemilik_rekening'     => 'required',
            'nominal_transfer'     => 'required',
            'harga_npl'     => 'required',
            'jumlah_tiket'     => 'required',
            'bukti'     => 'required|mimes:jpeg,jpg,png',
        ]);


        try {
            DB::beginTransaction();
            $bukti = $request->file('bukti');
            $bukti->storeAs('public/image', $bukti->hashName());
            $pembelian_npl = PembelianNpl::create([
                'event_lelang_id' => $request->event_lelang_id,
                'user_id' => $request->user_id,
                'type_pembelian' => 'online',
                'type_transaksi' => 'transfer',
                'no_rek' => $request->no_rekening,
                'nama_pemilik' => $request->nama_pemilik_rekening,
                'nominal' => $request->nominal_transfer,
                'bukti' => $bukti->hashName(),
            ]);
            
            for ($i = 0; $i < $request->jumlah_tiket; $i++) {
                $npl = Npl::create([
                    'kode_npl' => 'SUN_0'. $pembelian_npl->id . Str::random(5),
                    'harga_item' => $request->harga_npl,
                    'user_id' => $pembelian_npl->user_id,
                    'pembelian_npl_id' => $pembelian_npl->id,
                    'event_lelang_id' => $pembelian_npl->event_lelang_id,
                ]);
            }
        
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'data' => $th,
            ],400);
        }
        return response()->json([
            'success' => true,
            'data_pembelian_npl' => $pembelian_npl,
        ]);

        
    }

     /**
     * @OA\Get(
     *      path="/api/lelang/list-npl-user/{id}/",
     *      tags={"Npl"},
     *      summary="Menampilkan List Npl berdasrkan id user",
     *      description="",
     *      operationId="List Npl user",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="id user",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function list_npl_berdasarkan_id_peserta_npl(){
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $npl = Npl::with(['event_lelang' => function($query){
                $query->select('id','judul');
            }])->where('created_at', '>', Carbon::now()->subDays(30))->where('status','active')->where('user_id',$user->id)->orderBy('created_at','desc')->get();
            
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            // dd($th);
            return response()->json([
                'success' => false,
                'data' => $th,
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => $npl,
        ]);

    }
}
