<?php

namespace App\Http\Controllers;

use App\Models\User;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use App\Models\PesertaEvent;
use Illuminate\Http\Request;
use App\Mail\SendEmailMember;
use App\Models\PembayaranEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SendEmailMemberController extends Controller
{
    

    public function send_email_member($id){
        
        // $data = PesertaEvent::with('pembayaran_event','event')->find($id);
        // $barcode = new DNS2D();
        // $barcodeHTML = $barcode->getBarcodeHTML($data->nama, 'QRCODE');
        // Mail::to($data->email)->send(new SendEmailMember($data, $barcodeHTML));
        // $data->update([
        //     'status_verif' => 1
        // ]);
 
        // return redirect()->back()->with(['success' => 'Data berhasil diverifikasi!']);


        $data = PesertaEvent::with('pembayaran_event','event')->find($id);
        $barcode = new DNS2D();
        QrCode::format('png')->generate('Make me into a QrCode!', '../public/qrcode.png');
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
