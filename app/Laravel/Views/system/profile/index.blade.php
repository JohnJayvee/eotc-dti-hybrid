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
        <h1 class="mb-3">EMPLOYEE #<strong><u>{{$account->reference_id}}</u></strong></h1>
        <div class="text-center">
          <img src="{{strlen($account->filename) > 0 ? "{$account->directory}/resized/{$account->filename}" : asset('placeholder/user.png')}}" alt="profile" class="img-lg rounded-circle mb-3 img-avatar">
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
            <h3>{{$account->name}}</h3>
          </div>
        </div>
        <div class="text-left">
          <p><strong>Account Information</strong>
            <span class="float-right"><a href="{{route('system.profile.edit')}}" class="btn btn-sm btn-primary">Edit Record</a></span>
          </p>
          <ul>
            
            <li>Name: <strong>{{$account->name}}</strong></li>
            <li>Account Type : <strong>{{str::title($account->type)}}</strong></li>
            <li>Status : <strong>{{str::title($account->status)}}</strong></li>
            <li>Contact Number: <strong>{{$account->contact_number}}</strong></li>
            <li>Username: <strong>{{$account->username}}</strong></li>
            <li>Personal Email Address: <strong><u>{{$account->email?:"n/a"}}</u></strong></li>
            @if($account->type == "processor" || $account->type == "office_head")
            <li>Bureau/Office : <strong>{{$account->department->name}}</strong></li>
            <li>Application : 
              <ul>
              @forelse($application_list as $index)
                <li> <strong>{{$index->name}}</strong></li>
              @empty
              @endforelse
               </ul>
            </li>
            @endif

           <!--  <li>Residence Address: <strong>{{$account->residence_address}}</strong></li>
            <li>SSS Number: <strong>{{$account->sss_number}}</strong></li>
            <li>Philhealth Number: <strong>{{$account->philhealth_number}}</strong></li>
            <li>PAG-IBIG Number: <strong>{{$account->pagibig_number}}</strong></li>
            <li>Tax Identification Number (TIN): <strong>{{$account->tin}}</strong></li> -->
          </ul>
        </div>
         
        
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