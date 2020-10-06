@extends('system._layouts.main')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('system.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('system.application_requirements.index')}}">Application Requirement Management</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add New Application Requirement</li>
  </ol>
</nav>
@stop

@section('content')
<div class="col-md-8 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Application Requirement Create Form</h4>
      <form class="create-form" method="POST" enctype="multipart/form-data">
        @include('system._components.notifications')
        {!!csrf_field()!!}
        <div class="form-group">
          <label for="input_title">Name</label>
          <input type="text" class="form-control {{$errors->first('name') ? 'is-invalid' : NULL}}" id="input_title" name="name" placeholder="Requirement Name" value="{{old('name')}}">
          @if($errors->first('name'))
          <p class="mt-1 text-danger">{!!$errors->first('name')!!}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="input_title">Is Required?</label>
          {!!Form::select("is_required", $status_type, old('is_required'), ['id' => "input_is_required", 'class' => "custom-select".($errors->first('is_required') ? ' is-invalid' : NULL)])!!}
          @if($errors->first('is_required'))
          <p class="mt-1 text-danger">{!!$errors->first('is_required')!!}</p>
          @endif
        </div>

        <button type="submit" class="btn btn-primary mr-2">Create Record</button>
        <a href="{{route('system.application_requirements.index')}}" class="btn btn-light">Return to Requirements list</a>
      </form>
    </div>
  </div>
</div>
@stop

