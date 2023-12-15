<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="{{ asset('favicon.png') }}" rel="icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
  @routes
  <style>
    body {
      font-family: 'DM Sans', sans-serif !important;
    }
    .q-page-container {
      padding-top: 70px;
      background-color: #f7fafd;
    }
    .q-toolbar {
      min-height: 60px !important;
    }
    .q-card, .q-table__card {
      border-radius: 15px !important;
      /* border: 1px solid lightslategrey; */
      box-shadow: 0 7px 19px 7px rgb(149 176 230 / 8%) !important
    }
  </style>
  @vite(['resources/scss/app.scss', 'resources/js/app.js'])
  @inertiaHead
  <title>PHenlínea</title>
</head>

<body>
  @inertia
</body>

</html>