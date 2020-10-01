@extends('system._layouts.main')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('system.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">My Profile</li>
  </ol>
</nav>
@stop

@section('content')
<div class="row">
  <div class="col-12 stretch-card">
    @include('system._components.notifications')
  </div>
</div>
<div class="row">
  <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 grid-margin stretch-card">

    <div class="card">
      <div class="card-body">
        <h1 class="mb-3">EMPLOYEE #<strong><u>{{$employee->employee_number}}</u></strong></h1>
        <div class="text-center">
          <img src="{{strlen($employee->filename) > 0 ? "{$employee->directory}/resized/{$employee->filename}" : asset('placeholder/user.png')}}" alt="profile" class="img-lg rounded-circle mb-3 img-avatar">
          @if($errors->first('file'))
          <p class="mt-1 text-danger">{!!$errors->first('file')!!}</p>
          @endif
          <form enctype="multipart/form-data" id="update_avatar_form" action="{{route('system.profile.image.edit')}}" method="POST">
            <div class="form-group text-left">
              {!!csrf_field()!!}
              <input type="file" name="file" id="input_avatar" class="form-control" accept="image/jpg,image/jpeg,image/png">
              <button type="submit" class="btn btn-success btn-sm">Update Picture</button>
            </div>
          </form>
          <div class="mb-3">
            <h3>{{$employee->name}}</h3>
            <div class="d-flex align-items-center justify-content-center">
              <h6 class="mb-0 mr-2 text-muted">{{$employee->employment_position}} - {{Helper::department_display($employee->employment_department)}}</h6>
            </div>
          </div>
        </div>
        <div class="text-left">
          {{-- <p><strong>Skills</strong></p>
          <div>
            <label class="badge badge-outline-dark">Chalk</label>
            <label class="badge badge-outline-dark">Hand lettering</label>
            <label class="badge badge-outline-dark">Information Design</label>
            <label class="badge badge-outline-dark">Graphic Design</label>
            <label class="badge badge-outline-dark">Web Design</label>  
          </div> --}} 
          <p><strong>Personal Information</strong>
            <span class="float-right"><a href="{{route('system.profile.edit')}}" class="btn btn-sm btn-primary">Edit Record</a></span>
          </p>
          <ul>
            <li>Name: <strong>{{$employee->name}}</strong></li>
            <li>Birthdate: <strong>{{$employee->birthdate ?$employee->birthdate->format("F d Y"):""}}</strong></li>
            <li>Contact Number: <strong>{{$employee->contact_number}}</strong></li>
            <li>Civil Status: <strong>{{Helper::nice_display($employee->civil_status)}}</strong></li>

            <li>Personal Email Address: <strong><u>{{$employee->personal_email?:"n/a"}}</u></strong></li>
            <li>Residence Address: <strong>{{$employee->residence_address}}</strong></li>
            <li>SSS Number: <strong>{{$employee->sss_number}}</strong></li>
            <li>Philhealth Number: <strong>{{$employee->philhealth_number}}</strong></li>
            <li>PAG-IBIG Number: <strong>{{$employee->pagibig_number}}</strong></li>
            <li>Tax Identification Number (TIN): <strong>{{$employee->tin}}</strong></li>
          </ul>
        </div>
         
        
      </div>
    </div>

  </div>
  
  <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <p><strong>Employment Information</strong>
        </p>
        <label class="badge badge-{{Helper::status_badge($employee->status)}}" style="font-size: 24px;">{{Helper::status_display($employee->status)}}</label>
        <ul>
          <li>Time IN : <strong>{{Helper::time_only($employee->time_in)}}</strong> {{$employee->grace_period_min > 0 ? "w/ {$employee->grace_period_min} mins. grace period" : NULL}}</li>
          <li>Employment Status: <strong>{{Helper::status_display($employee->employment_status)}}</strong></li>
          <li>Job Position: <strong>{{$employee->employment_position}}</strong></li>
          <li>Department: <strong>{{Helper::department_display($employee->employment_department)}}</strong></li>
          <li>Assigned Email Address: <strong>{{$employee->email}}</strong></li>
          <li>Date Hired: <strong>{{$employee->employment_date_hired ? $employee->employment_date_hired->format("d F Y"):""}}</strong> </li>
          <li>Last Day Duty: <strong>{{$employee->employment_last_day?  $employee->employment_last_day->format("d F Y"):"Present"}} 
            @if($employee->employment_date_hired)
            ({{Str::title($employee->employment_date_hired->longAbsoluteDiffForHumans($employee->employment_last_day?:Carbon::now(),2))}})
            @endif
          </strong></li>
          <li>Temporary Employment Start Date:  <strong>{{$employee->employment_temp_start_date?  $employee->employment_temp_start_date->format("d F Y"):"-"}}</strong></li>
          <li>Temporary Employment End Date:  <strong>{{$employee->employment_temp_end_date?  $employee->employment_temp_end_date->format("d F Y"):"-"}}</strong></li>
          <li>Probationary Start Date:  <strong>{{$employee->employment_probationary_start_date?  $employee->employment_probationary_start_date->format("d F Y"):"-"}}</strong></li>
          <li>Probationary End Date:  <strong>{{$employee->employment_probationary_end_date?  $employee->employment_probationary_end_date->format("d F Y"):"-"}}</strong></li>
          <li>Regular Start Date:  <strong>{{$employee->employment_regular_start_date?  $employee->employment_regular_start_date->format("d F Y"):"-"}}</strong></li>
          <li>Regular End Date:  <strong>{{$employee->employment_regular_end_date?  $employee->employment_regular_end_date->format("d F Y"):"-"}}</strong></li>
          <li>HMO Provider: <strong>{{Helper::nice_display($employee->employment_hmo_provider?:"-")}}</strong></li>
          <li>HMO Number: <strong>{{$employee->employment_hmo_number?:"n/a"}}</strong></li>
          <li>Life Insurance Provider: <strong>{{Helper::nice_display($employee->employment_life_insurance_provider?:"-")}}</strong></li>
          <li>Life Insurance Policy #: <strong>{{$employee->employment_life_insurance_number?:"n/a"}}</strong></li>
        </ul>
      </div>
    </div>
  </div>
</div>
@stop

@section('page-styles')
<style type="text/css">
  .img-avatar{cursor: pointer;}
  #update_avatar_form{ display: none; }
</style>
@stop

@section('page-scripts')
<script type="text/javascript">
  $(function(){
    $(".img-avatar").on("click",function(){
      $("#input_avatar").trigger('click')
    })

    $("#input_avatar").on("change",function(){
      $("#update_avatar_form").submit()
    });
  })
</script>
@stop