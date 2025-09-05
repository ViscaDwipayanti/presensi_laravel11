<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prezy Web</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('modern/src/assets/images/logos/favicon.png') }}" />
  <link rel="stylesheet" href="{{ asset('modern/src/assets/css/styles.min.css') }}" />
</head>
<body>

          <!--  Body Wrapper -->
          <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
          data-sidebar-position="fixed" data-header-position="fixed">
          <div
            class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
              <div class="row justify-content-center w-100">
                <div class="col-md-8 col-lg-6 col-xxl-3">
                  <div class="card mb-0">
                    <div class="card-body">
                      <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                        <img src="{{ asset('modern/src/assets/images/logos/logo-prezy.png') }}" width="250" alt="">
                    </a>
                      {{-- <p class="text-center">Simplifying Your Attendance</p> --}}
                        @include('flash::message')
                        @yield('content')

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


{{-- Script --}}
<script src="{{ asset('modern/src/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('modern/src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

</body>