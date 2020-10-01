<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @include('web._components.metas')

    @include('web._components.styles')
    @yield('page-styles')
</head>
<body>

<!--loader start-->
<div id="preloader">
    <div class="loader1">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<!--loader end-->

@include('web._components.header')

<!--body content wrap start-->
<div class="main">
    @yield('content')
    
</div>
<!--body content wrap end-->

@include('web._components.footer')

<!--bottom to top button start-->
<button class="scroll-top scroll-to-target" data-target="html">
    <span class="ti-angle-up"></span>
</button>
<!--bottom to top button end-->
@include('web._components.scripts')
@yield('page-scripts')
</body>
</html>