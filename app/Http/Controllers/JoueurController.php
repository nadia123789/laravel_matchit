<?php

namespace App\Http\Controllers;

use App\Models\Joueur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        // Create a new joueur record
        $joueur = Joueur::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'age' => $request->age,
            'sexe' => $request->sexe,
            'cin' => $request->cin,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'password' => $request->password, // Password is already hashed in the model
        ]);

        // Return the newly created joueur
        return response()->json([
            'message' => 'Inscription réussie !',
            'joueur' => $joueur
        ], 201);
    }
}
