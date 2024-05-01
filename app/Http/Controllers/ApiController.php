<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use Throwable;
use Validator;
use DateTimeZone;
use Carbon\Carbon;
use App\Models\Lot;
use App\Models\Npl;
use App\Models\City;
use App\Models\Toko;
use App\Models\User;
use App\Models\Event;
use App\Models\Kurir;
use App\Models\Order;
use App\Models\Produk;
use App\Models\Review;
use App\Events\Message;
use App\Models\Bidding;
use App\Models\LotItem;
use App\Models\Promosi;
use App\Models\Tagihan;
use App\Models\TLogApi;
use App\Models\Pemenang;
use App\Models\Province;
use App\Models\Wishlist;
use App\Models\Keranjang;
use App\Models\OrderItem;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\PesertaNpl;
use App\Models\BannerUtama;
use App\Models\EventLelang;
use App\Models\GambarEvent;
use App\Models\ProdukPromo;
use Illuminate\Support\Str;
use App\Models\BannerDiskon;
use App\Models\BarangLelang;
use App\Models\GambarProduk;
use App\Models\InvoiceStore;
use App\Models\PembelianNpl;
use App\Models\PesertaEvent;
use Illuminate\Http\Request;
use App\Models\BannerSpesial;
use App\Models\KategoriBarang;
use App\Models\KategoriProduk;
use App\Models\PembayaranEvent;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{    
      /**
     * @OA\Post(
     *      path="/api/cost",
     *      tags={"Tarif Pengiriman"},
     *      summary="tarif pengiriman",
     *      description="masukkan id_kota_asal, id_kota_tujuan, total_berat_produk, kurir",
     *      operationId="tarif pengiriman",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              @OA\Property(property="id_kota_asal", type="integer"),
     *              @OA\Property(property="id_kota_tujuan", type="integer"),
     *              @OA\Property(property="total_berat_produk", type="integer"),
     *              @OA\Property(property="kurir", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function tarif_pengiriman(Request $request){
        $validator = Validator::make($request->all(), [
            'id_kota_asal'     => 'required|integer|min:0',
            'id_kota_tujuan'     => 'required|integer|min:0',
            'total_berat_produk'     => 'required|integer',
            'kurir'     => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' =>$validator->errors()
            ], 422);
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "origin=$request->id_kota_asal&destination=$request->id_kota_tujuan&weight=$request->total_berat_produk&courier=$request->kurir",
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
            "key: ".env('API_KEY_RAJAONGKIR')."",
        ),
        ));

        $response = curl_exec($curl);
        $responseArray = json_decode($response, true);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            // Tambahkan tanggapan kesalahan jika terjadi cURL error
            return response()->json(['error' => "cURL Error #:" . $err]);
        } else {
            // Menggunakan $responseArray yang sudah diproses
            if ($responseArray['rajaongkir']['status']['code'] == 400) {
                return response()->json([$responseArray],400);
            } else {
                return response()->json($responseArray);
                # code...
            }
            
        }
        
    }
   

    public function tescekongkir(Request $request){
        $d = Kurir::where('status', 1)->get();
        $kurir_imploded = $d->implode('kurir', ',');
        dd(['rt' => $kurir_imploded]);
        
    }
}
