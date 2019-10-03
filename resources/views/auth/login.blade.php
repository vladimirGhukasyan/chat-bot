@extends('layouts.auth')

@section('content')

    <!-- TITLE -->
    <div class="title-information">
        Hello ! We were waiting for you, <br>
        enter your details to create something new!
    </div>

    <!-- BODY -->

    <form  class="form-container" method="POST" action="{{ route('login') }}">
        @csrf
        <input value="{{ old('email') }}" name="email" type="email" placeholder="Email" required autocomplete="email" autofocus>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <input type="password" placeholder="Password" name="password" required autocomplete="current-password">
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror


            <a class="hint-green" href="{{ route('password.request') }}" >{{ __('Forgot Your Password?') }}</a>



        <button type="submit">LOG IN</button>
        <div class="hint">Don't have an account? <a href="{{route('register')}}">SIGN UP</a></div>
    </form>


@endsection
