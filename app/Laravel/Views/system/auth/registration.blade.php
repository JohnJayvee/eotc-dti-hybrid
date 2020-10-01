@extends('web._layouts.main')


@section('content')
<!--team section start-->
<section class="px-120 pt-110 pb-80 gray-light-bg">
    <div class="container">
         <h5 class="text-title pb-3"><i class="fa fa-pencil-alt"></i> CREATE ACCOUNT</h5>
        <div class="card login-signup-form" style="border-radius: 8px;">
            <form method="POST" action="" enctype="multipart/form-data">
            {!!csrf_field()!!}
                <div class="card-body pl-5 py-0" style="padding-right: 10em;">
                    <h5 class="text-title text-uppercase pt-5">Account Details</h5>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 ">
                            <div class="form-group">
                                <label class="text-form pb-2">Email</label>
                                <input type="email" class="form-control {{ $errors->first('email') ? 'is-invalid': NULL  }} form-control-sm" name="email" placeholder="Email Address" value="{{old('email')}}">
                                @if($errors->first('email'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('email')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-form pb-2">Password</label>
                                <input type="password" class="form-control {{ $errors->first('password') ? 'is-invalid': NULL  }} form-control-sm" name="password" placeholder="Password">
                                @if($errors->first('password'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('password')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-form pb-2">Confirm Password</label>
                                <input type="password" class="form-control form-control-sm" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="form pt-0">
                <div class="card-body pl-5" style="padding-right: 10em">
                    <h5 class="text-title text-uppercase ">Account Information</h5>
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-form pb-2">First Name</label>
                                <input type="text" class="form-control {{ $errors->first('fname') ? 'is-invalid': NULL  }} form-control-sm" name="fname" placeholder="Firstname" value="{{old('fname')}}">
                                 @if($errors->first('fname'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('fname')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group mb-0">
                                <label class="text-form pb-2">Last Name</label>
                                <input type="text" class="form-control {{ $errors->first('lname') ? 'is-invalid': NULL  }} form-control-sm" name="lname" placeholder="Lastname" value="{{old('lname')}}">
                                @if($errors->first('lname'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('lname')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group ">
                                <label class="text-form pb-2">Middle Name</label>
                                <input type="text" class="form-control form-control-sm" name="mname" placeholder="Middlename" value="{{old('mname')}}">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-form pb-2">Contact Number</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-title fw-600">+63 <span class="pr-1 pl-2" style="padding-bottom: 2px"> |</span></span>
                                    </div>
                                    <input type="text" class="form-control {{ $errors->first('contact_number') ? 'is-invalid': NULL  }} br-left-white" name="contact_number" placeholder="Contact Number" value="{{old('contact_number')}}">
                                    
                                </div>
                                @if($errors->first('contact_number'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('contact_number')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="region_name" id="input_region_name" value="{{old('region_name')}}">
                    <input type="hidden" class="form-control" name="town_name" id="input_town_name" value="{{old('town_name')}}">
                    <input type="hidden" class="form-control" name="brgy_name" id="input_brgy_name" value="{{old('brgy_name')}}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-form pb-2">Region</label>
                                {!!Form::select('region',[],old('region'),['id' => "input_region",'class' => "form-control form-control-sm classic ".($errors->first('region') ? 'border-red' : NULL)])!!}
                                @if($errors->first('region'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('region')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-form pb-2">City Municipality</label>
                                {!!Form::select('town',[],old('town'),['id' => "input_town",'class' => "form-control form-control-sm classic ".($errors->first('city') ? 'border-red' : NULL)])!!}
                                @if($errors->first('town'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('town')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-form pb-2">Barangay</label>
                                {!!Form::select('brgy',[],old('brgy'),['id' => "input_brgy",'class' => "form-control form-control-sm classic ".($errors->first('brgy') ? 'border-red' : NULL)])!!}
                                @if($errors->first('brgy'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('brgy')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-form pb-2">Street Name</label>
                                <input type="text" class="form-control {{ $errors->first('street_name') ? 'is-invalid': NULL  }} form-control-sm" name="street_name" placeholder="Street Name" value="{{old('street_name')}}">
                                 @if($errors->first('street_name'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('street_name')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-form pb-2">BLDG/ Unit Number</label>
                                <input type="text" class="form-control {{ $errors->first('unit_number') ? 'is-invalid': NULL  }} form-control-sm" name="unit_number" placeholder="Unit Number" value="{{old('unit_number')}}">
                                @if($errors->first('unit_number'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('unit_number')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="input_zipcode" class="text-form pb-2">Zipcode</label>
                                <input type="text" id="input_zipcode" class="form-control {{ $errors->first('zipcode') ? 'is-invalid': NULL  }}" name="zipcode" value="{{old('zipcode',session()->get('soleproprietorship.new_business.zip_code'))}}" readonly="readonly">
                                @if($errors->first('zipcode'))
                                <p class="help-block text-danger">{{$errors->first('zipcode')}}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-form pb-2">Birthdate</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control datepicker {{ $errors->first('birthdate') ? 'is-invalid': NULL  }} br-right-white p-2" name="birthdate" placeholder="YYYY-MM-DD" value="{{old('birthdate')}}">
                                    <div class="input-group-append">
                                        <span class="input-group-text text-title fw-600"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                                @if($errors->first('birthdate'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('birthdate')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <h5 class="text-title text-uppercase ">Account Requirements</h5>
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-form pb-2">TIN No.</label>
                                <input type="text" class="form-control {{ $errors->first('tin_no') ? 'is-invalid': NULL  }} form-control-sm" name="tin_no" placeholder="TIN No." value="{{old('tin_no')}}">
                                @if($errors->first('tin_no'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('tin_no')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-form pb-2">SSS No.</label>
                                <input type="text" class="form-control {{ $errors->first('sss_no') ? 'is-invalid': NULL  }} form-control-sm" name="sss_no" placeholder="SSS No." value="{{old('sss_no')}}">
                                @if($errors->first('sss_no'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('sss_no')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-form pb-2">PHIC No.</label>
                                <input type="text" class="form-control {{ $errors->first('phic_no') ? 'is-invalid': NULL  }} form-control-sm" name="phic_no" placeholder="PHIC No." value="{{old('phic_no')}}">
                                @if($errors->first('phic_no'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('phic_no')}}</small>
                                @endif
                            </div>
                        </div>
                        
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <label class="text-form pb-2">Application Requirements</label>
                            <div class="form-group">
                                <div class="upload-btn-wrapper">
                                    <button class="btn vertical" style="color: #ADADAD">
                                        <i class="fa fa-upload fa-4x" ></i>
                                        <span class="pt-1">Upload Here</span>
                                    </button>
                                    <input type="file" name="file" class="form-control" id="file">
                                </div>
                                @if($errors->first('file'))
                                    <label style="vertical-align: top;padding-top: 40px;color: red;" class="fw-500 pl-3">{{$errors->first('file')}}</label>
                                @else
                                    <label id="lblName" style="vertical-align: top;padding-top: 40px;" class="fw-500 pl-3"></label>
                                @endif
                                    
                            </div>
                        </div>
                    </div> -->
                     <button type="submit" class="btn secondary-solid-btn px-3 py-3 fs-14 "><i class="fa fa-paper-plane pr-2"></i>Create Account</button>
                </div>
            </form>
        </div>
        
    </div>

</section>
<!--team section end-->
<div id="gwt-standard-footer"></div>


@stop

@section('page-styles')
<link rel="stylesheet" href="{{asset('system/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<style type="text/css">
    .table-condensed{
        width: 15em;
        margin: 5px;
    }
    .table-condensed tr{
        text-align: center;
    }
</style>
@stop
@section('page-scripts')
<script src="{{asset('system/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
    (function(d, s, id) {
    var js, gjs = d.getElementById('gwt-standard-footer');

    js = d.createElement(s); js.id = id;
    js.src = "//gwhs.i.gov.ph/gwt-footer/footer.js";
    gjs.parentNode.insertBefore(js, gjs);
    }(document, 'script', 'gwt-footer-jsdk'));

    $('#file').change(function(e){
      var fileName = e.target.files[0].name;
      $('#lblName').text(fileName);
    });

    $.fn.get_region = function(input_region,input_province,input_city,input_brgy,selected){
    
      $(input_city).empty().prop('disabled',true)
      $(input_brgy).empty().prop('disabled',true)

      $(input_region).append($('<option>', {
                value: "",
                text: "Loading Content..."
            }));
      $.getJSON("{{env('PSGC_REGION_URL')}}", function( response ) {
          $(input_region).empty().prop('disabled',true)
          $.each(response.data,function(index,value){
            $(input_region).append($('<option>', {
                value: index,
                text: value
            }));
          })

          $(input_region).prop('disabled',false)
          $(input_region).prepend($('<option>',{value : "",text : "--Select Region--"}))
          if(selected.length > 0){
            $(input_region).val($(input_region+" option[value="+selected+"]").val());
          }else{
            $(input_region).val($(input_region+" option:first").val());
          }
      });
      // return result;
    };

    $.fn.get_city = function(reg_code,input_city,input_brgy,selected){
      $(input_brgy).empty().prop('disabled',true)
      $(input_city).append($('<option>', {
            value: "",
            text: "Loading Content..."
        }));
      $.getJSON("{{env('PSGC_CITY_URL')}}?region_code="+reg_code, function( data ) {
        console.log(data)
          $(input_city).empty().prop('disabled',true)
          $.each(data,function(index,value){
              $(input_city).append($('<option>', {
                  value: index,
                  text: value
              }));
          })

          $(input_city).prop('disabled',false)
          $(input_city).prepend($('<option>',{value : "",text : "--SELECT MUNICIPALITY/CITY, PROVINCE--"}))
          if(selected.length > 0){
            $(input_city).val($(input_city+" option[value="+selected+"]").val());
          }else{
            $(input_city).val($(input_city+" option:first").val());
          }
      });
      // return result;
    };

    $.fn.get_brgy = function(munc_code,input_brgy,selected){
      $(input_brgy).empty().prop('disabled',true);
      $(input_brgy).append($('<option>', {
                value: "",
                text: "Loading Content..."
            }));
      $.getJSON("{{env('PSGC_BRGY_URL')}}?city_code="+munc_code, function( data ) {
          $(input_brgy).empty().prop('disabled',true);

          $.each(data,function(index,value){
            $(input_brgy).append($('<option>', {
                value: index,
                text: value.desc,
                "data-zip_code" : (value.zip_code).trim()
            }));
          })
          $(input_brgy).prop('disabled',false)
          $(input_brgy).prepend($('<option>',{value : "",text : "--SELECT BARANGAY--"}))

          if(selected.length > 0){
            $(input_brgy).val($(input_brgy+" option[value="+selected+"]").val());

            if(typeof $(input_brgy+" option[value="+selected+"]").data('zip_code')  === undefined){
              $(input_brgy.replace("brgy","zipcode")).val("")
            }else{
              $(input_brgy.replace("brgy","zipcode")).val($(input_brgy+" option[value="+selected+"]").data('zip_code'))
            }

          }else{
            $(input_brgy).val($(input_brgy+" option:first").val());
          }
      });
    }
   
    $(function(){
        $('.datepicker').datepicker({
          format : "yyyy-mm-dd",
          maxViewMode : 2,
          autoclose : true,
          zIndexOffset: 9999
        });

        $(this).get_region("#input_region","#input_province","#input_town","#input_brgy","{{old('region')}}")

        $("#input_region").on("change",function(){
            var _val = $(this).val();
            var _text = $("#input_region option:selected").text();
            $(this).get_city($("#input_region").val(), "#input_town", "#input_brgy", "{{old('town')}}");
            $('#input_zipcode').val('');
            $('#input_region_name').val(_text);
        });

        $("#input_town").on("change",function(){
            var _val = $(this).val();
            var _text = $("#input_town option:selected").text();
            $(this).get_brgy(_val, "#input_brgy", "");
            $('#input_zipcode').val('');
            $('#input_town_name').val(_text);
        });

        @if(strlen(old('region')) > 0)
            $(this).get_city("{{old('region')}}", "#input_town", "#input_brgy", "{{old('town')}}");
        @endif

        @if(strlen(old('town')) > 0)
            $(this).get_brgy("{{old('town')}}", "#input_brgy", "{{old('brgy', session()->get('soleproprietorship.new_business.brgy'))}}");
        @endif

        $("#input_brgy").on("change",function(){
            $('#input_zipcode').val($(this).find(':selected').data('zip_code'))
            var _text = $("#input_brgy option:selected").text();
            $('#input_brgy_name').val(_text);
        });

    })
</script>

@endsection
