<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('audience_statement', function (Blueprint $table) {
            $table->unsignedBigInteger('audience_id');
            $table->foreign('audience_id', 'audience_id_fk_5749586')->references('id')->on('audience')->onDelete('cascade');
            $table->unsignedBigInteger('statement_id');
            $table->foreign('statement_id', 'statement_id_fk_5749586')->references('id')->on('statements')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('audience_statement');
    }
};
