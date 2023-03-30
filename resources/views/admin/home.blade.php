<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap-grid.min.css" rel="stylesheet" />
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <title>Home</title>
  <style>
    body {
      background: #F3F6FC;
      font-family: 'Roboto', sans-serif;
    }

    .menu-option {
      position: relative;
      text-align: center;
      font-weight: 400;
      display: flex;
      height: 200px;
      flex-flow: column;
      justify-content: center;
      align-items: center;
      margin-bottom: 20px;
      border-radius: 5px;
    }

    .menu-option:hover {
      /* background-color: #fff;
      box-shadow: 0 3px 10px #1A61A350; */
    }

    .menu-option__icon {
      height: 200px;
    }

    .menu-option__icon img {
      margin-bottom: 20px;
      max-width: 205px;
    }

    .menu-option__icon i {
      color: #1A61A3;
      font-size: 54px;
    }

    .menu-option__title {
      color: #000;
      font-size: 19px;
      letter-spacing: .015em;
    }

    .btn-round i {
      font-size: 24px;
    }

    .brandnew-tag {
      color: #fff;
      font-size: 13px;
      font-weight: 500;
      border-radius: 5px;
      background: red;
      padding: .25rem;
      position: absolute;
      top: 1em;
      right: 1.5em;
    }
  </style>
</head>

<body>
  @include('layouts.navbar.new', ['title'=>'Menú principal'])

  <div class="container">
    <div class="menu-options row">
      @php
      $routes = [
      [
      'name' => 'extensions.index',
      'icon' => 'citofonia.png'
      ],
      [
      'name' => 'visits.index',
      'icon' => 'visitas.png'
      ],
      [
      'name' => 'novelties.index',
      'icon' => 'novedades.png'
      ],
      [
      'name' => 'invoices.index',
      'icon' => 'facturas.png'
      ],
      ]
      @endphp

      @foreach($routes as $route)
      <div class="col-sm-6 col-lg-3">
        <a href="{{ route($route['name']) }}">
          <div class="menu-option">
            <div class="menu-option__icon">
              <img src="{{ asset('img/iconos/' . $route['icon']) }}" alt="">
            </div>
          </div>
        </a>
      </div>
      @endforeach
    </div>

    <div style="text-align: center; position: relative;">
      <hr style="position: absolute; top: 50%; left: 0; right: 0; border-color: #6c96d1;" />
      <img src="{{ asset('img/iconos/logo_comunidad.png') }}" style="display: inline-block; width: 200px; z-index: 100; position: relative;" />
    </div>

    <div class="row justify-content-center">
      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('petitions.index') }}">
          <div class="menu-option">
            <div class="brandnew-tag">
              Nuevo
            </div>
            <div class="menu-option__icon">
              <img src="{{ asset('img/iconos/pqrs.png') }}" alt="">
            </div>
          </div>
        </a>
      </div>

      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('whatsapp.index') }}">
          <div class="menu-option">
            <div class="brandnew-tag">
              Nuevo
            </div>
            <div class="menu-option__icon">
              <img src="{{ asset('img/iconos/whatsapp.png') }}" alt="">
            </div>
          </div>
        </a>
      </div>

      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('whatsapp.comunity') }}">
          <div class="menu-option">
            <div class="brandnew-tag">
              Nuevo
            </div>
            <div class="menu-option__icon">
              <img src="{{ asset('img/iconos/comunidad.png') }}" alt="">
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</body>

</html>