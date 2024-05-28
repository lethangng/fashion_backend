@extends('admin.master')
@section('title', 'Quản lý giá')

@section('script')

    <script>
        $(document).ready(function() {
            var selectedItems = [];
            var isDelete = false;

            function handleCheck() {
                if (selectedItems.length > 0 && isDelete == false) {
                    $('#btn-delete').removeClass('d-none');
                    isDelete = true;
                } else if (selectedItems.length == 0) {
                    $('#btn-delete').addClass('d-none');
                    isDelete = false;
                }
            }
            // console.log(window.location.href);

            function handleDelete() {
                if (selectedItems.length > 0) {
                    $.ajax({
                        url: "{{ route('product_price.delete') }}",
                        type: 'POST',
                        data: {
                            _token: @json(csrf_token()),
                            id: selectedItems,
                            product_id: "{{ $product_id }}"
                        },
                        success: function(response) {
                            console.log(response);
                            window.location.href = window.location.href;
                        },
                        error: function(e) {
                            console.log('Lỗi ' + e.responseText);
                        }
                    });
                }
            }

            $('#select-all').click(function() {
                var isChecked = $(this).prop('checked');
                $('.checkbox').prop('checked', isChecked);

                if (isChecked) {
                    $('.checkbox').each(function() {
                        var value = $(this).val();
                        if (!selectedItems.includes(value)) {
                            selectedItems.push(value);
                        }
                    });
                } else {
                    selectedItems = [];
                }

                console.log(selectedItems);
                handleCheck();
            });

            $('.checkbox').click(function() {
                var isAllChecked = $('.checkbox:not(:checked)').length === 0;
                $('#select-all').prop('checked', isAllChecked);

                var isChecked = $(this).prop('checked');
                var value = $(this).val();
                if (isChecked && !selectedItems.includes(value)) {
                    selectedItems.push(value);
                } else if (!isChecked) {
                    var index = selectedItems.indexOf(value);
                    if (index !== -1) {
                        selectedItems.splice(index, 1);
                    }
                }
                console.log(selectedItems);
                handleCheck();
            });

            $('#btn-submit-delete').on('click', function(e) {
                e.preventDefault();
                handleDelete();
            });
        });
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
                            <h4 class="mb-sm-0 font-size-18">Quản lý giá</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-sm-12">
                                    <div class="row hidden-xs">
                                        <!-- App Search-->
                                        <form class="d-none d-lg-block col-sm-6"
                                            action="{{ route('product_price.search') }}" method="GET">
                                            <div class="row">
                                                <div class="app-search col-sm-6">
                                                    <div class="position-relative">
                                                        <input type="text" class="form-control"
                                                            placeholder="Từ khóa cần tìm..." name="search"
                                                            value= "@if (isset($search)) {{ $search }} @endif">
                                                        <span class="bx bx-search-alt"></span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <button type="submit"
                                                        class="btn btn-primary waves-effect waves-light w-xs">
                                                        <i class="fas fa-search"></i>
                                                        Tìm kiếm
                                                    </button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="col-sm-6" id="btn-wrap">
                                            <div class="text-end">
                                                <button type="button" id="btn-delete"
                                                    class="btn btn-danger waves-effect waves-light w-xs  d-none"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModalDelete"
                                                    data-bs-whatever="@mdo">
                                                    <span class="icon-status node-delete-53">
                                                        <i class="fas fa-trash"></i>
                                                        Xóa
                                                    </span>
                                                </button>

                                                <a href="{{ route('product_price.create', $product_id) }}"
                                                    class=" btn btn-success waves-effect waves-light w-xs">
                                                    <i class="bx bx-add-to-queue"></i>
                                                    Thêm
                                                </a>
                                            </div>

                                            {{-- Modal --}}
                                            <div class="modal fade" id="exampleModalDelete" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-center" id="exampleModalLabel">
                                                                Xóa</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3 fs-5 text-center">
                                                                Bạn chắn chắn muốn xóa ?
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Quay lại</button>
                                                            <button type="submit" id="btn-submit-delete"
                                                                class="btn btn-danger">Đồng
                                                                ý</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table class="table align-middle table-nowrap mb-0 table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 20px;">
                                                    <div class="form-check font-size-16 align-middle">
                                                        <input class="form-check-input" type="checkbox" id="select-all">

                                                    </div>
                                                </th>

                                                <th class="align-middle">Giá bán</th>
                                                <th class="align-middle">Giá niêm yết</th>
                                                <th class="align-middle">Tiết kiệm</th>
                                                <th class="align-middle">Thời gian tạo</th>
                                                <th class="text-center">Sửa</th>
                                                <th class="text-center">Xóa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product_prices as $product_price)
                                                <tr>
                                                    <td>
                                                        <div class="form-check font-size-16">
                                                            <input class="form-check-input checkbox" type="checkbox"
                                                                id="{{ $product_price->id }}"
                                                                value="{{ $product_price->id }}">
                                                        </div>
                                                    </td>

                                                    <td>
                                                        {{ $product_price->price }}
                                                    </td>
                                                    <td>
                                                        {{ $product_price->price_off }}
                                                    </td>
                                                    <td>
                                                        {{ $product_price->sell_off }}
                                                    </td>

                                                    <td>
                                                        {{ $product_price->created_at->format('H:i d-m-Y') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn btn-warning btn-sm"
                                                            href="{{ route('product_price.edit', $product_price->id) }}">
                                                            <i class="fas fa-pencil-alt">
                                                            </i>
                                                        </a>
                                                    </td>
                                                    <form action="{{ route('product_price.delete') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id"
                                                            value="{{ $product_price->id }}">
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $product_price->product_id }}">

                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModal{{ $product_price->id }}"
                                                                data-bs-whatever="@mdo">

                                                                <span class="icon-status node-delete-53">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                            </button>
                                                            <div class="modal fade"
                                                                id="exampleModal{{ $product_price->id }}" tabindex="-1"
                                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title text-center"
                                                                                id="exampleModalLabel">
                                                                                Xóa</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="mb-3 fs-5">
                                                                                Bạn chắn chắn muốn xóa ?
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Quay lại</button>
                                                                            <button type="submit"
                                                                                class="btn btn-danger">Đồng ý</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    </form>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- end table-responsive -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <ul class="pagination pagination-rounded justify-content-center mt-1 mb-4 pb-1">
                            @for ($i = 1; $i < $total_pages + 1; $i++)
                                <li @class(['page-item', 'disabled' => $current_page == 1])>
                                    <a href="{{ route('product_price.index', ['product_id' => $product_price->product_id, 'page' => 1]) }}"
                                        class="page-link"><i class="mdi mdi-chevron-left"></i>
                                    </a>
                                </li>

                                <li @class(['page-item', 'active' => $i == $current_page])>
                                    <a href="{{ route('product_price.index', ['product_id' => $product_price->product_id, 'page' => $i]) }}"
                                        class="page-link">{{ $i }}</a>
                                </li>

                                <li @class(['page-item', 'disabled' => $current_page == $total_pages])>
                                    <a href="{{ route('product_price.index', ['product_id' => $product_price->product_id, 'page' => $i + 1]) }}"
                                        class="page-link"><i class="mdi mdi-chevron-right"></i>
                                    </a>
                                </li>
                            @endfor
                        </ul>
                    </div>
                </div>
                <!-- end row -->

            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

    </div>
@endsection