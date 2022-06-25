@extends('layouts.app')
@section('title', ' الإشعارات')
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

        .model {
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

        .add_model {
            visibility: visible;
            opacity: 1;
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

        form .dt-button {
            padding: 15px 25px;
            margin-top: 25px;
        }

        .select2-results__option {
            font-size: 1.6rem;
            text-align: center;
        }

        @media only screen and (max-width: 990px) {
            .dt-button {
                padding: 10px !important;
                margin-bottom: 5px !important;
            }
        }

        @media only screen and (max-width: 785px) {

            form input[type="text"],
            form input[type="password"],
            .select2 {
                display: inline-block !important;
                width: 40vw !important;
            }

            form {
                align-items: center;
            }

            .buttons-excel,
            .buttons-print {
                display: none;
            }

            .pagination {
                font-size: 1.2rem;
            }
        }

        @media only screen and (max-width: 665px) {

            form input[type="text"],
            form input[type="password"],
            .select2 {
                display: inline-block !important;
                width: 60vw !important;
            }

            .dt-buttons {
                position: relative !important;
            }

            input {
                width: 70vw !important;
            }

            form {
                height: 55vh !important;
            }
        }

        @media only screen and (max-width: 580px) {

            form input[type="text"],
            form input[type="password"],
            .select2 {
                display: inline-block !important;
                width: 50vw !important;
            }

            form {
                right: 5vw !important;
                width: 90vw !important;
            }
        }

        @media only screen and (max-width: 420px) {

            form input[type="text"],
            form input[type="password"] {
                display: inline-block !important;
                width: 75vw !important;
                right: 5vw !important;
            }

            .select2 {
                display: inline-block !important;
                width: 75vw !important;
            }
        }

    </style>
@endsection
@section('content')


    <table id='table'>
        <thead>
            <tr>
                <th>المستخدم</th>
                <th>المحتوى</th>
                <th>الوقت</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($notifications as $notification)
                @if ($notification->data['user_id'] != '1' and $notification->notifiable_id != 'it')
                    <tr>
                        <td>{{ $notification->notifiable_id }}</td>
                        <td>{{ $notification->data['message'] }}</td>
                        <td>{{ $notification->created_at }}</td>
                    </tr>
                @endif

            @endforeach
        </tbody>
    </table>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap.min.js"></script>

    <script>
        // CREATE
        var dt = $('#table').DataTable({
            dom: 'lBfrtip',
            responsive: true,
            columnDefs: [{
                orderable: false,
                targets: 0
            }],
            "order": [
                [2, "desc"]
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
            ]
        });
    </script>


@endsection
