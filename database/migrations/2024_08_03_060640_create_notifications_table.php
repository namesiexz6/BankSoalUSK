<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user')->onDelete('cascade'); // ผู้ใช้ที่ได้รับการแจ้งเตือน
            $table->string('type'); // ประเภทของการแจ้งเตือน เช่น 'post_created', 'comment_created'
            $table->text('data'); // ข้อมูลเพิ่มเติมที่เกี่ยวข้องกับการแจ้งเตือน เช่น ข้อความ, ลิงก์, ฯลฯ
            $table->timestamp('read_at')->nullable(); // เวลาที่อ่านการแจ้งเตือน (ถ้ายังไม่อ่านจะเป็นค่า null)
            $table->timestamps(); // เวลา created_at และ updated_at
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
