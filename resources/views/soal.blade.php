@extends('navbar')
@section('body')
<head>
    <style>
        .background {
            position: relative;
        }

        .notification-icon {
          margin-top: 20px;
            margin-right: 40px;
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            cursor: pointer;
            font-size: 24px;
            transition: color 0.3s;
        }

        .notification-icon.active {
            color: rgba(255, 232, 57, 1);
        }

        .thread-button-container {
          margin-top: 20px;
            display: flex;
            justify-content:space-between;
        }

        
    </style>
</head>
<div class="background"
  style="background-image: url('{{  asset('background.png') }}'); background-size: cover; background-position: top; height: 20vh; display: flex; align-items: center; justify-content: center;">
  @if(app()->getLocale() == 'id')
  <h2 style="color: white; text-align: center; margin-bottom: 25px; margin-top: 28px;">{{ __('soal.mata_kuliah') }} {{ session('namamk') }}</h2>
  @else
  <h2 style="color: white; text-align: center; margin-bottom: 25px; margin-top: 28px;"> {{ session('namamk') }} {{ __('soal.mata_kuliah') }}</h2>
  @endif
  <i class="fa fa-bell notification-icon" id="notificationBell" data-mk-id="{{ session('id_matakuliah') }}"></i>
</div>
<div class="container mt-3">
  
<div class="thread-button-container">
    <h2 class="mb3">{{ __('soal.daftar_soal') }}</h2>
    <a href="{{ route('post.index', ['id_mk' => session('id_matakuliah')]) }}" class="btn btn-info text-light" style="background-color: rgba(37, 144, 251, 1); font-size: larger">{{ __('soal.thread') }}</a>
</div>

  <table class="table table-bordered table-light table-striped">
    <thead class="table-dark">
      <input type="hidden" name="id_semester" value="1">
      <tr>
        @if(app()->getLocale() == 'id')
        <th colspan="5">{{ __('soal.mata_kuliah') }} {{ session('namamk') }}</th>
        @else
        <th colspan="5">{{ session('namamk') }} {{ __('soal.mata_kuliah') }}</th>
        @endif
      </tr>
      <tr>
        <th scope="col">{{ __('soal.no') }}</th>
        <th scope="col">{{ __('soal.nama_soal') }}</th>
        <th scope="col">{{ __('soal.dibuat_oleh') }}</th>
        <th scope="col">{{ __('soal.update') }}</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($soal as $soals)
      <tr>
        <th scope="row">{{ $loop->iteration }}</th>
        <td>{{ $soals->nama_soal }}</td>
        <td>{{ $soals->user->nama }}</td>
        <td>{{ \Carbon\Carbon::parse($soals->updated_at)->locale(app()->getLocale())->diffForHumans() }}</td>

        <td>
        <a href="{{ route('tamplikanhHsoal', ['soal_id' => $soals->id]) }}" class="btn btn-info text-light" style="background-color: #134F5C;">{{ __('soal.lihat') }}</a>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
      const notificationBell = document.getElementById('notificationBell');
      const mkId = notificationBell.getAttribute('data-mk-id');

      // ตรวจสอบสถานะการสมัครรับข้อมูล
      fetch(`/check-subscription/${mkId}`)
          .then(response => response.json())
          .then(data => {
              if (data.isSubscribed) {
                  notificationBell.classList.add('active');
              }
          });

      notificationBell.addEventListener('click', function () {
          this.classList.toggle('active');
          const isSubscribed = this.classList.contains('active');

          // ส่งคำขอ AJAX ไปยังเซิร์ฟเวอร์
          fetch('{{ route("subscribe") }}', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: JSON.stringify({
                  id_mk: mkId,
                  subscribe: isSubscribed
              })
          })
          .then(response => response.json())
          .then(data => {
              console.log(data.message);
          })
          .catch(error => {
              console.error('Error:', error);
          });
      });
  });
</script>
@endsection
