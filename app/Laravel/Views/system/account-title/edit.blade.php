@extends('system._layouts.main')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('system.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('system.department.index')}}">Account Title Management</a></li>
    <li class="breadcrumb-item active" aria-current="page">Update Account Title</li>
  </ol>
</nav>
@stop

@section('content')
<div class="col-md-8 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Account Title Update Form</h4>
      <form class="create-form" method="POST" enctype="multipart/form-data">
        @include('system._components.notifications')
        {!!csrf_field()!!}
        <div class="form-group">
          <label for="input_suffix">Department</label>
          {!!Form::select("department_id", $department, old('department_id',$account_title->department_id), ['id' => "input_department_id", 'class' => "custom-select mb-2 mr-sm-2 ".($errors->first('department_id') ? 'is-invalid' : NULL)])!!}
          @if($errors->first('department_id'))
          <p class="mt-1 text-danger">{!!$errors->first('department_id')!!}</p>
          @endif
        </div>

        <div class="form-group">
          <label for="input_title">Name</label>
          <input type="text" class="form-control {{$errors->first('name') ? 'is-invalid' : NULL}}" id="input_title" name="name" placeholder="Account Title" value="{{old('name',$account_title->name)}}">
          @if($errors->first('name'))
          <p class="mt-1 text-danger">{!!$errors->first('name')!!}</p>
          @endif
        </div>

        <button type="submit" class="btn btn-primary mr-2">Update Record</button>
        <a href="{{route('system.account_title.index')}}" class="btn btn-light">Return to Account Title list</a>
      </form>
    </div>
  </div>
</div>
@stop

