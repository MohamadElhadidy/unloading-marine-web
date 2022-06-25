@extends('layouts.app')
@section('title', 'تعديل حساب ')
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

        .role {
            background: #000 !important;
            position: absolute !important;
            height: 100vh !important;
            top: 0vh !important;
            width: 100vw !important;
            right: 0vw !important;
            padding: 10px;
            color: #fff;
            font-size: 2rem !important;
            text-align: center;
            z-index: 4;
            transition: opacity 0.5s ease-in-out;
            visibility: hidden;
            opacity: 0;
            align-content: center;
        }

        .add_role {
            visibility: visible;
            opacity: 1;
        }

        table {
            text-align: center;
            font-size: 1.3rem;
            margin-right: 28vw;
        }

    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://jqueryvalidation.org/files/demo/site-demos.css">

@endsection
@section('content')
    @if ($user->done == 1)
        <script>
            window.location = "/management";
        </script>
    @endif
    <div class="model">
        <form method="POST" id="myform">
            @csrf
            <h2><i class="fas fa-edit"></i> تعديل حساب </h2>
            <input type="hidden" value="{{ $user->id }}" name="id" id="id">
            <input type="text" value="{{ $user->username }}" name="username" id="username" placeholder="اسم الدخول ">
            <select name="type" id="type">
                @foreach ($types as $type)
                    @if ($user->type == $type->id)
                        <option value='{{ $type->id }}'>{{ $type->name }}</option>
                    @endif
                @endforeach
                @foreach ($types as $type)
                    @if ($user->type != $type->id)
                        <option value='{{ $type->id }}'>{{ $type->name }}</option>
                    @endif
                @endforeach
            </select> <input type="text" value="{{ $user->name }}" name="name" id="name" placeholder="الاسم">
            <input type="text" value="{{ $user->email }}" name="email" id="email" placeholder="الايميل">
            <input type="password" name="password" id="password" placeholder="كلمة المرور">
            <input type="text" value="{{ $user->hint }}" name="hint" id="hint" placeholder="hint">
            <br>
            <button id="btn-save" class="dt-button"><i class='fas fa-edit'></i> تعديل </button>
            <a onclick="showRole()" class="dt-button"><i class='fas fa-plus'></i> الصلاحيات </a>
        </form>
    </div>
    <div class="role">
        <form method="POST" id="myform2">
            @csrf
            <i class="fas fa-times-circle close2"></i>
            <h2><i class="fas fa-plus-circle"></i>الصلاحيات</h2>
            <input type="hidden" value="{{ $roles->id ?? '' }}" name="role_id" id="role_id">
            <input type="hidden" value="{{ $user->id }}" name="user_id" id="user_id">
            <textarea name="role_name" id="role_name" dir="ltr"
                placeholder="الصلاحيات ">{{ $roles->report_or_vessel ?? '' }}</textarea>
            <br>
            <button id="btn-role" class="dt-button"><i class='fas fa-plus-square'></i> حفظ البيانات</button>
            <table>
                @foreach ($reports as $report)
                    <tr>
                        <td>{{ $report->name }}</td>
                        <td>{{ $report->name_ar }}</td>
                    </tr>
                @endforeach

            </table>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <script>
        $(".close2").click(function() {
            $('.role').toggleClass("add_role");
        });

        function showRole() {
            $('.role').toggleClass("add_role");
        }
        $('#type').select2();
        $("#btn-save").click(function(e) {
            $("#myform").validate({
                rules: {
                    name: {
                        required: true
                    },
                    username: {
                        required: true
                    },
                    email: {
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
                        username: $("#username").val(),
                        email: $("#email").val(),
                        type: $("#type").val(),
                        hint: $("#hint").val(),
                        password: $("#password").val()
                    };
                    if (formData.password == '') delete formData.password;
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "PUT",
                        url: '/users/' + id,
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
                            content: 'تم تعديل الحساب  بنجاح',
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
                                        $.redirect("{!! route('admin') !!}", "",
                                            "GET", "");
                                    }
                                },
                            }
                        });
                    });
                }
            });
        });
        $("#btn-role").click(function(e) {
            $("#myform2").validate({
                rules: {
                    role_id: {
                        required: true
                    },
                    user_id: {
                        required: true
                    },
                    role_name: {
                        required: true
                    }
                },
                highlight: function(input) {
                    $(input).addClass('error_input');
                },
                unhighlight: function(input) {
                    $(input).removeClass('error_input');
                },
                errorPlacement: function(error, element) {
                    $(element).append(error);
                },
                submitHandler: function() {
                    e.preventDefault();
                    var formData = {
                        role_id: $("#role_id").val(),
                        user_id: $("#user_id").val(),
                        report_or_vessel: $("#role_name").val()
                    };
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "/roles",
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
                            content: 'تم تعديل الصلاحيات  بنجاح',
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
                                        $.redirect("{!! route('admin') !!}", "",
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
