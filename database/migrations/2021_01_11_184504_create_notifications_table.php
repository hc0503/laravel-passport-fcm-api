<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->char('guid', 36)->unique()->nullable();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->enum(
                'type',
                ['PERFORMANCE', 'AUDIENCE']
            )->default('PERFORMANCE');
            $table->enum(
                'notification_type',
                ['FOLLOW', 'CLAP', 'GIG', 'INVITE', 'MIC']
            )->default('FOLLOW');
            $table->string('title')->nullable();
            $table->string('body')->nullable();
            $table->boolean('is_archive')->default(0);
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
        Schema::dropIfExists('notifications');
    }
}
