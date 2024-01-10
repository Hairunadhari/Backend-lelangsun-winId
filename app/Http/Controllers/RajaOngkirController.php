<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;

class RajaOngkirController extends Controller
{
    public function import_province(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "key: ".env('API_KEY_RAJAONGKIR')."",
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $data = json_decode($response, true); // Decode JSON response

            if ($data['rajaongkir']['status']['code'] == 200) {
                $provinces = $data['rajaongkir']['results'];

                foreach ($provinces as $province) {
                    Province::create([
                        'id' => $province['province_id'],
                        'provinsi' => $province['province'],
                    ]);
                }

                echo 'Import provinsi RajaOngkir berhasil';
            } else {
                echo 'Error in RajaOngkir API response';
            }
        }

    }

    public function import_city(){
        $curl = curl_init();
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.rajaongkir.com/starter/city",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "key: ".env('API_KEY_RAJAONGKIR')."",
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $data = json_decode($response, true); // Decode JSON response

            if ($data['rajaongkir']['status']['code'] == 200) {
                $provinces = $data['rajaongkir']['results'];

                foreach ($provinces as $province) {
                    City::create([
                        'id' => $province['city_id'],
                        'province_id' => $province['province_id'],
                        'type' => $province['type'],
                        'city_name' => $province['city_name'],
                        'postal_code' => $province['postal_code'],
                    ]);
                }

                echo 'Import City RajaOngkir berhasil';
            } else {
                echo 'Error in RajaOngkir API response';
            }
        }
    }
}
