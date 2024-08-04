@extends('navbar')
@section('body')
<div class="bg-image"
    style="background-image: url('{{  asset('background.png') }}'); background-size: cover; background-position: center; height: 83vh; display: flex; align-items: center; justify-content: center;">
    <div class="container text-center" style="padding: 5% 2%; background: rgba(0, 0, 0, 0.5); border-radius: 10px;">
        <p class="fs-2 fw-bolder text-light">BankSoal</p>
        <p class="fs-5 fw-bolder" style="color: #87F7FF; text-align: justify; text-indent: 3em;">
            Selamat datang di BankSoal USK (Bank Soal Universitas Syiah Kuala), situs yang dirancang untuk mendukung
            pembelajaran dan persiapan ujian mahasiswa. Kami memahami bahwa persiapan ujian adalah tantangan, dan
            memiliki
            sumber informasi yang lengkap dan terpercaya akan membantu mahasiswa lebih percaya diri dan siap menghadapi
            ujian.
        </p>
        <p class="fs-5 fw-bolder" style="color: #87F7FF; text-align: justify; text-indent:3em;">
            BankSoal USK menyediakan soal dan latihan dari tahun-tahun sebelumnya yang dapat diakses kapan saja dan di
            mana
            saja. Fitur kami dirancang untuk meningkatkan kenyamanan dan efektivitas pembelajaran. Mahasiswa dapat
            melihat
            soal, memberikan komentar, membalas komentar, dan memberikan rating, sehingga pembelajaran menjadi lebih
            interaktif dan menarik.
        </p>
        <p class="fs-5 fw-bolder" style="color: #87F7FF; text-align: justify; text-indent: 3em;">
            Kami juga memiliki fitur thread di mana mahasiswa dapat memposting pertanyaan atau berbagi informasi terkait
            soal
            atau pendidikan. Mahasiswa juga dapat memberikan komentar dan menyukai postingan, menjadikan pertukaran
            pengetahuan lebih lancar dan efektif. Cobalah BankSoal USK hari ini dan rasakan perbedaannya!
        </p>

    </div>
</div>
@endsection