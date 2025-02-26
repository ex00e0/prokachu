@extends('layout.header')
@section('title', 'Мои заявки')
@section('content')

@error('message')
<script>alert("{{   $message}}");</script>
@enderror

<div class="vh2"></div>
<div class="container-fluid">
    <h3>Мои заявки</h3>
</div>
<div class="vh2"></div>
<div class="container-fluid" >
    @if ($count !=0)
    @foreach ($appls as $appl)
<div class="card" style="width: 25rem;">
  <div class="card-body">
    <h5 class="card-title">Заявка {{$appl->id}}</h5>
    <p class="card-text">Тур: {{$appl->name}}</p>
    <p class="card-text">Даты тура: {{substr($appl->date_start, 8, 2).'.'.substr($appl->date_start, 5, 2).'.'.substr($appl->date_start, 0, 4).' - '.substr($appl->date_end, 8, 2).'.'.substr($appl->date_end, 5, 2).'.'.substr($appl->date_end, 0, 4)}}</p>
    <p class="card-text">Номер телефона: {{$appl->phone}}</p>
    @if ($appl->comment != null)
    <p class="card-text">Ваш комментарий: {{$appl->comment}}</p>
    @endif
    <p class="card-text"><font color="blue">Статус: {{$appl->status}}</font></p>
    @if ($appl->status == 'отменена')
            <p class="card-text"><font color="blue">Причина отказа: {{$appl->admin_text}}</font></p>
        @endif
    <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
  </div>
</div>
<div style="height:1vmax;"></div>
@endforeach

@else
<div class="vh2">Нет заявок</div>
@endif
</div>

@endsection