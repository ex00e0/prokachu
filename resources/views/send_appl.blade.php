@extends('layout.header')
@section('title', 'Подать заявку')
@section('content')

@error('message')
<script>alert("{{$message}}");</script>
@enderror

@error('message_error')
<div class="alert alert-danger">{{ $message }}</div>
@enderror


<div class="vh2"></div>
<div class="container">
    <h3>Подать заявку</h3>
</div>
<div class="vh2"></div>
<div class="container">
<form action="{{route('send_appl_db')}}" method="POST">
@csrf
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Выбрать автомобиль</label>
    <select class="form-control" id="exampleInputPassword1" name="car_id">
      @foreach ($cars as $car)
        <option value="{{ $car->id }}">{{ $car->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Выбрать дату</label>
    <input type="date" class="form-control" id="exampleInputPassword1" name="date" min="<?=date('Y-m-d')?>" required>
  </div>
  <button type="submit" class="btn btn-primary">Отправить</button>
</form>
</div>

@endsection