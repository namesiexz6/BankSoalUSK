<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tableName = 'matakuliah';

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->string('kode', 255)->notNullable();
            $table->string('nama', 255)->notNullable();
            $table->integer('sks')->notNullable();
            $table->timestamps(true, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
};
