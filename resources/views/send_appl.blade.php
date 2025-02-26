@extends('layout.header')
@section('title', 'Подать заявку')
@section('content')

@error('message')
<script>alert("{{$message}}");</script>
@enderror

@error('phone')
<div class="alert alert-danger">{{ $message }}</div>
@enderror


<div class="vh2"></div>
<div class="container-fluid">
    <h3>Подать заявку на тур {{$tour->name}}</h3>
</div>
<div class="vh2"></div>
<div class="container-fluid">
<form action="{{route('send_appl_db')}}" method="POST">
@csrf
  <input type="hidden" value="{{$tour->id}}" name="id">
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Номер телефона для связи</label>
    <input type="text" class="form-control" id="exampleInputPassword1" name="phone" required>
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Комментарий к заявке (необязательно)</label>
    <input type="text" class="form-control" id="exampleInputPassword1" name="comment">
  </div>
  <button type="submit" class="btn btn-primary">Отправить</button>
</form>
</div>

@endsection