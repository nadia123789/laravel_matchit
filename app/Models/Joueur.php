<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject; // Import the JWTSubject interface

class Joueur extends Model implements JWTSubject // Implement the JWTSubject interface
{
    use HasFactory;

    // Define the table if it doesn't follow Laravel's naming convention
    protected $table = 'joueurs';

    // Define which fields are mass assignable
    protected $fillable = [
        'prenom',
        'nom',
        'age',
        'sexe',
        'cin',
        'telephone',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',  // Hide the password field
        'created_at', // Hide created_at timestamp
        'updated_at', // Hide updated_at timestamp
    ];

    /**
     * Get the identifier that will be stored in the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Usually returns the primary key (id) of the user
    }

    /**
     * Get the custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return []; // You can add custom claims here if needed
    }
}
