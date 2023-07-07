<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use Validator;
use DateTimeZone;
use Carbon\Carbon;
use App\Models\Toko;
use App\Models\User;
use App\Models\Order;
use App\Models\Produk;
use App\Models\Review;
use App\Models\Promosi;
use App\Models\Tagihan;
use App\Models\TLogApi;
use App\Models\Wishlist;
use App\Models\OrderItem;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\BannerUtama;
use App\Models\ProdukPromo;
use Illuminate\Support\Str;
use App\Models\BannerDiskon;
use App\Models\GambarProduk;
use Illuminate\Http\Request;
use App\Models\BannerSpesial;
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
     *      summary="Menampilkan detail produk berdasarkan ID",
     *      description="Menampilkan detail produk berdasarkan ID yg diberikan",
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
            $success['user_id'] = $auth->id;

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
        $promosi->each(function ($item) {
            $item->gambar = url('https://backendwin.spero-lab.id/storage/image/' . $item->gambar);
        });
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
        $datapromosi->gambar =  url('https://backendwin.spero-lab.id/storage/image/' . $datapromosi->gambar);

        $detailproduk = ProdukPromo::with('produk')->where('promosi_id',$id)->get();
        $detailproduk->each(function ($item){
            $item->produk->thumbnail =  url('https://backendwin.spero-lab.id/storage/image/' . $item->produk->thumbnail);
        });
        return response()->json([
            'datapromosi' => $datapromosi,
            'detailproduk' => $detailproduk,
        ]);
    }


     /**
     * @OA\Post(
     *      path="/api/add-order",
     *      tags={"Order"},
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
        // dd($request);
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
                'user_id' => $request->user_id ?? null
            ]);

            $orderitem = OrderItem::create([
                'order_id' => $order->id ?? null,
                'produk_id' => $request->produk_id ?? null,
                'qty' => $request->qty ?? null,
            ]);
                        
            $produk = Produk::find($request->produk_id);
            $produk->update([
                'stok' => $produk->stok - $request->qty,
            ]);
                        
            $pengiriman = Pengiriman::create([
                'order_id' => $order->id ?? null,
                'pengiriman' => $request->pengiriman ?? null,
                'lokasi_pengiriman' => $request->lokasi_pengiriman ?? null,
                'nama_pengirim' => $request->nama_pengirim ?? null
            ]);

            
            $secret_key = 'Basic '.config('xendit.key_auth');
            $timestamp = time();
            $strRandom = Str::random(5);
            $external_id = "invoice-win-{$timestamp}-{$strRandom}";

            $data_request = Http::withHeaders([
                'Authorization' => $secret_key
            ])->post('https://api.xendit.co/v2/invoices', [
                'external_id' => $external_id,
                'amount' => $request->total_pembayaran,
                'payer_email' => $user->email
            ]);

            $response = $data_request->object();
            $dataExipre = $response->expiry_date;
            $dateTime = new DateTime($dataExipre);
            $exp_date = $dateTime->format('Y-m-d H:i:s'); 
            $tagihan = Tagihan::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'external_id' => $external_id,
                'status' => $response->status,
                'total_pembayaran' => $request->total_pembayaran,
                'payment_link' => $response->invoice_url,
                'exp_date' => $exp_date
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
                    'tagihan' => $tagihan,
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
        $invoice = Tagihan::with('user')->where('external_id', $request->external_id)->first();
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
            'email_user' => $invoice->user->email,
            'status' => $request->status,
            'total_pembayaran' => $request->paid_amount,
            'bank_code' => $request->bank_code,
            'tagihan_id' => $invoice->id,
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

     /**
     * @OA\Get(
     *      path="/api/cari-produk/{name}",
     *      tags={"Search Produk"},
     *      summary="cari produk",
     *      description="menampilkan produk berdasarkan nama produk yg di masukkan",
     *      operationId="SearchProduk",
     *       @OA\Parameter(
    *          name="name",
    *          in="path",
    *          required=true,
    *          description="masukkan nama produk",
    *          @OA\Schema(
    *              type="string"
    *          )
    *      ),
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function cari_produk($name){
        $produk = Produk::where('nama','Like','%'.$name.'%')->get();
        return response()->json([
            'data' => $produk
        ]);
    }   
    
     /**
     * @OA\Get(
     *      path="/api/info-akun/{id}",
     *      tags={"Akun"},
     *      summary="Informasi Akun",
     *      description="menampilkan informasi akun berdasarkan id user yg di masukkan",
     *      operationId="InfoAkun",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="masukkan id user",
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
    public function info_akun($id){
        $user = User::find($id);
        return response()->json([
            'data' => $user
        ]);
    }


    /**
     * @OA\Put(
     *      path="/api/update-akun/{id}",
    *      tags={"Akun"},
    *      summary="Update Akun",
    *      description="Mengupdate data akun berdasarkan ID",
     *      @OA\RequestBody(
    *          required=true,
    *          description="form edit",
    *          @OA\JsonContent(
    *              required={"name", "no_telp","alamat"},
    *              @OA\Property(property="name", type="string"),
    *              @OA\Property(property="no_telp", type="integer"),
    *              @OA\Property(property="alamat", type="string"),
    *              @OA\Property(property="foto", type="string"),
    *          )
    *      ),
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function update_akun(Request $request, $id){
        $data = User::find($id);

        if ($request->hasFile('foto')) {

            //upload new image
            $foto = $request->file('foto');
            $foto->storeAs('public/image', $foto->hashName());

            Storage::delete('public/image/'.$data->foto);

            $data->update([
                'name' => $request->name,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'foto'     => $foto->hashName(),
            ]);

        } else {
            $data->update([
                'name'     => $request->name,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
            ]);
        }

        return response()->json([
            'message' => 'SUCCESS',
            'data' => $data
        ]);
    }


     /**
     * @OA\Get(
     *      path="/api/list-banner-utama",
     *      tags={"Banner"},
     *      summary="List Banner Utama",
     *      description="menampilkan semua banner utama",
     *      operationId="banner utama",
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function daftar_banner_utama(){
        $data = BannerUtama::all();
        $data->each(function ($item) {
            $item->gambar = url('https://backendwin.spero-lab.id/storage/image/' . $item->gambar);
        });
        return response()->json([
            'data' => $data,
        ]);
    }

     /**
     * @OA\Get(
     *      path="/api/list-banner-diskon",
     *      tags={"Banner"},
     *      summary="List Banner Diskon",
     *      description="menampilkan semua banner diskon",
     *      operationId="banner diskon",
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function daftar_banner_diskon(){
        $data = BannerDiskon::all();
        $data->each(function ($item) {
            $item->gambar = url('https://backendwin.spero-lab.id/storage/image/' . $item->gambar);
        });
        return response()->json([
            'data' => $data,
        ]);
    }
     /**
     * @OA\Get(
     *      path="/api/list-banner-spesial",
     *      tags={"Banner"},
     *      summary="List Banner Spesial",
     *      description="menampilkan semua banner spesial",
     *      operationId="banner spesial",
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function daftar_banner_spesial(){
        $data = BannerSpesial::all();
        $data->each(function ($item) {
            $item->gambar = url('https://backendwin.spero-lab.id/storage/image/' . $item->gambar);
        });
        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/list-pesanan/{id}",
     *      tags={"List Pesanan"},
     *      summary="id user",
     *      description="Menampilkan list pesanan berdasarkan user yg login",
     *      operationId="ListPesanan",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="data user yg akan ditampilkan",
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
    public function list_pesanan($userid){
        $data = Order::with('user', 'orderitem', 'tagihan')->where('user_id', $userid)->get();
        return response()->json([
            'order' => $data
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/detail-pesanan/{id}",
     *      tags={"Detail Pesanan"},
     *      summary="order id",
     *      description="Menampilkan detail pesanan berdasrkan order id",
     *      operationId="DetailPesanan",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="order id",
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
    public function detail_pesanan($id){
        $tagihan = Tagihan::with('user','pembayaran')->where('order_id', $id)->first();
        $pengiriman = Pengiriman::where('order_id', $id)->first();
        $itemproduk = OrderItem::with('produk')->where('order_id', $id)->first();
        $itemproduk->produk->thumbnail = url('https://backendwin.spero-lab.id/storage/image/' . $itemproduk->produk->thumbnail);
        return response()->json([
            'id_order' => $tagihan->order_id,
            'email_user' => $tagihan->user->email,
            'user_name' => $tagihan->user->name,
            'no_telp_user' => $tagihan->user->no_telp,
            'alamat_user' => $tagihan->user->alamat,
            'pengiriman' => $pengiriman->pengiriman,
            'lokasi_pengiriman' => $pengiriman->lokasi_pengiriman,
            'nama_pengirim' => $pengiriman->nama_pengirim,
            'order_date' => $tagihan->created_at,
            'exp_date' => $tagihan->exp_date,
            'status' => $tagihan->status,
            'metode_pembayaran' => $tagihan->pembayaran->metode_pembayaran ?? null,
            'bank_code' => $tagihan->pembayaran->bank_code ?? null,
            'item_pesanan' => $itemproduk->produk,
            'qty' => $itemproduk->qty,
            'total_pembayaran' => $tagihan->total_pembayaran,
            'link_payment' => $tagihan->payment_link,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/detail-toko/{id}",
     *      tags={"Deskripsi Toko"},
     *      summary="id toko",
     *      description="Menampilkan detail toko berdasrkan id toko",
     *      operationId="DeskrispsiToko",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="id toko",
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
    public function detail_toko($id){
        $toko = Toko::with('produk.kategoriproduk')->find($id);
        $toko->logo = url('https://backendwin.spero-lab.id/storage/image/' . $toko->logo);
        
        $toko->produk->each(function ($item) {
            $item->thumbnail = url('https://backendwin.spero-lab.id/storage/image/' . $item->thumbnail);
        });
        return response()->json([
            'toko' => $toko
        ]);        
    }

    public function add_wishlist(Request $request){
        $this->validate($request, [
            'user_id'     => 'required',
            'produk_id'     => 'required',
        ]);

        Wishlist::where('produk_id', $request->produk_id)->where('user_id', $request->user_id)->delete();

        $data = Wishlist::create([
            'user_id' => $request->user_id,
            'produk_id' => $request->produk_id,
        ]);

        return response()->json([
            'message' => 'SUCCESS',
            'data' => $data,
        ]);
    }

    public function list_wishlist($id){
        $data = Wishlist::with('produk')->where('user_id', $id)->get();
        $data->each(function ($item){
            $item->produk->thumbnail =  'https://backendwin.spero-lab.id/storage/image/' . $item->produk->thumbnail;
        });
        return response()->json([
            'data' => $data
        ]);
    }

    public function add_review(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id'     => 'required',
            'produk_id'     => 'required',
            'review'     => 'required',
            'rating'     => 'required|numeric|between:1,5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->fails()
            ]);
        }

        Review::create([
            'user_id' => $request->user_id,
            'produk_id' => $request->produk_id,
             'review' => $request->review,
            'rating' => $request->rating,
        ]);

    }

 
}
