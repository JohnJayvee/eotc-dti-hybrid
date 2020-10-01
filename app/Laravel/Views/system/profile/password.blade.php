@extends('system._layouts.main')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('system.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('system.profile.index')}}">My Profile</a></li>
    <li class="breadcrumb-item active" aria-current="page">Change Password</li>
  </ol>
</nav>
@stop

@section('content')
<div class="col-6 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Change Password Form</h4>
      <p class="card-description">
        Fill up the <strong class="text-danger">* required</strong> fields.
      </p>
      <form class="create-form" method="POST">
        @include('system._components.notifications')
        {!!csrf_field()!!}
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="input_password">New Password</label>
              <input type="password" class="form-control {{$errors->first('password') ? 'is-invalid' : NULL}}" id="input_password" name="password" placeholder="" value="">
              @if($errors->first('password'))
              <p class="mt-1 text-danger">{!!$errors->first('password')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="input_password_confirmation">Confirm Password</label>
              <input type="password" class="form-control" id="input_password_confirmation" name="password_confirmation" placeholder="" value="">
            </div>
          </div>
        </div>


        <button type="submit" class="btn btn-primary mr-2">Update Password</button>
        <a href="{{route('system.profile.index')}}" class="btn btn-light">Return to My Profile</a>
      </form>
    </div>
  </div>
</div>
@stop

@section('page-styles')
@stop

@section('page-scripts')
@stop