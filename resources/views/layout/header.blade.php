<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Эх, прокачу!</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        @guest
        <li class="nav-item">
          <a class="nav-link" href="{{route('login_show')}}">Авторизация</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('reg_show')}}">Регистрация</a>
        </li>
        @endguest
        @auth
        @if (Auth::user()->role == 'admin') 
        <li class="nav-item">
          <a class="nav-link" href="{{route('all_appls')}}">Все заявки</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('logout')}}">Выход</a>
        </li>
        @else
        <li class="nav-item">
          <a class="nav-link" href="{{route('my_appls')}}">Мои заявки</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('logout')}}">Выход</a>
        </li>
        @endif
       
        @endauth
      </ul>
    </div>
  </div>
</nav>
    
@yield('content')

</body>
</html>