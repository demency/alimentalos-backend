<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->unique()->index();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('photo_id')->unsigned()->index();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->boolean('is_public')->default(true);
            $table->text('photo_url')->nullable();
            $table->timestamps();
        });

        Schema::create('groupables', function (Blueprint $table) {
            $table->bigInteger('group_id')->index()->unsigned();
            $table->bigInteger('sender_id')->unsigned()->index()->nullable();
            $table->string('status')->default(\App\Group::PENDING_STATUS)->index();
            $table->boolean('is_admin')->default(false)->index();
            $table->morphs('groupable');
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
        Schema::dropIfExists('groups');
        Schema::dropIfExists('groupables');
    }
}
