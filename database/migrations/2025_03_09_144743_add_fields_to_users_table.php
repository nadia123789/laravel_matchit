<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Appliquer la migration (ajouter les colonnes).
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cin')->nullable();         // Add CIN field
            $table->string('telephone')->nullable();   // Add telephone field
            $table->integer('age')->nullable();        // Add age field
            $table->string('sexe')->nullable();        // Add sex field
        });
    }

    /**
     * Annuler la migration (supprimer les colonnes).
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['prenom', 'cin', 'telephone', 'age', 'sexe']);
        });
    }
}
