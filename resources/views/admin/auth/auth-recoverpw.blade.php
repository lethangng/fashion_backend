@extends('admin.auth.master')
@section('title', 'Lấy lại mật khẩu')

@section('main-content')
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-primary bg-soft">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-4">
                                        <h5 class="text-primary"> Reset Password</h5>
                                        <p>Reset Password with Fashion.</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ asset('assets') }}/images/profile-img.png" alt=""
                                        class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div>
                                <a href="#">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ asset('assets') }}/images/logo.svg" alt=""
                                                class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <div class="p-2">
                                <div class="alert alert-success text-center mb-4" role="alert">
                                    Nhập email của bạn và hướng dẫn sẽ được gửi!
                                </div>
                                <form class="form-horizontal"
                                    action="https://themesbrand.com/skote-cakephp/layouts/index.html">

                                    <div class="mb-3">
                                        <label for="useremail" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="useremail" placeholder="Enter email"
                                            required>
                                    </div>

                                    <div class="text-end">
                                        <button class="btn btn-primary w-md waves-effect waves-light"
                                            type="submit">Reset</button>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>Remember It ? <a href="{{ route('login') }}" class="fw-medium text-primary"> Sign In here</a>
                        </p>
                        <p>©
                            <script>
                                document.write(new Date().getFullYear())
                            </script> Fashion. Crafted with <i class="mdi mdi-heart text-danger"></i> by Le
                            Ngoc Thang
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
