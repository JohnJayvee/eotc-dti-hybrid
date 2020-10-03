@extends('system._layouts.main')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('system.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('system.regional_office.index')}}">Regional Office Management</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add New Regional Office</li>
  </ol>
</nav>
@stop

@section('content')
<div class="col-md-10 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Regional Office Create Form</h4>
      <p class="card-description">
        Fill up the <strong class="text-danger">* required</strong> fields.
      </p>
      <form class="create-form" method="POST" enctype="multipart/form-data">
        @include('system._components.notifications')
        {!!csrf_field()!!}
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Regional Office Name</label>
              <input type="text" class="form-control {{$errors->first('name') ? 'is-invalid' : NULL}}" id="input_name" name="name" placeholder="Regional Office Name" value="{{old('name')}}">
              @if($errors->first('name'))
              <p class="mt-1 text-danger">{!!$errors->first('name')!!}</p>
              @endif
            </div>
          </div>
        </div>
       

        <button type="submit" class="btn btn-primary mr-2">Create Record</button>
        <a href="{{route('system.regional_office.index')}}" class="btn btn-light">Return to Regional Office list</a>
      </form>
    </div>
  </div>
</div>
@stop
@section('page-styles')
<style type="text/css">
   .border-red{
        border:solid 2px #dc3545 !important;
    }
</style>
@endsection
