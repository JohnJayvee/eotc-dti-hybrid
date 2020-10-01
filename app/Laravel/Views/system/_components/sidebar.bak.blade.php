<nav class="sidebar sidebar-offcanvas" id="sidebar" style="background-color: #2D2F32;color: #ffff;">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="{{route('system.dashboard')}}">
        <i class="mdi mdi-clipboard-text-outline menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    {{-- <li class="nav-item">
      <a class="nav-link" href="{{route('system.account_code.generate')}}">
        <i class="mdi mdi-account-card-details menu-icon"></i>
        <span class="menu-title">Generate Account Code</span>
      </a>
    </li> --}}
    {{-- <li class="nav-item">
      <a class="nav-link" href="{{route('system.directory')}}">
        <i class="mdi mdi-account-card-details menu-icon"></i>
        <span class="menu-title">Employee Directory</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('system.attendance.index')}}">
        <i class="mdi mdi-calendar-clock menu-icon"></i>
        <span class="menu-title">My Attendance</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('system.overtime.index')}}">
        <i class="mdi mdi-calendar-plus menu-icon"></i>
        <span class="menu-title">My Overtime</span>
      </a>
    </li>

    <li class="nav-item ">
      <a class="nav-link" href="{{route('system.leave.index')}}">
        <i class="mdi mdi-calendar-multiple menu-icon"></i>
        <span class="menu-title">My Leave</span>
      </a>
    </li> --}}

    {{-- <li class="nav-item">
      <a class="nav-link" href="#">
        <i class="mdi mdi-airplay menu-icon"></i>
        <span class="menu-title">My Taskboard</span>
      </a>
    </li> --}}
    {{-- <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#my_report" aria-expanded="false" aria-controls="my_report">
        <i class="mdi mdi-calendar-text menu-icon"></i>
        <span class="menu-title">My Reports</span>
        @if($counter['for_approval'] > 0)
        <span class="badge badge-sm badge-primary">{{$counter['for_approval']}}</span>
        @endif
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="my_report">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('system.my_report.pending_overtime')}}">For Approval Overtime 
            @if($counter['pending_overtime'] > 0)
            <span class="badge badge-sm badge-primary">{{$counter['pending_overtime']}}</span>
            @endif
          </a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('system.my_report.pending_leave')}}">For Approval Leave
            @if($counter['pending_leave'] > 0)
            <span class="badge badge-sm badge-primary">{{$counter['pending_leave']}}</span>
            @endif
          </a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('system.my_report.attendance')}}">Summary of Attendance</a></li>
        </ul>
      </div>
    </li> --}}
  </ul>
  
  @if(in_array($auth->type,['super_user','admin']))
  <h6>OVERVIEW</h6>
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="{{route('system.travel_history.index')}}">
        <i class="mdi mdi-map-legend menu-icon"></i>
        <span class="menu-title">Applications</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('system.reporting.index')}}">
        <i class="mdi mdi-message-text-outline menu-icon"></i>
        <span class="menu-title">Reporting</span>
      </a>
    </li>
    
    
  </ul>
  @endif

  @if(in_array($auth->type,['super_user','admin']))
  <h6>Account Management</h6>
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="{{route('system.officer.index')}}">
        <i class="mdi mdi-shield-account menu-icon"></i>
        <span class="menu-title">Officer Accounts</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('system.account_code.index')}}">
        <i class="mdi mdi-key menu-icon"></i>
        <span class="menu-title">Account Code</span>
      </a>
    </li>
  </ul>
  @endif

</nav>