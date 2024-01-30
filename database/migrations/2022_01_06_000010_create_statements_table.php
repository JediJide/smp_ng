<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('statements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('description')->nullable();
            $table->boolean('is_notify_all')->default(0)->nullable();
            $table->integer('order_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
