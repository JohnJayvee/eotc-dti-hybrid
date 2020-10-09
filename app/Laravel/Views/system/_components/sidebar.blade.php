<nav class="sidebar sidebar-offcanvas p-0" id="sidebar" style="background-color: #31353D;color: #ffff;">
  <h6 class="pl-3 pt-4">Menu</h6>
  <ul class="nav">
    
    <li class="p-3 nav-item {{ in_array(Route::currentRouteName(), array('system.dashboard')) ? 'active' : ''}}">
      <a class="nav-link" href="{{route('system.dashboard')}}">
        <i class="fa fa-home menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    @if(in_array($auth->type,['super_user','admin','processor','office_head']))
      @if(in_array($auth->type,['super_user','admin','office_head']))
        <li class="p-3 nav-item {{ in_array(Route::currentRouteName(), array('system.processor.list','system.processor.show' )) ? 'active' : ''}}">
          <a class="nav-link" href="{{route('system.processor.list')}}">
            <i class="fa fa-user-circle menu-icon"></i>
            <span class="menu-title">Processors</span>
          </a>
        </li>
      @endif
    <li class="p-3 nav-item {{ in_array(Route::currentRouteName(), array('system.transaction.index','system.transaction.show')) ? 'active' : ''}}">
      <a class="nav-link" data-toggle="collapse" href="#my_report" aria-expanded="false" aria-controls="my_report">
        <i class="fa fa-file menu-icon"></i>
        <span class="menu-title">Transactions</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="my_report">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('system.transaction.pending')}}">Pending
            @if($counter['pending'] > 0)
              <span class="badge badge-sm badge-primary">{{$counter['pending']}}</span>
            @endif
          </a></li>
        </ul>
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('system.transaction.approved')}}">Approved
            @if($counter['approved'] > 0)
              <span class="badge badge-sm badge-primary">{{$counter['approved']}}</span>
            @endif
          </a></li>
        </ul>
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('system.transaction.declined')}}">Declined
            @if($counter['declined'] > 0)
              <span class="badge badge-sm badge-primary">{{$counter['declined']}}</span>
            @endif
          </a></li>
        </ul>
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('system.transaction.resent')}}">Resent
            @if($counter['resent'] > 0)
              <span class="badge badge-sm badge-primary">{{$counter['resent']}}</span>
            @endif
          </a></li>
        </ul>
        @if(in_array($auth->type,['processor']))
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('system.transaction.create')}}">Create New</a></li>
        </ul>
        @endif
      </div>
    </li>
    @if(in_array($auth->type,['super_user','admin','office_head']))
      @if(in_array($auth->type,['super_user','admin']))
        <li class="p-3 nav-item {{ in_array(Route::currentRouteName(), array('system.application.index','system.application.create','system.application.edit')) ? 'active' : ''}}">
          <a class="nav-link" href="{{route('system.application.index')}}">
            <i class="fa fa-bookmark menu-icon"></i>
            <span class="menu-title">Applications</span>
          </a>
        </li>
        <li class="p-3 nav-item {{ in_array(Route::currentRouteName(), array('system.department.index','system.department.create','system.department.edit')) ? 'active' : ''}}">
          <a class="nav-link" href="{{route('system.department.index')}}">
            <i class="fa fa-globe menu-icon"></i>
            <span class="menu-title">Bureau/Office</span>
          </a>
        </li>
       
        <li class="p-3 nav-item {{ in_array(Route::currentRouteName(), array('system.application_requirements.index','system.application_requirements.create','system.application_requirements.edit')) ? 'active' : ''}}">
          <a class="nav-link" href="{{route('system.application_requirements.index')}}">
            <i class="fa fa-check-circle menu-icon"></i>
            <span class="menu-title">Application Requirements</span>
          </a>
        </li>
      @endif
        <!-- <li class="p-3 nav-item {{ in_array(Route::currentRouteName(), array('system.regional_office.index','system.regional_office.create','system.regional_office.edit')) ? 'active' : ''}}">
          <a class="nav-link" href="{{route('system.regional_office.index')}}">
            <i class="fa fa-compass menu-icon"></i>
            <span class="menu-title">Regional Offices</span>
          </a>
        </li> -->
      @if(in_array($auth->type,['super_user','admin','office_head']))
        <li class="p-3 nav-item {{ in_array(Route::currentRouteName(), array('system.report.index')) ? 'active' : ''}}">
          <a class="nav-link" href="{{route('system.report.index')}}">
            <i class="fa fa-chart-line menu-icon"></i>
            <span class="menu-title">Reporting</span>
          </a>
        </li>
        <li class="p-3 nav-item {{ in_array(Route::currentRouteName(), array('system.processor.index','system.processor.create','system.processor.edit','system.processor.reset')) ? 'active' : ''}}">
          <a class="nav-link" href="{{route('system.processor.index')}}">
            <i class="fa fa-user-plus menu-icon"></i>
            <span class="menu-title">Accounts</span>
          </a>
        </li>
      @endif
    @endif
  @endif
  </ul>

</nav>