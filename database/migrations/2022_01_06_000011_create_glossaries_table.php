<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('glossaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('term')->nullable();
            $table->longText('definition')->nullable();
            $table->bigInteger('order_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
