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
            dictRemoveFile: 'Xóa',
            dictMaxFilesExceeded: 'Vui lòng chỉ chọn 1 hình ảnh.',
            dictFileTooBig: 'Vui lòng chọn hình ảnh dưới 1 MB',
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
            dictRemoveFile: 'Xóa',
            dictMaxFilesExceeded: 'Vui lòng chỉ chọn 1 hình ảnh.',
            dictFileTooBig: 'Vui lòng chọn hình ảnh dưới 1 MB',
        });
    </script>

    <script>
        var myEditor;
        CKEDITOR.ClassicEditor.create(document.getElementById('editor'), {
            toolbar: {
                items: [
                    'exportPDF', 'exportWord', '|',
                    'findAndReplace', 'selectAll', '|',
                    'heading', '|',
                    'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript',
                    'removeFormat', '|',
                    'bulletedList', 'numberedList', 'todoList', '|',
                    'outdent', 'indent', '|',
                    'undo', 'redo',
                    '-',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                    'alignment', '|',
                    'link', 'uploadImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed',
                    '|',
                    'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                    'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            },
            // Changing the language of the interface requires loading the language file using the <script> tag.
            // language: 'es',
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
            heading: {
                options: [{
                        model: 'paragraph',
                        title: 'Paragraph',
                        class: 'ck-heading_paragraph'
                    },
                    {
                        model: 'heading1',
                        view: 'h1',
                        title: 'Heading 1',
                        class: 'ck-heading_heading1'
                    },
                    {
                        model: 'heading2',
                        view: 'h2',
                        title: 'Heading 2',
                        class: 'ck-heading_heading2'
                    },
                    {
                        model: 'heading3',
                        view: 'h3',
                        title: 'Heading 3',
                        class: 'ck-heading_heading3'
                    },
                    {
                        model: 'heading4',
                        view: 'h4',
                        title: 'Heading 4',
                        class: 'ck-heading_heading4'
                    },
                    {
                        model: 'heading5',
                        view: 'h5',
                        title: 'Heading 5',
                        class: 'ck-heading_heading5'
                    },
                    {
                        model: 'heading6',
                        view: 'h6',
                        title: 'Heading 6',
                        class: 'ck-heading_heading6'
                    }
                ]
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/editor-placeholder.html#using-the-editor-configuration
            placeholder: 'Welcome to CKEditor 5!',
            // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-family-feature
            fontFamily: {
                options: [
                    'default',
                    'Arial, Helvetica, sans-serif',
                    'Courier New, Courier, monospace',
                    'Georgia, serif',
                    'Lucida Sans Unicode, Lucida Grande, sans-serif',
                    'Tahoma, Geneva, sans-serif',
                    'Times New Roman, Times, serif',
                    'Trebuchet MS, Helvetica, sans-serif',
                    'Verdana, Geneva, sans-serif'
                ],
                supportAllValues: true
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-size-feature
            fontSize: {
                options: [10, 12, 14, 'default', 18, 20, 22],
                supportAllValues: true
            },
            // Be careful with the setting below. It instructs CKEditor to accept ALL HTML markup.
            // https://ckeditor.com/docs/ckeditor5/latest/features/general-html-support.html#enabling-all-html-features
            htmlSupport: {
                allow: [{
                    name: /.*/,
                    attributes: true,
                    classes: true,
                    styles: true
                }]
            },
            // Be careful with enabling previews
            // https://ckeditor.com/docs/ckeditor5/latest/features/html-embed.html#content-previews
            htmlEmbed: {
                showPreviews: true
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/link.html#custom-link-attributes-decorators
            link: {
                decorators: {
                    addTargetToExternalLinks: true,
                    defaultProtocol: 'https://',
                    toggleDownloadable: {
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'file'
                        }
                    }
                }
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html#configuration
            mention: {
                feeds: [{
                    marker: '@',
                    feed: [
                        '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes',
                        '@chocolate', '@cookie', '@cotton', '@cream',
                        '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread',
                        '@gummi', '@ice', '@jelly-o',
                        '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding',
                        '@sesame', '@snaps', '@soufflé',
                        '@sugar', '@sweet', '@topping', '@wafer'
                    ],
                    minimumCharacters: 1
                }]
            },
            // The "superbuild" contains more premium features that require additional configuration, disable them below.
            // Do not turn them on unless you read the documentation and know how to configure them and setup the editor.
            removePlugins: [
                // These two are commercial, but you can try them out without registering to a trial.
                // 'ExportPdf',
                // 'ExportWord',
                'AIAssistant',
                'CKBox',
                'CKFinder',
                'EasyImage',
                'MultiLevelList',
                'RealTimeCollaborativeComments',
                'RealTimeCollaborativeTrackChanges',
                'RealTimeCollaborativeRevisionHistory',
                'PresenceList',
                'Comments',
                'TrackChanges',
                'TrackChangesData',
                'RevisionHistory',
                'Pagination',
                'WProofreader',
                'MathType',
                // The following features are part of the Productivity Pack and require additional license.
                'SlashCommand',
                'Template',
                'DocumentOutline',
                'FormatPainter',
                'TableOfContents',
                'PasteFromOfficeEnhanced',
                'CaseChange'
            ]
        }).then(editor => {
            myEditor = editor;
        });
    </script>

    <script>
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

                showLoading();

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
                                            <option value="1">Còn hàng</option>
                                            <option value="0">Hết hàng</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="example-search-input" class="col-md-2 col-form-label">Mới nhất</label>
                                    <div class="col-md-10 d-flex align-items-center">
                                        <div class="row row-cols-lg-auto g-3">
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
                                    <div class="col-md-10 d-flex align-items-center">
                                        <div class="row row-cols-lg-auto g-3 ">
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
                                    <div class="col-md-10 d-flex align-items-center">
                                        <div class="row row-cols-lg-auto g-3">
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
