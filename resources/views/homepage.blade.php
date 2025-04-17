@extends('navbar')
@section('body')
<div class="bg-image"
    style="background-image: url('{{  asset('background.png') }}'); background-size: cover; background-position: center; height: 83vh; display: flex; align-items: center; justify-content: center;">
    <div class="container text-center" style="padding: 5% 2%; background: rgba(0, 0, 0, 0.5); border-radius: 10px;">
        <p class="fs-2 fw-bolder text-light">{{ __('home.welcome') }}</p>
        <p class="fs-5 fw-bolder" style="color: #87F7FF; text-align: justify; text-indent: 3em;">
            {{ __('home.description_1') }}
        </p>
        <p class="fs-5 fw-bolder" style="color: #87F7FF; text-align: justify; text-indent:3em;">
            {{ __('home.description_2') }}
        </p>
        <p class="fs-5 fw-bolder" style="color: #87F7FF; text-align: justify; text-indent: 3em;">
            {{ __('home.description_3') }}
        </p>
        <p class="fs-5 fw-bolder" style="color: #87F7FF; text-align: justify; text-indent: 3em;">
            {{ __('home.cta') }}
        </p>
    </div>
</div>
@endsection
