<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        //verifica si se cumplen o no las validaciones
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        $email = $request->input('email');
        $password = $request->input('password');

        # validar que email y password existan en la base de datos
        $user = User::where('email', $email)->first();
        
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        

        // definir el tiempo de expiracion en minutos
        $tokenExpiration = config('sanctum.token_expiration', 30); // 30 minutes

        // crear el token personal
        $token = $user->createToken('api-token')->plainTextToken;

        // retornar el token con informacion adicional
        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $tokenExpiration * 60, // 60 segundos
        ], 200);
    }


    public function refreshToken(Request $request){

        // validar la solicitud
        $validator = Validator::make($request->all(), [
            'token' =>'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        // buscar el token en la base de datos
        $accessToken = PersonalAccessToken::findToken($request->token);

        if (!$accessToken) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        // definir el tiempo de expiracion 
        $expirationMinutes = config('sanctum.token_expiration',30); //30 minutes

        //verificar si el token ha expirado
        $tokenExpiry = $accessToken->created_at->addMinutes($expirationMinutes);

        //comprobar si el token no ha expirado
        if(!Carbon::now()->greaterThan($tokenExpiry)){
            return response()->json(['error' => 'Token is not expired'],400);
        }

        //eliminar el token expirado
        $accessToken->delete();

        //crear un nuevo token 
        $user = $accessToken->tokenable;
        $newToken = $user->createToken('api-token')->plainTextToken;

        //retornar el nuevo token con informacion adicional
        return response()->json([
            'token' => $newToken,
            'token_type' => 'Bearer',
            'expires_in' => $expirationMinutes * 60, // 60 segundos
        ], 200);
    }
}
