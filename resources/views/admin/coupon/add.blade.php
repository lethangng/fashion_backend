@extends('admin.master')
@section('title', 'Thêm Mã giảm giá')

@section('css')
    <link href="{{ asset('assets') }}/libs/spectrum-colorpicker2/spectrum.min.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets') }}/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet"
        type="text/css">
    <link rel="stylesheet" href="{{ asset('assets') }}/libs/%40chenfengyuan/datepicker/datepicker.min.css">
    <link href="{{ asset('assets') }}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('script')
    <script src="{{ asset('assets') }}/libs/select2/js/select2.min.js"></script>
    <script src="{{ asset('assets') }}/libs/spectrum-colorpicker2/spectrum.min.js"></script>
    <script src="{{ asset('assets') }}/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('assets') }}/libs/%40chenfengyuan/datepicker/datepicker.min.js"></script>

    <!-- form advanced init -->
    <script src="{{ asset('assets') }}/libs/select2/js/select2.min.js"></script>
    <script src="{{ asset('assets') }}/js/pages/form-advanced.init.js"></script>
@endsection

@section('main-content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Thêm Mã giảm giá</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <form action="" class="card-body" method="POST">
                                @csrf
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Tên mã giảm giá</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Nhập tên mã giảm giá"
                                            id="example-text-input" name="name" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Code</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Nhập code"
                                            id="example-text-input" name="code" value="{{ old('code') }}">
                                        @error('code')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-md-2 form-label">Giá trị giảm</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Nhập giá trị giảm"
                                            id="example-text-input" name="price" value="{{ old('price') }}">
                                        @error('price')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-md-2 form-label">Giảm giá cho đơn hàng trên</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Nhập giảm giá theo giá trị"
                                            id="example-text-input" name="for_sum" value="{{ old('for_sum') }}">
                                        @error('for_sum')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-search-input" class="col-md-2 col-form-label">Tình trạng</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="coupon_type">
                                            <option value="0">Giảm theo giá</option>
                                            <option value="1">Giảm theo phần trăm</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Hạn sử dụng</label>
                                    <div class="col-md-4">
                                        <div class="input-group" id="datepicker1">
                                            <input type="text" class="form-control" placeholder="dd-mm-yyyy"
                                                data-date-format="dd-mm-yyyy" data-date-container='#datepicker1'
                                                data-provide="datepicker" name="expired">

                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-search-input" class="col-md-2 col-form-label">Mô tả</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Nhập mô tả"
                                            name="description">{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success waves-effect waves-light w-xs">
                                            Thêm
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div> <!-- end col -->

            </div>

        </div>
    @endsection
