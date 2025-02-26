@extends('layout.header')
@section('title', 'Все заявки')
@section('content')

@error('message')
<script>alert("{{   $message}}");</script>
@enderror

<div class="vh2"></div>
<div class="container-fluid">
    <h3>Все заявки</h3>
</div>
<div class="vh2"></div>
<div class="container-fluid" >
    @if ($count !=0)
    @foreach ($appls as $appl)
<div class="card" style="width: 25rem;">
  <div class="card-body">
  <h5 class="card-title">Заявка {{$appl->id}}</h5>
  <p class="card-text">Тур: {{$appl->name}}</p>
    <p class="card-text"><font color="#a7aafa">ФИО заказчика: {{$appl->fio}}</font></p>
    <p class="card-text">Даты тура: {{substr($appl->date_start, 8, 2).'.'.substr($appl->date_start, 5, 2).'.'.substr($appl->date_start, 0, 4).' - '.substr($appl->date_end, 8, 2).'.'.substr($appl->date_end, 5, 2).'.'.substr($appl->date_end, 0, 4)}}</p>
    <p class="card-text">Номер телефона: {{$appl->phone}}</p>
    @if ($appl->comment != null)
    <p class="card-text">Комментарий заказчика: {{$appl->comment}}</p>
    @endif
    <p class="card-text"><font color="blue">Статус:</font></p>
    <form action="{{route('change_status')}}" method="POST" id="sh_form_{{$appl->id}}">
        @csrf
        <input type="hidden" value="{{$appl->id}}" name="id">
        <select name="status" class="form-control" id='sh_{{$appl->id}}' onchange="st_change('{{$appl->id}}')" value="{{$appl->status}}"  <?= $appl->status == 'принята' || $appl->status == 'отменена' ? 'disabled' : '' ?>>
            <option value="создана" <?= $appl->status == 'создана'? 'selected' : '' ?> >создана</option>
            <option value="принята" <?= $appl->status == 'принята'? 'selected' : '' ?>>принята</option>
            <option value="отменена" <?= $appl->status == 'отменена'? 'selected' : '' ?>>отменена</option>
        </select>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label" id="label_sh_{{$appl->id}}" style='display:none;'>Опишите причину отказа</label>
            <input type="text" class="form-control" id="input_sh_{{$appl->id}}" name="admin_text" style='display:none;'>
        </div>
        <button type="submit" class="btn btn-primary" style="<?= $appl->status == 'принята' || $appl->status == 'отменена' ? 'display:none;' : '' ?>">Отправить</button>
        @if ($appl->status == 'отменена')
            <p class="card-text"><font color="blue">Причина отказа: {{$appl->admin_text}}</font></p>
        @endif
    </form>
    
  </div>
</div>
<div style="height:1vmax;"></div>
@endforeach

@else
<div class="vh2">Нет заявок</div>
@endif
</div>

<script>
function st_change (id) {
    let val = document.getElementById(`sh_${id}`).value;
    if (val == 'отменена') {
      document.getElementById(`input_sh_${id}`).style.display = 'block';
      document.getElementById(`label_sh_${id}`).style.display = 'block';
      document.getElementById(`input_sh_${id}`).setAttribute('required', '');
    }
    else {
      document.getElementById(`input_sh_${id}`).style.display = 'none';
      document.getElementById(`label_sh_${id}`).style.display = 'none';
      document.getElementById(`input_sh_${id}`).removeAttribute('required');
    }
}

//   document.getElementById('sh').addEventListener('change', function () {
//     let val = document.getElementById('sh').value;

//     if (val == 'отменено') {
//       document.getElementById('input_sh').style.display = 'block';
//       document.getElementById('label_sh').style.display = 'block';
//       document.getElementById('input_sh').setAttribute('required', '');
//     }
//     else {
//       document.getElementById('input_sh').style.display = 'none';
//       document.getElementById('label_sh').style.display = 'none';
//       document.getElementById('input_sh').removeAttribute('required');
//       document.getElementById('sh_form').submit();
//     }
    
//   });
</script>

@endsection