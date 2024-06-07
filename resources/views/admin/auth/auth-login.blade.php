@extends('admin.auth.master')
@section('title', 'Đăng nhập')

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
                                        <h5 class="text-primary">Chào mừng quay lại !</h5>
                                        <p>Sign in to continue to Fashion.</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ asset('assets') }}/images/profile-img.png" alt=""
                                        class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="auth-logo">
                                <a href="#" class="auth-logo-light">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ asset('assets') }}/images/logo-light.svg" alt=""
                                                class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>

                                <a href="#" class="auth-logo-dark">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ asset('assets') }}/images/logo.svg" alt=""
                                                class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2">
                                <form class="form-horizontal" action="" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="username" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="username" placeholder="Nhập email"
                                            name="email">
                                        @error('email')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Mật khẩu</label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input type="password" class="form-control" placeholder="Nhập mật khẩu"
                                                aria-label="Password" aria-describedby="password-addon" name="password">
                                            <button class="btn btn-light" type="button" id="password-addon"><i
                                                    class="mdi mdi-eye-outline"></i></button>
                                        </div>
                                        @error('password')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember-check">
                                        <label class="form-check-label" for="remember-check">
                                            Remember me
                                        </label>
                                    </div>

                                    <div class="mt-3 d-grid">
                                        <button class="btn btn-primary waves-effect waves-light" type="submit">Đăng
                                            nhập</button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <a href="{{ route('reset-password') }}" class="text-muted"><i
                                                class="mdi mdi-lock me-1"></i> Quên mật khẩu?</a>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="mt-2 text-center">

                        <div>
                            {{-- <p>Don't have an account ? <a href="auth-register.html" class="fw-medium text-primary"> Signup now </a> </p> --}}
                            <p>©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> Fashion. Crafted with <i class="mdi mdi-heart text-danger"></i> by
                                Le Ngoc Thang
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
