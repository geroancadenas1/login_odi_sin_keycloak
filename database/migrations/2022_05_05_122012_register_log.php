<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('REGISTER_LOGS', function (Blueprint $table) {
            $table->id();
            $table->string('PROCESO', 500);
            $table->string('ACCION', 500);
            $table->string('ID_REGISTRO', 100);
            $table->text('DATA_PROCESO', 15000);
            $table->dateTime('FECHA_PROCESO');
            $table->string('IP_LOCAL', 100);
            $table->string('IP_REMOTE', 100);
            $table->string('ID_USER', 100);
            $table->timestamps();
        });
    }

    
    public function down()
    {
        //
    }
};
