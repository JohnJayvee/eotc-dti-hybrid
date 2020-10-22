@extends('system._layouts.main')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('system.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('system.department.index')}}">Account Management</a></li>
    <li class="breadcrumb-item active" aria-current="page">Reset Password</li>
  </ol>
</nav>
@stop

@section('content')
<div class="col-md-8 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Account Update Form</h4>
      <p class="card-description">
        Fill up the <strong class="text-danger">* required</strong> fields.
      </p>
      <form class="create-form" method="POST" enctype="multipart/form-data">
        @include('system._components.notifications')
        {!!csrf_field()!!}
     

        <div class="form-group">
          <label for="input_title">Current Password</label>
          <input type="password" class="form-control {{$errors->first('current_password') ? 'is-invalid' : NULL}}" id="input_current_password" name="current_password" placeholder="Current Password" value="{{old('current_password')}}">
          @if($errors->first('current_password'))
          <p class="mt-1 text-danger">{!!$errors->first('current_password')!!}</p>
          @endif
        </div>
        
        <div class="form-group">
          <label for="input_title">New Password</label>
          <input type="password" class="form-control {{$errors->first('password') ? 'is-invalid' : NULL}}" id="input_password" name="password" placeholder="New Password" value="{{old('password')}}">
          @if($errors->first('password'))
          <p class="mt-1 text-danger">{!!$errors->first('password')!!}</p>
          @endif
        </div>

        <div class="form-group">
          <label for="input_title">Confirm Password</label>
          <input type="password" class="form-control {{$errors->first('password_confirmation') ? 'is-invalid' : NULL}}" id="input_password_confirmation" name="password_confirmation" placeholder="Confirm Password" value="{{old('password_confirmation')}}">
          @if($errors->first('password_confirmation'))
          <p class="mt-1 text-danger">{!!$errors->first('password_confirmation')!!}</p>
          @endif
        </div>
        <button type="submit" class="btn btn-primary mr-2">Reset Password</button>
        <a href="{{route('system.processor.index')}}" class="btn btn-light">Return to Processors list</a>
      </form>
    </div>
  </div>
</div>
@stop
@section('page-styles')
<style type="text/css">
  .is-invalid{
    border: solid 2px;
  }
</style>
@endsection
