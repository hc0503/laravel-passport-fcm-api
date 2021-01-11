<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->char('guid', 36)->unique()->nullable();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('type')->default('PERFORMER');
            // PERFORMER
            $table->string('cover_photo')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('stage_name')->nullable();
            $table->text('about_you')->nullable();
            $table->json('categories')->nullable();
            $table->json('tags')->nullable();
            // AUDIENCE
            $table->string('name')->nullable();
            $table->json('interested_in')->nullable();
            $table->string('organization_type')->nullable();

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
        Schema::dropIfExists('profiles');
    }
}
