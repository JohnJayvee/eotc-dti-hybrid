<link rel="stylesheet" href="{{asset('system/vendors/mdi/css/materialdesignicons.min.css')}}">
<link rel="stylesheet" href="{{asset('web/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('system/vendors/css/vendor.bundle.base.css')}}">
<link rel="stylesheet" href="{{asset('system/css/vertical-layout-light/style.css')}}">
<style type="text/css">
    .text-bold{font-weight: 600;}
    table tbody a { text-decoration: underline; }
    table tbody .dropdown-menu a{ text-decoration: none; }
    .sidebar .nav {margin-bottom: 30px; }
    .nav-timestamp{ min-width: 150px;  max-width: 280px; }
    .img-lg{ height: auto; }
    .uppercase { text-transform: uppercase; }
    .lowercase { text-transform: lowercase; }
    .datepicker-days table,.datepicker-months table,.datepicker-years table,.datepicker-decades table,.datepicker-centuries table{ width: 100% }

    .datepicker tr td.disabled{ cursor : not-allowed !important; color: #999 !important; }
	input.datepicker{ text-transform: uppercase; }
	.mb5{ margin-bottom: 5px; }
	.badge{ font-size: 12px; padding: 5px 7px; }
	.badge-black {background: #333333; color: #fff;}
    .task-container{ background: #f2f2f2; padding: 15px 10px; margin-top: 10px; margin-bottom: 10px; border: 1px dashed #333; min-height: 100px; height: 100px; overflow-y: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis;}
    .navbar .navbar-brand-wrapper .navbar-brand img{
        width: 100%;
    }
    .navbar .navbar-brand-wrapper .brand-logo-mini img {  height: auto; }
    .btn-action { text-decoration: none; }
    .fw{ width: 100%; max-width: 100%; }

    .badge-danger,.btn-danger{
        background-color: #df1234;
    }
    .border-red{
        border-color: #dc3545;
    }
    .is-invalid{
        border: 1px solid #dc3545 !important;
    }
    .text-danger{
        color: #df1234 !important;
    }
    .table-wrap td{
        word-wrap:break-word !important;
        white-space: normal !important;
    }
    .badge-success,.btn-success{
        background-color: #14a70a;
    }
    .text-success{
        color: #14a70a;
    }
    .text-title{
        color: #254DA0 !important;
    }
    .text-sub-title{
        color: #B53232 !important;
    }
    .fw-500{
        font-weight: 500;
    }
    .fw-600{
        font-weight: 600;
    }
    a.text-title{
        text-decoration: none;
    }

    .card-counter{
    box-shadow: 2px 2px 10px #DADADA;
    margin: 5px;
    padding: 20px 10px;
    background-color: #fff;
    height: 100px;
    border-radius: 5px;
    transition: .3s linear all;
  }

  .card-counter:hover{
    box-shadow: 4px 4px 20px #DADADA;
    transition: .3s linear all;
  }

  .card-counter.primary{
    background-color: #9125B1;
    color: #FFF;
  }

  .card-counter.danger{
    background-color: #CC1126;
    color: #FFF;
  }  

  .card-counter.success{
    background-color: #2F9A2A;
    color: #FFF;
  }  

  .card-counter.info{
    background-color: #EF9E06;
    color: #FFF;
  }  

  .card-counter i{
    font-size: 4em;
    opacity: 0.2;
    float: right;
    padding-right: 15px;
  }

  .card-counter.info .count-numbers{
    position: absolute;
    padding-left: 10px;
    right: 50px;
    top: 30px;
    font-size: 32px;
    display: block;
  }
   .card-counter.success .count-numbers{
    position: absolute;
    padding-left: 10px;
    right: 52px;
    top: 30px;
    font-size: 30px;
    display: block;
  }

  .card-counter.primary .count-numbers{
    position: absolute;
    right: 1.6em;
    top: 30px;
    font-size: 30px;
    display: block;
  }

   .card-counter.danger .count-numbers{
    position: absolute;
    padding-left: 10px;
    right: 52px;
    top: 30px;
    font-size: 30px;
    display: block;
  }
    .card-counter .count-name{
        top: 35px;
        text-transform: capitalize;
        display: block;
        font-size: 18px;
    }
    .fs-15{
        font-size: 15px !important;
    }
    .text-dim{
        color:#C4C4C4;
    }

    select.classic {
        background-image:
        linear-gradient(45deg, transparent 50%, #0235AA 60%),
        linear-gradient(135deg, #0235AA 40%, transparent 50%) !important;
        background-position:calc(100% - 23px) 18px,calc(100% - 16px) 18px,100% 0;
        background-size:5px 8px,8px 5px;
        background-repeat: no-repeat;
        -webkit-appearance: none;
        -moz-appearance: none;
    }
    .has-search .form-control-feedback {
        position: absolute;
        z-index: 2;
        display: block;
        right: 10px;
        width: 2.375rem;
        height: 2.375rem;
        line-height: 2.7rem;
        text-align: center;
        pointer-events: none;
        color: #254DA0;

    }
    .has-search input{
        padding-left: 15px;
    }


    .my-hr-line {
        position: relative;
        left: -20px;
        width: calc(100% + 38px);
        border: 1px dashed  #ECECEC;
    }
    
    .btn-transparent{

        color: #254DA0;
        background-color: transparent;
        border-color: #00cccc;
        border-radius: 5px;
    }
    .border-5{
        border-radius: 5px !important;
    }
    a.sign-up {
        color: red;
    }
    .field-icon {
        right: 70px;
        margin-top: -25px;
        position: absolute;
        z-index: 2;
        color: #254DA0;
    }

    input[type=password]::-ms-reveal,
    input[type=password]::-ms-clear
    {
        display: none;
    }
</style>
@yield('page-styles')
