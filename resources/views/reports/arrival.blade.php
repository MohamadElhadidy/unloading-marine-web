@extends('layouts.app')
@section('title', 'تقرير التعتيق بالمخزن')
@section('style')
    <style>
        .header {
            visibility: hidden !important;
            opacity: 0 !important;
        }
#headers{
      visibility: hidden !important;
            opacity: 0 !important;
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
    </style>
    <link href="{{ asset('css/cars.css') }}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
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
        <table class="vessel__detail headerss">
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
</div>
    <div class="wrapper">
        <p style='color:orange;'>{{ $vessel->name }}</p>
        <p class="title"> تقرير التعتيق بالمخزن</p>

        <table id='table'>
            <thead>
                <tr>
                    <th>كود السيارة</th>
                    <th>رقم السيارة</th>
                    <th>وقت التعتيق</th>
                    <th>الكمية</th>
                    <th>جامبو</th>
                    <th>اسم الصنف </th>
                    <th>رقم المخزن </th>
                    <th>اسم الموظف</th>
                    <th>ملاحظات</th>
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
                        var html = document.getElementById("headers").innerHTML;

        // CREATE
        @php
        $url = 'DArrival/' . request()->id;
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
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'qnt',
                    name: 'qnt'
                },
                {
                    data: 'jumbo',
                    name: 'jumbo'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'store_no',
                    name: 'store_no'
                },
                {
                    data: 'ename',
                    name: 'ename'
                },
                {
                    data: 'notes',
                    name: 'notes'
                },

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
                    messageTop: '<img src="/images/arrive.png" style="position:relative;width:100%;" /><br>' +
                        html,
                    autoPrint: true,
                    title:'',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    },
                    customize: function(win) {
                        $(win.document.body).find('table.dataTable tbody td')
                            .css('text-align', 'center')
                            .css('padding', '0px')
                            .css('font-size', '1rem');
                        $(win.document.body).find('table.dataTable thead th')
                            .css('text-align', 'center')    
                            .css('padding', '0px')
                            .css('font-size', '1rem');
                            $(win.document.body).find('table.dataTable')
                            .css('width', '100%');

                    }
                },
            ]
        });
        var Pchannel = pusher.subscribe('arrival');
        Pchannel.bind('report', function(data) {
            if (data.message == JSON.parse("{{ json_encode($vessel->vessel_id) }}")) {
                $('#table').DataTable().ajax.reload();
            }
        });
    </script>


@endsection
