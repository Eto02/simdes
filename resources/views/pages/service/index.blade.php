@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Layanan</h1>
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
        <div id="serviceGrid"></div>
        <script type="text/x-kendo-template" id="detailTemplate">
            <div class="detailTabstrip">
                <ul>
                    <li class="k-state-active">
                        Berkas Layanan
                    </li>
                </ul>
                <div class="serviceFileGrid">
                </div>
            </div>
        </script>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- service -->
<div id="deleteDialog"></div>

<script type="text/x-kendo-template" id="deleteTemplateDialog">
    Anda yakin ingin menghapus layanan <strong>#= name #</strong>?
</script>

<script type="text/x-kendo-template" id="editPopupTemplate">
    <div class="k-edit-label">
        <label for="nik">NIK <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="nik" class="k-edit-field">
        <input type="number" class="k-input k-textbox" name="nik" style="width: 100%" required="required" data-bind="value:nik" validationMessage="Field tidak boleh kosong">
        <span class="k-invalid-msg" data-for="nik"></span>
    </div>

    <div class="k-edit-label">
        <label for="name">Nama <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="name" class="k-edit-field">
        <input type="text" class="k-input k-textbox" name="name" style="width: 100%" required="required" data-bind="value:name" validationMessage="Field tidak boleh kosong">
        <span class="k-invalid-msg" data-for="name"></span>
    </div>

    <div class="k-edit-label">
        <label for="service_type_id">Jenis Layanan <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="service_type_id" class="k-edit-field">
        <input type="text" class="k-input k-textbox" name="service_type_id" style="width: 100%" required="required" data-bind="value:service_type_id" validationMessage="Field tidak boleh kosong">
        <span class="k-invalid-msg" data-for="service_type_id"></span>
    </div>

    <div class="k-edit-label">
        <label for="letter_number">Nomor Surat</label>
    </div>
    <div data-container-for="letter_number" class="k-edit-field">
        <input type="text" class="k-input k-textbox" name="letter_number" style="width: 100%" data-bind="value:letter_number">
    </div>

    {{-- <div class="k-edit-label">
        <label for="serviced_by">Dilayani Oleh <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="serviced_by" class="k-edit-field">
        <input type="text" class="k-input k-textbox" name="serviced_by" style="width: 100%" required="required" data-bind="value:serviced_by" validationMessage="Field tidak boleh kosong">
        <span class="k-invalid-msg" data-for="serviced_by"></span>
    </div> --}}

    <div class="k-edit-label">
        <label for="notes">Catatan</label>
    </div>
    <div data-container-for="notes" class="k-edit-field">
        <textarea type="text" class="k-input k-textbox" style="min-height: 80px" name="notes" data-bind="value:notes"></textarea>
    </div>
</script>
<!-- /.service -->

<!-- service file -->
<div id="deleteServiceFileDialog"></div>

<script type="text/x-kendo-template" id="deleteServiceFileTemplateDialog">
    Anda yakin ingin menghapus berkas layanan <strong>#= file_name #</strong>?
</script>

<script type="text/x-kendo-template" id="editServiceFilePopupTemplate">
    <div class="k-edit-label">
        <label for="file_type_id">Jenis Berkas <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="file_type_id" class="k-edit-field">
        <input type="text" class="k-input k-textbox" name="file_type_id" style="width: 100%" required="required" data-bind="value:file_type_id" validationMessage="Field tidak boleh kosong">
        <span class="k-invalid-msg" data-for="file_type_id"></span>
    </div>

    <div class="k-edit-label">
        <label for="file_name">Berkas <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="file_name" class="k-edit-field">
        <input type="file" name="file_name" id="file_name" aria-label="file_name">
    </div>
    <div style="float: right">
        <div class="fileNameDropZoneElement">
            <div class="textWrapper">
                <p>Drag &amp; Drop Files Here</p>
                <p class="dropImageHereText">Drop file here to upload</p>
            </div>
        </div>
    </div>

    <div class="k-edit-label">
        <label for="file_location">Lokasi Berkas (No Rak) <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="file_location" class="k-edit-field">
        <input type="text" class="k-input k-textbox" name="file_location" style="width: 100%" required="required" data-bind="value:file_location" validationMessage="Field tidak boleh kosong">
        <span class="k-invalid-msg" data-for="file_location"></span>
    </div>
</script>
<!-- /.service file -->

<script type="text/javascript">
    var deleteDialog, deleteTemplateDialog;

    $(function () {
        deleteTemplateDialog = kendo.template($("#deleteTemplateDialog").html());

        var serviceDataSource = new kendo.data.DataSource({
            transport: {
                read: function (options) {
                    $.ajax({
                        url: "{{ route('service.get_all') }}",
                        type: "GET",
                        success: function (res) {
                            console.log(res);
                            options.success(res);
                        }
                    });
                },
                create: function (options) {
                    options.data.service_type_id = $("[name='service_type_id']").data("kendoDropDownList").value()

                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        url: "{{ route('service.store') }}",
                        type: "POST",
                        data: options.data,
                        dataType: "json",
                        success: function (res) {
                            options.success(res);
                        },
                        complete: function (e) {
                            $("#serviceGrid").data("kendoGrid").dataSource.read();
                        }
                    });
                },
                update: function (options) {
                    options.data.service_type_id = $("[name='service_type_id']").data("kendoDropDownList").value()
                    
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        url: "{{ route('service.update') }}/"+options.data.service_id,
                        type: "PUT",
                        data: options.data,
                        dataType: "json",
                        success: function (res) {
                            options.success(res);
                        },
                        complete: function (e) {
                            $("#serviceGrid").data("kendoGrid").dataSource.read();
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

        $("#serviceGrid").kendoGrid({
            dataSource: serviceDataSource,
            columns: [
                {
                    field: "no",
                    title: "No",
                    width: 50,
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    field: "nik",
                    title: "NIK",
                    width: 130,
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    field: "name",
                    title: "Nama",
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    field: "service_type",
                    title: "Jenis Layanan",
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    field: "letter_number",
                    title: "Nomor Surat",
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    field: "serviced_by",
                    title: "Dilayani Oleh",
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    field: "notes",
                    title: "Catatan",
                    headerAttributes: { style: "text-align: center" }
                },
                @if(auth()->user()->roles[0]->name == 'employee')
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
                            name: "deleteService",
                            iconClass: "k-icon k-i-close",
                            text: "Hapus",
                            click: deleteData
                        }
                    ]
                }
                @endif
            ],
            dataBinding: function() {
                record = (this.dataSource.page() -1) * this.dataSource.pageSize();
            },
            toolbar: [
                @if(auth()->user()->roles[0]->name == 'employee')
                {
                    name: "create",
                    text: "Tambah Data",
                    hidden: true
                }
                @endif
            ],
            edit: function (e) {
                e.container.parent().find('.k-window-title').text(e.model.isNew() ? "Tambah Data" : "Edit Data");

                e.container.find("input[name='service_type_id']").kendoDropDownList({
                    filter: "contains",
                    dataTextField: "text",
                    dataValueField: "value",
                    optionLabel: "--- Pilih Jenis Layanan ---",
                    dataSource: {
                        transport: {
                            read: {
                                dataType: "json",
                                url: "{{ route('dropdown.service_type') }}",
                            }
                        }
                    }
                });
            },
            noRecords: true,
            sortable: true,
            pageable: {
                numeric: false,
                input: true,
                refresh: true,
                pageSizes: true,
            },
            detailTemplate: kendo.template($("#detailTemplate").html()),
            detailInit: detailInit,
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
                                url: "{{ route('service.destroy') }}/"+data.service_id,
                                type: "DELETE",
                                dataType: "json",
                                success: function (status) {
                                    $("#serviceGrid").data("kendoGrid").dataSource.read();
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

        function detailInit(e) {
            var detailRow = e.detailRow;

            var deleteServiceFileDialog, deleteServiceFileTemplateDialog;
            deleteServiceFileTemplateDialog = kendo.template($("#deleteServiceFileTemplateDialog").html());

            detailRow.find(".detailTabstrip").kendoTabStrip();

            var serviceFileDataSource = new kendo.data.DataSource({
                transport: {
                    read: function (options) {
                        options.data.service_id = e.data.service_id

                        $.ajax({
                            url: "{{ route('service_file.get_all') }}",
                            type: "GET",
                            data: options.data,
                            dataType: "json",
                            success: function (res) {
                                console.log(res);
                                options.success(res);
                            }
                        });
                    },
                    create: function (options) {
                        options.data.service_id = e.data.service_id
                        options.data.file_type_id = $("[name='file_type_id']").data("kendoDropDownList").value()

                        $.ajax({
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            url: "{{ route('service_file.store') }}",
                            type: "POST",
                            data: options.data,
                            dataType: "json",
                            success: function (res) {
                                options.success(res);
                            },
                            complete: function (e) {
                                detailRow.find(".serviceFileGrid").data("kendoGrid").dataSource.read();
                            }
                        });
                    },
                    update: function (options) {
                        options.data.file_type_id = $("[name='file_type_id']").data("kendoDropDownList").value()

                        $.ajax({
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            url: "{{ route('service_file.update') }}/"+options.data.service_file_id,
                            type: "PUT",
                            data: options.data,
                            dataType: "json",
                            success: function (res) {
                                options.success(res);
                            },
                            complete: function (e) {
                                detailRow.find(".serviceFileGrid").data("kendoGrid").dataSource.read();
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

            detailRow.find(".serviceFileGrid").kendoGrid({
                dataSource: serviceFileDataSource,
                columns: [
                    {
                        field: "no",
                        title: "No",
                        width: 50,
                        headerAttributes: { style: "text-align: center" }
                    },
                    {
                        template: function(data) {
                            return '<div class="text-center"><a href="'+data.file_path+'" target="_blank"><i class="fa fa-file fa-lg" aria-hidden="true"></i></a><div>';
                        },
                        title: "Berkas",
                        width: 80,
                        headerAttributes: { style: "text-align: center" }
                    },
                    {
                        field: "file_location",
                        title: "Lokasi Berkas (No Rak)",
                        headerAttributes: { style: "text-align: center" }
                    },
                    {
                        field: "file_type",
                        title: "Jenis Berkas",
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
                                name: "deleteServiceFile",
                                iconClass: "k-icon k-i-close",
                                text: "Hapus",
                                click: deleteServiceFileData
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

                    e.container.find("input[name='file_type_id']").kendoDropDownList({
                        filter: "contains",
                        dataTextField: "text",
                        dataValueField: "value",
                        optionLabel: "--- Pilih Jenis Berkas ---",
                        dataSource: {
                            transport: {
                                read: {
                                    dataType: "json",
                                    url: "{{ route('dropdown.file_type') }}",
                                }
                            }
                        }
                    });

                    var initUpload = function () {
                        var validation = {};
                        validation.allowedExtensions = ["pdf","docx","doc","xlsx","xls","csv","png","jpg","jpeg"]
                        $("#file_name").kendoUpload({
                            async: {
                                saveUrl: "{{ route('service_file.upload') }}",
                                autoUpload: false
                            },
                            multiple: false,
                            validation: validation,
                            dropZone: ".fileNameDropZoneElement",
                            upload: onUpload,
                            success: onSuccess,
                        }).data("kendoUpload");
                    };

                    function onSuccess(e2) {
                        e.model.file_name = e2.response.file_name;
                        serviceFileDataSource.sync();
                    }

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
                    template: $("#editServiceFilePopupTemplate").html()
                },
                save: function(e) {
                    if (!$("[name='file_name']").closest(".k-upload").hasClass("k-upload-empty")) {
                        e.preventDefault();
                        $("#file_name").data("kendoUpload").upload();
                    }
                }
            });

            function deleteServiceFileData(e) {
                e.preventDefault();
                var tr = $(e.target).closest("tr"),
                data = this.dataItem(tr);
                deleteServiceFileDialog = $("#deleteServiceFileDialog").kendoDialog({
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
                                    url: "{{ route('service.destroy') }}/"+data.service_id,
                                    type: "DELETE",
                                    dataType: "json",
                                    success: function (status) {
                                        detailRow.find(".serviceFileGrid").data("kendoGrid").dataSource.read();
                                    }
                                });
                            }
                        },
                        {text: "Batal"}
                    ]
                }).data("kendoDialog");
                deleteServiceFileDialog.content(deleteServiceFileTemplateDialog(data));
                deleteServiceFileDialog.open();
            }
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
    .fileNameDropZoneElement {
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
