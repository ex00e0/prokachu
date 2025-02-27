@extends('layout.header')
@section('title', 'Регистрация')
@section('content')

@error('message')
<script>alert("{{$message}}");</script>
@enderror

@error('drive_licence')
<div class="alert alert-danger">{{ $message }}</div>
@enderror

@error('password')
<div class="alert alert-danger">{{ $message }}</div>
@enderror

@error('fio')
<div class="alert alert-danger">{{ $message }}</div>
@enderror

@error('phone')
<div class="alert alert-danger">{{ $message }}</div>
@enderror

@error('email')
<div class="alert alert-danger">{{ $message }}</div>
@enderror

<div class="vh2"></div>
<div class="container">
    <h3>Регистрация</h3>
</div>
<div class="vh2"></div>
<div class="container">
<form action="{{route('reg')}}" method="POST">
@csrf
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Серия и номер водительского удостоверения</label>
    <input type="text" class="form-control" id="exampleInputEmail1" name="drive_licence">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Пароль</label>
    <input type="password" class="form-control" id="exampleInputPassword1" name="password">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Фио</label>
    <input type="text" class="form-control" id="exampleInputEmail1" name="fio">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Телефон</label>
    <input type="text" class="form-control" id="exampleInputEmail1" name="phone">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Электронная почта</label>
    <input type="email" class="form-control" id="exampleInputEmail1" name="email">
  </div>
  <button type="submit" class="btn btn-primary">Отправить</button>
</form>
</div>

@endsection