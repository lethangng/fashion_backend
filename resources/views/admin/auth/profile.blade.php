@extends('admin.master')
@section('title', 'Cập nhật thông tin')

@section('main-content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Cập nhật thông tin</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <form action="{{ route('profile.edit') }}" class="card-body" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <input type="hidden" name="u_id" value="{{ $user->u_id }}">
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Họ và tên</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Nhập họ và tên"
                                            id="example-text-input" name="fullname" value="{{ $user->fullname }}">
                                        @error('fullname')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Email</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="email" placeholder="Email"
                                            id="example-text-input" name="email" value="{{ $user->email }}">
                                        @error('email')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Số điện thoại</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Số điện thoại"
                                            id="example-text-input" name="phone_number" value="{{ $user->phone_number }}">
                                        @error('phone_number')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Mật khẩu mới</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Nhập mật khẩu mới"
                                            id="example-text-input" name="new_password" value="">
                                        @error('new_password')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Nhập lại mật khẩu
                                        mới</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Nhập lại mật khẩu mới"
                                            id="example-text-input" name="confirm-password" value="">
                                        @error('confirm-password')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Mật khẩu hiện
                                        tại</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Nhập mật khẩu hiện tại"
                                            id="example-text-input" name="password" value="">
                                        @error('password')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div> --}}

                                <div class="row">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light w-xs">
                                            Xác nhận
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
