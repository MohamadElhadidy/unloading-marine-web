@extends('layouts.app')
@section('title', 'تقرير السيارات النقل')
@section('style')

    <link href="{{ asset('css/cars.css') }}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            font-size: 1rem !important;
        }

        .header {
            visibility: hidden !important;
            opacity: 0 !important;
        }

        a {
            text-decoration: none !important;
            color: rgb(0, 0, 0);
            font-weight: bold;
            cursor: pointer;
        }

        a:hover {
            color: rgb(6, 47, 114);
        }

        p {
            color: rgb(255, 255, 255);
            font-size: 1.5rem;
            display: inline;
        }

        .dt-buttons {
            margin-right: 40vw;
            width: 20vw;
        }

        td {
            text-align: center
        }


        .dataTables_wrapper {
            top: 5vh;
            width: 95vw;
            margin-right: 2.5vw;
        }

        .vessel__detail {
            width: 95vw;
            margin-right: 2.5vw !important;
            text-align: center;
            margin-bottom: 5px
        }

        .vessel__detail td,
        .vessel__detail th {
            border: 1px solid #000;
        }

        .vessel__detail th {
            background-color: #80808075;
            color: #fff;
        }

        .vessel__detail td {
            background-color: #FFF8DCA3;
            color: #000;
        }

        .dataTables_length select {
            font-size: 1.5rem !important;
        }

        .small {
            width: 50vw !important;
            margin-right: 25vw !important;
        }

        .form_table {
            width: 50vw ;
           margin-right: 10vw !important;
        }

        .cars__counts {
            width: 95vw;
            margin-right: 2.5vw !important;
            text-align: center;
        }

        .cars__counts th {
            border: 1px solid #000;
            background-color: #FFF8DCA3;
            color: #220acc;
        }

        .cars__counts span {
            color: #d71111;
        }

        #footers {
            margin-top: 3vh;
        }

        .model {
            background: #000 !important;
           position: absolute;
height: 250vh !important;
            top: 0vh !important;
            width: 100% !important;
            right: 0vw !important;
            padding: 10px;
            color: #fff;
            font-size: 2rem !important;
            text-align: center;
            z-index: 4;
            transition: opacity 0.5s ease-in-out;
            visibility: visible;
            opacity: 1;
            align-content: center;
        }


@media (min-aspect-ratio: 16/9) {
  .model {
    width: 100%;
    height: auto;
  }
}

@media (max-aspect-ratio: 16/9) {
  .model {
    width: auto;
    height: 100%;
  }
}

        .add_model {
            visibility:  hidden;
            opacity: 0;
        }

        form {
            height: 50vh;
            top: 15vh;
            width: 70vw;
            right: 15vw;
            position: absolute;
            background: #25699300 !important;
            color: #fff;
        }

        form input[type="text"] {
            text-align: center;
            padding: 15px 5px;
            width: 10vw;
            height: 2vh !important;
            color: rgb(255, 255, 255);
            right: 0vw;
            top: 10vh;
            margin: 5px !important;
            border: #ffffff solid 2px;
            background: transparent;
            border-radius: 25px;
        }

        form label {
            width: 15vw;
        }

        .select2 {
            border-radius: 25px;
            padding: 10px;
            color: rgb(255, 255, 255);
            border: #ffffff solid 2px;
            background-color: #fff;
            text-align: center;
            background: transparent;
            width: 10vw !important;
            font-size: 1.5rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #fff !important;
            line-height: 20px !important;
        }

        .select2 .selection {
            width: 30vw !important;
            left: 37.5vw;
            text-align: center !important;
            background: transparent !important;
        }

        .select2-container--default .select2-selection--single {
            background: transparent !important;
            color: #000;
        }

        .select2-selection {
            border: none !important;
        }

        .fa-times-circle {
            cursor: pointer;
            float: right;
            transition: all 0.5s ease-out;
            font-size: 3rem !important;
            margin-top: -25px;
        }

        .fa-times-circle:hover {
            transform: scale(2);
        }
@media only screen and (max-width: 785px) {

.form_table {
  width: 80vw !important;
  margin-right: 5vw !important;
}
form label {
  width: 90vw !important;
}
form {
  top: 10vh !important;
}
.select2-container--default .select2-search--dropdown .select2-search__field {
  width: 48vw !important;
}

.select2 {
  border: 1px solid #aaa;
  width: 50vw !important;
}
}
    </style>
@endsection
@section('content')
                @userType(['accounts','admin'])

    <div class="model">
        <form method="POST" id="myform">
            <input type="hidden" name="id" id="id" value="{{ $car->id }}" readonly>
            <table class="vessel__detail form_table">
                <thead>
                    <tr>
                        <th> تقرير <span>{{ $car->car_type }}</span>
                        </th>
                        <th>رقم <span>{{ $car->car_no }}</span>
                        </th>
                        <th> مقاول
                            <span>{{ $car->car_owner }}</span>
                        </th>
                    </tr>
                </thead>
            </table>
            @csrf
            <i class="fas fa-times-circle" id="close"></i>
            <label> صافى الساعات :</label>
            @if ($car->all_hours == '')
                <input type="text" name="all_hours" id="all_hours" value="{{ $all_hours }}" placeholder="ساعة ">
            @else
                <input type="text" name="all_hours" id="all_hours" value="{{ $car->all_hours }}" placeholder="ساعة ">
            @endif
            <P> ساعة </P>
            <br>
            {{-- <label> وقت الانتظار :</label>
            <input type="text" name="wait_hours" id="wait_hours" value="{{ $wait_hours }}" placeholder="ساعة ">
            <P> ساعة </P>
            <br> --}}
            <label> عدد النقلات : </label>
            @if ($car->moves == '')
                <input type="text" name="moves" id="moves" value="{{ $count }}" placeholder="عدد النقلات">
            @else
                <input type="text" name="moves" id="moves" value="{{ $car->moves }}" placeholder="عدد النقلات">
            @endif
            <P> نقلة </P>
            <br>
            <label> الكمية : </label>
            @if ($car->qnt == '')
                <input type="text" name="qnt" id="qnt" value="{{ $qnt }}" placeholder="الكمية">
            @else
                <input type="text" name="qnt" id="qnt" value="{{ $car->qnt }}" placeholder="الكمية">
            @endif
            <P>طن </P>
            <br>
                <label> انتظارات</label>
            @if ($car->wait == '')
                <input type="text" name="wait" id="wait" value="0" placeholder="انتظارات">
            @else
                <input type="text" name="wait" id="wait" value="{{ $car->wait }}" placeholder="انتظارات">
            @endif
            <P> ساعة </P>
            <br>
                 <label> تكلفة الانتظار</label>
            @if ($car->wait == '')
                <input type="text" name="wait_cost" id="wait_cost" value="0" placeholder="تكلفة الانتظار">
            @else
                <input type="text" name="wait_cost" id="wait_cost" value="{{ $car->wait_cost }}" placeholder="تكلفة الانتظار">
            @endif
            <P> جنيها  </P>
            <br>
            <label> طريقة الحساب : </label>
            <select name="cost_type" id="cost_type">
                  @if ($car->cost_type == '')
                <option disabled value='' selected> طريقة الحساب </option>
                 <option value='hour'>بالساعة</option>
                <option value='move'>بالنقلة</option>
                <option value='qnt'>بالطن</option>
                    @else
                    
                    <option value='hour' @if($car->cost_type == 'hour')  selected @endif>بالساعة</option>
                <option value='move' @if($car->cost_type == 'move')  selected @endif>بالنقلة</option>
                <option value='qnt' @if($car->cost_type == 'qnt')  selected @endif>بالطن</option>
                @endif
               
            </select>
            <br>
            <div id='costs'></div>
            <button id="btn-save" class="dt-button"><i class='fas fa-plus-square'></i> حفظ البيانات</button>
        </form>
    </div>
     @enduserType
    <div id='headers'>
        <table class="vessel__detail small">
            <thead>
                <tr>
                    <th> تقرير <span>{{ $car->car_type }}</span>
                    </th>
                    <th>رقم <span>{{ $car->car_no }}</span>
                    </th>
                    <th> مقاول
                        <span>{{ $car->car_owner }}</span>
                    </th>
                </tr>
            </thead>
        </table>
        <table class="vessel__detail">
            <thead>
                <tr>
                    <th> اسم الباخره </th>
                    <th> العميل</th>
                    <th>رقم الرصيف </th>
                    <th> الصنف</th>
                    <th> الكمية</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $vessel->name }}</td>
                    <td>{{ $vessel->client }}</td>
                    <td>{{ $vessel->quay }}</td>
                    <td>{{ $vessel->type }}</td>
                    <td>{{ $vessel->qnt }}</td>
                </tr>
            </tbody>
        </table>
        <table class="cars__counts">
            <thead>
                <tr>
                    @if ($car->all_cost != '')
                        <th> إجمالى التكلفة : <span>{{ $car->all_cost }} </span> جنيها</th>
                    @endif
                    <th> عدد النقلات : <span>{{ $count }}</span></th>
                    <th> الكمية بالطن : <span>{{ $qnt }}</span></th>
                    <th> وقت الدخول : <span>{{ $car->start_date }}</span></th>
                    <th> وقت الخروج : <span>{{ $car->exit_date }}</span></th>
                </tr>
            </thead>
        </table>
    </div>
    <table id='table'>
        <thead>
            <tr>
                <th>رقم النقلة </th>
                <th>وقت التحميل </th>
                <th>الموظف</th>
                <th>وقت الشحن</th>
                <th>الموظف</th>
                <th>الكميه</th>
                <th>مدة الرحلة</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($moves as $move)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $move->load_date }}</td>
                    <td>{{ $move->load_employee }}</td>
                    <td>{{ $move->arrival_date }}</td>
                    <td>{{ $move->arrival_employee }}</td>
                    <td>{{ $move->qnt }}</td>
                    <td>{{ $move->duration }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>



    <div id='footers'>
        @if ($minus_minutes != 0)
            <table class="vessel__detail small">
                <thead>
                    <tr>
                        <th> إجمالى الخصم :</th>
                        <th>{{ $minus_minutes }} دقيقة</th>
                        @if ($minus_hours != 0)
                            <th>{{ $minus_hours }} ساعة</th>
                        @endif
                        @if ($minus_days != 0)
                            <th>{{ $minus_days }} يوم</th>
                        @endif
                    </tr>
                </thead>
            </table>
        @endif
    </div>
@endsection

@section('scripts')
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/mgalante/jquery.redirect@master/jquery.redirect.js"></script>

    <script>
        $('#cost_type').select2();
        $("#close").click(function() {
            $('.model').toggleClass("add_model");
        });

    
        @if ($car->cost_type == '')
        $(document).on('change', '#cost_type', function() { 
                    var cost_type = $('#cost_type').val();
                    load_data(cost_type);
        });
        @else
                    var cost_type = $('#cost_type').val();
                    load_data(cost_type);
                @endif
        
        
        $(document).on('input', "input[type='text']", function() {
            let cost = $('#cost');
            let cost_type = $('#cost_type').val();
            let all_cost = $('#all_cost');
            if (cost_type == 'hour') {
                let all_hours = $('#all_hours');
                let sum = (cost.val() * all_hours.val()).toFixed(2)
                all_cost.val(sum).change();
            } else if (cost_type == 'move') {
                let moves = $('#moves');
                let sum = (cost.val() * moves.val()).toFixed(2)
                all_cost.val(sum).change();
            } else if (cost_type == 'qnt') {
                let qnt = $('#qnt');
                let sum = (cost.val() * qnt.val()).toFixed(2)
                all_cost.val(sum).change();
            }
        });

        function load_data(cost_type) {
            var html = '';
             @if ($car->cost_type == '')
                  if (cost_type == 'hour') {
                html +=
                    '<label> تكلفة الساعة : </label><input type="text" name="cost" id="cost" value="0" placeholder="تكلفة الساعة">';
                html += ' <P>جنيها </P><br>';
                html +=
                    '<label> إجمالى التكلفة  : </label><input type="text" readonly id="all_cost" value="0" placeholder="إجمالى التكلفة">';
                html += ' <P>جنيها </P><br>';
            } else if (cost_type == 'move') {
                html +=
                    '<label> تكلفة النقلة : </label><input type="text" name="cost" id="cost" value="0" placeholder="تكلفة النقلة">';
                html += ' <P>جنيها </P><br>';
                html +=
                    '<label> إجمالى التكلفة  : </label><input type="text" readonly id="all_cost" value="0" placeholder="إجمالى التكلفة">';
                html += ' <P>جنيها </P><br>';
            } else if (cost_type == 'qnt') {
                html +=
                    '<label> تكلفة الطن : </label><input type="text" name="cost" id="cost" value="0" placeholder="تكلفة الطن">';
                html += ' <P>جنيها </P><br>';
                html +=
                    '<label> إجمالى التكلفة  : </label><input type="text" readonly id="all_cost" name="all_cost" value="0" placeholder="إجمالى التكلفة">';
                html += ' <P>جنيها </P><br>';
            }
                @else
                              if (cost_type == 'hour') {
                html +=
                    '<label> تكلفة الساعة : </label><input type="text" name="cost" id="cost" value="{{ $car->cost }}" placeholder="تكلفة الساعة">';
                html += ' <P>جنيها </P><br>';
                html +=
                    '<label> إجمالى التكلفة  : </label><input type="text" readonly id="all_cost" value="{{ $car->cost * $car->all_hours }}" placeholder="إجمالى التكلفة">';
                html += ' <P>جنيها </P><br>';
            } else if (cost_type == 'move') {
                html +=
                    '<label> تكلفة النقلة : </label><input type="text" name="cost" id="cost" value="{{ $car->cost }}" placeholder="تكلفة النقلة">';
                html += ' <P>جنيها </P><br>';
                html +=
                    '<label> إجمالى التكلفة  : </label><input type="text" readonly id="all_cost" value="{{ $car->cost * $car->moves }}" placeholder="إجمالى التكلفة">';
                html += ' <P>جنيها </P><br>';
            } else if (cost_type == 'qnt') {
                html +=
                    '<label> تكلفة الطن : </label><input type="text" name="cost" id="cost" value="{{ $car->cost }}" placeholder="تكلفة الطن">';
                html += ' <P>جنيها </P><br>';
                html +=
                    '<label> إجمالى التكلفة  : </label><input type="text" readonly id="all_cost" name="all_cost" value="{{ $car->cost * $car->qnt }}" placeholder="إجمالى التكلفة">';
                html += ' <P>جنيها </P><br>';
            }
                @endif


            $('#costs').html(html);
        }

        // CREATE
        var header = document.getElementById("headers").innerHTML;
        var footer = document.getElementById("footers").innerHTML;
        var dt = $('#table').DataTable({
            dom: 'lBfrtip',
            responsive: true,
            columnDefs: [{
                orderable: false,
                targets: 0
            }],
            "order": [
                [3, "asc"]
            ],
            "language": {
                "searchPlaceholder": "ابحث",
                "sSearch": "",
                "sProcessing": "جاري التحميل...",
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
            buttons: [
                @userType(['accounts','admin'])

                {
                    text: '<i class="fas fa-plus-circle">  تصفية ',
                    className: "dt-button cost-button",
                    action: function(e, dt, node, config) {
                        $('.model').toggleClass("add_model");
                    }
                },
                @enduserType {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> excel',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    },
                },
                {
                    extend: 'print',
                    title: '',
                    text: '<i class="fas fa-print"> طباعة',
                    messageTop: '<img src="/images/print_header2.png" style="position:relative;width:100%;" /><br>' +
                        header,
                    messageBottom: footer,
                    autoPrint: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    },
                    customize: function(win) {
                        $(win.document.body).addClass('body_content');
                        $(win.document.body)
                            .css('font-size', '1rem !important')
                            .css('direction', 'rtl !important')
                            .css('width', '100%');
                        $(win.document.body).find('h1')
                            .css('display', 'none');
                        $(win.document.body).find('.vessel__detail td')
                            .css('background-color', ' #FFF');
                        $(win.document.body).find('.vessel__detail')
                            .css('margin-bottom', ' 5px');
                        $(win.document.body).find('.cars__counts')
                            .css('margin-bottom', ' 5px');
                        $(win.document.body).find('table.dataTable tbody td')
                            .css('padding', ' 0px !important');
                        $(win.document.body).find('table th')
                            .css('text-align', 'center')
                            .css('font-size', '1rem')
                            .css('color', '#000');
                        $(win.document.body).find('dataTable td')
                            .css('text-align', 'center')
                            .css('font-size', '1rem')
                            .css('color', '#fff');
                        $(win.document.body).find('.vessel__detail')
                            .css('font-size', '1rem !important');
                        $(win.document.body).find('.cars__counts')
                            .css('font-size', '1rem !important');
                        $(win.document.body).find('.dataTable   th')
                            .css('border', '1px solid #000');
                        $(win.document.body).find(
                                '.vessel__detail   th')
                            .css('border', '1px solid #000');
                        $(win.document.body).find('.cars__counts   th')
                            .css('border', '1px solid #000');
                    }
                },
            ]
        });
        $("#btn-save").click(function(e) {

            $("#myform").validate({
                rules: {
                    all_hours: {
                        required: true
                    },
                    moves: {
                        required: true
                    },
                    qnt: {
                        required: true
                    },
                    cost_type: {
                        required: true
                    },
                    cost: {
                        required: true
                    },
                    all_cost: {
                        required: true
                    },
                    wait: {
                        required: true
                    },
                    wait_cost: {
                        required: true
                    }
                },
                highlight: function(input) {
                    $(input).addClass('error_input');
                    $('.bootstrap-select button').css('border', 'red solid 2px');
                },
                unhighlight: function(input) {
                    $(input).removeClass('error_input');
                    $('.bootstrap-select button').css('border', 'black solid 1px');
                },
                errorPlacement: function(error, element) {
                    $(element).append(error);
                },
                submitHandler: function() {

                    e.preventDefault();
                    var formData = {
                        id: $("#id").val(),
                        all_hours: $("#all_hours").val(),
                        moves: $("#moves").val(),
                        qnt: $("#qnt").val(),
                        cost_type: $("#cost_type").val(),
                        cost: $("#cost").val(),
                        wait: $("#wait").val(),
                        wait_cost: $("#wait_cost").val(),
                        all_cost: $("#all_cost").val()
                    };
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr(
                                'content')
                        }
                    });
                    $.ajax({
                        type: "PUT",
                        url: '/cars/' + formData.id,
                        data: formData,
                        dataType: "json",
                        encode: true,
                        error: function(xhr, status, error) {
                            msg = 'اعد المحاولة مرة أخرى';
                            $.alert({
                                title: '',
                                type: 'red',
                                content: msg,
                                icon: 'fa fa-warning'
                            })
                        },
                        success: function(data) {
                            $.confirm({
                                title: ' تم إضافة التكلفة بنجاح',
                                icon: 'fa fa-thumbs-up',
                                content: ' ارجع للخلف',
                                type: 'green',
                                rtl: true,
                                closeIcon: false,
                                draggable: true,
                                dragWindowGap: 0,
                                typeAnimated: true,
                                theme: 'supervan',
                                autoClose: 'okAction|6000',
                                buttons: {
                                    okAction: {
                                        text: 'ارجع للخلف',
                                        action: function() {
                                            $.redirect("" +"/carOwner/{{ $car->car_owner }}/{{ $vessel->vessel_id }}"
                                                , "",
                                                "GET", "");
                                        }
                                    },
                                }
                            })
                        }
                    })
                }
            });
        });
    </script>


@endsection
