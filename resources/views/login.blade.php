@extends('layout.header')
@section('title', 'Авторизация')
@section('content')

@error('message')
<script>alert("{{$message}}");</script>
@enderror
@error('email')
<div class="alert alert-danger">{{ $message }}</div>
@enderror
@error('password')
<div class="alert alert-danger">{{ $message }}</div>
@enderror

<div class="vh2"></div>
<div class="container">
    <h3>Авторизация</h3>
</div>
<div class="vh2"></div>
<div class="container">
<form action="{{route('login')}}" method="POST">
@csrf
<div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Электронная почта</label>
    <input type="email" class="form-control" id="exampleInputEmail1" name="email">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Пароль</label>
    <input type="password" class="form-control" id="exampleInputPassword1" name="password">
  </div>
  <button type="submit" class="btn btn-primary">Отправить</button>
</form>
</div>

@endsection