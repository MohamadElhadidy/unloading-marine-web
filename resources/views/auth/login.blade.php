@extends('layouts.app')
@section('title', 'منظومة التفريغ الجديدة')
@section('style')
<link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="model">
        <form method="POST" id="myform">
            @csrf
            <input type="text" dir="ltr" name="username" id="username" placeholder="اسم  المستخدم">
            <br>
             <span id="show_hide_password">
            <input type="password" dir="ltr" class="form-control" name="password" id="password" placeholder="كلمة المرور">
                <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                </span>
            <br>
            <button id="btn-save" class="dt-button"><i class='fas fa-sign-in-alt'></i> تسجيل الدخول </button>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <script>
        $("#btn-save").click(function(e) {
            $("#myform").validate({
                rules: {
                    username: {
                        required: true
                    },
                    password: {
                        required: true
                    },
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
                    var formData = {
                        username: $("#username").val(),
                        password: $("#password").val(),
                    };
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: '/login',
                        data: formData,
                        dataType: "json",
                        encode: true,
                        error: function(xhr, status, error) {
                            var msg = (xhr.responseText);
                            if (xhr.status != 422) {
                                msg = 'اعد المحاولة مرة أخرى';
                            }
                            $.alert({
                                title: '',
                                type: 'red',
                                content: msg,
                                icon: 'fa fa-warning',
                                confirm: function() {
                                    alert('Confirmed!');
                                },
                            })
                        },
                        success: function(data) {
                            $.confirm({
                                title: formData.username,
                                icon: 'fa fa-thumbs-up',
                                content: 'تم  تسجيل الدخول  بنجاح',
                                type: 'green',
                                rtl: true,
                                closeIcon: false,
                                draggable: true,
                                dragWindowGap: 0,
                                typeAnimated: true,
                                theme: 'supervan',
                                autoClose: 'okAction|3000',
                                buttons: {
                                    okAction: {
                                        text: 'ok',
                                        action: function() {
                                            $.redirect(
                                                "{!! route('/') !!}",
                                                "",
                                                "GET", "");
                                        }
                                    },
                                }
                            })
                        }
                    })
                }
            })
        });
          $("#show_hide_password a").on("click", function (event) {
    event.preventDefault();
    if ($("#show_hide_password input").attr("type") == "text") {
      $("#show_hide_password input").attr("type", "password");
      $("#show_hide_password i").addClass("fa-eye-slash");
      $("#show_hide_password i").removeClass("fa-eye");
    } else if ($("#show_hide_password input").attr("type") == "password") {
      $("#show_hide_password input").attr("type", "text");
      $("#show_hide_password i").removeClass("fa-eye-slash");
      $("#show_hide_password i").addClass("fa-eye");
    }
  });
    </script>

@endsection
