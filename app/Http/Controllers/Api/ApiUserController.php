<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ApiUserController extends Controller
{
    /**
* @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="User's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="no_telephone",
     *         in="query",
     *         description="User's no telp",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="confirm_password",
     *         in="query",
     *         description="User's confirm password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="201", description="User registered successfully",
      *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example="true"),
 *          )
     * ),
     *     @OA\Response(response="422", description="Validation errors",
      *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="Invalid credentials"),
 *          )
     *    )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'no_telephone' => 'required|string|min:1|unique:users,no_telp',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password',
        ]);
        
        if($validator->fails()){
            $messages = $validator->messages();
            $alertMessage = $messages->first();
          
            return response()->json([
                'success' => false,
                'message' => $alertMessage
            ],422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_telp' => $request->no_telephone,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success'=>true,
            'message' => 'Registrasi Berhasil']);
    }
 

   /**
     * @OA\Post(
     *     path="/api/login",
     *      tags={"Auth"},
     *     summary="Authenticate user and generate JWT token",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Login Berhasil"),
     *     @OA\Response(response="401", description="Invalid credentials")
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = JWTAuth::attempt($credentials)) {
            $user = JWTAuth::user(); // Mendapatkan data pengguna dari token
            return response()->json([
                'success' => true,
                'user' => $user, // Menyertakan data pengguna dalam respons
                'token' => $token,
            ]);
        }
    
        return response()->json([
            'success'=>false,
            'message' => 'Invalid credentials'], 401);
    }


     /**
     * @OA\Get(
     *      path="/api/profil",
     *      tags={"Auth"},
     * security={{ "bearer_token":{} }},
     *      summary="Profil User",
     *      description="menampilkan profil user",
     *      operationId="profil",
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example="true"),
     *          )
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
    public function profil(){
        $id = Auth::user()->id;
        $user = User::find($id);
        return response()->json([
            'success'=>true,
            'data' => $user
        ]);
    }


     /**
     * @OA\Post(
     *      path="/api/update-profil",
     *      tags={"Auth"},
     * security={{ "bearer_token":{} }},
     *      summary="Akun",
     *      description="masukkan name, telp, alamat, foto, detail_alamat. DAPATKAN kota_kecamatan_provinsi_postal_code dari API MAPS",
     *      operationId="Akun",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                   @OA\Property(property="name", type="string"),
     *                   @OA\Property(property="kota_kecamatan_provinsi_postal_code", type="string", example="Gambir, Jakarta Pusat, DKI Jakarta. 10110"),
     *                   @OA\Property(property="detail_alamat", type="string", example="perumahan jakarta, blok 5 no.88"),
     *                  @OA\Property(property="foto", type="file", format="binary"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
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
     *      ),
     *  @OA\Response(
     *          response=500,
 *          description="Internal Server Error",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example="false"),
 *              @OA\Property(property="message", type="string", example="..."),
 *          )
     *      ),
     *  @OA\Response(
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
    public function update_profil(Request $request){
        $validator = Validator::make($request->all(), [
            'foto' => 'nullable|image|mimes:jpeg,jpg,png',
            'kota_kecamatan_provinsi_postal_code' => 'required|string',
            'detail_alamat' => 'required|string',
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
            $id = Auth::user()->id;
            $data = User::find($id);
            $arrayAlamat = explode(', ',$request->kota_kecamatan_provinsi_postal_code);
            $lokasiArray = explode('. ',end($arrayAlamat));
    
            $arrayAlamat[count($arrayAlamat)-1] = $lokasiArray[0];
            $arrayAlamat[] = $lokasiArray[1];
            $userData = [
                'name' => $request->name,
                'kecamatan' => $arrayAlamat[0],
                'kota' => $arrayAlamat[1],
                'provinsi' => $arrayAlamat[2],
                'postal_code' => $arrayAlamat[3],
                'detail_alamat' => $request->detail_alamat,
            ];
            if ($request->hasFile('foto')) {
                Storage::delete('public/image/'.$data->foto);
                $foto = $request->file('foto');
                $userData['foto'] = $foto->hashName();
                $foto->storeAs('public/image', $userData['foto']);

            }
            $data->update($userData);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success'=>false,
                'message'=>$th->getMessage()
            ],500);
            //throw $th;
        }
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }


     /**
     * @OA\Post(
     *      path="/api/logout",
     *      tags={"Auth"},
     * security={{ "bearer_token":{} }},
     *      summary="logout",
     *      description="logout user",
     *      operationId="logout",
     *       @OA\Response(
     *          response=200,
     *          description="Success",
     *   @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="true"),
                 )
     *      ),
     *      @OA\Response(
     *          response=500,
 *          description="Internal Server Error",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolea", example="false"),
 *              @OA\Property(property="message", type="string", example="..."),
 *          )
     *      )
     * )
     */
    public function logout()
    {
        try {
            Auth::logout();
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
        ],500);
        }
            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out',
        ]);
    }

     /**
     * @OA\Post(
     *      path="/api/forgot-password",
     *      tags={"Auth"},
     * security={{ "bearer_token":{} }},
     *      summary="forgot-password",
     *      description="masukkan email dan password baru",
     *      operationId="forgot-password",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              required={},
     *              @OA\Property(property="email", type="string", format="email"),
     *              @OA\Property(property="new_password", type="string"),
     *              @OA\Property(property="confirm_password", type="string"),
     * 
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *   @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="true"),
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
     *    @OA\Response(
                 response=422,
                 description="Validation Errors",
                 @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="false"),
                     @OA\Property(property="message", type="string", example="..."),
                 )
            ),
     *    @OA\Response(
                 response=404,
                 description="Not Found",
                 @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="false"),
                     @OA\Property(property="message", type="string", example="..."),
                 )
            ),
     * )
     */

     public function forgot_password(Request $request){
        $validator = Validator::make($request->all(), [
            'email'     => 'required|string',
            'new_password'     => 'required|string|min:8',
            'confirm_password'     => 'required|same:new_password',
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
            $user = User::where('email',$request->email)->first();
            if ($user == null) {
                return response()->json([
                    'success'=>false,
                    'message'=>'Email Not Found.'
                ],404);
            } 
            $user->update([
                'password'=>Hash::make($request->new_password),
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
            return response()->json([
                'success'=>false,
                'message'=> $th->getMessage()
            ],500);
        }
        return response()->json([
            'success'=>true,
            'message'=> 'Password successfully changed.'
        ]);
     }


      /**
     * @OA\Post(
     *      path="/api/refresh",
     *      tags={"Auth"},
     * security={{ "bearer_token":{} }},
     *      summary="refresh",
     *      description="refresh token",
     *      operationId="refresh",
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *   @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="true"),
                 )
     *      ),
     *    *      @OA\Response(
     *          response=401,
 *          description="Unauthorized",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="Unauthenticated"),
 *          )
     *      ),
     * )
     */

     public function refresh()
     {
       
         return response()->json([
             'success' => true,
             'user' => Auth::user(),
             'authorisation' => [
                 'token' => Auth::refresh(),
                 'type' => 'bearer',
             ]
         ]);
     }
}
