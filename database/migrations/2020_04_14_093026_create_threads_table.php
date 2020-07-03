<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('channel_id');
            $table->foreignId('best_reply_id')->nullable();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('replies_count')->default(0);
            $table->unsignedBigInteger('visits_count')->default(0);
            $table->string('title');
            $table->text('body');
            $table->timestamps();

            // J'ai préféré géré cela au niveau de PHP - Voir Reply boot
            // $table->foreign('best_reply_id')
            //     ->references('id')
            //     ->on('replies')
            //     ->onDelete('set null'); // et pas cascade... on ne veut pas supprimé le thread associé à la réponse, mais indiqué l'id comme null
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
}
