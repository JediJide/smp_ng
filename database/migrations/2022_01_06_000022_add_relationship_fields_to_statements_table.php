<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('statements', function (Blueprint $table) {
            $table->unsignedBigInteger('therapy_area_id')->nullable();
            $table->foreign('therapy_area_id', 'therapy_area_fk_5749693')->references('id')->on('therapy_areas');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id', 'parent_fk_5749694')->references('id')->on('statements');
            $table->unsignedBigInteger('theme_id')->nullable();
            $table->foreign('theme_id', 'theme_fk_5749591')->references('id')->on('themes');
            $table->unsignedBigInteger('status_id')->nullable();
            $table->foreign('status_id', 'status_fk_5813909')->references('id')->on('statement_statuses');
        });
    }
};
