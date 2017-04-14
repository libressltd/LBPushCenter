<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushNotificationSentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_notification_sents', function (Blueprint $table) {
            $table->char('id', 32);
            $table->char('device_id', 32)->nullable();
            $table->text('title');
            $table->text('message');
            $table->char('worker_id', 32)->nullable();
            $table->integer("status_id");
            $table->timestamps();

            $table->primary('id');
        });
        DB::unprepared('
            CREATE TRIGGER `push_notifications_insert` AFTER INSERT ON `push_notifications`
             FOR EACH ROW BEGIN
                INSERT INTO push_notification_sents (id, device_id, title, message, worker_id, status_id, created_at) VALUES (new.id, new.device_id, new.title, new.message, new.worker_id, 1, new.created_at);
            END
        ');
        DB::unprepared('
            CREATE TRIGGER `push_notifications_update` AFTER UPDATE ON `push_notifications`
             FOR EACH ROW BEGIN
                UPDATE push_notification_sents SET worker_id = new.worker_id where id = new.id;
            END
        ');
        DB::unprepared('
            CREATE TRIGGER `push_notification_sent_update` BEFORE UPDATE ON `push_notification_sents`
             FOR EACH ROW BEGIN
                IF (new.status_id <> 1) THEN
                    DELETE FROM push_notifications WHERE id = new.id;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `push_notifications_insert`');
        DB::unprepared('DROP TRIGGER `push_notifications_update`');
        DB::unprepared('DROP TRIGGER `push_notification_sent_update`');
        Schema::dropIfExists('push_notification_sents');
    }
}
