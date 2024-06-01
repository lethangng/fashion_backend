<!doctype html>
<html lang="en">
<!-- Mirrored from themesbrand.com/skote-cakephp/layouts/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 03 May 2024 09:17:12 GMT -->

<head>
    <meta charset="utf-8" />

    <title>@yield('title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />

    @yield('css')

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.ico">
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets') }}/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets') }}/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets') }}/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <!-- Responsive Table css -->
    <link href="{{ asset('assets') }}/libs/admin-resources/rwd-table/rwd-table.min.css" rel="stylesheet"
        type="text/css" />
    {{-- Style css --}}
    <link href="{{ asset('assets') }}/css/style.css" id="app-style" rel="stylesheet" type="text/css" />


</head>

<body data-sidebar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('admin.layouts.header')

        <!-- ========== Left Sidebar Start ========== -->
        @include('admin.layouts.menu')
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        @yield('main-content')
        <!-- end main content-->

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © Fashion.
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end d-none d-sm-block">
                            Design & Develop by Le Ngoc Thang
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </div>
    <!-- END layout-wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <script src="{{ asset('assets') }}/libs/jquery/jquery.min.js"></script>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/super-build/ckeditor.js"></script>
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script> --}}


    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets') }}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets') }}/libs/metismenu/metisMenu.min.js"></script>
    <script src="{{ asset('assets') }}/libs/simplebar/simplebar.min.js"></script>
    <script src="{{ asset('assets') }}/libs/node-waves/waves.min.js"></script>

    <!-- Responsive Table js -->
    <script src="{{ asset('assets') }}/libs/admin-resources/rwd-table/rwd-table.min.js"></script>

    <!-- Init js -->
    <script src="{{ asset('assets') }}/js/pages/table-responsive.init.js"></script>

    <!-- App js -->
    <script src="{{ asset('assets') }}/js/app.js"></script>


    {{-- <script src="{{ asset('assets') }}/js/style.js"></script> --}}

    @yield('script')
</body>


<!-- Mirrored from themesbrand.com/skote-cakephp/layouts/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 03 May 2024 09:17:13 GMT -->

</html>
