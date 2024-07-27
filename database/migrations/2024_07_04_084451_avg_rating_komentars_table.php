<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tableName = 'avg_rating_komentar';

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_komentar');
            $table->foreign('id_komentar')->references('id')->on('komentar_soal');
            $table->unsignedBigInteger('avg_rating')->notNullable();
            $table->timestamps(true, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
};
