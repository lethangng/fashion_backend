@extends('admin.master')
@section('title', 'Cập nhập mã giảm giá')

@section('main-content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Cập nhập mã giảm giá</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <form action="" class="card-body" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $coupon->id }}">
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Code</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Nhập code"
                                            id="example-text-input" name="code" value="{{ $coupon->code }}">
                                        @error('code')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-md-2 form-label">Giá trị giảm</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Nhập giá trị giảm"
                                            id="example-text-input" name="price" value="{{ $coupon->price }}">
                                        @error('price')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-md-2 form-label">Giảm giá cho đơn hàng trên</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Nhập giảm giá theo giá trị"
                                            id="example-text-input" name="for_sum" value="{{ $coupon->for_sum }}">
                                        @error('for_sum')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-search-input" class="col-md-2 col-form-label">Tình trạng</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="coupon_type">
                                            <option value="0" @selected($coupon->coupon_type == 0)>Giảm theo giá</option>
                                            <option value="1" @selected($coupon->coupon_type == 1)>Giảm theo phần trăm</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Hạn sử dụng</label>
                                    <div class="col-md-4">
                                        <div class="input-group" id="datepicker1">
                                            <input type="text" class="form-control" placeholder="dd-mm-yyyy"
                                                data-date-format="dd-mm-yyyy" data-date-container='#datepicker1'
                                                data-provide="datepicker" name="expired"
                                                value="{{ date('d-m-Y', strtotime($coupon->expired)) }}">

                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-search-input" class="col-md-2 col-form-label">Mô tả</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Nhập mô tả"
                                            name="description">{{ $coupon->description }}</textarea>
                                        @error('description')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="text-end">
                                        <a href="{{ route('coupon.index') }}" type="button"
                                            class="btn btn-light waves-effect waves-light w-xs">
                                            Quay lại
                                        </a>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light w-xs">
                                            Đồng ý
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
