@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Jenis Berkas</h1>
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
        <div id="fileTypeGrid"></div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<div id="deleteDialog"></div>

<script type="text/x-kendo-template" id="deleteTemplateDialog">
    Anda yakin ingin menghapus jenis surat <strong>#= name #</strong>?
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
        <label for="description">Deskripsi <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="description" class="k-edit-field">
        <textarea type="text" class="k-input k-textbox" style="min-height: 80px" name="description" required="required" data-bind="value:description" validationMessage="Field tidak boleh kosong"></textarea>
        <span class="k-invalid-msg" data-for="description"></span>
    </div>
</script>

<script type="text/javascript">
    var deleteDialog, deleteTemplateDialog;

    $(function () {
        deleteTemplateDialog = kendo.template($("#deleteTemplateDialog").html());

        var fileTypeDataSource = new kendo.data.DataSource({
            transport: {
                read: function (options) {
                    $.ajax({
                        url: "{{ route('file_type.get_all') }}",
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
                        url: "{{ route('file_type.store') }}",
                        type: "POST",
                        data: options.data,
                        dataType: "json",
                        success: function (res) {
                            options.success(res);
                        },
                        complete: function (e) {
                            $("#fileTypeGrid").data("kendoGrid").dataSource.read();
                        }
                    });
                },
                update: function (options) {
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        url: "{{ route('file_type.update') }}/"+options.data.file_type_id,
                        type: "PUT",
                        data: options.data,
                        dataType: "json",
                        success: function (res) {
                            options.success(res);
                        },
                        complete: function (e) {
                            $("#fileTypeGrid").data("kendoGrid").dataSource.read();
                        }
                    });
                }
            },
            schema: {
                data: "data",
                total: "recordsTotal",
                model: {
                    id: "no",
                },
            },
            pageSize: 10
        });
        
        $("#fileTypeGrid").kendoGrid({
            dataSource: fileTypeDataSource,
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
                    field: "description",
                    title: "Deskripsi",
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    headerTemplate: "<span class='k-icon k-i-gear'></span>",
                    headerAttributes: { class: "table-header-cell", style: "text-align: center" },
                    attributes: { class: "table-cell", style: "text-align: center" },
                    width: "180px",
                    command: [
                        {
                            name: "edit",
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
                                url: "{{ route('file_type.destroy') }}/"+data.file_type_id,
                                type: "DELETE",
                                dataType: "json",
                                success: function (status) {
                                    $("#fileTypeGrid").data("kendoGrid").dataSource.read();
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
</style>

@endsection
