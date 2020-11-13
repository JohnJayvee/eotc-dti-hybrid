@extends('system._layouts.main')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('system.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('system.department.index')}}">Bureau/Office Management</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Bureau/Office</li>
  </ol>
</nav>
@stop

@section('content')
<div class="col-md-8 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Bureau/Office Edit Form</h4>
      <form class="create-form" method="POST" enctype="multipart/form-data">
        @include('system._components.notifications')
        {!!csrf_field()!!}
        <div class="form-group">
          <label for="input_title">Name</label>
          <input type="text" class="form-control {{$errors->first('name') ? 'is-invalid' : NULL}}" id="input_title" name="name" placeholder="Bureau/Office Name" value="{{old('name',$department->name)}}">
          @if($errors->first('name'))
          <p class="mt-1 text-danger">{!!$errors->first('name')!!}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="input_title">Code</label>
          <input type="text" class="form-control {{$errors->first('code') ? 'is-invalid' : NULL}}" id="input_title" name="code" placeholder="Bureau/Office Code" value="{{old('code',$department->code)}}">
          @if($errors->first('code'))
          <p class="mt-1 text-danger">{!!$errors->first('code')!!}</p>
          @endif
        </div>

        <button type="submit" class="btn btn-primary mr-2">Edit Record</button>
        <a href="{{route('system.department.index')}}" class="btn btn-light">Return to Bureau/Office list</a>
      </form>
    </div>
  </div>
</div>
@stop

