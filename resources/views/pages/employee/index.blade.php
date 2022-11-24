@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Company</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    {{-- <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard v1</li> --}}
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div id="employeeGrid"></div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<div id="deleteDialog"></div>

<script type="text/x-kendo-template" id="deleteTemplateDialog">
    Anda yakin ingin menghapus employee <strong>#= name #</strong>?
</script>

<script type="text/x-kendo-template" id="editPopupTemplate">
    <div class="k-edit-label">
        <label for="name">Nama <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="name" class="k-edit-field">
        <input type="text" class="k-input k-textbox" name="name" style="width: 100%" required="required" data-bind="value:name" validationMessage="Field tidak boleh kosong">
        <span class="k-invalid-msg" data-for="name"></span>
    </div>

    <div class="k-edit-label">
        <label for="email">Email <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="email" class="k-edit-field">
        <input type="email" class="k-input k-textbox" name="email" style="width: 100%" required="required" data-bind="value:email" validationMessage="Field tidak boleh kosong">
        <span class="k-invalid-msg" data-for="email"></span>
    </div>

    <div class="k-edit-label">
        <label for="password">Password</label>
    </div>
    <div data-container-for="password" class="k-edit-field">
        <input type="text" class="k-input k-textbox" name="password" style="width: 100%" data-bind="value:password">
    </div>

    <div class="k-edit-label">
        <label for="employee_name">Nama Company <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="employee_name" class="k-edit-field">
        <input type="text" class="k-input k-textbox" name="employee_name" style="width: 100%" required="required" data-bind="value:employee_name" validationMessage="Field tidak boleh kosong">
        <span class="k-invalid-msg" data-for="employee_name"></span>
    </div>

    <div class="k-edit-label">
        <label for="address">Alamat <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="address" class="k-edit-field">
        <textarea type="text" class="k-input k-textbox" style="min-height: 80px" name="address" required="required" data-bind="value:address" validationMessage="Field tidak boleh kosong"></textarea>
        <span class="k-invalid-msg" data-for="address"></span>
    </div>

    <div class="k-edit-label">
        <label for="phone_number">Nomor HP <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="phone_number" class="k-edit-field">
        <input type="number" class="k-input k-textbox" name="phone_number" style="width: 100%" required="required" data-bind="value:phone_number" validationMessage="Field tidak boleh kosong">
        <span class="k-invalid-msg" data-for="phone_number"></span>
    </div>

    <div class="k-edit-label">
        <label for="logo">Logo</label>
    </div>
    <div data-container-for="logo" class="k-edit-field">
        <input type="file" name="logo" id="logo" aria-label="logo">
    </div>
    <div style="float: right">
        <div class="logoDropZoneElement">
            <div class="textWrapper">
                <p>Drag &amp; Drop Files Here</p>
                <p class="dropImageHereText">Drop file here to upload</p>
            </div>
        </div>
    </div>

    <div class="k-edit-label">
        <label for="login_background">Background Login</label>
    </div>
    <div data-container-for="login_background" class="k-edit-field">
        <input type="file" name="login_background" id="login_background" aria-label="login_background">
    </div>
    <div style="float: right">
        <div class="loginBackgroundDropZoneElement">
            <div class="textWrapper">
                <p>Drag &amp; Drop Files Here</p>
                <p class="dropImageHereText">Drop file here to upload</p>
            </div>
        </div>
    </div>
</script>

<script type="text/javascript">
    var deleteDialog, deleteTemplateDialog;

    $(function () {
        deleteTemplateDialog = kendo.template($("#deleteTemplateDialog").html());

        var employeeDataSource = new kendo.data.DataSource({
            transport: {
                read: function (options) {
                    $.ajax({
                        url: "{{ route('employee.get_all') }}",
                        type: "GET",
                        success: function (res) {
                            console.log(res);
                            options.success(res);
                        }
                    });
                },
                create: function (options) {
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        url: "{{ route('employee.store') }}",
                        type: "POST",
                        data: options.data,
                        dataType: "json",
                        success: function (res) {
                            namaBerkas = null;
                            options.success(res);
                        },
                        complete: function (e) {
                            $("#employeeGrid").data("kendoGrid").dataSource.read();
                        }
                    });
                },
                update: function (options) {
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        url: "{{ route('employee.update') }}/"+options.data.user_id,
                        type: "PUT",
                        data: options.data,
                        dataType: "json",
                        success: function (res) {
                            options.success(res);
                        },
                        complete: function (e) {
                            $("#employeeGrid").data("kendoGrid").dataSource.read();
                        }
                    });
                }
            },
            schema: {
                data: "data",
                total: "recordsTotal",
                model: {
                    id: "user_id",
                },
            },
            pageSize: 10
        });
        
        $("#employeeGrid").kendoGrid({
            dataSource: employeeDataSource,
            columns: [
                {
                    field: "no",
                    title: "No",
                    width: 50,
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    field: "name",
                    title: "Nama",
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    field: "email",
                    title: "Email",
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    field: "employee_name",
                    title: "Company",
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    field: "address",
                    title: "Alamat",
                    width: 100,
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    field: "phone_number",
                    title: "No. HP",
                    width: 120,
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    template: function(data) {
                        return '<div class="text-center"><a href="'+data.logo_view+'" target="_blank"><img src="'+data.logo_view+'" width="60" height="60"></a></div>';
                    },
                    title: "Logo",
                    width: 80,
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    template: function(data) {
                        return '<div class="text-center"><a href="'+data.login_background_view+'" target="_blank"><img src="'+data.login_background_view+'" width="60"></a></div>';
                    },
                    title: "Background",
                    width: 100,
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    headerTemplate: "<span class='k-icon k-i-gear'></span>",
                    headerAttributes: { class: "table-header-cell", style: "text-align: center" },
                    attributes: { class: "table-cell", style: "text-align: center" },
                    width: "100px",
                    command: [
                        {
                            name: "edit",
                            className: "mb-1",
                            text: {
                                edit: "Edit",
                                update: "Simpan",
                                cancel: "Batal"
                            }
                        },
                        {
                            name: "customDelete",
                            iconClass: "k-icon k-i-close",
                            text: "Hapus",
                            click: deleteData
                        }
                    ]
                }
            ],
            dataBinding: function() {
                record = (this.dataSource.page() -1) * this.dataSource.pageSize();
            },
            toolbar: [
                {
                    name: "create",
                    text: "Tambah Data",
                    hidden: true
                }
            ],
            edit: function (e) {
                e.container.parent().find('.k-window-title').text(e.model.isNew() ? "Tambah Data" : "Edit Data");

                var initUpload = function () {
                    var validation = {};
                    validation.allowedExtensions = ["png","jpg","jpeg"]
                    $("#logo").kendoUpload({
                        async: {
                            saveUrl: "{{ route('employee.upload') }}/logo",
                            autoUpload: false
                        },
                        multiple: false,
                        validation: validation,
                        dropZone: ".logoDropZoneElement",
                        upload: onUpload,
                        success: function(e2) {
                            e.model.logo = e2.response.file_name;
                            // employeeDataSource.sync();
                        },
                    }).data("kendoUpload");

                    $("#login_background").kendoUpload({
                        async: {
                            saveUrl: "{{ route('employee.upload') }}/login_background",
                            autoUpload: false
                        },
                        multiple: false,
                        validation: validation,
                        dropZone: ".loginBackgroundDropZoneElement",
                        upload: onUpload,
                        success: function(e2) {
                            e.model.login_background = e2.response.file_name;
                            employeeDataSource.sync();
                        },
                    }).data("kendoUpload");
                };

                function onUpload(e) {
                    var token = $('meta[name="csrf-token"]').attr('content');  
                    var xhr = e.XMLHttpRequest;
                    if (xhr) {
                        xhr.addEventListener("readystatechange", function (e) {
                            if (xhr.readyState == 1 /* OPENED */) {
                                xhr.setRequestHeader("X-CSRF-TOKEN", token);
                            }
                        });
                    }
                }

                initUpload();
            },
            noRecords: true,
            sortable: true,
            pageable: {
                numeric: false,
                input: true,
                refresh: true,
                pageSizes: true,
            },
            editable: {
                mode: "popup",
                template: $("#editPopupTemplate").html()
            },
            save: function(e) {
                if (!$("[name='logo']").closest(".k-upload").hasClass("k-upload-empty")) {
                    e.preventDefault();
                    $("#logo").data("kendoUpload").upload();
                }
                if (!$("[name='login_background']").closest(".k-upload").hasClass("k-upload-empty")) {
                    e.preventDefault();
                    $("#login_background").data("kendoUpload").upload();
                }
            }
        });

        function deleteData(e) {
            e.preventDefault();
            var tr = $(e.target).closest("tr"),
            data = this.dataItem(tr);
            deleteDialog = $("#deleteDialog").kendoDialog({
                width: "350px",
                title: "Hapus Data",
                visible: false,
                buttonLayout: "stretched",
                actions: [
                    {
                        text: "Hapus",
                        primary: true,
                        action: function (e) {
                            $.ajax({
                                headers: {
                                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                                },
                                url: "{{ route('employee.destroy') }}/"+data.employee_id,
                                type: "DELETE",
                                dataType: "json",
                                success: function (status) {
                                    $("#employeeGrid").data("kendoGrid").dataSource.read();
                                }
                            });
                        }
                    },
                    {text: "Batal"}
                ]
            }).data("kendoDialog");
            deleteDialog.content(deleteTemplateDialog(data));
            deleteDialog.open();
        }
    });
</script>
<style>
    .k-popup-edit-form {
        width: 700px;
    }
    .k-edit-form-container {
        width: 570px;
    }
    .logoDropZoneElement, .loginBackgroundDropZoneElement {
        position: relative;
        display: inline-block;
        background-color: #f8f8f8;
        border: 1px solid #c7c7c7;
        width: 350px;
        height: 150px;
        text-align: center;
    }
    .textWrapper {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 100%;
        font-size: 18px;
        line-height: 1.2em;
        font-family: Arial,Helvetica,sans-serif;
        color: #000;
    }
    .dropImageHereText {
        color: #c7c7c7;
        text-transform: uppercase;
        font-size: 12px;
    }
    .k-clear-selected, .k-upload-selected{
        display: none !important;
    }
</style>

@endsection
