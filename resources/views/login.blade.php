@extends('masterLayout')

{{-- Page Title --}}
@section('page-title')
Login
@endsection

{{-- Page Style --}}
@section('page-style')

@endsection

{{-- Page Content --}}
@section('page-content')

{{-- Login Form --}}
<h1>Login</h1>

<form method="POST" action="{{route('UserLogin')}}">
    @csrf

    <input type="email" name="email" class="form-control m-2" placeholder="Email">

    <input type="password" name="password" class="form-control m-2" placeholder="Password">

    <input type="submit" class="btn btn-primary mb-2">
</form>
<br>

Dont have an account? <a href="{{route('registerPage')}}" class="bg-primary p-2 rounded text-light link-underline-opacity-0">Register</a>
@endsection