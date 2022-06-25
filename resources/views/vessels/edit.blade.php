@extends('layouts.app')
@section('title', 'تعديل باخرة ')
@section('style')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet">
    <style>
        .header {
            visibility: hidden !important;
            opacity: 0 !important;
        }

        .model {
            visibility: visible;
            opacity: 1;
        }

        @media only screen and (max-width: 420px) {

            .select2 {
                display: inline-block !important;
                width: 85vw !important;
            }
        }

    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://jqueryvalidation.org/files/demo/site-demos.css">

@endsection
@section('content')
    @if ($vessel->done == 1)
        <script>
            window.location = "/management";
        </script>
    @endif
    <div class="model">
        <form method="POST" id="myform">
            @csrf
            <h2><i class="fas fa-edit"></i> تعديل باخرة </h2>
            <input type="hidden" value="{{ $vessel->id }}" name="id" id="id">
            <input type="text" value="{{ $vessel->name }}" name="name" id="name" placeholder="اسم الباخرة">
            <input type="text" name="type" value='{{ $vessel->type }}' id="type" placeholder="الصنف">
            {{-- <select name="type" id="type">
                <option value='{{ $vessel->type }}' selected>{{ $vessel->type }}</option>
                @foreach ($types as $type)
                    @if ($vessel->type != $type->name)
                        <option value='{{ $type->name }}'>{{ $type->name }}</option>
                    @endif
                @endforeach
            </select>  --}}
            <input type="text" value="{{ $vessel->qnt }}" name="qnt" id="qnt" placeholder="الكمية">
            <input type="text" value="{{ $vessel->quay }}" name="quay" id="quay" placeholder="الرصيف">
            <input type="text" value="{{ $vessel->client }}" name="client" id="client" placeholder="العميل">
            <input type="text" value="{{ $vessel->shipping_agency }}" name="shipping_agency" id="shipping_agency"
                placeholder="التوكيل الملاحي">
            <input type="text" value="{{ $vessel->notes }}" name="notes" id="notes" placeholder="ملاحظات">
            <br>
            <button id="btn-save" class="dt-button"><i class='fas fa-edit'></i> تعديل </button>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <script>
        //$('#type').select2();
        $("#btn-save").click(function(e) {
            $("#myform").validate({
                rules: {
                    name: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                    qnt: {
                        required: true
                    },
                    quay: {
                        required: true
                    },
                    client: {
                        required: true
                    },
                    shipping_agency: {
                        required: true
                    }
                },
                highlight: function(input) {
                    $(input).addClass('error_input');
                    $('.select2 ').addClass('error_input');
                },
                unhighlight: function(input) {
                    $(input).removeClass('error_input');
                    $('.select2 ').removeClass('error_input');
                },
                errorPlacement: function(error, element) {
                    $(element).append(error);
                },
                submitHandler: function() {
                    e.preventDefault();
                    var id = $("#id").val();
                    var formData = {
                        name: $("#name").val(),
                        type: $("#type").val(),
                        qnt: $("#qnt").val(),
                        quay: $("#quay").val(),
                        client: $("#client").val(),
                        shipping_agency: $("#shipping_agency").val(),
                        start_date: $("#start_date").val(),
                        notes: $("#notes").val(),
                    };
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "PUT",
                        url: '/vessels/' + id,
                        data: formData,
                        dataType: "json",
                        encode: true,
                        error: function() {
                            $.alert({
                                title: '',
                                type: 'red',
                                content: 'اعد المحاولة مرة أخرى',
                                icon: 'fa fa-warning',
                                confirm: function() {
                                    alert('Confirmed!');
                                },
                            });
                        }
                    }).done(function(data) {
                        $.confirm({
                            title: name,
                            icon: 'fa fa-thumbs-up',
                            content: 'تم تعديل الباخرة  بنجاح',
                            type: 'green',
                            rtl: true,
                            closeIcon: false,
                            draggable: true,
                            dragWindowGap: 0,
                            typeAnimated: true,
                            theme: 'supervan',
                            autoClose: 'okAction|60000',
                            buttons: {
                                okAction: {
                                    text: 'ok',
                                    action: function() {
                                        $.redirect("{!! route('management') !!}", "",
                                            "GET", "");
                                    }
                                },
                            }
                        });
                    });
                }
            });
        });
    </script>

@endsection
