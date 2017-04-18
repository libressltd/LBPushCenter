<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_workers', function (Blueprint $table) {
            $table->char('id', 32);
            $table->primary('id');
            
            $table->timestamps();
        });

        Schema::table('push_notification_sents', function (Blueprint $table) {
            $table->index('worker_id');
            $table->foreign('worker_id')
                  ->references('id')->on('push_workers')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('push_notification_sents', function (Blueprint $table) {
            $table->dropForeign('worker_id');
        });

        Schema::dropIfExists('push_workers');
    }
}
