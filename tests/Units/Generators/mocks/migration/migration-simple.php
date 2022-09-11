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
    public function up()
    {
        Schema::create('resource_example', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('platform_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('title');
            $table->text('body');
            $table->boolean('is_featured');
            $table->date('published_at');
            $table->string('password');
            $table->integer('post_type');
            $table->integer('status');
            $table->unsignedBigInteger('created_by');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('platform_id')->references('id')->on('platforms');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('created_by')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resource_example');
    }
};