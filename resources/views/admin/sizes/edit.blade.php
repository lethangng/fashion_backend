@extends('admin.master')
@section('title', 'Cập nhập kích thước')

@section('main-content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Cập nhập kích thước</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <form action="" class="card-body" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $size->id }}">
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Tên kích thước</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Nhập tên kích thước"
                                            id="example-text-input" name="size" value="{{ $size->size }}">
                                        @error('size')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="example-search-input" class="col-md-2 col-form-label">Mô tả</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Nhập mô tả"
                                            name="description">{{ $size->description }}</textarea>
                                        @error('description')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="text-end">
                                        <a href="{{ route('size.index') }}" type="button"
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
