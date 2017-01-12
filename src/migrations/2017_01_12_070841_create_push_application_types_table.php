<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushApplicationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_application_types', function (Blueprint $table) {
            $table->char('id', 32);
            $table->string("name");
            $table->string("description");
            $table->string("color_class");
            $table->timestamps();

            $table->primary();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('push_application_types');
    }
}
