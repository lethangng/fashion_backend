<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        {{-- <i class="bx bx-home-circle"></i><span class="badge rounded-pill bg-info float-end">04</span> --}}
                        <i class="bx bx-store"></i>
                        <span key="t-dashboards">Dashboards</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('admin.index') }}" key="t-default">Dashboards</a></li>
                    </ul>
                </li>

                <li class="menu-title" key="t-menu">Sản phẩm</li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-box"></i>
                        <span key="t-ecommerce">Quản lý sản phẩm</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="ecommerce-products.html" key="t-products">Sản phẩm</a></li>
                        <li><a href="{{ route('product.add') }}" key="t-product-detail">Thêm sản phẩm</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-gem"></i>
                        <span key="t-ecommerce">Quản lý Thương hiệu</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('brand.index') }}" key="t-products">Thương hiệu</a></li>
                        <li><a href="{{ route('brand.create') }}" key="t-product-detail">Thêm thương hiệu</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-list"></i>
                        <span key="t-ecommerce">Quản lý Danh mục</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('category.index') }}" key="t-products">Danh mục</a></li>
                        <li><a href="{{ route('category.create') }}" key="t-product-detail">Thêm danh mục</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-adjust"></i>
                        <span key="t-ecommerce">Quản lý Màu sắc</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('color.index') }}" key="t-products">Màu sắc</a></li>
                        <li><a href="{{ route('color.create') }}" key="t-product-detail">Thêm màu sắc</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-ruler"></i>
                        <span key="t-ecommerce">Quản lý Kích thước</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('size.index') }}" key="t-products">Kích thước</a></li>
                        <li><a href="{{ route('size.create') }}" key="t-product-detail">Thêm kích thước</a></li>
                    </ul>
                </li>

                <li class="menu-title" key="t-menu">Tài khoản</li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-user"></i>
                        <span key="t-ecommerce">Quản lý Người dùng</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('user.index') }}" key="t-products">Người dùng</a></li>
                    </ul>
                </li>

                <li class="menu-title" key="t-menu">Đơn hàng</li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-cart-plus"></i>
                        <span key="t-ecommerce">Quản lý Đơn hàng</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="ecommerce-products.html" key="t-products">Đơn hàng</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-coupon"></i>
                        <span key="t-ecommerce">Quản lý Coupon</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('brand.index') }}" key="t-products">Coupon</a></li>
                        <li><a href="{{ route('brand.create') }}" key="t-product-detail">Thêm coupon</a></li>
                    </ul>
                </li>

                <li class="menu-title" key="t-menu">Khác</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-clipboard-search-outline"></i>
                        <span key="t-ecommerce">Quản lý Đánh giá</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="ecommerce-products.html" key="t-products">Đánh giá</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-chart-line"></i>
                        <span key="t-ecommerce">Thống kê</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="ecommerce-products.html" key="t-products">Thống kê</a></li>
                    </ul>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
