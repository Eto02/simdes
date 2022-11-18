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
        <div id="companyGrid"></div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<div id="deleteDialog"></div>

<script type="text/x-kendo-template" id="deleteTemplateDialog">
    Anda yakin ingin menghapus company <strong>#= name #</strong>?
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
        <label for="password">Password <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="password" class="k-edit-field">
        <input type="text" class="k-input k-textbox" name="password" style="width: 100%" required="required" data-bind="value:password" validationMessage="Field tidak boleh kosong">
        <span class="k-invalid-msg" data-for="password"></span>
    </div>

    <div class="k-edit-label">
        <label for="company_name">Nama Company <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="company_name" class="k-edit-field">
        <input type="text" class="k-input k-textbox" name="company_name" style="width: 100%" required="required" data-bind="value:company_name" validationMessage="Field tidak boleh kosong">
        <span class="k-invalid-msg" data-for="company_name"></span>
    </div>

    <div class="k-edit-label">
        <label for="Alamat_Pengirim">Alamat Pengirim <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="Alamat_Pengirim" class="k-edit-field">
        <textarea type="text" class="k-input k-textbox" style="min-height: 80px" name="Alamat_Pengirim" required="required" data-bind="value:Alamat_Pengirim" validationMessage="Field tidak boleh kosong"></textarea>
        <span class="k-invalid-msg" data-for="Alamat_Pengirim"></span>
    </div>

    <div class="k-edit-label">
        <label for="phone_number">Nomor HP <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="phone_number" class="k-edit-field">
        <input type="number" class="k-input k-textbox" name="phone_number" style="width: 100%" required="required" data-bind="value:phone_number" validationMessage="Field tidak boleh kosong">
        <span class="k-invalid-msg" data-for="phone_number"></span>
    </div>

    <div class="k-edit-label">
        <label for="logo">Logo <i class="text-danger">*</i></label>
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
        <label for="login_background">Background Login <i class="text-danger">*</i></label>
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

        var companyDataSource = new kendo.data.DataSource({
            transport: {
                read: function (options) {
                    $.ajax({
                        url: "{{ route('company.get_all') }}",
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
                        url: "{{ route('company.store') }}",
                        type: "POST",
                        data: options.data,
                        dataType: "json",
                        success: function (res) {
                            namaBerkas = null;
                            options.success(res);
                        },
                        complete: function (e) {
                            $("#companyGrid").data("kendoGrid").dataSource.read();
                        }
                    });
                },
                update: function (options) {
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        url: "{{ route('company.update') }}/"+options.data.Company_Id,
                        type: "PUT",
                        data: options.data,
                        dataType: "json",
                        success: function (res) {
                            options.success(res);
                        },
                        complete: function (e) {
                            $("#companyGrid").data("kendoGrid").dataSource.read();
                        }
                    });
                }
            },
            schema: {
                data: "data",
                total: "recordsTotal",
                model: {
                    id: "Company_Id",
                },
            },
            pageSize: 10
        });
        
        $("#companyGrid").kendoGrid({
            dataSource: companyDataSource,
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
                    field: "company_name",
                    title: "Company",
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    field: "address",
                    title: "Alamat",
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
                        return '<div class="text-center"><a href="'+data.logo+'" target="_blank"><img src="'+data.logo+'" alt="Logo" width="60" height="60"></a></div>';
                    },
                    title: "Logo",
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    field: "",
                    title: "Background",
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
                            saveUrl: "",
                            autoUpload: false
                        },
                        multiple: false,
                        validation: validation,
                        dropZone: ".logoDropZoneElement",
                    }).data("kendoUpload");

                    $("#login_background").kendoUpload({
                        async: {
                            saveUrl: "",
                            autoUpload: false
                        },
                        multiple: false,
                        validation: validation,
                        dropZone: ".loginBackgroundDropZoneElement",
                    }).data("kendoUpload");
                };
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
                                url: "{{ route('company.destroy') }}/"+data.Company_Id,
                                type: "DELETE",
                                dataType: "json",
                                success: function (status) {
                                    $("#companyGrid").data("kendoGrid").dataSource.read();
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
</style>

@endsection
