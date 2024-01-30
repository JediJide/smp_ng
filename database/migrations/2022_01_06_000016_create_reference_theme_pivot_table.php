<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reference_theme', function (Blueprint $table) {
            $table->unsignedBigInteger('theme_id');
            $table->foreign('theme_id', 'theme_id_fk_5749586')->references('id')->on('themes')->onDelete('cascade');
            $table->unsignedBigInteger('reference_id');
            $table->foreign('reference_id', 'reference_id_fk_5749586')->references('id')->on('references')->onDelete('cascade');
        });
    }
};
