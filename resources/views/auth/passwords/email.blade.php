@extends('layouts.auth')

@section('content')
    <!-- TITLE -->
    <div class="title-information">
        Enter your email to reset your password
    </div>
    <!-- BODY -->
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif


    <form class="form-container" method="POST" action="{{ route('password.email') }}">
        @csrf
        <input type="email" placeholder="E-Mail" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
        @error('email')
        <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <button>CHECK</button>
        <div class="hint">Don't have an account? <a href="{{route('register')}}">SIGN UP</a></div>
    </form>




@endsection
