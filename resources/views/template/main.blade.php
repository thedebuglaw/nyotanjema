@auth
<?php 
    $user = Auth::user();
    $name = ucwords($user->name);
    $image_path = $user->img_path ?? 'images/default-user.png'; // fallback if null
    $outlet = 'NEW NYOTA NJEMA DISPENSARY';
    \App::setLocale(session('locale', 'en'));
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>NEW NYOTA NJEMA DISPENSARY | @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap & AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/skins/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/bsutility.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    @yield('custom_style_sheets')

    <style>
        @yield('custom_styles')
        /* Spinner styles */
        .spinner { /* your existing spinner CSS */ }
    </style>
</head>
<body onload="startTime(); setdate()" class="hold-transition skin-blue sidebar-mini">

    <!-- Preloader -->
    <div id="preloader"></div>
    <div style="display:none" id="preloader1"></div>
    <div id="spinner" class="spinner" style="display:none">
        <div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div>
    </div>

    <div class="wrapper">
        <!-- Header -->
        <header class="main-header">
            <a href="/" class="logo">
                <span class="logo-mini">HMS</span>
                <span class="logo-lg">NEW NYOTA NJEMA DISPENSARY</span>
            </a>
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <i class="fas fa-sliders-h"></i>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="nav-item">
                            <p style="padding-top:1.3rem;font-weight:400;margin-right:1.5vw;color:ivory;font-size:1.7rem">
                                <span class="mr-3" id="today"></span><span id="time"></span>
                            </p>
                        </li>
                        <!-- Language Switch -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                {{ session('locale', 'en') == 'si' ? 'සිං' : 'EN' }}
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Select Language</li>
                                <li><a href="{{ route('lang', 'en') }}">English</a></li>
                                <li><a href="{{ route('lang', 'si') }}">සිංහල</a></li>
                            </ul>
                        </li>
                        <!-- User Menu -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{ asset($image_path) }}" class="user-image" alt="User Image">
                                <span class="hidden-xs">{{ $name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <img src="{{ asset($image_path) }}" class="img-circle" alt="User Image">
                                    <p>{{ $name }} <small>{{ ucfirst($user->user_type) }}</small></p>
                                </li>
                                <li class="user-body"><h5 class="text-center">{{ $outlet }}</h5></li>
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{{ route('profile') }}" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-default btn-flat">Sign Out</button>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- Sidebar -->
        <aside class="main-sidebar">
            <section class="sidebar">
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="{{ asset($image_path) }}" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>{{ $name }}</p>
                        <a href="#"><i class="fas fa-circle text-success"></i> Online</a>
                    </div>
                </div>

                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">Main Menu</li>

                    <li class="{{ Active::checkRoute('dash') }}"><a href="{{ route('dash') }}"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>

                    <!-- Patient Menu -->
                    @if($permissions['register_patient'])
                    <li class="treeview {{ Active::checkRoute(['patient','register_in_patient_view','searchPatient','searchData','discharge_inpatient','patientProfileIntro','patientProfile']) }}">
                        <a href="#"><i class="fas fa-user-injured"></i> <span>Patient</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                        <ul class="treeview-menu">
                            <li class="{{ Active::checkRoute('patient') }}"><a href="{{ route('patient') }}"><i class="fas fa-user-plus"></i> Register New</a></li>
                            <li class="{{ Active::checkRoute(['searchPatient','searchData']) }}"><a href="{{ route('searchPatient') }}"><i class="fas fa-search"></i> Search Patient</a></li>
                            <li class="{{ Active::checkRoute(['patientProfileIntro','patientProfile']) }}"><a href="{{ route('patientProfileIntro') }}"><i class="fas fa-id-card"></i> Patient Profile</a></li>
                            <li class="{{ Active::checkRoute('register_in_patient_view') }}"><a href="{{ route('register_in_patient_view') }}"><i class="fas fa-user-plus"></i> Register In Patient</a></li>
                            @if($permissions['view_reports'])
                            <li class="{{ Active::checkRoute('discharge_inpatient') }}"><a href="{{ route('discharge_inpatient') }}"><i class="fa fa-hospital-o"></i> Discharge In Patient</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if($permissions['clinic_report'])
                    <li class="{{ Active::checkRoute('create_channel_view') }}"><a href="{{ route('create_channel_view') }}"><i class="fas fa-folder-plus"></i> <span>Create Appointment</span></a></li>
                    @endif

                    @if($permissions['view_reports'])
                    <li class="{{ Active::checkRoute('check_patient_view') }}"><a href="{{ route('check_patient_view') }}"><i class="fas fa-procedures"></i> <span>Check Patient</span></a></li>
                    @endif

                    @if($permissions['issue_medicine'])
                    <li class="{{ Active::checkRoute('issueMedicineView') }}"><a href="{{ route('issueMedicineView') }}"><i class="fa fa-plus-square"></i> <span>Issue Medicine</span></a></li>
                    @endif

                    <!-- Attendance -->
                    <li class="treeview {{ Active::checkRoute(['attendmore','myattend']) }}">
                        <a href="#"><i class="fas fa-calendar-check"></i> <span>Attendance</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                        <ul class="treeview-menu">
                            <li class="{{ Active::checkRoute('myattend') }}"><a href="{{ route('myattend') }}"><i class="fas fa-calendar-day"></i> My Attendance</a></li>
                            @if($permissions['reset_user'])
                            <li class="{{ Active::checkRoute('attendmore') }}"><a href="{{ route('attendmore') }}"><i class="fas fa-plus-square"></i> More</a></li>
                            @endif
                        </ul>
                    </li>

                    <!-- Users Management -->
                    @if($permissions['register_user'])
                    <li class="treeview {{ Active::checkRoute(['newuser','regfinger','resetuser']) }}">
                        <a href="#"><i class="fas fa-users-cog"></i> <span>Users</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                        <ul class="treeview-menu">
                            <li class="{{ Active::checkRoute('newuser') }}"><a href="{{ route('newuser') }}"><i class="fa fa-user-plus"></i> New User</a></li>
                            <li class="{{ Active::checkRoute('regfinger') }}"><a href="{{ route('regfinger') }}"><i class="fa fa-fingerprint"></i> Register Fingerprint</a></li>
                            <li class="{{ Active::checkRoute('resetuser') }}"><a href="{{ route('resetuser') }}"><i class="fa fa-user-edit"></i> Reset User</a></li>
                        </ul>
                    </li>
                    @endif

                    <li class="{{ Active::checkRoute('profile') }}"><a href="{{ route('profile') }}"><i class="fas fa-user"></i> <span>Profile</span></a></li>

                    @if($permissions['register_patient'])
                    <li class="{{ Active::checkRoute('wards') }}"><a href="{{ route('wards') }}"><i class="fas fa-warehouse"></i> <span>Wards</span></a></li>
                    @endif

                    @if($permissions['manage_notices'])
                    <li class="{{ Active::checkRoute('createnoticeview') }}"><a href="{{ route('createnoticeview') }}"><i class="fas fa-envelope-open-text"></i> <span>Notices</span></a></li>
                    @endif

                    @if($permissions['view_statistics'])
                    <li class="{{ Active::checkRoute(['stats','stats_old']) }}"><a href="{{ route('stats') }}"><i class="fas fa-chart-line"></i> <span>Statistics</span></a></li>
                    @endif

                    <!-- Report Generation -->
                    @if($permissions['view_reports'] || $permissions['clinic_report'])
                    <li class="treeview {{ Active::checkRoute(['inPatientReport','inPatientReportData','clinic_reports','mob_clinic_report','mon_stat_report','out_p_report','attendance_report']) }}">
                        <a href="#"><i class="fas fa-sticky-note"></i> <span>Report Generation</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                        <ul class="treeview-menu">
                            @if($permissions['clinic_report'])
                            <li class="{{ Active::checkRoute('clinic_reports') }}"><a href="{{ route('clinic_reports') }}"><i class="fa fa-stethoscope"></i> Clinic Report</a></li>
                            <li class="{{ Active::checkRoute('mon_stat_report') }}"><a href="{{ route('mon_stat_report') }}"><i class="fa fa-sticky-note"></i> Monthly Statistic Report</a></li>
                            @endif
                            <li class="{{ Active::checkRoute(['inPatientReport','inPatientReportData']) }}"><a href="{{ route('inPatientReport') }}"><i class="fa fa-hospital-o"></i> In Patient Stats</a></li>
                            <li class="{{ Active::checkRoute('attendance_report') }}"><a href="{{ route('attendance_report') }}"><i class="fa fa-clipboard"></i> Attendance Report</a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </section>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <section class="content-header">
                <h1>@yield('content_title') <small>@yield('content_description')</small></h1>
                @yield('breadcrumbs')
            </section>
            <section class="content container-fluid">
                @yield('main_content')
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="pull-right hidden-xs">Version 1.0</div>
            <strong>Copyright &copy; {{ date('Y') }} <a href="#">Smart Hospital Systems</a>.</strong> All rights reserved.
        </footer>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('bower_components/fastclick/lib/fastclick.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>

    @yield('optional_scripts')

    <script>
        // Preloader & AJAX spinner
        $(document).ajaxSend(function() { $("#preloader1, #spinner").fadeIn(); });
        $(document).ajaxComplete(function() { $("#preloader1, #spinner").fadeOut(); });
        $("#preloader").fadeOut();

        function setdate() {
            const today = new Date();
            const dd = String(today.getDate()).padStart(2, '0');
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const yyyy = today.getFullYear();
            document.getElementById("today").innerHTML = dd + '-' + mm + '-' + yyyy;
        }

        function startTime() {
            const today = new Date();
            let h = today.getHours();
            const ampm = h >= 12 ? 'pm' : 'am';
            h = h % 12 || 12;
            let m = today.getMinutes();
            let s = today.getSeconds();
            m = m < 10 ? '0' + m : m;
            s = s < 10 ? '0' + s : s;
            document.getElementById('time').innerHTML = h + ":" + m + ":" + s + " " + ampm;
            setTimeout(startTime, 1000);
        }
    </script>
</body>
</html>
@endauth

@guest
    "you are not logged in";
@endguest
