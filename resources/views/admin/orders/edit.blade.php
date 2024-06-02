@extends('admin.master')
@section('title', 'Thông tin đơn hàng')
@section('css')
    <style>
        .table-data img {
            height: 50px;
        }

        .red {
            color: #f46a6a;
        }

        .title {
            font-weight: 600;
        }
    </style>
@endsection

@section('main-content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Thông tin đơn hàng</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <form action="" class="card-body" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $order->id }}">

                                <div class="mb-3 row">
                                    <label for="example-search-input" class="col-md-2 col-form-label title">Trạng
                                        thái:</label>
                                    <div class="col-md-10 d-flex align-items-center">
                                        <div class="row row-cols-lg-auto g-3">
                                            @php
                                                $status = [
                                                    'Mới tiếp nhận',
                                                    'Đang xử lý',
                                                    'Chuyển qua kho đóng gói',
                                                    'Đang giao hàng',
                                                    'Hoàn tất',
                                                ];
                                            @endphp
                                            @foreach ($status as $item)
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="status"
                                                            value="{{ $loop->index }}" id="formCheck{{ $loop->index }}"
                                                            @checked($order->status == $loop->index)>
                                                        <label class="form-check-label" for="formCheck1">
                                                            {{ $item }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light w-xs">
                                            Đồng ý
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div> <!-- end col -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table align-middle table-nowrap mb-0 table-hover table-data">
                                        <thead class="table-light">
                                            <tr>
                                                {{-- <th style="width: 20px;">
                                                    <div class="form-check font-size-16 align-middle">
                                                        <input class="form-check-input" type="radio" id="select-all">
                                                    </div>
                                                </th> --}}

                                                <th class="align-middle">STT</th>
                                                <th class="align-middle">Mã sản phẩm</th>
                                                <th class="align-middle">Hình ảnh</th>
                                                <th class="align-middle">Tên sản phẩm</th>
                                                <th class="align-middle">Đơn giá</th>
                                                <th class="align-middle">Số lượng</th>
                                                <th class="align-middle">Tổng tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($order_products as $order_product)
                                                <tr>
                                                    {{-- <td>
                                                        <div class="form-check font-size-16">
                                                            <input class="form-check-input radio" type="radio"
                                                                id="{{ $order_product['id'] }}"
                                                                value="{{ $order_product['id'] }}">
                                                        </div>
                                                    </td> --}}

                                                    <td>
                                                        {{ $i++ }}
                                                    </td>
                                                    <td>
                                                        {{ $order_product['id'] }}
                                                    </td>
                                                    <td>
                                                        <img src="{{ $order_product['image_url'] }}"
                                                            alt="Hình ảnh sản phẩm">
                                                    </td>
                                                    <td>
                                                        {{ $order_product['name'] }}
                                                    </td>
                                                    <td>
                                                        {{ $order_product['price'] }} VNĐ
                                                    </td>
                                                    <td>
                                                        {{ $order_product['quantity'] }}
                                                    </td>
                                                    <td>
                                                        {{ $order_product['total_price'] }} VNĐ
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="6">
                                                    <p class="text-end mb-0"><strong>Giá gốc:</strong></p>
                                                </td>
                                                <td><strong class="red"> {{ $order->total_price }} VNĐ</strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">
                                                    <p class="text-end mb-0"><strong>Số tiền giảm:</strong></p>
                                                </td>
                                                <td><strong class="red"> {{ $order->price_off }} VNĐ</strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">
                                                    <p class="text-end mb-0"><strong>Tổng:</strong></p>
                                                </td>
                                                <td><strong class="red"> {{ $order->total_price - $order->price_off }}
                                                        VNĐ</strong></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <!-- end table-responsive -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label title">Mã khách
                                        hàng:</label>
                                    <div class="col-md-10 d-flex align-items-center">
                                        <p class="mb-0">{{ $user->id }}</p>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label title">Tên khách
                                        hàng:</label>
                                    <div class="col-md-10 d-flex align-items-center">
                                        <p class="mb-0">{{ $user->fullname }}</p>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label title">Số điện
                                        thoại:</label>
                                    <div class="col-md-10 d-flex align-items-center">
                                        <p class="mb-0">{{ $user->phone }}</p>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label title">Email:</label>
                                    <div class="col-md-10 d-flex align-items-center">
                                        <p class="mb-0">{{ $user->email }}</p>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label title">Địa chỉ giao
                                        hàng:</label>
                                    <div class="col-md-10 d-flex align-items-center">
                                        <p class="mb-0">{{ $order->delivery_address }}</p>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label title">Thời gian đặt
                                        hàng:</label>
                                    <div class="col-md-10 d-flex align-items-center">
                                        <p class="mb-0">{{ date('h:m d-m-Y', strtotime($order['created_at'])) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- end col -->
            </div>

        </div>
    @endsection
