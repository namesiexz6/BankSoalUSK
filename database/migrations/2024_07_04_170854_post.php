<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tableName = 'post';

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mk');
            $table->foreign('id_mk')->references('id')->on('matakuliah');
            $table->unsignedBigInteger('id_user')->notNullable();
            $table->string('isi_post', 255)->notNullable();
            $table->string('file_post', 255)->nullable();
            $table->timestamps(true, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
};
