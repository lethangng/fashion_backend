@extends('admin.master')
@section('title', 'Thêm giá')

@section('main-content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Thêm giá</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <form action="" class="card-body" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product_id }}">
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Giá bán</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Nhập giá bán"
                                            id="example-text-input" name="price" value="{{ old('price') }}" required>
                                        @error('price')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Giá niêm yết</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Nhập giá niêm yết"
                                            id="example-text-input" name="price_off" value="{{ old('price_off') }}">
                                        @error('price_off')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                {{-- <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Tiết kiệm</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Nhập tiết kiệm"
                                            id="example-text-input" name="sell_off" value="{{ old('sell_off') }}">
                                        @error('sell_off')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div> --}}


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
