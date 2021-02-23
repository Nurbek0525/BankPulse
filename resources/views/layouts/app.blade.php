<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <!-- Meta data -->
    <meta charset="UTF-8"> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="RatingApp" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @if(!empty($title))
            {{ $title }}
        @endif
    </title>
    <link rel="shortcut icon" href="{{ URL::asset('assetsnew/img/BankPulse.png') }}" />
    {{-- <link rel="stylesheet" type="text/css" href="{{ URL::asset('assetsnew/plugins/bootstrap/bootstrap.css') }}" rel="stylesheet" type="text/css" > --}}
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assetsnew/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assetsnew/bower_components/dropzone/dist/dropzone.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assetsnew/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assetsnew/bower_components/fullcalendar/dist/fullcalendar.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assetsnew/bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assetsnew/bower_components/slick-carousel/slick/slick.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assetsnew/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/css/bootstrap-select.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assetsnew/plugins/fontawesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assetsnew/plugins/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assetsnew/plugins/ajax/css/ajax-bootstrap-select.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assetsnew/css/main_e920cc5c.css') }}" rel="stylesheet" />
    

</head>
@php
    $user = Auth::user();
@endphp
<body class="{{ (Request::path() == 'login' || Request::path() == 'register')?'auth-wrapper':'menu-position-top full-screen with-content-panel' }}" >

    @php
        $array = array('login', 'register')
    @endphp
    @if(!in_array(Request::path(), $array))
        <div class="all-wrapper with-side-panel solid-bg-all">
            <div class="layout-w">
                <div class="menu-w color-scheme-light color-style-default menu-position-top menu-layout-compact sub-menu-style-inside sub-menu-color-bright selected-menu-color-bright menu-activated-on-hover">
                    <a href="/"  class="display-logo">
                        <div class="logo-wrap" style="width: 110px; float: left; margin-left: 8px">
                            <img src="{{ URL::asset('assets/img/login-page.png') }}" alt="" style="height: 40px" class="logo-img" />
                        </div>
                    </a>
                    <a href="/"  class="display-logo-none">
                        <div class="logo-wrap" style="width: 110px; float: left; margin-left: 8px">
                            <img src="{{ URL::asset('assetsnew/img/bp-logo.png') }}" alt="" style="height: 40px" class="logo-img" />
                        </div>
                    </a>
                    <ul class="main-menu">
                        <li class="has-sub-menu">
                            <a href="javascript:void(0)">
                                <span>{{ trans('app.rating') }}</span>
                            </a>
                            <div class="sub-menu-w">
                                <div class="sub-menu-header">{{ trans('app.rating') }}</div>
                                <div class="sub-menu-icon"><i class="os-icon os-icon-layers"></i></div>
                                <div class="sub-menu-i">
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="{!! url('/report/final/table')!!}">{{ trans('app.monthly all report') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/report/cash/table')!!}">{{ trans('app.cash') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/report/business/table')!!}">{{ trans('app.business') }}</a>
                                        </li>                            
                                        <li>
                                            <a href="{!! url('/report/inspeksiya/table')!!}">{{ trans('app.inspeksiya') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/report/currency/table')!!}">{{ trans('app.currency') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/report/ijro/table')!!}">{{ trans('app.ijro') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/report/mainbanks/table')!!}">{{ trans('app.mainbank rating table') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <li class="has-sub-menu">
                                <a href="javascript:void(0)">
                                    <span>{{ trans('app.portfolio') }}</span>
                                </a>
                                <div class="sub-menu-w">
                                    <div class="sub-menu-header">{{ trans('app.portfolio') }}</div>
                                    <div class="sub-menu-icon"><i class="os-icon os-icon-layers"></i></div>
                                    <div class="sub-menu-i">
                                        <ul class="sub-menu">
                                            <li>
                                                <a href="{!! url('/report/loan/table')!!}">{{ trans('app.loan report') }}</a>
                                            </li>
                                            <li>
                                                <a href="{!! url('/report/loan/table?status=problem')!!}">{{ trans('app.problem loan report') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </li>
                        <li class="has-sub-menu">
                            <a href="javascript:void(0)">
                                <span>{{ trans('app.analysis') }}</span>
                            </a>
                            <div class="sub-menu-w">
                                <div class="sub-menu-header">{{ trans('app.analysis') }}</div>
                                <div class="sub-menu-icon">
                                    <i class="os-icon os-icon-package"></i>
                                </div>
                                <div class="sub-menu-i">
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="{!! url('/charts/rating-chart')!!}">{{ trans('app.rating chart') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/charts/pie-chart')!!}">{{ trans('app.pie chart') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/charts/line-chart')!!}">{{ trans('app.linear chart') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/charts/loan-pie-credit')!!}">{{ trans('app.loan pie credit') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/charts/loan-pie-problem')!!}">{{ trans('app.loan pie problem') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/charts/loan-line-portfolio')!!}">{{ trans('app.loan line portfolio') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/charts/loan-line-problem')!!}">{{ trans('app.loan line problem') }}</a>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="has-sub-menu">
                            <a href="javascript:void(0)">
                                <span>{{ trans('app.first settings') }}</span>
                            </a>
                            <div class="sub-menu-w">
                                <div class="sub-menu-header">{{ trans('app.first settings') }}</div>
                                <div class="sub-menu-icon"><i class="os-icon os-icon-file-text"></i></div>
                                <div class="sub-menu-i">
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="{!! url('/bank/list?type=main')!!}">{{ trans('app.list mainbank') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/bank/list?type=fill')!!}">{{ trans('app.list fillial') }}</a>
                                        </li>
                                        <li>
                                        <a href="{!! url('/settings/department/list?key=cat')!!}">{{ trans('app.department') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/settings/department/list?key=sub')!!}">{{ trans('app.sub department') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/settings/account-sheet/list')!!}">{{ trans('app.account-sheet') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/settings/activity-code/list')!!}">{{ trans('app.activity code') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/settings/loan-goal/list')!!}">{{ trans('app.loan goal list page') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/user/list')!!}">{{ trans('app.employee list') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/region/list')!!}">{{ trans('app.regions') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/city/list')!!}">{{ trans('app.cities') }}</a>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="has-sub-menu">
                            <a href="javascript:void(0)">
                                <span>{{ trans('app.Import excel') }}</span>
                            </a>
                            <div class="sub-menu-w">
                                <div class="sub-menu-header">{{ trans('app.Import excel') }}</div>
                                <div class="sub-menu-icon">
                                    <i class="os-icon os-icon-package"></i>
                                </div>
                                <div class="sub-menu-i">
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="{!! url('/excel/balance/balance')!!}">{{ trans('app.balance') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/excel/sxema/sxema')!!}">{{ trans('app.sxema') }}</a>
                                        </li>
                                        <li>
                                            <a href="{!! url('/excel/credit/credit')!!}">{{ trans('app.credit box link') }}</a>
                                        </li>
                                        <li class="has-sub-sub-menu">
                                            <a class="sub-sub-menu" href="javascript:void(0)">{{ trans('app.cash') }}</a>
                                            <div class="sub-menu-w" style="display: none">
                                                <div class="sub-menu-header">{{ trans('app.cash') }}</div>
                                                <div class="sub-menu-icon">
                                                    <i class="os-icon os-icon-package"></i>
                                                </div>
                                                <div class="sub-menu-i">
                                                    <ul class="sub-menu">
                                                        {{-- <li>
                                                            <a href="{!! url('/excel/cash/tushum')!!}">{{ trans('app.cash tushum') }}</a>
                                                        </li> --}}
                                                        <li>
                                                            <a href="{!! url('/excel/cash/hisobot')!!}">{{ trans('app.cash monthly report') }}</a>
                                                        </li>
                                                        {{-- <li>
                                                            <a href="{!! url('/excel/cash/qaytish')!!}">{{ trans('app.cash qaytish') }}</a>
                                                        </li> --}}
                                                        <li>
                                                            <a href="{!! url('/excel/cash/execution')!!}">{{ trans('app.cash ijro') }}</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="has-sub-sub-menu">
                                            <a class="sub-sub-menu" href="{!! url('/excel/inspeksiya/others')!!}">{{ trans('app.inspeksiya') }}</a>
                                            <div class="sub-menu-w"  style="display: none">
                                                <div class="sub-menu-header">
                                                 {{ trans('app.inspeksiya') }}
                                                </div>
                                                <div class="sub-menu-icon">
                                                    <i class="os-icon os-icon-package"></i>
                                                </div>
                                                {{-- <div class="sub-menu-i">
                                                    <ul class="sub-menu">
                                                        <li>
                                                            <a href="{!! url('/excel/inspeksiya/others')!!}">{{ trans('app.i others') }}</a>
                                                        </li>
                                                    </ul>
                                                </div> --}}
                                            </div>
                                        </li>
                                        <li class="has-sub-sub-menu">
                                            <a class="sub-sub-menu" href="javascript:void(0)">{{ trans('app.business') }}</a>
                                            <div class="sub-menu-w"  style="display: none">
                                                <div class="sub-menu-header">{{ trans('app.business') }}</div>
                                                <div class="sub-menu-icon">
                                                    <i class="os-icon os-icon-package"></i>
                                                </div>
                                                <div class="sub-menu-i">
                                                    <ul class="sub-menu">
                                                        <li>
                                                            <a href="{!! url('/excel/business/home')!!}">{{ trans('app.b home') }}</a>
                                                        </li>
                                                        <li>
                                                            <a href="{!! url('/excel/business/kontur')!!}">{{ trans('app.b kontur') }}</a>
                                                        </li>
                                                        <li>
                                                            <a href="{!! url('/excel/business/guarantee')!!}">{{ trans('app.b guarantee') }}</a>
                                                        </li>
                                                        <li>
                                                            <a href="{!! url('/excel/business/family')!!}">{{ trans('app.b family') }}</a>
                                                        </li>
                                                        <li>
                                                            <a href="{!! url('/excel/business/past')!!}">{{ trans('app.b past') }}</a>
                                                        </li>
                                                        <li>
                                                            <a href="{!! url('/excel/business/monthly-report')!!}">{{ trans('app.b monthly report') }}</a>
                                                        </li>
                                                        <li>
                                                            <a href="{!! url('/excel/business/execution')!!}">{{ trans('app.b execution') }}</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="has-sub-sub-menu">
                                            <a class="sub-sub-menu" href="javascript:void(0)">{{ trans('app.currency') }}</a>
                                            <div class="sub-menu-w"  style="display: none">
                                                <div class="sub-menu-header">{{ trans('app.currency') }}</div>
                                                <div class="sub-menu-icon">
                                                    <i class="os-icon os-icon-package"></i>
                                                </div>
                                                <div class="sub-menu-i">
                                                    <ul class="sub-menu">
                                                        <li>
                                                            <a href="{!! url('/excel/currency/check-vash')!!}">{{ trans('app.c check') }}</a>
                                                        </li>
                                                        <li>
                                                            <a href="{!! url('/excel/currency/monthly-report')!!}">{{ trans('app.c monthly report') }}</a>
                                                        </li>
                                                        <li>
                                                            <a href="{!! url('/excel/currency/execution')!!}">{{ trans('app.c execution') }}</a>
                                                        </li>
                                                        <li>
                                                            <a href="{!! url('/excel/currency/phone')!!}">{{ trans('app.c phone') }}</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <a href="{!! url('/excel/ijro/ijro')!!}">{{ trans('app.ijro') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        
                    </ul>
                    {{-- <a href="/" class="display-logo" style="margin-right: 7%; margin-left: auto;">
                        <div class="app-logo">
                            <div class="logo-wrap">
                                <img src="{{ URL::asset('assets/img/logo.svg') }}" alt="" style="height: 40px;" class="logo-img" />
                            </div>
                        </div>
                    </a> --}}
                    <a href="/" class="display-logo-none logo-2">
                        <div class="app-logo">
                            <div class="logo-wrap ">
                                <img src="{{ URL::asset('assetsnew/img/mb-logo.png') }}" alt="" style="height: 40px;" class="logo-img" />
                            </div>
                        </div>
                    </a>
                    <div class="top-menu-controls" style="margin-left:0">
                        <div class="top-icon top-settings os-dropdown-trigger os-dropdown-position-left">
                            @php
                                $user = Auth::user();
                            @endphp
                            @if($user->language == 'uz')
                                <span class="square-box blue">{{ trans('app.Uzbek short') }}</span>
                            @elseif($user->language == 'уз')
                                <span class="square-box blue">{{ trans('app.Uzbek kril short') }}</span>
                            @else
                                <span class="square-box blue">{{ trans('app.Russian short') }}</span>
                            @endif
                            <div class="os-dropdown">
                                <div class="icon-w"><i class="os-icon os-icon-ui-46"></i></div>
                                <ul>
                                    <li>
                                        <a class="lang-img" href="{!! url('/settings/lang/change?lang=ru')!!}">
                                            <span class="square-box">{{ trans('app.Russian short') }}</span>
                                            <span>{{ trans('app.Russian') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="lang-img" href="{!! url('/settings/lang/change?lang=uz')!!}">
                                            <span class="square-box">{{ trans('app.Uzbek short') }}</span>
                                            <span>{{ trans('app.Uzbek') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="lang-img" href="{!! url('/settings/lang/change?lang=уз')!!}">
                                            <span class="square-box">{{ trans('app.Uzbek kril short') }}</span>
                                            <span>{{ trans('app.Uzbek kril') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="logged-user-w">
                            <div class="logged-user-i">
                                <div class="avatar-w">
                                    @php
                                        $user = Auth::user();
                                    @endphp
                                    @if(!empty($user->photo))
                                        <img src="{{ URL::asset('users/'.$user->photo) }}" alt="" style="width: 40px; height: 40px" class="rounded-500 mr-1" /> 
                                    @else
                                        <img src="{{ URL::asset('assets/content/user-400-1.jpg') }}" alt="" width="40" height="40" class="rounded-500 mr-1" /> 
                                    @endif
                                </div>
                                <div class="logged-user-menu color-style-bright">
                                    <div class="logged-user-avatar-info">
                                        <div class="avatar-w">
                                            @if(!empty($user->photo))
                                                <img src="{{ URL::asset('users/'.$user->photo) }}" alt="" style="width: 40px; height: 40px" class="rounded-500 mr-1" /> 
                                            @else
                                                <img src="{{ URL::asset('assets/content/user-400-1.jpg') }}" alt="" width="40" height="40" class="rounded-500 mr-1" /> 
                                            @endif
                                        </div>
                                        <div class="logged-user-info-w">
                                            <div class="logged-user-name">{{ $user->firstname }} </div>
                                            <div class="logged-user-role">Administrator </div>
                                        </div>
                                    </div>
                                    <div class="bg-icon"><i class="os-icon os-icon-wallet-loaded"></i></div>
                                    <ul>
                                       <!--  <li><a href="apps_email.html"><i class="os-icon os-icon-mail-01"></i><span>Incoming Mail </span></a></li>
                                        <li><a href="users_profile_big.html"><i class="os-icon os-icon-user-male-circle2"></i><span>Profile Details </span></a></li>
                                        <li><a href="users_profile_small.html"><i class="os-icon os-icon-coins-4"></i><span>Billing Details </span></a></li>
                                        <li><a href="#"><i class="os-icon os-icon-others-43"></i><span>Notifications </span></a></li> -->
                                        <li><a href="users_profile_big.html"><i class="os-icon os-icon-user-male-circle2"></i><span>Profile</span></a></li>
                                        <li>
                                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();" class="align-items-center"><i class="os-icon os-icon-signs-11"></i><span>{{ trans('app.log out') }}</span> </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class=" custom-developper top-icon top-settings os-dropdown-trigger os-dropdown-position-left" style="padding: 0;">
                            
                            @if($user->role_id == 'admin')
                                <div class="custom-animation-by-developer">
                                    <div class="icon-w">
                                        <i class="os-icon os-icon-ui-46"></i>
                                    </div>
                                </div>
                            @else
                                <div class="custom-animation-by-developer">
                                    <div class="icon-w">
                                        <i class="os-icon os-icon-ui-46"></i>
                                    </div>
                                </div>
                            @endif
                            <div class="os-dropdown">
                                <div class="icon-w"><i class="os-icon os-icon-ui-46"></i></div>
                                <ul>
                                    {{-- <li>
                                        <a href="{!! url('/settings/department/list?key=cat')!!}"><span>{{ trans('app.department') }}</span></a>
                                    </li> --}}
                                    {{-- <li>
                                        <a href="{!! url('/settings/department/list?key=sub')!!}"><span>{{ trans('app.sub department') }}</span></a>
                                    </li> --}}
                                    <li>
                                        <a href="{!! url('/settings/weight/list')!!}"><span>{{ trans('app.Weight distribution') }}</span></a>
                                    </li>
                                    {{-- <li>
                                        <a href="{!! url('/settings/account-sheet/list')!!}"><span>{{ trans('app.account-sheet') }}</span></a>
                                    </li> --}}
                                    {{-- <li>
                                        <a href="{!! url('/settings/activity-code/list')!!}"><span>{{ trans('app.activity code') }}</span></a>
                                    </li> --}}
                                    <li>
                                        <a href="{!! url('/settings/role/list')!!}"><span>{{ trans('app.role management') }}</span></a>
                                    </li>
                                </ul>
                            </div> 
                        </div>
                    </div>
                     <div class="content-panel-toggler"><i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar </span></div>
                </div>
            
    @endif


        @yield('content')



    @if(!in_array(Request::path(), $array))

            </div>
        </div>
    @endif



    <script src="{{ URL::asset('assets/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery-migrate-1.4.1.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery.barrating.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/Chart.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/morris.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/echarts.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/echarts-gl.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ URL::asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.uz-cyrl.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.uz-latn.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/ajax/js/ajax-bootstrap-select.min.js') }}"></script>

    <script src="{{ URL::asset('assetsnew/bower_components/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/moment/moment.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/jquery-bar-rating/dist/jquery.barrating.min.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/bootstrap-validator/dist/validator.min.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/ion.rangeSlider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/editable-table/mindmup-editabletable.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/fullcalendar/dist/fullcalendar.min.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/tether/dist/js/tether.min.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/slick-carousel/slick/slick.min.js') }}"></script>
    
    <script src="{{ URL::asset('assetsnew/bower_components/bootstrap/js/dist/util.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/bootstrap/js/dist/alert.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/bootstrap/js/dist/button.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/bootstrap/js/dist/carousel.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/bootstrap/js/dist/collapse.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/bootstrap/js/dist/dropdown.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/bootstrap/js/dist/modal.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/bootstrap/js/dist/tab.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/bootstrap/js/dist/tooltip.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/bower_components/bootstrap/js/dist/popover.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/js/demo_customizer_e920cc5c.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assetsnew/js/main_e920cc5c.js') }}"></script>
    <script type="text/javascript">
        $('input.datepicker').attr('readonly', true);
        $('input.datepicker').datepicker({
            format:'yyyy-mm',
            startView: 'months',
            minViewMode: 'months',
            autoclose:1,
            startView:'1',
            language: 'uz-cyrl',
            endDate: new Date()
        });
        $('.has-sub-sub-menu').on('mouseenter', function(){
            var show = $(this).children('.sub-menu-w').css('display', 'block');
        }).on('mouseleave', function(){
            var hide = $(this).children('.sub-menu-w').css('display', 'none');
        })
        function selectpicker() {
            var select = $('.selectpicker');

            if (select.length) {
            select.each(function () {
                $(this).selectpicker({
                style: '',
                styleBase: 'form-control',
                tickIcon: 'icofont-check-alt'
                });
            });
            }
        }
        selectpicker();


        jQuery(window).on('load',function(){
            jQuery(".datepicker").fadeIn(500);
        });
    </script>
    @php
        $url_library = array('bank/list', 'user/list', 'region/list', 'city/list', 'settings/account-sheet/list', 'settings/role/list', 'settings/department/list');
    @endphp
    @if(!in_array(Request::path(), $url_library))
        <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    @endif
</body>
</html>
