@extends('Admin.layouts.layout')

@section('title', 'Danh sách media')

{{-- impoort thư viện css --}}
@section('style-libraries')

@stop
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="contaimer-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Quản trị Media</h4>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form>
                                <div class="row hidden-xs filters-list-form">
                                    <div class="col-sm-3 mb-3">
                                        <input class="form-control btn-search" type="search" id="search_key" name="s"
                                            value="" placeholder="Từ khóa cần tìm...">
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <select id="treeview" name="cid" class="form-control select2">
                                            <option value="">Loại Media...</option>
                                            <?php	 foreach($banner_types as $k=>$v) {  	 ?>
                                            <option value="<?php echo $k; ?>">
                                                <?php	 echo $v; 	 ?>
                                            </option>
                                            <?php	 } 	 ?>
                                        </select>

                                    </div>
                                    <div class="col-sm-6 tools">
                                        <div class="text-end">
                                            <a href="<?php echo $DOMAIN; ?>admin/banner/banner_add"
                                                class="me-3 btn btn-success waves-effect waves-light w-xs">
                                                <i class="bx bx-add-to-queue"></i>
                                                Thêm
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-rep-plugin">
                                <div class="table-responsive">
                                    <table id="table-data" class="table table-data table-striped table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50">Stt</th>
                                                <th class="text-center" width="200">
                                                    Hình ảnh
                                                </th>
                                                <th class="text-center">
                                                    Tên media
                                                </th>
                                                <th class="text-center" width="100">
                                                    Loại banner
                                                </th>
                                                <th class="text-center" width="100">
                                                    Vị trí
                                                </th>
                                                <!-- <th class="text-center">
                                                    Trạng thái
                                                </th> -->
                                                <th class="text-center" width="100">
                                                    Sửa
                                                </th>
                                                <th class="text-center" width="100">
                                                    Xóa
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php	 if(isset($data) && count($data) > 0) {
                                                $stt = 0;
                                                foreach($data as $v) { 	$stt++;
                                                ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?php	 echo $stt; 	 ?>
                                                </td>
                                                <td class="text-center">
                                                    <img src="<?php	 echo $v['images']; 	 ?>" alt="" width="80">

                                                </td>
                                                <td class="text-center">
                                                    <?php	 echo $v['title']; 	 ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php	 echo $v['type'];	 ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php	 echo $v['pos']; 	 ?>
                                                </td>
                                                <td class="text-center">
                                                    <a class="btn btn-outline-secondary btn-sm"
                                                        href="<?php echo $DOMAIN . 'admin/banner/banner_edit/' . ($v['id']); ?>">
                                                        <i class="fas fa-pencil-alt">
                                                        </i>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a class="btn  btn-sm" href="javascript:;"
                                                        onclick="remove_item('tr-53', 'K3RJSWw0RUZYSXI4UkVVQmJvbkFhdDJUL3FBMDI5Q0pSaHNpdkhWa3gydz0=');">
                                                        <span class="icon-status node-delete-53">
                                                            <i class="fas fa-trash"></i>
                                                        </span>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php	 } 	 ?>
                                            <?php	 } 	 ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

{{-- impoort thư viện js--}}
@section('scripts')
<script>
    $('.select2').select2();
</script>
@stop