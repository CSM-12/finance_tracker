@extends('masterLayout')

{{-- Page Title --}}
@section('page-title')
Register
@endsection

{{-- Page Style --}}
@section('page-style')

@endsection

{{-- Page Content --}}
@section('page-content')

{{-- Login Form --}}
<h1>Register</h1>

<form method="POST" action="{{route('UserRegister')}}">
    @csrf

    <input type="text" name="name" class="form-control m-2" placeholder="Username">

    <input type="email" name="email" class="form-control m-2" placeholder="Email">

    <input type="password" name="password" class="form-control m-2" placeholder="Password">

    <input type="password" name="password_confirmation" class="form-control m-2" placeholder="Confirm Password">

    <input type="submit" class="btn btn-primary mb-2">
</form>
<br>

Already have an account? <a href="{{route('LoginPage')}}" class="bg-primary p-2 rounded text-light link-underline-opacity-0">Login</a>
@endsection