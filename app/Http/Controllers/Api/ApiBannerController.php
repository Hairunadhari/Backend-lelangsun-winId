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
         *      path="/api/list-banner",
         *      tags={"Banner"},
         *  security={{ "bearer_token":{} }},
         *      summary="List Banner Utama",
         *      description="menampilkan semua banner utama, diskon, spesial",
         *      operationId="banner utama",
         *      @OA\Response(
         *          response=200,
         *          description="Success",
         *   @OA\JsonContent(
                         type="object",
                         @OA\Property(property="success", type="boolean", example="true"),
                         @OA\Property(property="bannerUtama", type="string", example="..."),
                         @OA\Property(property="bannerDiskon", type="string", example="..."),
                         @OA\Property(property="bannerSpesial", type="string", example="..."),
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

    public function list_banner(){
        $bannerUtama = BannerUtama::all();
        $bannerUtama->each(function ($item) {
            $item->gambar = env('APP_URL').'/storage/image/' . $item->gambar;
        });

        $bannerDiskon = BannerDiskon::all();
        $bannerDiskon->each(function ($item) {
            $item->gambar = env('APP_URL').'/storage/image/' . $item->gambar;
        });

        $bannerSpesial = BannerSpesial::all();
        $bannerSpesial->each(function ($item) {
            $item->gambar = env('APP_URL').'/storage/image/' . $item->gambar;
        });

        return response()->json([
            'success'=>true,
            'bannerUtama' => $bannerUtama,
            'bannerDiskon' => $bannerDiskon,
            'bannerSpesial' => $bannerSpesial,
        ]);
    }

   
   
}
