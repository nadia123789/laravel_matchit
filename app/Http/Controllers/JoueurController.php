<?php

namespace App\Http\Controllers;

use App\Models\Joueur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth; // For handling JWT tokens

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

    // Handle player login
    public function login(Request $request)
{
    // Validate the incoming request
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    // Attempt to find the joueur by email
    $joueur = Joueur::where('email', $request->email)->first();

    // If the joueur doesn't exist or the password is incorrect, return an error
    if (!$joueur) {
        return response()->json(['error' => 'Email not found'], 401);
    }

    if (!Hash::check($request->password, $joueur->password)) {
        return response()->json(['error' => 'Incorrect password'], 401);
    }

    // Create a token for the authenticated joueur
    try {
        $token = JWTAuth::fromUser($joueur);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Could not create token'], 500);
    }

    // Return the token in the response
    return response()->json([
        'token' => $token,
        'joueur' => $joueur
    ]);
}

}
