<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prezy Web</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('modern/src/assets/images/logos/favicon.png') }}" />
  <link rel="stylesheet" href="{{ asset('modern/src/assets/css/styles.min.css') }}" />
  <style>
    .usersession{
      padding-left: 5px;
    }

    
  </style>
</head>
 

<body>
    <div class="body-wrapper">
      <div class="container-fluid">
        {{-- @include('flash::message') --}}
        @yield('content')
      </div>
    </div>
  </div>
  <script src="{{ asset('modern/src/assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('modern/src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('modern/src/assets/js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('modern/src/assets/js/app.min.js') }}"></script>
  <script src="{{ asset('modern/src/assets/libs/simplebar/dist/simplebar.js') }}"></script>
</body>

</html>