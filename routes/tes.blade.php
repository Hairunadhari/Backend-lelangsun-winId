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
*              @OA\Property(property="message", type="string", example="Silahkan Lengkapi Kolom Nama Dengan Benar!"),
*          ),
*      ),
* )
*/

public function register(Request $request){
   $validator = Validator::make($request->all(), [
       'name'     => 'required',
       'email'     => 'required|email|unique:users,email',
       'password'     => 'required|min:8',
       'confirm_password'     => 'required|same:password',
   ],
   [
     'name.required' => 'Silahkan Lengkapi Kolom Nama Dengan Benar!',  
     'email.required' => 'Silahkan Lengkapi Kolom Email Dengan Benar!',  
     'email.email' => 'Silahkan Lengkapi Kolom Email Dengan Valid!',  
     'email.unique' => 'Email Sudah Terdaftar!',  
     'password.required' => 'Silahkan Lengkapi Kolom Password Dengan Benar!!',  
     'password.min' => 'Password Minimal 8 Karakter!',  
     'confirm_password.required' => 'Silahkan Lengkapi Kolom Konfirmasi Password Dengan Benar!',  
     'confirm_password.same' => 'Konfirmasi Password Tidak Cocok!',  
   ]);
   if($validator->fails()){
       $messages = $validator->messages();
       $alertMessage = $messages->first();
     
       return response()->json([
           'success' => false,
           'message' => $alertMessage
       ],401);
   }
  
   try {
       DB::beginTransaction();
       $input = $request->all();
       $input['password'] = bcrypt($input['password']);
       $user = User::create($input);
       
       $success['token'] = $user->createToken('auth_token')->plainTextToken;
       $success['name'] = $user->name;
       DB::commit();
   } catch (Throwable $th) {
       DB::rollBack();
       return response()->json([
           'message' => 'ERROR',
           'data' => $th,
       ],401);
   }

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
       $success['alamat'] = $auth->alamat;

       return response()->json([
           'success' => true,
           'message' => 'Login Berhasil',
           'data' => $success,
       ]);
   } else {
       return response()->json([
           'success' => false,
           'message' => 'Cek Email dan Password lagi',
       ], 401);
   }
}
 