<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Page not found</title>
  <link rel="shortcut icon" href="{{asset('system/images/favicon.png')}}" />
  <link rel="stylesheet" href="{{asset('system/vendors/mdi/css/materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('system/vendors/css/vendor.bundle.base.css')}}">
  <link rel="stylesheet" href="{{asset('system/css/vertical-layout-light/style.css')}}">
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center text-center error-page bg-danger">
        <div class="row flex-grow">
          <div class="col-lg-7 mx-auto text-white">
            <div class="row align-items-center d-flex flex-row">
              <div class="col-lg-6 text-lg-right pr-lg-4">
                <h1 class="display-1 mb-0 text-white">404</h1>
              </div>
              <div class="col-lg-6 error-page-divider text-lg-left pl-lg-4">
                <h2 class="text-white">SORRY!</h2>
                <h3 class="font-weight-light text-white">The requested URL <u>{{url()->current()}}</u> was not found on this server.</h3>
              </div>
            </div>
            <div class="row mt-5">
              <div class="col-12 text-center mt-xl-2">
                <a class="text-white font-weight-medium" href="{{route('system.dashboard')}}">Back to home</a>
              </div>
            </div>
            <div class="row mt-5">
              <div class="col-12 mt-xl-2">
                <p class="text-white font-weight-medium text-center">Copyright &copy; 2013-{{Carbon::now()->format("Y")}}.  All rights reserved.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <script src="{{asset('system/vendors/js/vendor.bundle.base.js')}}"></script>
  <script src="{{asset('system/js/off-canvas.js')}}"></script>
  <script src="{{asset('system/js/hoverable-collapse.js')}}"></script>
  <script src="{{asset('system/js/template.js')}}"></script>
  <script src="{{asset('system/js/settings.js')}}"></script>
</body>

</html>
