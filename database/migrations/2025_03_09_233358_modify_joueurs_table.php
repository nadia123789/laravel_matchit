<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyJoueursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('joueurs', function (Blueprint $table) {
            // Vérifier si la colonne id_equipe existe déjà avant d'essayer de l'ajouter
            if (!Schema::hasColumn('joueurs', 'id_equipe')) {
                $table->unsignedBigInteger('id_equipe')->nullable()->after('age');
                $table->foreign('id_equipe')->references('id_equipe')->on('equipes')->onDelete('SET NULL');
            }
            // Ajouter la colonne capitaine avec valeur par défaut false
            if (!Schema::hasColumn('joueurs', 'capitaine')) {
                $table->boolean('capitaine')->default(false)->after('sexe');
            }
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
            // Supprimer la clé étrangère et la colonne id_equipe si elle existe
            if (Schema::hasColumn('joueurs', 'id_equipe')) {
                $table->dropForeign(['id_equipe']);
                $table->dropColumn('id_equipe');
            }
            // Supprimer la colonne capitaine si elle existe
            if (Schema::hasColumn('joueurs', 'capitaine')) {
                $table->dropColumn('capitaine');
            }
        });
    }
}
