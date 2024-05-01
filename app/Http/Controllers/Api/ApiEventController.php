<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\TLogApi;
use App\Models\GambarEvent;
use Illuminate\Http\Request;
use App\Models\PembayaranEvent;
use App\Http\Controllers\Controller;

class ApiEventController extends Controller
{
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

        $event->gambar = env('APP_URL').'/storage/image/' . $event->gambar;

        $detail_gambar_event->each(function ($item){
            $item->gambar = env('APP_URL').'/storage/image/' . $item->gambar;
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
        $validator = Validator::make($request->all(), [
            'user_id'     => 'required',
            'event_id'     => 'required',
            'bukti_bayar'     => 'required|image|mimes:jpeg,jpg,png',
            'peserta_id'     => 'required',
        ]);
       

        try {
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

        } catch (Excpetion $e) {
            $abc = PembayaranEvent::create([
                'user_id' => $request->user_id ?? null,
                'event_id' => $request->event_id ?? null,
                'bukti_bayar' => $request->bukti_bayar->hashName() ?? null,
                'peserta_event_id' => $request->peserta_id ?? null,
            ]);
            $success = false;
            $message = 'error';
            $res = [
                'success' => $success,
                'message' => $message,
                'data' => $abc,
            ];

            TLogApi::create([
                'k_t' => 'terima',
                'object' => 'mobile',
                'data' => json_encode([
                    'event' => $event,
                ]),
                'result' => json_encode($res),
            ]);
        }
       
        return response()->json($res);

    }

     
    public function detail_pembayaran_event($id){
        $event = PembayaranEvent::where('user_id', $id)->get();
        return response()->json([
            'success' => true,
            'data' => $event 
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

        $data = PesertaEvent::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'jumlah_tiket' => $request->jumlah_tiket,
            'event_id' => $request->event_id,
        ]);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
