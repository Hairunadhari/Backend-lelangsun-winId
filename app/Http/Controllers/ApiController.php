<?php

namespace App\Http\Controllers;

use Exception;
use Validator;
use DateTimeZone;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Produk;
use App\Models\Promosi;
use App\Models\Tagihan;
use App\Models\TLogApi;
use App\Models\OrderItem;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\ProdukPromo;
use Illuminate\Support\Str;
use App\Models\GambarProduk;
use Illuminate\Http\Request;
use App\Models\KategoriProduk;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/produk",
     *      tags={"Produk"},
     *      summary="List Produk",
     *      description="menampilkan semua produk",
     *      operationId="produk",
     *      @OA\Response(
     *          response="default",
     *          description="return array model produk"
     *      )
     * )
     */
    public function daftar_produk(){
        $produk = Produk::where('stok', '>', 0)->get();
        $produk->each(function ($item) {
            $item->thumbnail = url('https://backendwin.spero-lab.id/storage/image/' . $item->thumbnail);
        });
        return response()->json([
            'produk' => $produk,
        ]);
    }


    /**
     * @OA\Get(
     *      path="/api/topproduk",
     *      tags={"Produk"},
     *      summary="List Top Produk",
     *      description="menampilkan top 5 produk ",
     *      operationId="ListTopProduk",
     *      @OA\Response(
     *          response="default",
     *          description="return array model produk"
     *      )
     * )
     */
    public function daftar_top_produk(){
        $topproduk = Produk::take(5)->get();
        $topproduk->each(function ($item) {
            $item->thumbnail = url('https://backendwin.spero-lab.id/storage/image/' . $item->thumbnail);
        });
        return response()->json([
            'topproduk' => $topproduk
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/detailproduk/{id}",
     *      tags={"Produk"},
     *      summary="Mendapatkan detail produk berdasarkan ID",
     *      description="Mendapatkan detail produk berdasarkan ID yg diberikan",
     *      operationId="DetailProduk",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="ID produk yang akan ditampilkan",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
     *      @OA\Response(
     *          response="default",
     *          description="return array model produk"
     *      )
     * )
     */
    public function detail_produk($id){
        $produk = Produk::with('toko','kategoriproduk')->find($id);
            $produk->thumbnail = url('https://backendwin.spero-lab.id/storage/image/' . $produk->thumbnail);
            $produk->toko->logo = url('https://backendwin.spero-lab.id/storage/image/' . $produk->toko->logo);
            $produk->kategoriproduk->gambar = url('https://backendwin.spero-lab.id/storage/image/' . $produk->kategoriproduk->gambar);

        $gambarproduk = GambarProduk::where('produk_id', $id)->get();
        $gambarproduk->each(function ($item) {
            $item->gambar = url('https://backendwin.spero-lab.id/storage/image/' . $item->gambar);
        });
        return response()->json([
            'produk' => $produk,
            'gambarproduk' => $gambarproduk
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/detailkategori/{id}",
     *      tags={"Kategori Produk"},
     *      summary="Detail Kategori",
     *      description="Menampilkan semua produk berdasarkan kategori yg dipillih",
     *      operationId="DetailKategori",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="ID kategori yang akan ditampilkan",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
     *      @OA\Response(
     *          response="default",
     *          description="return array model produk"
     *      )
     * )
     */
     public function daftar_produk_berdasarkan_kategori($id){
        $kategoriproduk = KategoriProduk::find($id);
        $kategoriproduk->gambar = url('https://backendwin.spero-lab.id/storage/image/' . $kategoriproduk->gambar);
        
        $produk = Produk::where('kategoriproduk_id',$id)->get();
        $produk->each(function ($item) {
            $item->thumbnail = url('https://backendwin.spero-lab.id/storage/image/' . $item->thumbnail);
        });
        return response()->json([
            'kategoriproduk' => $kategoriproduk,
            'produk' => $produk
        ]);
    }

     /**
     * @OA\Get(
     *      path="/api/kategori",
     *      tags={"Kategori Produk"},
     *      summary="Kategori Produk",
     *      description="menampilkan semua kategori produk ",
     *      operationId="KategoriProduk",
     *      @OA\Response(
     *          response="default",
     *          description="return array model produk"
     *      )
     * )
     */
    public function daftar_kategori(){
        $kategoriproduk = KategoriProduk::all();
        $kategoriproduk->each(function ($item) {
            $item->gambar = url('https://backendwin.spero-lab.id/storage/image/' . $item->gambar);
        });
        return response()->json([
            'kategoriproduk' => $kategoriproduk
            
        ]);
    }


   /**
     * @OA\Post(
     *      path="/api/register",
     *      tags={"Register dan Login"},
     *      summary="register",
     *      description="masukkan name,email,password dan konfirmasi password untuk register",
     *      operationId="Registrasi",
     *      @OA\RequestBody(
     *          required=true,
     *          description="form register",
     *          @OA\JsonContent(
     *              required={"name", "email", "password", "confirm_password"},
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="email", type="string", format="email"),
     *              @OA\Property(property="password", type="string"),
     *              @OA\Property(property="confirm_password", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="registrasi berhasil",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="register Sukses"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="token", type="string"),
     *                  @OA\Property(property="name", type="string"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="registrasi gagal",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="ada kesalahan"),
     *              @OA\Property(property="data", type="null"),
     *          ),
     *      ),
     * )
     */

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name'     => 'required|',
            'email'     => 'required|email|unique:users,email',
            'password'     => 'required',
            'confirm_password'     => 'required|same:password',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Ada Kesalahan',
                'data' =>$validator->errors()
            ], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $user->name;

        return response()->json([
            'success' => true,
            'message' => 'Sukses Register',
            'data' => $success,
        ])->withHeaders([
            'X-CSRF-Token' => csrf_token(), // kasih token CSRF di header response
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/login",
     *      tags={"Register dan Login"},
     *      summary="login",
     *      description="Masukkan email dan password yang sudah terdaftar",
     *      operationId="Login",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Form login",
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", format="email"),
     *              @OA\Property(property="password", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Login berhasil",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Login Sukses"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="token", type="string"),
     *                  @OA\Property(property="name", type="string"),
     *                  @OA\Property(property="email", type="string", format="email"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Login gagal",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Cek email dan password lagi"),
     *              @OA\Property(property="data", type="null"),
     *          ),
     *      ),
     * )
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::user();
            $success['token'] = $auth->createToken('auth_token')->plainTextToken;
            $success['name'] = $auth->name;
            $success['email'] = $auth->email;

            return response()->json([
                'success' => true,
                'message' => 'Login Sukses',
                'data' => $success,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Cek email dan password lagi',
                'data' => null,
            ], 401);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/promosi",
     *      tags={"Promosi"},
     *      summary="List Promosi",
     *      description="menampilkan semua jenis promo produk yg sedang berlangsung",
     *      operationId="promosi",
     *      @OA\Response(
     *          response="default",
     *          description="return array model produk"
     *      )
     * )
     */
    public function daftar_promo(){
        $promosi = Promosi::where('status', 'sedang berlangsung')->get();
        return response()->json([
            'promosi' => $promosi,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/detailpromosi/{id}",
     *      tags={"Promosi"},
     *      summary="Mendapatkan detail promo produk berdasarkan ID",
     *      description="menampilkan semua produk yg sedang diskon berdasarkan ID Promosi yg diberikan",
     *      operationId="DetailPromosi",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="ID produk yang akan ditampilkan",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
     *      @OA\Response(
     *          response="default",
     *          description="return array model produk"
     *      )
     * )
     */
    public function detail_promosi($id){
        $datapromosi = Promosi::find($id);
        $detailprodukpromosi = ProdukPromo::with('produk')->where('promosi_id',$id)->get();
        return response()->json([
            'datapromosi' => $datapromosi,
            'detailprodukpromosi' => $detailprodukpromosi,
        ]);
    }

    public function tes_xendit(Request $request){
        var_dump(json_encode($request));
        Storage::disk('local')->put('response-xendit.txt', json_encode($request));
    }
    
    public function show_xendit(){
        $myfile = fopen("response-xendit.txt", "r") or die("Unable to open file!");
        echo fread($myfile,filesize("response-xendit.txt"));
        fclose($myfile);
    }
    

     /**
     * @OA\Post(
     *      path="/api/add-order",
     *      tags={"Order Produk"},
     *      summary="Order",
     *      description="masukkan user id, produk id, qty, pengiriman, lokasi pengiriman, nama pengirim/kurir, total pembayaran",
     *      operationId="Order",
     *      @OA\RequestBody(
     *          required=true,
     *          description="form data",
     *          @OA\JsonContent(
     *              required={"user_id","produk_id", "qty","pengiriman","lokasi_pengiriman","nama_pengirim","metode_pembayaran","total_pembayaran"},
     *              @OA\Property(property="user_id", type="integer"),
     *              @OA\Property(property="produk_id", type="integer"),
     *              @OA\Property(property="qty", type="integer"),
     *              @OA\Property(property="pengiriman", type="string"),
     *              @OA\Property(property="lokasi_pengiriman", type="string"),
     *              @OA\Property(property="nama_pengirim", type="string"),
     *              @OA\Property(property="total_pembayaran", type="integer"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */

    public function add_order(Request $request){
        $validator = Validator::make($request->all() , [
            'user_id'     => 'required',
            'produk_id'     => 'required',
            'qty'     => 'required',
            'pengiriman'     => 'required',
            'lokasi_pengiriman'     => 'required',
            'nama_pengirim'     => 'required',
            'total_pembayaran'     => 'required',
        ]);

        try {
           
            $user = User::where('id', $request->user_id)->first();
            $order = Order::create([
                'id' => $request->id,
                'user_id' => $request->user_id ?? null
            ]);

            $orderitem = OrderItem::create([
                'id' => $request->id,
                'order_id' => $order->id ?? null,
                'produk_id' => $request->produk_id ?? null,
                'qty' => $request->qty ?? null,
            ]);
                        
            $produk = Produk::find($request->produk_id);
            $produk->update([
                'stok' => $produk->stok - $request->qty,
            ]);
                        
            $pengiriman = Pengiriman::create([
                'id' => $request->id,
                'order_id' => $order->id ?? null,
                'pengiriman' => $request->pengiriman ?? null,
                'lokasi_pengiriman' => $request->lokasi_pengiriman ?? null,
                'nama_pengirim' => $request->nama_pengirim ?? null
            ]);

            $secret_key = 'Basic '.config('xendit.key_auth');
            $external_id = Str::random(10);
            $data_request = Http::withHeaders([
                'Authorization' => $secret_key
            ])->post('https://api.xendit.co/v2/invoices', [
                'external_id' => $external_id,
                'amount' => $request->total_pembayaran
            ]);

            $response = $data_request->object();
            
            $tagihan = Tagihan::create([
                'order_id' => $order->id,
                'external_id' => $external_id,
                'status' => $response->status,
                'total_pembayaran' => $request->total_pembayaran,
                'payment_link' => $response->invoice_url
            ]);
            $success = true;
            $message = 'Data Order Berhasil Ditambahkan';

            $res = [
                'success' => $success,
                'message' => $message,
                'name' => $user->name,
                'status' => $tagihan->status ?? null,
                'total_pembayaran' => $tagihan->total_pembayaran ?? null,
                'payment_link' => $tagihan->payment_link ?? null,
            ];

            if($response){
                TLogApi::create([
                    'k_t' => 'kirim',
                    'object' => 'xendit',
                    'data' => json_encode([
                        'order' => $order,
                        'orderitem' => $orderitem,
                        'pengiriman' => $pengiriman,
                        'responseData'=> $response
                    ]),
                    'result' => json_encode($res),
                ]);
            }

            TLogApi::create([
                'k_t' => 'terima',
                'object' => 'mobile',
                'data' => json_encode([
                    'order' => $order,
                    'orderitem' => $orderitem,
                    'pengiriman' => $pengiriman,
                    'responsedata' => $response,
                ]),
                'result' => json_encode($res),
            ]);

        } catch (Exception $e) {
            $success = false;
            $message = 'Data Order Gagal Ditambahkan';
            $res = [
                'success' => $success,
                'message' => $message,
                'name' => $user->name ?? null,
                'status' => $tagihan->status ?? null,
                'total_pembayaran' => $tagihan->total_pembayaran ?? null,
                'payment_link' => $tagihan->payment_link ?? null,
            ];
            TLogApi::create([
                'k_t' => 'terima',
                'object' => 'mobile',
                'data' => json_encode([
                    'order' => [
                        'id' => $request->id,
                        'user_id' => $request->user_id ?? null
                    ],
                    'orderitem' => [
                        'id' => $request->id,
                        'order_id' => $order->id ?? null,
                        'produk_id' => $request->produk_id ?? null,
                        'qty' => $request->qty ?? null,
                    ],
                    'pengiriman' => [
                        'id' => $request->id,
                        'order_id' => $order->id ?? null,
                        'pengiriman' => $request->pengiriman ?? null,
                        'lokasi_pengiriman' => $request->lokasi_pengiriman ?? null,
                        'nama_pengirim' => $request->nama_pengirim ?? null    
                    ],
                    'responseData' => $response ?? null
                ]),
                'result' => json_encode($res),
            ]);
            
        }
        return response()->json($res);
    }

    public function callback_xendit(Request $request){
        $user = '1';
        $invoice = Tagihan::where('external_id', $request->external_id)->first();
        if ($invoice == null) {
            $failed = [
                'message' => 'FAILED'
            ];
            return response()->json($failed);
        }
        $invoice->update([
            'status' => $request->status,
        ]);
        
        Pembayaran::create([
            'external_id' => $request->external_id,
            'metode_pembayaran' => $request->payment_method,
            'email_user' => '1',
            'status' => $request->status,
            'total_pembayaran' => $request->paid_amount,
            'bank_code' => $request->bank_code,
        ]);

        $res = [
            'message' => 'success',
            'data' => json_encode($request->all())
        ];
        
        TLogApi::create([
            'k_t' => 'terima',
            'object' => 'xendit',
            'data' => json_encode($request->all()),
            'result' => json_encode($res)
        ]);
        return response()->json($res);

}

}
