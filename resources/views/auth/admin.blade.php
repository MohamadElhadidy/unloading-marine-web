@extends('layouts.app')
@section('title', 'لوحة التحكم')
@section('style')

    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet">
    <style>
        .header {
            visibility: hidden !important;
            opacity: 0 !important;
        }

        .pagination a {
            color: #000 !important;
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

    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="model">
        <form method="POST" id="myform">
            @csrf
            <i class="fas fa-times-circle close"></i>
            <h2><i class="fas fa-plus-circle"></i>إنشاء حساب جديد</h2>
            <input type="text" name="name" id="name" placeholder="الاسم ">
            <input type="text" name="username" id="username" placeholder="اسم الدخول ">
            <select name="type" id="type">
                <option disabled value='' selected> اختر نوع الحساب</option>
                @foreach ($types as $type)
                    <option value='{{ $type->id }}'>{{ $type->name }}</option>
                @endforeach
            </select>
            <input type="text" name="email" id="email" placeholder="الايميل">
            <input type="text" name="password" id="password" placeholder="كلمة السر">
            <input type="text" name="hint" id="hint" placeholder="hint">
            <br>
            <button id="btn-save" class="dt-button"><i class='fas fa-plus-square'></i>إنشاء حساب جديد</button>
        </form>
    </div>

    <div class="wrapper">
        <p>لوحة التحكم</p>
        <img src="">
        <table id='table'>
            <thead>
                <tr>
                    <th>الاسم </th>
                    <th>اسم الدخول</th>
                    <th>الايميل</th>
                    <th>نوع الحساب</th>
                    <th>الأدوات</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection


@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <script>
        // CREATE

        $(".close").click(function() {
            $('.model').toggleClass("add_model");
        });
        $(".close2").click(function() {
            $('.role').toggleClass("add_role");
        });
        var dt = $('#table').DataTable({
            dom: 'lBfrtip',
            responsive: true,
            "language": {
                "searchPlaceholder": "ابحث",
                "sSearch": "",
                "sProcessing": "جاري التحديث...",
                "sLengthMenu": "أظهر مُدخلات _MENU_",
                "sZeroRecords": "لم يُعثر على أية سجلات",
                "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مُدخل",
                "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجلّ",
                "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                "sInfoPostFix": "",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "الأول",
                    "sPrevious": "السابق",
                    "sNext": "التالي",
                    "sLast": "الأخير"
                }
            },
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "الكل"]
            ],
            processing: true,
            serverSide: true,
            //bLengthChange: false,
            ajax: '{!! route('Duser') !!}',
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'auth',
                    name: 'auth'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            buttons: [

                {
                    text: '<i class="fas fa-plus-circle">إنشاء حساب جديد',
                    className: "dt-button",
                    action: function(e, dt, node, config) {
                        $('.model').toggleClass("add_model");
                    }
                },

                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> excel',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    },
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"> طباعة',
                    messageTop: '<img src="/images/print_header.png" style="position:relative;width:100%;" />',
                    autoPrint: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    },
                    customize: function(win) {
                        $(win.document.body).addClass('body_content');
                        $(win.document.body).find('table th')
                            .css('background', 'rgb(24, 143, 190)')
                            .css('margin', '0px');
                        $(win.document.body)
                            .css('width', '100%');
                        $(win.document.body).find('h1')
                            .css('display', 'none');
                        $(win.document.body).find('table tr td')
                            .css('text-align', 'center')
                            .css('font-size', '1.3rem')
                            .css('padding', '5px');
                        $(win.document.body).find('table th')
                            .css('text-align', 'center')
                            .css('color', '#fff')
                            .css('font-size', '1.3rem !important');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit')
                            .css('margin-right', 'auto')
                            .css('margin-left', 'auto');

                    }
                },
            ]
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <script>
        $('#type').select2();
        $("#btn-save").click(function(e) {
            $("#myform").validate({
                rules: {
                    name: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                    username: {
                        required: true
                    },
                    email: {
                        required: true
                    },
                    hint: {
                        required: true
                    },
                    password: {
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

                    var formData = {
                        name: $("#name").val(),
                        type: $("#type").val(),
                        username: $("#username").val(),
                        email: $("#email").val(),
                        hint: $("#hint").val(),
                        password: $("#password").val()
                    };
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: '/register',
                        data: formData,
                        dataType: "json",
                        encode: true,
                        error: function() {
                            $.alert('اعد المحاولة مرة أخرى')
                        }
                    }).done(function(data) {
                        $('.model').toggleClass("add_model");
                        $('#table').DataTable().ajax.reload();
                        $('form').trigger("reset");
                        $.alert({
                            title: '',
                            type: 'green',
                            content: ' تم إضافة الحسااب بنجاح',
                            icon: 'fa fa-thumbs-up',
                        });

                    });
                }
            });
        });


        function getId(id) {
            let name = document.getElementById(id).coords;
            $.confirm({
                title: name,
                icon: 'fa fa-warning',
                content: 'هل أنت متأكد من عملية الحذف ؟ ',
                type: 'red',
                rtl: true,
                closeIcon: false,
                closeIconClass: 'fa fa-close',
                draggable: true,
                dragWindowGap: 0,
                typeAnimated: true,
                theme: 'supervan',
                autoClose: 'cancelAction|60000',
                buttons: {
                    ok: {
                        text: 'حذف',
                        btnClass: 'btn-red',
                        action: function() {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                type: "DELETE",
                                url: '/users/' + id,
                                error: function() {
                                    $('#table').DataTable().ajax.reload();
                                    $.alert({
                                        title: '',
                                        type: 'red',
                                        content: 'اعد المحاولة مرة أخرى',
                                        icon: 'fa fa-warning',
                                    });
                                }
                            }).done(function(data) {
                                $.alert({
                                    title: '',
                                    type: 'green',
                                    content: ' تم حذف ' + name + ' بنجاح  ',
                                    icon: 'fa fa-thumbs-up',
                                });
                                $('#table').DataTable().ajax.reload();
                            });

                        }
                    },
                    cancelAction: {
                        text: 'لا',
                        action: function() {
                            $.alert({
                                title: '',
                                type: 'red',
                                content: 'تم إلغاء عملية الحذف',
                                icon: 'fa fa-warning',
                            });
                        }
                    },
                }
            });
        }

        function end(ids) {
            let name = document.getElementById(ids).coords;
            let id = document.getElementById(ids).rel;
            $.confirm({
                title: name,
                icon: 'fas fa-hourglass-end',
                content: 'هل أنت متأكد من عملية الإنهاء ؟ ',
                type: 'orange',
                rtl: true,
                closeIcon: false,
                closeIconClass: 'fa fa-close',
                draggable: true,
                dragWindowGap: 0,
                typeAnimated: true,
                theme: 'supervan',
                autoClose: 'cancelAction|60000',
                buttons: {
                    ok: {
                        text: 'إنهاء',
                        btnClass: 'btn-orange',
                        action: function() {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                type: "GET",
                                url: '/vessels/end/' + id,
                                error: function() {
                                    $('#table').DataTable().ajax.reload();
                                    $.alert({
                                        title: '',
                                        type: 'orange',
                                        content: 'اعد المحاولة مرة أخرى',
                                        icon: 'fa fa-warning',
                                    });
                                }
                            }).done(function(data) {
                                $.alert({
                                    title: '',
                                    type: 'green',
                                    content: ' تم إنهاء ' + name + ' بنجاح  ',
                                    icon: 'fa fa-thumbs-up',
                                });
                                $('#table').DataTable().ajax.reload();
                            });

                        }
                    },
                    cancelAction: {
                        text: 'لا',
                        action: function() {
                            $.alert({
                                title: '',
                                type: 'orange',
                                content: 'تم إلغاء عملية  الإنهاء',
                                icon: 'fas fa-hourglass-end',
                            });
                        }
                    },
                }
            });
        }

        var Pchannel = pusher.subscribe('management');
        Pchannel.bind('add-vessel', function(data) {
            $('#table').DataTable().ajax.reload();
        });
    </script>
@endsection
