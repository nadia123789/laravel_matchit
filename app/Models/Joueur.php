<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Joueur extends Model
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

    // Optionally, you can hash the password field automatically when setting it
    protected static function booted()
    {
        static::creating(function ($joueur) {
            $joueur->password = bcrypt($joueur->password); // Hash the password before saving
        });
    }
}
