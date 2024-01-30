<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reference_statement', function (Blueprint $table) {
            $table->unsignedBigInteger('statement_id');
            $table->foreign('statement_id', 'statement_id_fk_5749696')->references('id')->on('statements')->onDelete('cascade');
            $table->unsignedBigInteger('reference_id');
            $table->foreign('reference_id', 'reference_id_fk_5749696')->references('id')->on('references')->onDelete('cascade');
        });
    }
};
