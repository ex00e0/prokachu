@extends('layout.header')
@section('title', 'Мои заявки')
@section('content')

@error('message')
<script>alert("{{   $message}}");</script>
@enderror

<div class="vh2"></div>
<div class="container">
    <h3>Все заявки</h3>
</div>
<div class="vh2"></div>
<div class="container">
    @if ($count !=0)
    @foreach ($appls as $appl)
<div class="card" style="width: 25rem;">
  <div class="card-body">
    <h5 class="card-title">Заявка {{$appl->id}}</h5>
    <p class="card-text">Автомобиль: {{$appl->name}}</p>
    <p class="card-text">ФИО заказчика: {{$appl->fio}}</p>
    <p class="card-text">Дата заявки: {{substr($appl->date, 8, 2).'.'.substr($appl->date, 5, 2).'.'.substr($appl->date, 0, 4)}}</p>
    <p class="card-text"><font color="blue">Статус: {{$appl->status}}</font></p>
    @if ($appl->status == 'создана')
    <a href="{{ route('accept_status', ['id'=>$appl->id]) }}" class="btn btn-primary">Принять</a>
    <a href="{{ route('decline_status', ['id'=>$appl->id]) }}" class="btn btn-danger">Отклонить</a>
    @endif
  </div>
</div>
<div style="height:1vmax;"></div>
@endforeach

@else
<div class="vh2">Нет заявок</div>
@endif
</div>

@endsection