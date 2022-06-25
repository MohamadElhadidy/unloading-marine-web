@extends('layouts.app')
@section('title', ' تقرير السيارات المُفعلة')
@section('style')
    <style>
        .header {
            visibility: hidden !important;
            opacity: 0 !important;
        }

    </style>
    <link href="{{ asset('css/cars.css') }}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="wrapper">
        <div>
            <p style='color:orange;'>{{ $vessel->name }}</p>
            <p class="title"> تقرير السيارات المُفعلة</p>
            <table class="cars__count">
                <thead>
                    <tr>
                        <th> إجمالي السيارات من البدايه : <span>{{ $total_cars }}</span></th>
                        @if ($vessel->done == 0)
                            <th> السيارات الحاليه : <span>{{ $active_cars }}</span></th>
                        @endif
                        @if ($toktok_cars != 0)
                            <th> سـ توكتوك : <span>{{ $toktok_cars }}</span></th>
                        @endif
                        @if ($qlab_cars != 0)
                            <th> سـ قلاب : <span>{{ $qlab_cars }}</span></th>
                        @endif
                        @if ($company_cars != 0)
                            <th> سـ شركة : <span>{{ $company_cars }}</span></th>
                        @endif
                        @if ($grar_cars != 0)
                            <th> سـ جرار : <span>{{ $grar_cars }}</span></th>
                        @endif
                    </tr>
                </thead>
            </table>
        </div>
        <table id='table'>
            <thead>
                <tr>
                    <th>كارت باركود</th>
                    <th>رقم وش السيارة</th>
                    <th>رقم مقطورة السيارة</th>
                    <th>النوع</th>
                    <th>المقاول/الشركة </th>
                    <th>اسم السائق</th>
                    <th>وقت التسجيل</th>
                </tr>
            </thead>
        </table>
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
    <script>
        // CREATE
        @php
        $url = 'DCars/' . request()->id;
        @endphp
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
            processing: true,
            serverSide: true,
            //bLengthChange: false,
            ajax: '{{ url($url) }}',
            columns: [{
                    data: 'sn',
                    name: 'sn'
                },
                {
                    data: 'car_no',
                    name: 'car_no'
                },
                {
                    data: 'car_no2',
                    name: 'car_no2'
                },
                {
                    data: 'car_type',
                    name: 'car_type'
                },
                {
                    data: 'car_owner',
                    name: 'car_owner'
                },
                {
                    data: 'car_driver',
                    name: 'car_driver'
                },
                {
                    data: 'start_date',
                    name: 'start_date'
                }
            ],
            buttons: [

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
                            .css('direction', 'rtl !important')
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
        var Pchannel = pusher.subscribe('car');
        Pchannel.bind('report', function(data) {
            if (data.message == JSON.parse("{{ json_encode($vessel->vessel_id) }}")) {
                $('#table').DataTable().ajax.reload();
            }
        });
    </script>


@endsection
