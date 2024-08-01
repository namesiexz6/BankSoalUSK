<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tableName = 'komentar_post';

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_post');
            $table->foreign('id_post')->references('id')->on('post');
            $table->unsignedBigInteger('id_user')->notNullable();
            $table->string('isi_komentar', 255)->notNullable();
            $table->string('file_komentar', 255)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps(true, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
};
