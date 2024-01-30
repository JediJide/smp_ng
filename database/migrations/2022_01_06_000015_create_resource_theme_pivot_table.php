<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('resource_theme', function (Blueprint $table) {
            $table->unsignedBigInteger('theme_id');
            $table->foreign('theme_id', 'theme_id_fk_5749585')->references('id')->on('themes')->onDelete('cascade');
            $table->unsignedBigInteger('resource_id');
            $table->foreign('resource_id', 'resource_id_fk_5749585')->references('id')->on('resources')->onDelete('cascade');
        });
    }
};
