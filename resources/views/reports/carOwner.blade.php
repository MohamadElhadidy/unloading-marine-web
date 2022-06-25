@extends('layouts.app')
@section('title', '  تقرير  مقاول  ' .$car_owner->car_owner )
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
            width: 50vw !important;
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
    table.dataTable tbody tr:hover {
            background-color: rgb(47, 212, 102);
            cursor: pointer;
        }
    </style>
@endsection
@section('content')

    <div id='headers'>
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
                <th>  اسم المقاول : <span>{{$car_owner->car_owner }}</span></th>
                    @if ($total_cost != 0)
                        <th> إجمالى التكلفة : <span>{{ $total_cost }} </span> جنيها</th>
                    @endif
                    <th> عدد النقلات : <span>{{ $car_owner->count }}</span></th>
                    <th> إجمالى الكمية بالطن : <span>{{ number_format( $car_owner->qnt  , 3) }}</span></th>
                </tr>
            </thead>
        </table>
    </div>
    <table id='table'>
        <thead>
            <tr>
                <th> م</th>
                <th> كود السيارة</th>
                <th> رقم السيارة</th>
                <th> نوع السيارة</th>
                <th>عدد النقلات</th>
                <th>الكمية</th>
                <th>سعر النقلة</th>
                <th>الإجمالى</th>
                <th>انتظارات</th>
                <th>التكلفة</th>
                <th>الإجمالى</th>
            </tr>
        </thead>
        <tbody>
            @php
                $moves_cost = 0;
                $total_moves_cost = 0;
                $wait = 0;
                $wait_cost = 0;
                $total_cost = 0;
            @endphp
            @foreach ($cars as $car)
                <tr    onclick="window.location.href = '{{ url('carAnalysis') }}/{{ $car->car_no }}/{{ $car->id }}' ">
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $car->sn }}</td>
                    <td>{{ $car->car_no }}</td>
                    <td>{{ $car->car_type }}</td>
                    @if ($car->moves == '')
                    <td>{{ $car->moves_count }}</td>
                    @else
                    <td>{{ $car->moves }}</td>
                    @endif
                    <td>{{  number_format( $car->qnts  , 3) }}</td>
                    <td>{{ $car->cost }}</td>
                    @if ($car->moves == '')
                      <td>{{ $car->cost  *   $car->moves_count}}</td>
                       @php
                        $moves_cost = $car->cost  *   $car->moves_count;
                    @endphp
                    @else
                    <td>{{ $car->cost  *   $car->moves}}</td>
                     @php
                        $moves_cost = $car->cost  *   $car->moves;
                    @endphp
                    @endif
                    <td>{{ $car->wait }}</td>
                    <td>{{ $car->wait  *   $car->wait_cost}}</td>
                      @if ($car->moves == '')
                    <td>{{ $car->wait  *   $car->wait_cost  +  $car->cost  *   $car->moves_count }}</td>
                    @php
                        $total_cost += $car->wait  *   $car->wait_cost  +  $car->cost  *   $car->moves_count;
                    @endphp
                    @else
                        <td>{{ $car->wait  *   $car->wait_cost  +  $car->cost  *   $car->moves }}</td>
                         @php
                        $total_cost +=$car->wait  *   $car->wait_cost  +  $car->cost  *   $car->moves;
                    @endphp
                    @endif
                </tr>
                @php
                $total_moves_cost += $moves_cost;
                $wait += $car->wait;
                $wait_cost += $car->wait  *   $car->wait_cost;
            @endphp
            @endforeach
        </tbody>
    <tfoot>

                <tr>
                    <td class="yes">الإجمالى</td>
                    <td  class="noo" ></td>
                    <td  class="noo" ></td>
                    <td  class="noo" ></td>
                    <td  class="noo" ></td>
                    <td class="noo" ></td>
                    <td class="noo" ></td>
                    <td  class="yes">{{  $total_moves_cost}}</td>
                    <td  class="yes">{{  $wait}}</td>
                    <td  class="yes">{{  $wait_cost}}</td>
                    <td  class="yes">{{  $total_cost}}</td>
                </tr>

    </tfoot>
    </table>




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
        // CREATE
        var header = document.getElementById("headers").innerHTML;
        var dt = $('#table').DataTable({
            dom: 'lBfrtip',
            responsive: true,
            searching: true,
            columnDefs: [{
                orderable: false,
                targets: 0
            }],
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
                [15, 25, 50, -1],
                [15, 25, 50, "الكل"]
            ],
            buttons: [
            {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> excel',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    },
                },
                {
                    extend: 'print',
                    footer: true,
                    title: '',
                    text: '<i class="fas fa-print"> طباعة',
                    messageTop: '<img src="/images/own.png" style="position:relative;width:100%;" /><br>' +
                        header,
                    autoPrint: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                    },
                    customize: function(win) {
                    
                    }
                },
            ]
        });
        $(".dataTables_wrapper .dataTables_filter input").prop('disabled', true);      
    </script>


@endsection
