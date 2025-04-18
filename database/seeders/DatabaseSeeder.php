<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Users ---
        DB::table('user')->insert([
            [
                'nama' => 'Hadafee Mudo',
                'username' => 'admin',
                'password' => Hash::make('123456'),
                'level' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'John Doe',
                'username' => 'lecturer1',
                'password' => Hash::make('123456'),
                'level' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Alice Smith',
                'username' => 'student1',
                'password' => Hash::make('123456'),
                'level' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // --- Jenjang ---
        DB::table('jenjang')->insert([
            ['id' => 1, 'nama' => 'Bachelor', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama' => 'Master', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // --- Fakultas (ต้องมี id_jenjang) ---
        DB::table('fakultas')->insert([
            ['id' => 1, 'nama' => 'Engineering', 'id_jenjang' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama' => 'Business', 'id_jenjang' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'Education', 'id_jenjang' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama' => 'Law', 'id_jenjang' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama' => 'Science', 'id_jenjang' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // --- Prodi (ต้องมี id_fakultas) ---
        DB::table('prodi')->insert([
            ['id' => 1, 'id_fakultas' => 1, 'nama' => 'Computer Science', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'id_fakultas' => 1, 'nama' => 'Electrical Engineering', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'id_fakultas' => 2, 'nama' => 'Management', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'id_fakultas' => 2, 'nama' => 'Accounting', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'id_fakultas' => 3, 'nama' => 'Mathematics Education', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'id_fakultas' => 4, 'nama' => 'Civil Law', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'id_fakultas' => 5, 'nama' => 'Physics', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // --- Semester (ต้องมี id_prodi) ---
        DB::table('semester')->insert([
            ['id' => 1, 'id_prodi' => 1, 'nama' => 'Semester 1', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'id_prodi' => 1, 'nama' => 'Semester 2', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'id_prodi' => 2, 'nama' => 'Semester 1', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'id_prodi' => 2, 'nama' => 'Semester 2', 'created_at' => now(), 'updated_at' => now()],
            // เพิ่ม semester สำหรับ prodi อื่น ๆ ตามต้องการ
        ]);

        // --- Mata Kuliah (ต้องมี id_prodi) ---
        DB::table('matakuliah')->insert([
            ['id' => 1,  'kode' => 'CS101', 'nama' => 'Web Programming', 'sks' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2,  'kode' => 'CS102', 'nama' => 'Database Systems', 'sks' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3,  'kode' => 'CS103', 'nama' => 'Algorithms and Data Structures', 'sks' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4,  'kode' => 'EE101', 'nama' => 'Circuit Theory', 'sks' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5,  'kode' => 'EE102', 'nama' => 'Digital Systems', 'sks' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6,  'kode' => 'MG101', 'nama' => 'Microeconomics', 'sks' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7,  'kode' => 'MG102', 'nama' => 'Macroeconomics', 'sks' => 3, 'created_at' => now(), 'updated_at' => now()],
            // เพิ่มวิชาอีกเยอะ ๆ ได้ตามต้องการ
        ]);

        // --- Multi MK (mapping matakuliah กับ semester) ---
        DB::table('multi_mk')->insert([
            ['id_mk' => 1, 'id_semester' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_mk' => 2, 'id_semester' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id_mk' => 3, 'id_semester' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_mk' => 4, 'id_semester' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id_mk' => 5, 'id_semester' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id_mk' => 6, 'id_semester' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_mk' => 7, 'id_semester' => 2, 'created_at' => now(), 'updated_at' => now()],
            // เพิ่ม mapping อื่น ๆ ตามต้องการ
        ]);

// --- Post ---
DB::table('post')->insert([
    [
        'id_mk' => 1,
        'id_user' => 2,
        'isi_post' => 'Let\'s discuss the basics of Web Programming. What frameworks do you use?',
        'file_post' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id_mk' => 2,
        'id_user' => 2,
        'isi_post' => 'Database Systems are the backbone of modern applications. Share your experience with SQL or NoSQL!',
        'file_post' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id_mk' => 3,
        'id_user' => 2,
        'isi_post' => 'Algorithms and Data Structures are essential for coding interviews. What is your favorite algorithm?',
        'file_post' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id_mk' => 4,
        'id_user' => 2,
        'isi_post' => 'Circuit Theory is fundamental in Electrical Engineering. Any tips for solving circuit problems?',
        'file_post' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id_mk' => 5,
        'id_user' => 2,
        'isi_post' => 'Digital Systems are everywhere. Let\'s talk about logic gates and flip-flops!',
        'file_post' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id_mk' => 6,
        'id_user' => 2,
        'isi_post' => 'Microeconomics helps us understand market behavior. What is the law of demand?',
        'file_post' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id_mk' => 7,
        'id_user' => 2,
        'isi_post' => 'Macroeconomics covers national economies. How does inflation affect GDP?',
        'file_post' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);

// --- Komentar Post ---
DB::table('komentar_post')->insert([
    [
        'id_post' => 1,
        'id_user' => 3,
        'isi_komentar' => 'I prefer using Laravel for web development. It\'s very powerful!',
        'file_komentar' => null,
        'parent_id' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id_post' => 2,
        'id_user' => 3,
        'isi_komentar' => 'I have experience with both MySQL and MongoDB. Each has its pros and cons.',
        'file_komentar' => null,
        'parent_id' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id_post' => 3,
        'id_user' => 3,
        'isi_komentar' => 'My favorite algorithm is Dijkstra\'s for shortest path problems.',
        'file_komentar' => null,
        'parent_id' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id_post' => 4,
        'id_user' => 3,
        'isi_komentar' => 'Practice is key! Try solving as many circuit problems as possible.',
        'file_komentar' => null,
        'parent_id' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id_post' => 5,
        'id_user' => 3,
        'isi_komentar' => 'Logic gates are the building blocks of digital systems.',
        'file_komentar' => null,
        'parent_id' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id_post' => 6,
        'id_user' => 3,
        'isi_komentar' => 'The law of demand states that as price increases, demand decreases.',
        'file_komentar' => null,
        'parent_id' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id_post' => 7,
        'id_user' => 3,
        'isi_komentar' => 'Inflation can reduce the purchasing power and affect GDP growth.',
        'file_komentar' => null,
        'parent_id' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);

        // --- Love Post ---
        DB::table('love_post')->insert([
            [
                'id_post' => 1,
                'id_user' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_post' => 2,
                'id_user' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // --- Rating Komentar Post ---
        DB::table('rating_komentar_post')->insert([
            [
                'id_komentar' => 1,
                'id_user' => 2,
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_komentar' => 2,
                'id_user' => 3,
                'rating' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}