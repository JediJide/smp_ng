<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->unsignedBigInteger('therapy_area_id')->nullable();
            $table->foreign('therapy_area_id', 'therapy_area_fk_5749553')->references('id')->on('therapy_areas');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id', 'category_fk_5749554')->references('id')->on('categories');
        });
    }
};
