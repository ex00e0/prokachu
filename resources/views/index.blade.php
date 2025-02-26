@extends('layout.header')
@section('title', 'Каталог')
@section('content')

@error('message')
<script>alert("{{$message}}");</script>
@enderror
<form action="{{route('index_sfs')}}" method="get" class="form_sfs">
    <input name="search" type="text" placeholder="Введите название тура.." class="c2 r1" value="<?=(!isset($_GET['search']) || $_GET['search'] == '')?'':$_GET['search']?>">
    <select name="filter" >
        <option value="" <?=(isset($_GET['filter']) && $_GET['filter'] == '')?'selected':''?>>-фильтрация-</option>
        <option value="1-5" <?=(isset($_GET['filter']) && $_GET['filter'] == '1-5')?'selected':''?>>на 1-5 дней</option>
        <option value="6-9" <?=(isset($_GET['filter']) && $_GET['filter'] == '6-9')?'selected':''?>>на 6-9 дней</option>
        <option value="10-15" <?=(isset($_GET['filter']) && $_GET['filter'] == '10-15')?'selected':''?>>на 10-15 дней</option>
    </select>
    <select name="sort">
        <option value="" <?=(isset($_GET['sort']) && $_GET['sort'] == '')?'selected':''?>>-сортировка-</option>
        <option value="DESC" <?=(isset($_GET['sort']) && $_GET['sort'] == 'DESC')?'selected':''?>>сначала дорогие</option>
        <option value="ASC" <?=(isset($_GET['sort']) && $_GET['sort'] == 'ASC')?'selected':''?>>сначала дешевые</option>
    </select>
    <input type="submit" value="Искать" class="btn btn-primary">
</form>

<div class="vh2"></div>
<div class="container-fluid">
    <h3>Каталог</h3>
    @if ($count !=0)
    @foreach ($tours as $tour)
    <div class="card" style="width: 18rem; display:inline-block; align-self:start">
        <img src="{{asset('images/'.$tour->image.'')}}" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">{{$tour->name}}</h5>
            <p class="card-text">{{$tour->description}}</p>
            <p class="card-text">Даты тура: {{substr($tour->date_start, 8, 2).'.'.substr($tour->date_start, 5, 2).'.'.substr($tour->date_start, 0, 4).' - '.substr($tour->date_end, 8, 2).'.'.substr($tour->date_end, 5, 2).'.'.substr($tour->date_end, 0, 4)}}</p>
            <p class="card-text">Цена: {{$tour->price}} руб.</p>
            @if (auth()->user() == null)
            <div></div>
            @else
            @if (auth()->user()->role == 'admin')
            <div></div>
            @else
            <a href="{{route('send_appl', ['id'=>$tour->id])}}" class="btn btn-primary">Подать заявку на тур</a>
            @endif
            @endif

        </div>
    </div>
    @endforeach
    @else
        <div class="vh2">Нет туров</div>
    @endif
</div>

@endsection