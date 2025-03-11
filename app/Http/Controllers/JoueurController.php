<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use App\Models\Joueur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
class JoueurController extends Controller
{
    // Handle player registration
    public function register(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'age' => 'required|integer|min:18',
            'sexe' => 'required|in:homme,femme',
            'cin' => 'required|string|max:20',
            'telephone' => 'required|string|max:15',
            'email' => 'required|email|unique:joueurs,email',
            'password' => 'required|string|min:6|confirmed',  // Password confirmation must be handled
        ]);

        // If validation fails, return with errors
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Hash the password before saving it
        $password = Hash::make($request->password);

        // Create a new joueur record
        $joueur = Joueur::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'age' => $request->age,
            'sexe' => $request->sexe,
            'cin' => $request->cin,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'password' => $password, // Store the hashed password
        ]);

        // Return the newly created joueur
        return response()->json([
            'message' => 'Inscription réussie !',
            'joueur' => $joueur
        ], 201);
    }

    public function login(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:8|max:12',
            ]);
        
            // Find the user by email
            $joueur = Joueur::where('email', $request->email)->first();
        
            // Check if the user exists
            if (!$joueur) {
                return response()->json(['error' => 'Invalid email address'], 401);
            }
        
            // Check if the provided password matches the hashed password in the database
            if (!Hash::check($request->password, $joueur->password)) {
                return response()->json(['error' => 'Incorrect password'], 401);
            }
        
            // Generate a JWT token for the user
            $token = JWTAuth::fromUser($joueur);
        
            // Return a successful login response with the token and user data (excluding sensitive fields)
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => $joueur->makeHidden(['password', 'created_at', 'updated_at']),  // Hide sensitive fields
            ], 200);
        } catch (\Exception $e) {
            // Catch and log any error that occurs during the process
            Log::error('Login error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong. Please try again.'], 500);
        }
    }
    
    
    


}
