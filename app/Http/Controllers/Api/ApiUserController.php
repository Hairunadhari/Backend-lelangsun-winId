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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
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
            return response()->json([
                'success'=>true,
                'token' => $token]);
        }
    
        return response()->json([
            'success'=>false,
            'message' => 'Invalid credentials'], 401);
    }


     /**
     * @OA\Get(
     *      path="/api/profil",
     *      tags={"Auth"},
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
     *      summary="Akun",
     *      description="masukkan name, telp, alamat, foto",
     *      operationId="Akun",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                   @OA\Property(property="name", type="string"),
     *                   @OA\Property(property="no_telp", type="integer"),
     *                   @OA\Property(property="alamat", type="string"),
     *                  @OA\Property(property="foto", type="file", format="binary"),
     *                  @OA\Property(property="_method", type="string", example="PUT"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function update_profil(Request $request){
        $validator = Validator::make($request->all(), [
            'foto' => 'image|mimes:jpeg,jpg,png',
        ]);
         try {
            DB::beginTransaction();
            $id = Auth::user()->id;
            $data = User::find($id);
            $userData = [
                'name' => $request->nama,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
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
            ],400);
            //throw $th;
        }
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }


    
}
