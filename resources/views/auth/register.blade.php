@extends('layouts.auth')

@section('content')
    <!-- TITLE -->
    <div class="title-information">
        Create your account and start building your Botico
    </div>
    <!-- BODY -->

    <form class="form-container" method="POST" action="{{ route('register') }}">
        @csrf
        <input type="text" placeholder="Company Name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <select name="company_size" id="" required>
            <option value="">Company size</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>

        <input type="text" placeholder="Phone" name="phone"  value="{{ old('email') }}" required>
        <input type="text" placeholder="E-Mail" name="email" value="{{ old('email') }}" required autocomplete="email">
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror


        <input type="password" placeholder="Password" name="password" required autocomplete="new-password">
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <input type="password" placeholder="Password confirmation" class="form-control" name="password_confirmation" required autocomplete="new-password">
        <div class="tas">
            By signing up you agree with our
            <a href="#">Terms & Condition and Privacy Policy</a>
        </div>

        <button type="submit">SIGN UP</button>

        <div class="hint">Already have an account? <a href="{{route('login')}}">LOG IN</a></div>
    </form>

@endsection
