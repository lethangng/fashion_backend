@extends('admin.master')
@section('title', 'Thêm sản phẩm')

@section('css')
    <link href="{{ asset('assets') }}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <!-- Plugins css -->
    <link href="{{ asset('assets') }}/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />

    <style>
        .dropzone .dz-preview .dz-progress {
            top: 115% !important;
        }

        .ck-editor__editable[role="textbox"] {
            /* Editing area */
            min-height: 200px;
        }

        .ck-content .image {
            /* Block images */
            max-width: 80%;
            margin: 20px auto;
        }
    </style>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets') }}/libs/select2/js/select2.min.js"></script>

    <!-- Plugins js -->
    <script src="{{ asset('assets') }}/libs/dropzone/min/dropzone.min.js"></script>

    <!-- form advanced init -->
    <script src="{{ asset('assets') }}/js/pages/form-advanced.init.js"></script>

    {{-- editor --}}
    <script src="{{ asset('assets') }}/js/editor.js"></script>

    <script src="{{ asset('assets') }}/js/style.js"></script>

    <script>
        Dropzone.autoDiscover = false;
        var dropzoneImage = new Dropzone("#form-image", {
            autoProcessQueue: false,
            uploadMultiple: false,
            parallelUploads: 1,
            maxFiles: 1,
            maxFilesize: 1, // MB
            createImageThumbnails: true,
            clickable: true,
            addRemoveLinks: true,
            acceptedFiles: 'image/*',
        });

        var dropzoneListImages = new Dropzone("#form-list-image", {
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 1,
            maxFiles: 10,
            maxFilesize: 1, // MB
            createImageThumbnails: true,
            clickable: true,
            addRemoveLinks: true,
            acceptedFiles: 'image/*',
        });
    </script>

    <script>
        var myEditor = editor('editor');
        $(document).ready(function() {
            var selectedSizes = [];
            var selectedColors = [];

            $('input[name="size"]').click(function() {
                var isChecked = $(this).prop('checked');
                var value = $(this).val();
                if (isChecked && !selectedSizes.includes(value)) {
                    selectedSizes.push(value);
                } else if (!isChecked) {
                    var index = selectedSizes.indexOf(value);
                    if (index !== -1) {
                        selectedSizes.splice(index, 1);
                    }
                }
                console.log(selectedSizes);
            });

            $('input[name="color"]').click(function() {
                var isChecked = $(this).prop('checked');
                var value = $(this).val();
                if (isChecked && !selectedColors.includes(value)) {
                    selectedColors.push(value);
                } else if (!isChecked) {
                    var index = selectedColors.indexOf(value);
                    if (index !== -1) {
                        selectedColors.splice(index, 1);
                    }
                }
                console.log(selectedColors);
            });

            $('#btn-add').click(function(e) {
                e.preventDefault();

                // Lấy danh sách các file đã upload
                var image = dropzoneImage.getAcceptedFiles()[0];
                console.log("Image:", image);
                // return;

                var list_images = dropzoneListImages.getAcceptedFiles();
                console.log("List image:", list_images);

                var formData = new FormData();
                formData.append('_token', @json(csrf_token()));
                formData.append('image_product', image);

                for (var i = 0; i < list_images.length; i++) {
                    formData.append('list_images_product[]', list_images[i]);
                }
                // formData.append('list_images', list_images);

                formData.append('description', myEditor.getData());
                formData.append('colors', JSON.stringify(selectedColors));
                formData.append('sizes', JSON.stringify(selectedSizes));

                var isNew = $('input[name="newest"]').prop('checked');
                formData.append('newest', isNew ? 1 : 0);

                var inputs = [{
                        name: 'name',
                        selector: 'input[name="name"]'
                    },
                    {
                        name: 'category_id',
                        selector: 'select[name="category_id"]'
                    },
                    {
                        name: 'brand_id',
                        selector: 'select[name="brand_id"]'
                    },
                    {
                        name: 'status',
                        selector: 'select[name="status"]'
                    },
                    {
                        name: 'price',
                        selector: 'input[name="price"]'
                    },
                    {
                        name: 'import_price',
                        selector: 'input[name="import_price"]'
                    },
                    {
                        name: 'price_off',
                        selector: 'input[name="price_off"]'
                    },
                    {
                        name: 'sell_off',
                        selector: 'input[name="sell_off"]'
                    },
                ];

                $.each(inputs, function(index, input) {
                    var value = $(input.selector).val();
                    formData.append(input.name, value);
                });

                $.ajax({
                    url: window.location.href,
                    type: 'POST',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        showLoading();
                        window.location.href = "{{ route('product.index', 1) }}";
                    },
                    error: function(e) {
                        console.log('Lỗi ' + e.responseText);
                    },
                });
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
                            <h4 class="mb-sm-0 font-size-18">Thêm sản phẩm</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                @csrf
                                <h4 class="card-title mb-3">Thông tin sản phẩm</h4>
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Tên sản phẩm</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Nhập tên sản phẩm..."
                                            id="example-text-input" name="name" value="{{ old('name') }}">
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-search-input" class="col-md-2 col-form-label">Danh mục</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="category_id">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-search-input" class="col-md-2 col-form-label">Thương hiệu</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="brand_id">
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Giá nhập</label>
                                    <div class="col-md-4">
                                        <input class="form-control" type="text" placeholder="Nhập giá nhập..."
                                            id="example-text-input" name="import_price" value="{{ old('import_price') }}">
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Giá bán</label>
                                    <div class="col-md-4">
                                        <input class="form-control" type="text" placeholder="Nhập giá bán..."
                                            id="example-text-input" name="price" value="{{ old('price') }}">
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Giá niêm yết</label>
                                    <div class="col-md-4">
                                        <input class="form-control" type="text" placeholder="Nhập giá niêm yết..."
                                            id="example-text-input" name="price_off" value="{{ old('price_off') }}">
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Tiết kiệm</label>
                                    <div class="col-md-4">
                                        <input class="form-control" type="text" placeholder="Nhập tiết kiệm..."
                                            id="example-text-input" name="sell_off" value="{{ old('sell_off') }}">

                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-search-input" class="col-md-2 col-form-label">Tình trạng</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="status">
                                            <option value="0">Còn hàng</option>
                                            <option value="1">Hết hàng</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-search-input" class="col-md-2 col-form-label">Mới nhất</label>
                                    <div class="col-md-10">
                                        <div class="row row-cols-lg-auto g-3 align-items-center">
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="newest"
                                                        id="formCheckNew">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-search-input" class="col-md-2 col-form-label">Kích cỡ</label>
                                    <div class="col-md-10">
                                        <div class="row row-cols-lg-auto g-3 align-items-center">
                                            @foreach ($sizes as $size)
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="size"
                                                            value="{{ $size->id }}"
                                                            id="formCheck{{ $size->id }}">
                                                        <label class="form-check-label"
                                                            for="formCheck{{ $size->id }}">
                                                            {{ $size->size }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="example-search-input" class="col-md-2 col-form-label">Màu sắc</label>
                                    <div class="col-md-10">
                                        <div class="row row-cols-lg-auto g-3 align-items-center">
                                            @foreach ($colors as $color)
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="color"
                                                            value="{{ $color->id }}"
                                                            id="formCheckColor{{ $color->id }}">

                                                        <label class="form-check-label"
                                                            for="formCheckColor{{ $color->id }}"
                                                            style="background: {{ $color->color }}; width: 10px; height:10px;">
                                                        </label>

                                                        <label class="form-check-label"
                                                            for="formCheckColor{{ $color->id }}">
                                                            {{ $color->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Hình ảnh sản phẩm</h4>
                                <div>
                                    <form action="#" class="dropzone" id="form-image">
                                        <div class="fallback">
                                            <input name="file" type="file" accept="image/*">
                                        </div>
                                        <div class="dz-message needsclick">
                                            <div class="mb-3">
                                                <i class="display-4 text-muted bx bxs-cloud-upload"></i>
                                            </div>

                                            <h4>Thả tập tin vào đây hoặc bấm vào để tải lên.</h4>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Danh sách hình ảnh sản phẩm</h4>
                                <div>
                                    <form action="#" class="dropzone" id="form-list-image"
                                        enctype="multipart/form-data">
                                        <div class="fallback">
                                            <input type="file" name="file">
                                        </div>
                                        <div class="dz-message needsclick">
                                            <div class="mb-3">
                                                <i class="display-4 text-muted bx bxs-cloud-upload"></i>
                                            </div>

                                            <h4>Thả tập tin vào đây hoặc bấm vào để tải lên.</h4>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Mô tả</h4>
                                <div class="row">
                                    <div id="container" class="col-md-12 mb-3">
                                        <textarea name="description" id="editor">
                                        </textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success waves-effect waves-light w-xs"
                                            id="btn-add">
                                            Thêm
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
