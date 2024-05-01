<?php

namespace App\Http\Controllers\Api;

use App\Models\BannerUtama;
use App\Models\BannerDiskon;
use Illuminate\Http\Request;
use App\Models\BannerSpesial;
use App\Http\Controllers\Controller;

class ApiBannerController extends Controller
{
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
            $item->gambar = env('APP_URL').'/storage/image/' . $item->gambar;
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
            $item->gambar = env('APP_URL').'/storage/image/' . $item->gambar;
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
            $item->gambar = env('APP_URL').'/storage/image/' . $item->gambar;
        });
        return response()->json([
            'data' => $data,
        ]);
    }
}
