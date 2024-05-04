<?php

namespace App\Http\Controllers\Api;

use Validator;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\TLogApi;
use App\Models\GambarEvent;
use App\Models\PesertaEvent;
use Illuminate\Http\Request;
use App\Models\PembayaranEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiEventController extends Controller
{
        /**
     * @OA\Get(
     *      path="/api/list-event",
     *      tags={"Event"},
     *   security={{ "bearer_token":{} }},
     *      description="menampilkan semua event",
     *      operationId="ListEvent",
     *        @OA\Response(
     *          response=200,
     *          description="Success",
     *   @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="true"),
                 )
     *      ),
     *      @OA\Response(
     *          response=401,
 *          description="Unauthorized",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="Unauthenticated"),
 *          )
     *      )
     * )
     */
    public function list_event(){
        $now = Carbon::now(); 
        $data = Event::where('status_data', 1)
        ->whereNot('tgl_selesai','<', $now)
        ->get();

        $data->each(function ($item) use ($now){
            $item->gambar = env('APP_URL').'/storage/image/' . $item->gambar;
            if ($item->tgl_selesai < $now) {
                $item->status_event = 1;
            } else if ($item->tgl_mulai <= $now) {
                $item->status_event = 'Sedang Berlangsung';
            } else {
                $item->status_event = 'Coming Soon';
            }
            
        });
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

      /**
     * @OA\Get(
     *      path="/api/detail-event/{id}",
     *      tags={"Event"},
     *   security={{ "bearer_token":{} }},
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
     *       @OA\Response(
     *          response=200,
     *          description="Success",
     *   @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="true"),
                 )
     *      ),
     *      @OA\Response(
     *          response=401,
 *          description="Unauthorized",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="Unauthenticated"),
 *          )
     *      )
     * )
     */
    public function detail_event($id){
        $event = Event::find($id);
        $detail_gambar_event = GambarEvent::where('event_id',$id)->get();

        $event->gambar = env('APP_URL').'/storage/image/' . $event->gambar;

        $detail_gambar_event->each(function ($item){
            $item->gambar = env('APP_URL').'/storage/image/' . $item->gambar;
        });

        return response()->json([
            'success'=>true,
            'data' => $event,
            'detail_gambar_event' => $detail_gambar_event
        ]);
    }

   /**
 * @OA\Post(
 *      path="/api/bukti-pembayaran-event",
 *      tags={"Event"},
 * security={{ "bearer_token":{} }},
 *      summary="Event",
 *      description="masukkan event id, bukti bayar, id peserta",
 *      operationId="event",
 *      @OA\RequestBody(
 *          required=true,
 *          description="",
 *          @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                  @OA\Property(property="event_id", type="integer"),
 *                  @OA\Property(property="peserta_id", type="integer"),
 *                  @OA\Property(property="bukti_bayar", type="file", format="binary"),
 *              )
 *          )
 *      ),
 *      @OA\Response(
     *          response=200,
     *          description="Success",
     *   @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="true"),
                     @OA\Property(property="message", type="string", example="..."),
                 )
     *      ),
     *      @OA\Response(
     *          response=500,
 *          description="Internal Server Error",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example="false"),
 *              @OA\Property(property="message", type="string", example="..."),
 *          )
     *      ),
     *      @OA\Response(
     *          response=401,
 *          description="Unauthorized",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="Unauthenticated"),
 *          )
     *      ),
     *    @OA\Response(
                 response=422,
                 description="Validation Errors",
                 @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="false"),
                     @OA\Property(property="message", type="string", example="..."),
                 )
            ),
 * )
 */

    public function bukti_pembayaran_event(Request $request){
        $validator = Validator::make($request->all(), [
            'event_id'     => 'required',
            'bukti_bayar'     => 'required|image|mimes:jpeg,jpg,png',
            'peserta_id'     => 'required',
        ]);
        if($validator->fails()){
            $messages = $validator->messages();
            $alertMessage = $messages->first();
          
            return response()->json([
                'success' => false,
                'message' => $alertMessage
            ],422);
        }

        try {
        DB::beginTransaction();
            $bukti_bayar = $request->file('bukti_bayar');
            $bukti_bayar->storeAs('public/image', $bukti_bayar->hashName());
            $event = PembayaranEvent::create([
                'user_id' => Auth::user()->id,
                'event_id' => $request->event_id,
                'bukti_bayar' => $request->bukti_bayar->hashName(),
                'peserta_event_id' => $request->peserta_id,
            ]);
          
        DB::commit();

        } catch (\Throwable $th) {
        DB::rollBack();
            return response()->json([
                'success'=>false,
                'message'=>$th->getMessage()
            ],500);
        }
       
        return response()->json([
            'success'=>true,
            'message'=> 'Data berhasil disimpan'
        ]);

    }

      /**
     * @OA\Get(
     *      path="/api/detail-pembayaran-event/{id}",
     *      tags={"Event"},
    * security={{ "bearer_token":{} }},
     *      summary="Menampilkan detail produk berdasarkan ID",
     *      description="Menampilkan detail produk berdasarkan ID yg diberikan",
     *      operationId="DetailPembayaranEvent",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="menampilkan semua detail pembayaran event user",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Success",
     *   @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="true"),
                 )
     *      ),
     *      @OA\Response(
     *          response=401,
 *          description="Unauthorized",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="Unauthenticated"),
 *          )
     *      )
     * )
     */
    public function detail_pembayaran_event(){
        $event = PembayaranEvent::where('user_id', Auth::user()->id)->get();
        return response()->json([
            'success' => true,
            'data' => $event 
        ]);
    }

      /**
     * @OA\Post(
     *      path="/api/form-peserta-event",
     *      tags={"Event"},
     * security={{ "bearer_token":{} }},
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
     *          response=200,
     *          description="Success",
     *   @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="true"),
                     @OA\Property(property="message", type="string", example="..."),
                 )
     *      ),
     *      @OA\Response(
     *          response=500,
 *          description="Internal Server Error",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example="false"),
 *              @OA\Property(property="message", type="string", example="..."),
 *          )
     *      ),
     *      @OA\Response(
     *          response=401,
 *          description="Unauthorized",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="Unauthenticated"),
 *          )
     *      ),
     *    @OA\Response(
                 response=422,
                 description="Validation Errors",
                 @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="false"),
                     @OA\Property(property="message", type="string", example="..."),
                 )
            ),
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
            $messages = $validator->messages();
            $alertMessage = $messages->first();
          
            return response()->json([
                'success' => false,
                'message' => $alertMessage
            ],422);
        }
        try {
            DB::beginTransaction();
            $data = PesertaEvent::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'jumlah_tiket' => $request->jumlah_tiket,
                'event_id' => $request->event_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
            return response()->json([
                'success'=>false,
                'message'=>$th->getMessage(),
            ],500);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
