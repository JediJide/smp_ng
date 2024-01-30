<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('references', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('file_name')->nullable();
            $table->longText('url')->nullable();
            $table->longText('temporary_url')->nullable();
            $table->string('ip_address')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('tag')->nullable();
            $table->smallInteger ('is_linked')->nullable ();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
