@extends('system._layouts.main')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('system.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('system.profile.index')}}">My Profile</a></li>
    <li class="breadcrumb-item active" aria-current="page">Update Profile</li>
  </ol>
</nav>
@stop

@section('content')
<div class="col-6 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Update Profile Form</h4>
      <p class="card-description">
        Fill up the <strong class="text-danger">* required</strong> fields.
      </p>
      <form class="create-form" method="POST">
        @include('system._components.notifications')
        {!!csrf_field()!!}
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="input_personal_email">Firstname</label>
              <input type="text" class="form-control lowercase {{$errors->first('fname') ? 'is-invalid' : NULL}}" id="input_fname" name="fname" placeholder="" value="{{old('fname',$account->fname)}}">
              @if($errors->first('email'))
              <p class="mt-1 text-danger">{!!$errors->first('email')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="input_personal_email">Middlename</label>
              <input type="text" class="form-control lowercase {{$errors->first('mname') ? 'is-invalid' : NULL}}" id="input_mname" name="mname" placeholder="" value="{{old('mname',$account->mname)}}">
              @if($errors->first('mname'))
              <p class="mt-1 text-danger">{!!$errors->first('mname')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="input_personal_email">Lastname</label>
              <input type="text" class="form-control lowercase {{$errors->first('lname') ? 'is-invalid' : NULL}}" id="input_lname" name="lname" placeholder="" value="{{old('lname',$account->lname)}}">
              @if($errors->first('lname'))
              <p class="mt-1 text-danger">{!!$errors->first('lname')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="input_personal_email">Email Address</label>
              <input type="email" class="form-control lowercase {{$errors->first('email') ? 'is-invalid' : NULL}}" id="input_email" name="email" placeholder="" value="{{old('email',$account->email)}}">
              @if($errors->first('email'))
              <p class="mt-1 text-danger">{!!$errors->first('email')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="input_contact_number">Contact Number</label>
              <input type="text" class="form-control {{$errors->first('contact_number') ? 'is-invalid' : NULL}}" id="input_contact_number" name="contact_number" placeholder="" value="{{old('contact_number',$account->contact_number)}}" data-inputmask-alias="0\9999999999">
              @if($errors->first('contact_number'))
              <p class="mt-1 text-danger">{!!$errors->first('contact_number')!!}</p>
              @endif
            </div>
          </div>
          
        </div>

       

        <button type="submit" class="btn btn-primary mr-2">Update Record</button>
        <a href="{{route('system.profile.index')}}" class="btn btn-light">Return to My Profile</a>
      </form>
    </div>
  </div>
</div>
@stop

@section('page-styles')
@stop

@section('page-scripts')
<script src="{{asset('system/vendors/inputmask/jquery.inputmask.bundle.js')}}"></script>
<script src="{{asset('system/js/inputmask.js')}}"></script>
@stop