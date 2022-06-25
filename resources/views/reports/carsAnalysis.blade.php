@extends('layouts.app')
@section('title', 'تقرير السيارات النقل')
@section('style')

    <link href="{{ asset('css/cars.css') }}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
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
            font-size: 2.5rem;
            margin-top: -1.5vh;
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
            margin-right: 2.5vw !important;
        }

        .vessel__detail {
            width: 95vw;
            margin-right: 2.5vw !important;
            text-align: center;
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

        table.dataTable tbody tr:hover {
            background-color: rgb(47, 212, 102);
            cursor: pointer;
        }

        @media print {
            table td {
                font-size: 1rem !important;
            }

            table.dataTable tbody th,
            table.dataTable tbody td {
                padding: 0px !important;
            }

            table.dataTable {
                border-collapse: collapse !important;
            }

            table.dataTable {
                width: 95vw !important;
            }
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
        <table class="vessel__detail">
            <thead>
                <tr>
                    <th>إجمالي السيارات من البدايه</th>
                    @if ($vessel->done == 0)
                        <th> السيارات الحاليه</th>
                    @endif
                    @if ($toktok_cars != 0)
                        <th>سـ توكتوك </th>
                    @endif
                    @if ($qlab_cars != 0)
                        <th> سـ قلاب</th>
                    @endif
                    @if ($company_cars != 0)
                        <th> سـ شركة </th>
                    @endif
                    @if ($grar_cars != 0)
                        <th> سـ جرار </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $total_cars }}</td>
                    @if ($vessel->done == 0)
                        <td>{{ $active_cars }}</td>
                    @endif
                    @if ($toktok_cars != 0)
                        <td>{{ $toktok_cars }}</td>
                    @endif
                    @if ($qlab_cars != 0)
                        <td>{{ $qlab_cars }}</td>
                    @endif
                    @if ($company_cars != 0)
                        <td>{{ $company_cars }}</td>
                    @endif
                    @if ($grar_cars != 0)
                        <td>{{ $grar_cars }}</td>
                    @endif
                </tr>
            </tbody>
        </table>
        <table class="vessel__detail">
            <thead>
                <tr>
                    @if ($total_cost != 0)
                        <th>إجمالي التكلفة</th>
                    @endif
                    <th>إجمالي مقاولين النقل</th>
                    @foreach ($owners as $owner)

                        <th> {{ $owner->car_owner }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    @if ($total_cost != 0)
                        <td>{{ $total_cost }} جنيها</td>
                    @endif
                    <td>{{ $total_owners }}</td>
                    @foreach ($owners as $owner)
                        <td>{{ $owner->vacant }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
    <table id='table'>
        <thead>
            <tr>
                <th>م </th>
                <th>كود </th>
                <th>رقم السيارة</th>
                <th>المقاول/ الشركة</th>
                <th>النوع</th>
                <th>نقلات</th>
                <th>الكميه</th>
                <th>التكلفة</th>
                <th>المتوسط</th>
                <th>دخول</th>
                <th>خروج</th>
                <th>الحالة</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($cars as $car)
                <tr
                    onclick="window.location.href = '{{ url('carAnalysis') }}/{{ $car->car_no }}/{{ $car->id }}' ">
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $car->sn }}</td>
                    <td>{{ $car->car_no }}</td>
                    <td>{{ $car->car_owner }}</td>
                    <td>{{ $car->car_type }}</td>
                    <td>{{ $car->moves_count }}</td>
                    <td>{{ $car->qnts }}</td>
                    <td>{{ $car->all_cost }}</td>
                    <td>{{ $car->avg }}</td>
                    <td>{{ $car->start_date }}</td>
                    <td>{{ $car->exit_date }}</td>
                    <td>{{ $car->status }}</td>
                </tr>
            @endforeach
        </tbody>
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
    <script>
        // CREATE
        var html = document.getElementById("headers").innerHTML;
        var dt = $('#table').DataTable({
            dom: 'lBfrtip',
            responsive: true,
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
                [10, 25, 50, -1],
                [10, 25, 50, "الكل"]
            ],
            buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> excel',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                    },
                },
                {
                    extend: 'print',
                    title: '',
                    text: '<i class="fas fa-print"> طباعة',
                    messageTop: '<img src="/images/print_header.png" style="position:relative;width:100%;" /><br>' +
                        html,
                    autoPrint: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
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
                        $(win.document.body).find('table td')
                            .css('font-size', '1rem !important ');
                        $(win.document.body).find('table.dataTable ')
                            .css('width', '95vw !important');
                        $(win.document.body).find('.vessel__detail')
                            .css('font-size', '1rem !important');
                        $(win.document.body).find('.cars__counts')
                            .css('font-size', '1rem !important');
                        $(win.document.body).find('.dataTable   th')
                            .css('border', '1px solid #000');
                        $(win.document.body).find('.vessel__detail   th')
                            .css('border', '1px solid #000');
                        $(win.document.body).find('.cars__counts   th')
                            .css('border', '1px solid #000');
                    }
                },
            ]
        });
    </script>


@endsection
