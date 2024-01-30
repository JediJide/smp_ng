<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lexicons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('preferred_phrase')->nullable();
            $table->longText('guidance_for_usage')->nullable();
            $table->longText('non_preferred_terms')->nullable();
            $table->bigInteger('order_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
