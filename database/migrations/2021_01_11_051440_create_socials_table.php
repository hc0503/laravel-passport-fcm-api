<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socials', function (Blueprint $table) {
            $table->id();
            $table->char('guid', 36)->unique()->nullable();
            $table->foreignId('profile_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('provider')->nullable();
            $table->bigInteger('social_id')->nullable();
            $table->string('token')->nullable();
            $table->string('refreshToken')->nullable();
            $table->bigInteger('expiresIn')->nullable();
            $table->string('nickname')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socials');
    }
}
