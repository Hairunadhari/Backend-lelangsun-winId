<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use Validator;
use DateTimeZone;
use Carbon\Carbon;
use App\Models\Toko;
use App\Models\User;
use App\Models\Event;
use App\Models\Order;
use App\Models\Produk;
use App\Models\Review;
use App\Models\Promosi;
use App\Models\Tagihan;
use App\Models\TLogApi;
use App\Models\Wishlist;
use App\Models\Keranjang;
use App\Models\OrderItem;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\BannerUtama;
use App\Models\GambarEvent;
use App\Models\ProdukPromo;
use Illuminate\Support\Str;
use App\Models\BannerDiskon;
use App\Models\GambarProduk;
use App\Models\PesertaEvent;
use Illuminate\Http\Request;
use App\Models\BannerSpesial;
use App\Models\KategoriProduk;
use App\Models\PembayaranEvent;
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
        $now = Carbon::now();

        $produk = Produk::with([
            'toko' => function ($querytoko) use ($now){
                $querytoko->select('id','toko','logo','status')->where('status','active');
            }, 
            'produkpromo' => function ($query) use ($now){
            $query->select('id','promosi_id','produk_id','total_diskon','diskon')->orderBy('created_at','desc')->where('tanggal_mulai','<=', $now)->where('tanggal_selesai','>',$now);
        }])
        ->where('stok', '>', 0)
        ->where('status', 'active')
        ->get();
    

        $produk->each(function ($item) {
            $item->thumbnail = url('https://backendwin.spero-lab.id/storage/image/' . $item->thumbnail);
        });
        $produk->each(function ($item) {
            $logo = $item->toko->logo;
            $url = 'https://backendwin.spero-lab.id/storage/image/';

            $item->toko->logo = str_replace($url, '', $logo);
            $item->toko->logo = $url . $item->toko->logo;
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

        $gambarproduk = GambarProduk::where('produk_id', $id)->get();
        $gambarproduk->each(function ($item) {
            $item->gambar = url('https://backendwin.spero-lab.id/storage/image/' . $item->gambar);
        });

        $review = Review::with('user')->where('produk_id', $id)->where('status','active')->get();
        $review->each(function ($item){
            $item->user->foto = url('https://backendwin.spero-lab.id/storage/image/' . $item->user->foto);
        });
        $totalreview = Review::where('produk_id', $id)->where('status','active')->count();

        return response()->json([
            'produk' => $produk,
            'gambarproduk' => $gambarproduk,
            'total_review' => $totalreview,
            'reviews' => $review,
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
        
        $produk = Produk::where('kategoriproduk_id',$id)->where('status','active')->get();
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
        $kategoriproduk = KategoriProduk::where('status','active')->get();
       
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
     *      summary="List Promo Produk",
     *      description="menampilkan semua jenis promo produk yg sedang berlangsung",
     *      operationId="promosi",
     *      @OA\Response(
     *          response="default",
     *          description="return array model produk"
     *      )
     * )
     */
    public function daftar_promo(){
        $today = Carbon::today();
        $promosi = Promosi::where('tanggal_mulai', '<=', $today)->get();
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
     *      summary="menampilkan detail promo produk berdasarkan ID",
     *      description="menampilkan semua produk yg sedang diskon berdasarkan ID Promosi yg diberikan",
     *      operationId="DetailPromosi",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="ID promosi yang akan ditampilkan",
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
        $datapromosi->diskon =  $datapromosi->diskon . '%';

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
     *      description="masukkan user id, produk id, qty, pengiriman, lokasi pengiriman, sub_total",
     *      operationId="Order",
     *      @OA\RequestBody(
     *          required=true,
     *          description="form data",
     *          @OA\JsonContent(
     *              required={"user_id","pengiriman","lokasi_pengiriman","sub_total"},
     *              @OA\Property(property="user_id", type="integer"),
     *              @OA\Property(
    *                  property="items",
    *                  type="array",
    *                  @OA\Items(
    *                      @OA\Property(property="produk_id", type="integer"),
    *                      @OA\Property(property="qty", type="integer"),
    *                      @OA\Property(property="harga", type="integer"),
    *                      @OA\Property(property="total_harga", type="integer"),
    *                      @OA\Property(property="nama_produk", type="string"),
    *                      @OA\Property(property="id_promo", type="integer"),
    *                  )
    *              ),
    *               @OA\Property(property="latitude", type="string"),
    *               @OA\Property(property="longitude", type="string"),
     *              @OA\Property(property="pengiriman", type="string"),
     *              @OA\Property(property="lokasi_pengiriman", type="string"),
     *              @OA\Property(property="sub_total", type="integer"),
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
            'pengiriman'     => 'required',
            'lokasi_pengiriman'     => 'required',
            'sub_total'     => 'required',
        ]);
        
        try {
           
            $user = User::where('id', $request->user_id)->first();
            $order = Order::create([
                'user_id' => $user->id
            ]);

            $items = $request->items;

            $produkIds = array_column($items, 'produk_id');

            $produks = Produk::whereIn('id', $produkIds)->get();
            
            
            // fungsi untuk looping orderan
            foreach ($items as $item) {

                if ($produks) {
                    $orderitem = OrderItem::create([
                        'order_id' => $order->id ?? null,
                        'produk_id' => $item['produk_id'] ?? null,
                        'qty' => $item['qty'] ?? null,
                        'harga' => $item['harga'] ?? null,
                        'nama_produk' => $item['nama_produk'] ?? null,
                        'promosi_id' => $item['promosi_id'] ?? null,
                        'harga_x_qty' => $item['total_harga'] ?? null,
                    ]);
                }
            }
            
            $pengiriman = Pengiriman::create([
                'order_id' => $order->id ?? null,
                'pengiriman' => $request->pengiriman ?? null,
                'lokasi_pengiriman' => $request->lokasi_pengiriman ?? null,
                'nama_pengirim' => '-',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);
            
            $secret_key = 'Basic '.config('xendit.key_auth');
            $timestamp = time();
            $strRandom = Str::random(5);
            $external_id = "invoice-win-{$timestamp}-{$strRandom}";

            $data_request = Http::withHeaders([
                'Authorization' => $secret_key
            ])->post('https://api.xendit.co/v2/invoices', [
                'external_id' => $external_id,
                'amount' => $request->sub_total,
                'payer_email' => $user->email
            ]);

            $response = $data_request->object();
            $dataExipre = $response->expiry_date;
            $expiryDate = Carbon::parse($response->expiry_date, 'UTC')->setTimezone('Asia/Jakarta');
            $formattedExpiryDate = $expiryDate->format('Y-m-d H:i:s'); 
            $tagihan = Tagihan::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'external_id' => $external_id,
                'status' => $response->status,
                'total_pembayaran' => $request->sub_total,
                'payment_link' => $response->invoice_url,
                'exp_date' => $formattedExpiryDate
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
        
        $invoice = Tagihan::with('user', 'order')->where('external_id', $request->external_id)->first();
        $invoice->update([
            'status' => $request->status,
        ]);
            
        // ambil id produk berasrkan order id
        $ambil_produk_id = OrderItem::where('order_id', $invoice->order->id)->pluck('produk_id');
        // ambil qty orderan 
        $qty_order = OrderItem::where('order_id', $invoice->order->id)->pluck('qty');
        $produks = Produk::whereIn('id', $ambil_produk_id)->get();
        $ambil_produkid_berdasarkan_userid = Keranjang::where('user_id', $invoice->user_id)->whereIn('produk_id', $ambil_produk_id)->get();
        
        if ($invoice->status == 'PAID') {
            foreach ($produks as $index => $p) {
                $p->update([
                    'stok' => $p->stok - $qty_order[$index], // Menggunakan indeks untuk mengambil nilai qty_order yang sesuai
                ]);
            }
            $ambil_produkid_berdasarkan_userid->each->delete();
        }

        $bayar = Pembayaran::create([
            'external_id' => $request->external_id,
            'metode_pembayaran' => $request->payment_method,
            'email_user' => $invoice->user->email,
            'status' => $request->status,
            'total_pembayaran' => $request->paid_amount,
            'bank_code' => $request->bank_code,
            'tagihan_id' => '1',
        ]);

        $res = [
            'message' => 'success',
            'data' => $bayar
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
        $produk->each(function ($data){
            $data->thumbnail = url('https://backendwin.spero-lab.id/storage/image/' . $data->thumbnail);
        });
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
        // dd($data);
        
        if ($request->has('foto')) {
            $base64Image = explode(";base64,", $request->foto);

            $explodeImage = explode("image/", $base64Image[0]);
            $imageType = $explodeImage[1];
            $image_base64 = base64_decode($base64Image[1]);
            $fileName = time() . '-' . $request->name  . '.' . $imageType;
            Storage::put($fileName, $image_base64);

            Storage::delete('public/image/'.$data->foto);

            $data->update([
                'name' => $request->name,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'foto'     => $fileName,
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
     *      tags={"Pesanan"},
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
        $data = Order::with('orderitem.produk', 'tagihan')->where('user_id', $userid)->get();
    
        $data->each(function ($item) {
            $item->orderitem->each(function ($orderItem) {
                $thumbnail = $orderItem->produk->thumbnail;
                $url = 'https://backendwin.spero-lab.id/storage/image/';
                
                $orderItem->produk->thumbnail = str_replace($url, '', $thumbnail);
                $orderItem->produk->thumbnail = $url . $orderItem->produk->thumbnail;
            });
        });
    
        return response()->json([
            'order' => $data
        ]);
    }
    

    /**
     * @OA\Get(
     *      path="/api/detail-pesanan/{id}",
     *      tags={"Pesanan"},
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
     * @OA\Post(
     *      path="/api/add-wishlist",
     *      tags={"Wishlist"},
     *      summary="Wishlist",
     *      description="masukkan user id, produk id",
     *      operationId="Wishlist",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              required={"user_id","produk_id"},
     *              @OA\Property(property="user_id", type="integer"),
     *              @OA\Property(property="produk_id", type="integer"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */

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

    /**
     * @OA\Get(
     *      path="/api/list-wishlist/{id}",
     *      tags={"Wishlist"},
     *      summary="user id",
     *      description="menampilkan semua data wishlist berdasrkan user id",
     *      operationId="ListWishlist",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="user id",
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
    public function list_wishlist($id){
        $data = Wishlist::with(['produk' => function ($query){
            $query->orderBy('created_at','desc');
        }])->where('user_id', $id)->latest()->get();
        $data->each(function ($item){
            $item->produk->thumbnail =  'https://backendwin.spero-lab.id/storage/image/' . $item->produk->thumbnail;
        });
        return response()->json([
            'data' => $data
        ]);
    }

     /**
     * @OA\Post(
     *      path="/api/add-review",
     *      tags={"Review"},
     *      summary="Review",
     *      description="masukkan user id, produk id, review, rating, rating maksimal 5",
     *      operationId="Review",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              required={"user_id","produk_id"},
     *              @OA\Property(property="user_id", type="integer"),
     *              @OA\Property(property="produk_id", type="integer"),
     *              @OA\Property(property="review", type="string"),
     *              @OA\Property(property="rating", type="integer"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function add_review(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id'     => 'required',
            'produk_id'     => 'required',
            'review'     => 'required',
            'rating'     => 'required|numeric|between:1,5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'VALIDASI GAGAL'
            ]);
        }

        Review::where('produk_id', $request->produk_id)->where('user_id', $request->user_id)->delete();
        $data = Review::create([
            'user_id' => $request->user_id,
            'produk_id' => $request->produk_id,
            'review' => $request->review,
            'rating' => $request->rating,
            'status' => 'active',
        ]);

        $totalRating = Review::where('produk_id', $request->produk_id)->sum('rating');
        $totalUser = Review::where('produk_id', $request->produk_id)->count('user_id');
        $hasil = $totalRating / $totalUser;
        $rating = number_format($hasil, 1, ',','');
        $ambilProduk = Produk::find($request->produk_id);
        $ambilProduk->update([
            'rating'=> $rating
        ]);

        return response()->json([
            'message' => 'SUCCESS',
            'data' => $data
        ]);
    }
    
     /**
     * @OA\Delete(
     *      path="/delete-wishlist/{id}",
     *      tags={"Wishlist"},
     *      summary="wishlist id",
     *      description="menghapus wishlist berdasarkan id wishlist",
     *      operationId="DeleteWishlist",
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function delete_wishlist($id){
        $data = Wishlist::find($id)->delete();
        return response()->json([
            'message' => 'SUCCESS',
            'data' => $data,
        ]);
    }

     /**
     * @OA\Post(
     *      path="/api/add-keranjang",
     *      tags={"Keranjang"},
     *      summary="Keranjang",
     *      description="masukkan user id, produk id, qty",
     *      operationId="Keranjang",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              required={"user_id","produk_id"},
     *              @OA\Property(property="user_id", type="integer"),
     *              @OA\Property(property="produk_id", type="integer"),
     *              @OA\Property(property="qty", type="integer"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function add_keranjang(Request $request){
        Keranjang::where('produk_id', $request->produk_id)->where('user_id', $request->user_id)->delete();
        $data = Keranjang::create([
            'user_id' => $request->user_id,
            'produk_id' => $request->produk_id,
            'qty' => $request->qty,
        ]);
        return response()->json([
            'message' => 'SUUCCESS',
            'data' => $data
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/list-keranjang/{id}",
     *      tags={"Keranjang"},
     *      summary="user id",
     *      description="menampilkan semua data keranjang berdasarkan user id",
     *      operationId="ListKeranjang",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="user id",
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
    public function list_keranjang($id){
        $data = Keranjang::with(['produk' => function($query) {
            $query->orderBy('created_at','desc');
        }])->where('user_id', $id)->latest()->get();
        
        $data->each(function ($item) {
            $item->produk->thumbnail = url('https://backendwin.spero-lab.id/storage/image/' . $item->produk->thumbnail);
        });
        return response()->json([
            'message' => 'SUCCESS',
            'keranjang' => $data
        ]);
    }

     /**
     * @OA\Delete(
     *      path="/delete-keranjang/{id}",
     *      tags={"Keranjang"},
     *      summary="Keranjang id",
     *      description="menghapus keranjang berdasarkan id keranjang",
     *      operationId="DeleteKeranjang",
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function delete_keranjang($id){
        $data = Keranjang::find($id)->delete();
        return response()->json([
            'message' => 'SUCCESS',
            'data' => $data,
        ]);
    }

     /**
     * @OA\Get(
     *      path="/api/detail-toko/{id}",
     *      tags={"Toko"},
     *      summary="Menampilkan detail Toko berdasarkan ID",
     *      description="Menampilkan detail Toko berdasarkan ID yg diberikan",
     *      operationId="DetailToko",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="ID Toko yang akan ditampilkan",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
     *      @OA\Response(
     *          response="default",
     *          description="return array model Toko"
     *      )
     * )
     */

    public function detail_toko($id){
        $toko = Toko::find($id);
        $kategori = KategoriProduk::select('id','kategori')->where('toko_id',$id)->get();
        $produk = Produk::where('toko_id',$id)->get();

        $toko->logo = url('https://backendwin.spero-lab.id/storage/image/' . $toko->logo);
        $produk->each(function ($item) {
            $item->thumbnail = url('https://backendwin.spero-lab.id/storage/image/' . $item->thumbnail);
        });
        return response()->json([
            'massage' => 'SUCCESS',
            'toko' => $toko,
            'kategori' => $kategori,
            'produk' => $produk,
        ]);
    }

     /**
     * @OA\Get(
     *      path="/api/list-event",
     *      tags={"Event"},
     *      description="menampilkan semua event",
     *      operationId="ListEvent",
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function list_event(){
        $now = Carbon::now(); 
        $data = Event::where('status_data', 1)
        ->whereNot('tgl_selesai','<', $now)
        ->get();

        $data->each(function ($item) use ($now){
            $item->gambar = url('https://backendwin.spero-lab.id/storage/image/' . $item->gambar);
            if ($item->tgl_selesai < $now) {
                $item->status_event = 1;
            } else if ($item->tgl_mulai <= $now) {
                $item->status_event = 'Sedang Berlangsung';
            } else {
                $item->status_event = 'Coming Soon';
            }
            
        });
        return response()->json([
            'message' => 'SUCCESS',
            'data' => $data,
        ]);
    }

      /**
     * @OA\Get(
     *      path="/api/detail-event/{id}",
     *      tags={"Event"},
     *      summary="Menampilkan detail event berdasarkan ID",
     *      description="Menampilkan detail event berdasarkan ID yg diberikan",
     *      operationId="DetailEvent",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="ID event yang akan ditampilkan",
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
    public function detail_event($id){
        $event = Event::find($id);
        $detail_gambar_event = GambarEvent::where('event_id',$id)->get();

        $event->gambar = url('https://backendwin.spero-lab.id/storage/image/' . $event->gambar);

        $detail_gambar_event->each(function ($item){
            $item->gambar = url('https://backendwin.spero-lab.id/storage/image/' . $item->gambar);
        });

        return response()->json([
            'event' => $event,
            'detail_gambar_event' => $detail_gambar_event
        ]);
    }

   /**
 * @OA\Post(
 *      path="/api/bukti-pembayaran-event",
 *      tags={"Event"},
 *      summary="Event",
 *      description="masukkan user id, event id, bukti bayar, id peserta",
 *      operationId="event",
 *      @OA\RequestBody(
 *          required=true,
 *          description="",
 *          @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                  @OA\Property(property="user_id", type="integer"),
 *                  @OA\Property(property="event_id", type="integer"),
 *                  @OA\Property(property="peserta_id", type="integer"),
 *                  @OA\Property(property="bukti_bayar", type="file", format="binary"),
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response="default",
 *          description=""
 *      )
 * )
 */

    public function bukti_pembayaran_event(Request $request){
        // $validator = Validator::make($request->all(), [
        //     'user_id'     => 'required',
        //     'event_id'     => 'required',
        //     'bukti_bayar'     => 'required|image|mimes:jpeg,jpg,png',
        //     'peserta_id'     => 'required',
        // ]);
        // if($validator->fails()){
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Ada Kesalahan',
        //         'data' => $validator->errors()
        //     ], 401);

        // }

        // try {
            $bukti_bayar = $request->file('bukti_bayar');
            $bukti_bayar->storeAs('public/image', $bukti_bayar->hashName());
            $event = PembayaranEvent::create([
                'user_id' => $request->user_id,
                'event_id' => $request->event_id,
                'bukti_bayar' => $request->bukti_bayar->hashName(),
                'peserta_event_id' => $request->peserta_id,
            ]);
            $success = true;
            $message = 'data berhasil disimpan';
            $res = [
                'success' => $success,
                'message' => $message,
                'data' => $event,
            ];

            TLogApi::create([
                'k_t' => 'terima',
                'object' => 'mobile',
                'data' => json_encode([
                    'event' => $event,
                ]),
                'result' => json_encode($res),
            ]);

        // } catch (Excpetion $e) {
        //     $abc = PembayaranEvent::create([
        //         'user_id' => $request->user_id ?? null,
        //         'event_id' => $request->event_id ?? null,
        //         'bukti_bayar' => $request->bukti_bayar->hashName() ?? null,
        //         'peserta_event_id' => $request->peserta_id ?? null,
        //     ]);
        //     $success = false;
        //     $message = 'error';
        //     $res = [
        //         'success' => $success,
        //         'message' => $message,
        //         'data' => $abc,
        //     ];

        //     TLogApi::create([
        //         'k_t' => 'terima',
        //         'object' => 'mobile',
        //         'data' => json_encode([
        //             'event' => $event,
        //         ]),
        //         'result' => json_encode($res),
        //     ]);
        // }
       
        return response()->json($res);

    }

     
    public function detail_pembayaran_event($id){
        $event = PembayaranEvent::where('user_id', $id)->get();
        return response()->json([
            'message' => 'SUCCESS',
            'data' => $event 
        ]);
    }

     /**
     * @OA\Post(
     *      path="/api/detail-kategori-toko",
     *      tags={"Toko"},
     *      summary="Kategori",
     *      description="Menampilkan semua produk berdasarkan kategori toko yg dipillih",
     *      operationId="Kategori",
     *        @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              required={"kategori_id", "toko_id"},
     *              @OA\Property(property="kategori_id", type="integer"),
     *              @OA\Property(property="toko_id", type="integer"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description="return array model produk"
     *      )
     * )
     */
    public function daftar_produk_berdasarkan_kategori_toko(Request $request){

        $kategoriproduk = Produk::where('toko_id',$request->toko_id)->where('kategoriproduk_id',$request->kategori_id)->where('status','active')->where('stok', '>', 0)->get();
        
        $kategoriproduk->each(function ($item) {
            $item->thumbnail = url('https://backendwin.spero-lab.id/storage/image/' . $item->thumbnail);
        });
        return response()->json([
            'message' => 'SUCCESS',
            'kategoriproduk' => $kategoriproduk
        ]);
    }

     /**
     * @OA\Post(
     *      path="/api/form-peserta-event",
     *      tags={"Event"},
     *      summary="Event",
     *      description="masukkan nama,email,notelp,jumlahtiket,eventid",
     *      operationId="Event",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              required={"nama","email","no_telp","jumlah_tiket","event_id"},
     *              @OA\Property(property="nama", type="string"),
     *              @OA\Property(property="email", type="string", format="email"),
     *              @OA\Property(property="no_telp", type="integer"),
     *              @OA\Property(property="jumlah_tiket", type="integer"),
     *              @OA\Property(property="event_id", type="integer"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function form_peserta_event(Request $request){
        $validator = Validator::make($request->all(), [
            'nama'     => 'required',
            'email'     => 'required|email',
            'no_telp'     => 'required',
            'jumlah_tiket'     => 'required',
            'event_id'     => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Ada Kesalahan',
                'data' =>$validator->errors()
            ], 401);
        }
        $data = PesertaEvent::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'jumlah_tiket' => $request->jumlah_tiket,
            'event_id' => $request->event_id,
        ]);

        return response()->json([
            'message' => 'SUCCESS',
            'peserta_event' => $data
        ]);
    }

    
}