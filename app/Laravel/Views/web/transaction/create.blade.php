@extends('web._layouts.main')


@section('content')



<!--team section start-->
<section class="px-120 pt-110 pb-80 gray-light-bg">
    <div class="container">
         
         <div class="row flex-row items-center px-4">
            <h5 class="text-title pb-3"><i class="fa fa-file"></i> E<span class="text-title-two"> SUBMISSION</span></h5>
            <a href="{{route('web.transaction.history')}}" class="custom-btn badge-primary-2 text-white " style="float: right;margin-left: auto;">Application History</a>
         </div>
          @include('web._components.notifications')
        <div class="card">
            <form method="POST" action="" enctype="multipart/form-data">
            {!!csrf_field()!!}
                <input type="hidden" name="department_name" id="input_department_name" value="{{old('department_name')}}">
                <input type="hidden" name="application_name" id="input_application_name" value="{{old('application_name')}}">
                <!-- <input type="hidden" name="regional_name" id="input_regional_name" value="{{old('regional_name')}}"> -->
                <div class="card-body px-5 py-0">
                    <h5 class="text-title text-uppercase pt-5">Application information</h5>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Your Name</label>
                                <input type="text" class="form-control form-control-sm {{ $errors->first('full_name') ? 'is-invalid': NULL  }}"  placeholder="Last Name, First Name, Middle Name" name="full_name" value="{{old('full_name',Auth::guard('customer')->user()->name) }}">
                                @if($errors->first('full_name'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('full_name')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Company Name</label>
                                <input type="text" class="form-control form-control-sm {{ $errors->first('company_name') ? 'is-invalid': NULL  }}" placeholder="Company Name" name="company_name" value="{{old('company_name')}}">
                                @if($errors->first('company_name'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('company_name')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Your Email Address</label>
                                <input type="text" class="form-control form-control-sm" name="email" placeholder="Email Address" value="{{old('email',Auth::guard('customer')->user()->email)}}">
                                @if($errors->first('email'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('email')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Contact Number</label>
                                <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text text-title fw-600">+63 <span class="pr-1 pl-2" style="padding-bottom: 2px"> |</span></span>
                                  </div>
                                  <input type="text" class="form-control br-left-white" placeholder="Contact Number" name="contact_number" value="{{old('contact_number',Auth::guard('customer')->user()->contact_number)}}">
                                </div>
                                @if($errors->first('contact_number'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('contact_number')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                            <!-- <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Regional Offices</label>
                                {!!Form::select("regional_id", $regional_offices, old('regional_id'), ['id' => "input_regional_id", 'class' => "form-control form-control-sm classic ".($errors->first('regional_id') ? 'border-red' : NULL)])!!}
                            </div>
                            @if($errors->first('regional_id'))
                                <small class="form-text pl-1" style="color:red;">{{$errors->first('regional_id')}}</small>
                            @endif
                            </div> -->
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Bureau/Office</label>
                                {!!Form::select("department_id", $department, old('department_id'), ['id' => "input_department_id", 'class' => "form-control form-control-sm classic ".($errors->first('department_id') ? 'border-red' : NULL)])!!}
                            </div>
                            @if($errors->first('department_id'))
                                <small class="form-text pl-1" style="color:red;">{{$errors->first('department_id')}}</small>
                            @endif
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Type of Application</label>
                                {!!Form::select('application_id',['' => "--Choose Application Type--"],old('application_id'),['id' => "input_application_id",'class' => "form-control form-control-sm classic ".($errors->first('application_id') ? 'border-red' : NULL)])!!}
                               
                            </div>
                            @if($errors->first('application_id'))
                                <small class="form-text pl-1" style="color:red;">{{$errors->first('application_id')}}</small>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Processing Fee</label>
                                <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text text-title fw-600">PHP <span class="pr-1 pl-2" style="padding-bottom: 2px"> |</span></span>
                                  </div>
                                  <input type="text" class="form-control br-left-white br-right-white {{ $errors->first('processing_fee') ? 'is-invalid': NULL  }}" placeholder="Payment Amount" name="processing_fee" id="input_processing_fee" value="{{old('processing_fee')}}" readonly>
                                  <div class="input-group-append">
                                    <span class="input-group-text text-title fw-600">| <span class="text-gray pl-2 pr-2 pt-1"> .00</span></span>
                                  </div>
                                </div>
                                @if($errors->first('processing_fee'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('processing_fee')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Partial Payment <code>Allowed partial amount is PHP <input type="text"  id="input_partial_label" value="{{old('partial_label')}}" name="partial_label" style="width: 70px;border:none;" readonly></code></label>
                                <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text text-title fw-600">PHP <span class="pr-1 pl-2" style="padding-bottom: 2px"> |</span></span>
                                  </div>
                                  <input type="number" class="form-control br-left-white br-right-white {{ $errors->first('partial_amount') ? 'is-invalid': NULL  }}" placeholder="Partial Payment Amount" name="partial_amount" id="input_partial_amount" value="{{old('partial_amount')}}" >
                                  <div class="input-group-append">
                                    <span class="input-group-text text-title fw-600">| <span class="text-gray pl-2 pr-2 pt-1"> .00</span></span>
                                  </div>
                                </div>
                                @if($errors->first('partial_amount'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('partial_amount')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                  
                    <div id="requirements_container">
                        <label class="text-form pb-2">Required Documents</label>
                        <table id="requirements">
                           <tbody>
                               
                           </tbody>
                        </table>
                    </div>
                    <input type="hidden" name="file_count" id="file_count">
                    <h5 class="text-title text-uppercase pt-3">Upload Requirements</h5>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <label class="text-form pb-2">Application Requirements</label>
                            <div class="form-group">
                                <div class="upload-btn-wrapper">
                                    <button class="btn vertical" style="color: #ADADAD">
                                        <i class="fa fa-upload fa-4x" ></i>
                                        <span class="pt-1">Upload Here</span>
                                    </button>
                                    <input type="file" name="file[]" class="form-control" id="file" accept="application/pdf" multiple>
                                </div>
                                @forelse($errors->all() as $error)
                                    @if($error == "Only PDF File are allowed.")
                                        <label id="lblName" style="vertical-align: top;padding-top: 40px;color: red;" class="fw-500 pl-3">{{$error}}</label>
                                    @elseif($error == "No File Uploaded.")
                                        <label id="lblName" style="vertical-align: top;padding-top: 40px;color: red;" class="fw-500 pl-3">{{$error}}</label>
                                    @elseif($error == "Please Submit minimum requirements.")
                                        <label id="lblName" style="vertical-align: top;padding-top: 40px;color: red;" class="fw-500 pl-3">{{$error}}</label>
                                    @endif
                                @empty
                                    <label id="lblName" style="vertical-align: top;padding-top: 40px;" class="fw-500 pl-3"></label>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="form pt-0">
                <div class=" card-body px-5 pb-5">
                    <h5 class="text-title text-uppercase ">Physical Submission</h5>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="customControlValidation1" name="is_check" value="1">
                        <label class="custom-control-label fs-14 fw-600 text-black" for="customControlValidation1">&nbsp;&nbsp; Check this option if you're going to submit physical copies of your documents. The system will generate a QR Code that you'll have to attach to the envelope of your physical copies.</label>
                        
                    </div>
                    <button class="btn badge badge-primary-2 text-white px-4 py-2 fs-14" type="submit"><i class="fa fa-paper-plane pr-2"></i>  Send Application</button>
                </div>
            </form>
        </div>
        
    </div>

</section>
<!--team section end-->


@stop
@section('page-styles')
<style type="text/css">
    .custom-btn{
        padding: 5px 10px;
        border-radius: 10px;
        height: 37px;
    }
    .custom-btn:hover{
        background-color: #7093DC !important;
        color: #fff !important;
    }
    #input_partial_label:focus{
         outline:none;
    }
</style>
@endsection
@section('page-scripts')
<script type="text/javascript">

    $('#file').change(function(e){
        $('#lblName').empty();
        $('#lblName').css("color", "black");
       var files = [];
        for (var i = 0; i < $(this)[0].files.length; i++) {
            files.push($(this)[0].files[i].name);
        }
        $('#lblName').text(files.join(', '));
        $('#file_count').val(files.length);
    });



    $.fn.get_application_type = function(department_id,input_purpose,selected){
        $(input_purpose).empty().prop('disabled',true)
        $(input_purpose).append($('<option>', {
                  value: "",
                  text: "Loading Content..."
              }));
        $.getJSON( "{{route('web.get_application_type')}}?department_id="+department_id, function( result ) {
            $(input_purpose).empty().prop('disabled',true)
            $.each(result.data,function(index,value){
              // console.log(index+value)
              $(input_purpose).append($('<option>', {
                  value: index,
                  text: value
              }));
            })

            $(input_purpose).prop('disabled',false)
            $(input_purpose).prepend($('<option>',{value : "",text : "--Choose Application Type--"}))

            if(selected.length > 0){
              $(input_purpose).val($(input_purpose+" option[value="+selected+"]").val());

            }else{
              $(input_purpose).val($(input_purpose+" option:first").val());
              //$(this).get_extra(selected)
            }
        });
        // return result;
    };

    $.fn.get_requirements = function(application_id){
        $("#requirements tr").remove(); 
        $.getJSON( "{{route('web.get_requirements')}}?type_id="+application_id, function( response ) {
            $.each(response.data,function(index,value){
                $("#requirements").find('tbody').append("<tr><td>" + value + "</td></tr>");
            })

            $("#requirements_container").show();
        });
        // return result;
    };
    $("#requirements_container").hide();

    $("#input_department_id").on("change",function(){
      var department_id = $(this).val()
      var _text = $("#input_department_id option:selected").text();
      $(this).get_application_type(department_id,"#input_application_id","")
      $('#input_department_name').val(_text);
    })

    $("#input_regional_id").on("change",function(){
      var _text = $("#input_regional_id option:selected").text();
      $('#input_regional_name').val(_text);
    })

    $('#input_application_id').change(function() {
        var amount;
        var _text = $("#input_application_id option:selected").text();
        $.getJSON('/amount?type_id='+this.value, function(result){
            amount = parseFloat(result.data[0])
            if (result.data[1]) {
                partial = parseFloat(result.data[1])
            }else{
                 partial = 0
            }
           
            $('#input_processing_fee').val(formatNumber(amount));
            $('#input_partial_label').val(partial);
        });
        var application_id = $(this).val()
        $(this).get_requirements(application_id,"#input_application_id","")
        
        $('#input_application_name').val(_text);

    }).change();
    function formatNumber (num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    }
    @if(old('application_id'))
        $(this).get_requirements("{{old('application_id')}}","#input_application_id","{{old('application_id')}}")
        $(this).get_application_type("{{old('department_id')}}","#input_application_id","{{old('application_id')}}")
    @endif
</script>

@endsection