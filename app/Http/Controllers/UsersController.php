<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

 use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Tymon\JWTAuth\Facades\JWTAuth;
 
class UsersController extends Controller
{
    public function register(Request $request)
    {
        // Validation des champs reçus depuis le frontend
        $validator = Validator::make($request->all(), [
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'age' => 'required|integer',
            'sexe' => 'required|string',
            'cin' => 'required|string|max:255',
            'telephone' => 'required|string|max:15',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:12',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Création de l'utilisateur avec les nouveaux champs
        $user = User::create([
            'name' => $request->prenom . ' ' . $request->nom, // Concatenation prénom et nom dans le champ "name"
            'prenom' => $request->prenom, // Prénom
            'cin' => $request->cin, // CIN
            'telephone' => $request->telephone, // Téléphone
            'age' => $request->age, // Âge
            'sexe' => $request->sexe, // Sexe
            'email' => $request->email, // Email
            'password' => Hash::make($request->password), // Hash du mot de passe
            
        ]);

        // Création du token JWT pour l'utilisateur
        $token = JWTAuth::fromUser($user);

        // Retour de la réponse avec message, utilisateur et token
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }
    public function login(Request $request)
    {
         $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|max:12',
        ]);
    
         $user = User::where('email', $request->email)->first();
    
       
if (!$user) {
    return response()->json(['error' => 'Invalid email address'], 401);
} elseif (!Hash::check($request->password, $user->password)) {
    return response()->json(['error' => 'Incorrect password'], 401);
}

         $token = JWTAuth::fromUser($user);
    
         return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user->makeHidden(['password', 'created_at', 'updated_at']),  // Hide sensitive fields
        ]);
    }
    
    public function dashboard(Request $request)
    {
         try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Token is expired'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Token not provided'], 401);
        }
    
         return response()->json([
            'user' => $user,
            'message' => 'Welcome to your dashboard'
        ]);
    }
    public function logout(Request $request)
    {
        try {
             $token = JWTAuth::getToken();
    
            if (!$token) {
                return response()->json(['error' => 'Token not provided'], 401);
            }
    
             JWTAuth::invalidate($token);
    
            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Failed to log out'], 500);
        }
    }    
}

