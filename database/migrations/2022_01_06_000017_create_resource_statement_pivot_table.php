<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_statement', function (Blueprint $table) {
            $table->unsignedBigInteger('statement_id');
            $table->foreign('statement_id', 'statement_id_fk_5749695')->references('id')->on('statements')->onDelete('cascade');
            $table->unsignedBigInteger('resource_id');
            $table->foreign('resource_id', 'resource_id_fk_5749695')->references('id')->on('resources')->onDelete('cascade');
        });
    }
};
