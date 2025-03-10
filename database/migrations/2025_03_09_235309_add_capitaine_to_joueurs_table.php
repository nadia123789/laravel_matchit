<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCapitaineToJoueursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('joueurs', function (Blueprint $table) {
            $table->boolean('capitaine')->default(false);  // Ajouter la colonne 'capitaine' avec valeur par défaut false
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('joueurs', function (Blueprint $table) {
            $table->dropColumn('capitaine');  // Supprimer la colonne 'capitaine' si la migration est annulée
        });
    }
}
