<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('file_mime_type')->nullable();
            $table->string('file_name')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->longText('url')->nullable();
            $table->longText('temporary_url')->nullable();
            $table->smallInteger('is_header_resource')->nullable();
            $table->string('ip_address')->nullable();
            $table->smallInteger('is_linked')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
