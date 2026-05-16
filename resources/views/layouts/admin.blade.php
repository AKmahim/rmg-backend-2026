<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Event Name - " name="description" />
    <meta content="Event Name" name="keywords" />
    <meta content="Event Name" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('admin') }}/assets/images/logo-ddiexpo.png">

    <!-- App css -->
    <link href="{{ asset('admin') }}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin') }}/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    @yield('style')

</head>

<body>

    <!-- Navigation Bar-->
    <header id="topnav">


        <!-- Topbar Start -->
        <div class="navbar-custom">
            <div class="container-fluid">
                <ul class="list-unstyled topnav-menu float-right mb-0">

                    <li class="dropdown notification-list">
                        <!-- Mobile menu toggle-->
                        <a class="navbar-toggle nav-link">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li>

                    <li class="d-none d-sm-block">
                        <form class="app-search">
                            <div class="app-search-box">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search...">
                                    <div class="input-group-append">
                                        <button class="btn" type="submit">
                                            <i class="fe-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </li>



                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle  waves-effect waves-light" data-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="fe-bell noti-icon"></i>
                            <span class="badge badge-danger rounded-circle noti-icon-badge">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-lg">

                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5 class="m-0">
                                    <span class="float-right">
                                        <a href="" class="text-dark">
                                            <small>Clear All</small>
                                        </a>
                                    </span>Notification
                                </h5>
                            </div>

                            <div class="slimscroll noti-scroll">

                                <!-- item-->
                                {{-- <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-success"><i class="mdi mdi-comment-account-outline"></i>
                                    </div>
                                    <p class="notify-details">Caleb Flakelar commented on Admin<small
                                            class="text-muted">1 min ago</small></p>
                                </a> --}}



                            </div>

                            <!-- All-->
                            <a href="javascript:void(0);"
                                class="dropdown-item text-center text-primary notify-item notify-all">
                                View all
                                <i class="fi-arrow-right"></i>
                            </a>

                        </div>
                    </li>

                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                            aria-expanded="false">
                            <img src="{{ asset('admin') }}/assets/images/logo-ddiexpo.png" alt="user-image"
                                class="rounded-circle">
                            <span class="ml-1">{{ auth()->user()->name }} <i class="mdi mdi-chevron-down"></i> </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-user"></i>
                                <span>Profile</span>
                            </a>

                            <div class="dropdown-divider"></div>

                            <!-- item-->
                            <form action="{{ route('logout') }}" method="post" class="d-inline">
                                @csrf
                                <button type="submit"
                                    class="dropdown-item notify-item border-0 bg-transparent text-left w-100">
                                    <i class="fe-log-out"></i> Logout
                                </button>
                            </form>
                        </div>
                    </li>

                    <li class="dropdown notification-list">
                        <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect">
                            <i class="fe-settings noti-icon"></i>
                        </a>
                    </li>

                </ul>

                <!-- LOGO -->
                <div class="logo-box">
                    <a href="{{ url('/dashboard') }}" class="logo text-center">
                        <span class="logo-lg">
                            {{-- <img src="{{ asset('admin') }}/assets/images/logo-light.png" alt="" height="16"> --}}
                            <span class="logo-lg-text-light">Event Name</span>
                        </span>
                        <span class="logo-sm">
                            <span class="logo-sm-text-dark">Event Name</span>
                            {{-- <img src="{{ asset('admin') }}/assets/images/logo-sm.png" alt="" height="24"> --}}
                        </span>
                    </a>
                </div>

            </div> <!-- end container-fluid-->
        </div>
        <!-- end Topbar -->

        <div class="topbar-menu">
            <div class="container-fluid">
                <div id="navigation">
                    <!-- Navigation Menu-->
                    <ul class="navigation-menu">

                        <li class="has-submenu">
                            <a href="{{ url('/dashboard') }}"><i class="fe-airplay"></i>Dashboard</a>
                        </li>

                        {{-- <li class="has-submenu">
                            <a href="#"><i class="ri-video-add-fill"></i>Employee Management</a>
                            <ul class="submenu">
                                <li><a href="{{ route('employees.index') }}">Employee List</a></li>
                                <li><a href="{{ route('employees.create') }}">Employee Create</a></li>
                            </ul>
                        </li> --}}

                        <li class="has-submenu">
                            <a href="{{ route('site-view.index') }}"><i class="ri-video-add-fill"></i>Site View
                                Statistics</a>
                        </li>

                       <li class="has-submenu">
                            <a href="{{ route('contents.index') }}">Content Management</a>
                            
                        </li>

                        





                    </ul>
                    <!-- End navigation menu -->

                    <div class="clearfix"></div>
                </div>
                <!-- end #navigation -->
            </div>
            <!-- end container -->
        </div>
        <!-- end navbar-custom -->

    </header>
    <!-- End Navigation Bar-->

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="wrapper">
        <div class="container-fluid">

            {{-- <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Abstack</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                                <li class="breadcrumb-item active">Starter Page</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Starter Page</h4>
                    </div>
                </div>
            </div>
            <!-- end page title --> --}}

            {{-- --- site main content --- --}}

            @yield('content')

            {{-- --- end site main content --- --}}
            {{-- --- site main content --- --}}



        </div> <!-- end container -->
    </div>
    <!-- end wrapper -->

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->


    <!-- Footer Start -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 text-center">
                    2025 - 2030 &copy; Event Name by <a href="https://xri.com.bd" class="text-muted">XR
                        Interactive</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->

    <!-- Right Sidebar -->
    <div class="right-bar">
        <div class="rightbar-title">
            <a href="javascript:void(0);" class="right-bar-toggle float-right">
                <i class="mdi mdi-close"></i>
            </a>
            <h5 class="m-0 text-white">Settings</h5>
        </div>
        <div class="slimscroll-menu">
            <hr class="mt-0">
            <h5 class="pl-3">Basic Settings</h5>
            <hr class="mb-0" />
            <form action="{{ route('logout') }}" method="post" class="d-inline">
                @csrf
                <button type="submit" class="dropdown-item notify-item border-0 bg-transparent text-left w-100">
                    <i class="fe-log-out"></i> Logout
                </button>
            </form>




            <!-- Messages -->
            <hr class="mt-0" />
            <h5 class="pl-3 pr-3">Messages <span class="float-right badge badge-pill badge-danger">0</span></h5>
            <hr class="mb-0" />
            <div class="p-3">
                {{-- <div class="inbox-widget">
                    <div class="inbox-item">
                        <div class="inbox-item-img"><img src="{{ asset('admin') }}/assets/images/users/avatar-1.jpg"
                                class="rounded-circle" alt=""></div>
                        <p class="inbox-item-author"><a href="javascript: void(0);">Chadengle</a></p>
                        <p class="inbox-item-text">Hey! there I'm available...</p>
                        <p class="inbox-item-date">13:40 PM</p>
                    </div> --}}

            </div> <!-- end inbox-widget -->
        </div> <!-- end .p-3-->

    </div> <!-- end slimscroll-menu-->
    </div>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>


    <!-- Vendor js -->
    <script src="{{ asset('admin') }}/assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="{{ asset('admin') }}/assets/js/app.min.js"></script>

    @yield('this-page-js')

</body>

</html>
