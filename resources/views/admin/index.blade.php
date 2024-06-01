@extends('admin.master')
@section('title', 'Dashboard')

@section('script')
    <!-- apexcharts -->
    <script src="{{ asset('assets') }}/libs/apexcharts/apexcharts.min.js"></script>

    <script>
        var options = {
            series: [{
                    name: 'Đơn hàng',
                    data: {!! json_encode($thong_ke_order) !!}
                },
                // {
                //     name: 'Người dùng',
                //     data: [76, 85, 101, 98, 87, 105, 91, 114, 94, 10, 11, 12]
                // },
                {
                    name: 'Sản phẩm',
                    data: {!! json_encode($thong_ke_product) !!}
                }
            ],
            chart: {
                type: 'bar',
                height: 350,
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['T.1', 'T.2', 'T.3', 'T.4', 'T.5', 'T.6', 'T.7', 'T.8', 'T.9', 'T.10', 'T.11', 'T.12'],
            },
            // yaxis: {
            //     title: {
            //         text: '$ (thousands)'
            //     }
            // },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val
                    }
                }
            },

        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
@endsection

@section('main-content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-xl-4">
                        <div class="card overflow-hidden">
                            <div class="bg-primary bg-soft">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-3">
                                            <h5 class="text-primary">Chào mừng trở lại !</h5>
                                            <p>Fashion</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="{{ 'assets' }}/images/profile-img.png" alt=""
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <img src="{{ 'assets' }}/images/users/avatar-1.jpg" alt=""
                                                class="img-thumbnail rounded-circle">
                                        </div>
                                        <h5 class="font-size-15 text-truncate">{{ Auth::user()->fullname }}</h5>
                                        <p class="text-muted mb-0 text-truncate">Quản trị viên</p>
                                    </div>

                                    <div class="col-sm-8">
                                        <div class="pt-4">

                                            <div class="row">
                                                <div class="col-6">
                                                    <h5 class="font-size-15">125</h5>
                                                    <p class="text-muted mb-0">Projects</p>
                                                </div>
                                                <div class="col-6">
                                                    <h5 class="font-size-15">$1245</h5>
                                                    <p class="text-muted mb-0">Doanh thu</p>
                                                </div>
                                            </div>
                                            {{-- <div class="mt-4">
                                                <a href="javascript: void(0);"
                                                    class="btn btn-primary waves-effect waves-light btn-sm">View Profile <i
                                                        class="mdi mdi-arrow-right ms-1"></i></a>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-8">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card mini-stats-wid">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-muted fw-medium">Đơn hàng</p>
                                                <h4 class="mb-0">{{ $order_count }}</h4>
                                            </div>

                                            <div class="flex-shrink-0 align-self-center">
                                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                                    <span class="avatar-title">
                                                        <i class="fas fa-cart-plus font-size-24"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mini-stats-wid">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-muted fw-medium">Người dùng</p>
                                                <h4 class="mb-0">{{ $user_count - 1 }}</h4>
                                            </div>

                                            <div class="flex-shrink-0 align-self-center ">
                                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                    <span class="avatar-title rounded-circle bg-primary">
                                                        <i class="fas fa-user font-size-24"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mini-stats-wid">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-muted fw-medium">Sản phẩm</p>
                                                <h4 class="mb-0">{{ $product_count }}</h4>
                                            </div>

                                            <div class="flex-shrink-0 align-self-center">
                                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                    <span class="avatar-title rounded-circle bg-primary">
                                                        <i class="fas fa-box font-size-24"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->


                    </div>
                </div>
                <!-- end row -->

                <div class="card">
                    <div class="card-body">
                        <div class="d-sm-flex flex-wrap">
                            <h4 class="card-title mb-4">Thống kê</h4>
                            <div class="ms-auto">
                                {{-- <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Tuần</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Tháng</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#">Năm</a>
                                    </li>
                                </ul> --}}

                            </div>
                        </div>

                        {{-- <div id="stacked-column-chart" class="apex-charts" dir="ltr"></div> --}}
                        <div id="chart" class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
    </div>
@endsection
