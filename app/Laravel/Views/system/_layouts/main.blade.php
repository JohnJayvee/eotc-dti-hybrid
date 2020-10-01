<!DOCTYPE html>
<html lang="en">

<head>
  @include('system._components.metas')
  @include('system._components.styles')
</head>

<body>
  <div class="container-scroller">
    @include('system._components.topbar')
    <div class="container-fluid page-body-wrapper">
      
      @include('system._components.sidebar')
      <div class="main-panel">

        <div class="content-wrapper" style="background-color: #F8F8F8">
          @yield('breadcrumbs')
          @yield('content')
        </div>
        
        @include('system._components.footer')
      </div>
    </div>
  </div>
  @yield('page-modals')
  @include('system._components.scripts')
  
</body>

</html>
