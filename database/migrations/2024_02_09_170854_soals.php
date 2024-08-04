<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tableName = 'soal';

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user')->notNullable();
            $table->unsignedBigInteger('id_mk');
            $table->foreign('id_mk')->references('id')->on('matakuliah');
            $table->string('nama_soal', 255)->notNullable();
            $table->integer('tipe')->notNullable();
            $table->string('isi_soal', 255)->notNullable();
            $table->timestamps(true, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
};
