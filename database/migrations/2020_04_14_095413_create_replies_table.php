<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('thread_id');
            $table->text('body');
            $table->timestamps();
        });

        // On exploite cette supression en cascade dans le thread controller (les deux choix se valent)
        // $table->foreign('thread_id') 
        //     ->references('id')
        //     ->on('threads')
        //     ->onDelete('cascade');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replies');
    }
}
