@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Settings</h1>
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
        <div id="settingsGrid"></div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<script type="text/x-kendo-template" id="editPopupTemplate">
    <div class="k-edit-label">
        <label for="key">Key <i class="text-danger">*</i></label>
    </div>
    <div data-container-for="key" class="k-edit-field">
        <input type="text" class="k-input k-textbox" name="key" style="width: 100%" required="required" data-bind="value:key" validationMessage="Field tidak boleh kosong" readonly>
        <span class="k-invalid-msg" data-for="key"></span>
    </div>

    # if (type == 'text') { #
        <div class="k-edit-label">
            <label for="value">Value <i class="text-danger">*</i></label>
        </div>
        <div data-container-for="value" class="k-edit-field">
            <input type="text" class="k-input k-textbox" name="value" style="width: 100%" required="required" data-bind="value:value" validationMessage="Field tidak boleh kosong">
            <span class="k-invalid-msg" data-for="value"></span>
        </div>
    # } else { #
        <div class="k-edit-label">
            <label for="value">Value <i class="text-danger">*</i></label>
        </div>
        <div data-container-for="value" class="k-edit-field">
            <input type="file" name="value" id="value" aria-label="value">
        </div>
        <div style="float: right">
            <div class="dropZoneElement">
                <div class="textWrapper">
                    <p>Drag &amp; Drop Files Here</p>
                    <p class="dropImageHereText">Drop file here to upload</p>
                </div>
            </div>
        </div>
    # } #

    <div class="k-edit-label">
        <label for="description">Deskripsi</label>
    </div>
    <div data-container-for="description" class="k-edit-field">
        <textarea type="text" class="k-input k-textbox" style="min-height: 80px" name="description" data-bind="value:description"></textarea>
    </div>
</script>

<script type="text/javascript">
    $(function () {
        var settingsDataSource = new kendo.data.DataSource({
            transport: {
                read: function (options) {
                    $.ajax({
                        url: "{{ route('settings.get_all') }}",
                        type: "GET",
                        success: function (res) {
                            console.log(res);
                            options.success(res);
                        }
                    });
                },
                update: function (options) {
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        url: "{{ route('settings.update') }}/"+options.data.key,
                        type: "PUT",
                        data: options.data,
                        dataType: "json",
                        success: function (res) {
                            options.success(res);
                        },
                        complete: function (e) {
                            $("#settingsGrid").data("kendoGrid").dataSource.read();
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
        
        $("#settingsGrid").kendoGrid({
            dataSource: settingsDataSource,
            columns: [
                {
                    field: "no",
                    title: "No",
                    width: 50,
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    field: "key",
                    title: "Key",
                    width: 160,
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    template: function(data) {
                        if (data.type == 'text') {
                            return data.show_value;
                        }
                        return '<div class="text-center"><a href="'+data.show_value+'" target="_blank"><img src="'+data.show_value+'" alt="Logo" height="60"></a></div>';
                    },
                    title: "Value",
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    field: "description",
                    title: "Deskripsi",
                    headerAttributes: { style: "text-align: center" }
                },
                {
                    field: "type",
                    title: "Tipe",
                    width: 80,
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
                            text: {
                                edit: "Edit",
                                update: "Simpan",
                                cancel: "Batal"
                            }
                        }
                    ]
                }
            ],
            dataBinding: function() {
                record = (this.dataSource.page() -1) * this.dataSource.pageSize();
            },
            edit: function (e) {
                e.container.parent().find('.k-window-title').text(e.model.isNew() ? "Tambah Data" : "Edit Data");

                var initUpload = function () {
                    var validation = {};
                    validation.allowedExtensions = ["png","jpg","jpeg"]
                    $("#value").kendoUpload({
                        async: {
                            saveUrl: "{{ route('settings.upload') }}/"+e.model.key,
                            autoUpload: false
                        },
                        multiple: false,
                        validation: validation,
                        dropZone: ".dropZoneElement",
                        upload: onUpload,
                        success: onSuccess,
                    }).data("kendoUpload");
                };

                function onSuccess(e2) {
                    e.model.value = e2.response.value;
                    settingsDataSource.sync();
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
                template: $("#editPopupTemplate").html()
            },
            save: function(e) {
                if (e.model.type == 'file') {
                    if (!$("[name='value']").closest(".k-upload").hasClass("k-upload-empty")) {
                        e.preventDefault();
                        $("#value").data("kendoUpload").upload();
                    }
                }
            }
        });
    });
</script>
<style>
    .k-popup-edit-form {
        width: 700px;
    }
    .k-edit-form-container {
        width: 570px;
    }
    .dropZoneElement {
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
