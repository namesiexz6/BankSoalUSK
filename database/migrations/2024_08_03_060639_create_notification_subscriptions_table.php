<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('notification_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user')->onDelete('cascade');
            $table->foreignId('id_mk')->constrained('matakuliah')->onDelete('cascade'); // assuming mata_kuliahs is the table name for mata kuliah
            $table->string('topic');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notification_subscriptions');
    }
};
