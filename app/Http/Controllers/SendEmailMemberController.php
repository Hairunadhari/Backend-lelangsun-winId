<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\User;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use App\Models\PesertaEvent;
use Illuminate\Http\Request;
use App\Mail\SendEmailMember;
use App\Models\PembayaranEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SendEmailMemberController extends Controller
{
    

    public function send_email_member($id){
        try {
            DB::beginTransaction();
            $data = PesertaEvent::with('pembayaran_event','event')->find($id);
            $barcode = new DNS2D();
            QrCode::format('png')->generate('Nama Peserta : '.$data->nama.' | '.'Judul Event : '.$data->event->judul, '../public/storage/qrcode/qrcode-'.$data->id.'.png');
            Mail::to($data->email)->send(new SendEmailMember($data));
            // $data->update([
            //     'status_verif' => 1
            // ]);

            DB::commit();
        } catch (Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->back()->with(['error' => 'Data Gagal diverifikasi!']);
        }
        return redirect()->back()->with(['success' => 'Data berhasil diverifikasi!']);


       
        // $barcodeHTML = $barcode->getBarcodeHTML($data->nama, 'QRCODE');
        // $barcodeHTML = $barcode->getBarcodePNGPath($data->nama, 'QRCODE');
        // $qr = \DNS2D::getBarcodePNGPath('tes', 'QRCODE');
        // dd($qr);
        // $barcodeHTML->move(public_path().'/storage/qrcode/', 'tes');
        // echo DNS1D::getBarcodeSVG('4445645656', 'PHARMA2T');
        // echo DNS1D::getBarcodeHTML('4445645656', 'PHARMA2T');
        // echo '<img src="data:image/png,' . DNS1D::getBarcodePNG('4', 'C39+') . '" alt="barcode"   />';
        // echo DNS1D::getBarcodePNGPath('4445645656', 'PHARMA2T');
        // echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG('4', 'C39+') . '" alt="barcode"   />';
        // return 'sukses';
        // Mail::to($data->email)->send(new SendEmailMember($data, $barcodeHTML));
        // $data->update([
        //     'status_verif' => 1
        // ]);
 
        // return redirect()->back()->with(['success' => 'Data berhasil diverifikasi!']);
    }
}
