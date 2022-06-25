<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="https://marine-co.online/ops/favicon.png">
    <title>مُطابقة الكميات مع
        الموازين</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Changa:wght@600&display=swap");

        html,
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Changa", sans-serif;
            font-size: 62.5%;
            font-size: 10px;
            direction: rtl;
            width: 100%;
            height: 100%;
            overflow: visible;
            position: absolute;
        }

        .wrapper {
            position: absolute;
        }

        #myVideo {
            position: fixed;
            z-index: -1;
            /*filter: grayscale();*/
        }

        @media (min-aspect-ratio: 16/9) {
            #myVideo {
                width: 100%;
                height: auto;
            }
        }

        @media (max-aspect-ratio: 16/9) {
            #myVideo {
                width: auto;
                height: 100%;
            }
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

        .cars__count {
            width: 95vw;
            margin-right: 2.5vw !important;
            text-align: center;
        }

        .cars__count th {
            border: 1px solid #000;
            background-color: #FFF8DCA3;
            color: #220acc;
        }

        .cars__count span {
            color: #d71111;
        }


        .total__time {
            width: 95vw;
            margin-right: 2.5vw !important;
            text-align: center;
        }

        .total__time th {
            border: 1px solid #000;
            background-color: #FFF8DCA3;
            color: #660033;
        }

        .total__time span {
            color: #419e3b;
        }

        .total__now {
            width: 95vw;
            margin-right: 2.5vw !important;
            text-align: center;
        }

        .total__now th {
            border: 1px solid #000;
            background-color: #b359008a;
            color: #ffffff;
        }

        .total__now span {
            color: #419e3b;
        }

        .total__now td {
            background-color: #FFFFFF82;
        }

        .total__now .count__moves {
            color: #1919ff;
        }

        .total__now .quantity {
            color: #d71111;
        }

        .total__now .jumbo {
            color: #419e3b;
        }


        .total__quantity {
            width: 95vw;
            margin-right: 2.5vw !important;
            text-align: center;
        }

        .total__quantity td,
        .total__quantity th {
            border: 1px solid #000;
        }

        .total__quantity th {
            color: rgb(255, 255, 255);
            background-color: #00BFFF8A;
        }

        .total__quantity td {
            background-color: #ffffff82;
            color: #000000;
        }

        .qnt {
            background-color: #ffffff82 !important;
            color: #f00 !important;
        }

    </style>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('027ae0da475a8bfb329b', {
            cluster: 'eu'
        });
    </script>
</head>

<body>
    <video autoplay muted loop id="myVideo">
        <source src="{{ asset('images/background.mp4') }}" type="video/mp4">
    </video>
    <div class="wrapper">

        <table class="vessel__detail">
            <thead>
                <tr>
                    <th> اسم الباخره </th>
                    <th>رقم الرصيف </th>
                    <th> الصنف</th>
                    <th> الكمية</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $vessel->name }}</td>
                    <td>{{ $vessel->quay }}</td>
                    <td>{{ $vessel->type }}</td>
                    <td>{{ $vessel->qnt }}</td>
                </tr>
            </tbody>
        </table>

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

        <table class="total__time">
            <thead>
                <tr>
                    <th> مدة التشغيل </th>
                    @if ($days != 0)
                        <th> <span> {{ $days }} يوم</span></th>
                    @endif
                    @if ($hours != 0)
                        <th> <span>{{ $hours }} ساعة </span></th>
                    @endif
                    @if ($minutes != 0)
                        <th> <span>{{ $minutes }} دقيقة </span></th>
                    @endif
                </tr>
            </thead>
        </table>

        <table class="total__now">
            <thead>
                <tr>
                    <th>نقلات وصلت </th>
                    <th>الرصيد الآن </th>
                    @if ($move_sum->total_jumbo != 0)
                        <th>جامبو</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="count__moves">{{ $move_sum->moves_count }}</span></td>
                    <td><span class="count__moves"> { {{ $loading_sum->moves_count }} } </span>
                        <span class="quantity"> { {{ $loading_sum->total_qnt }} } </span>
                        @if ($loading_sum->total_jumbo != 0)
                            <span class="jumbo"> { {{ $loading_sum->total_jumbo }} } </span>
                        @endif
                    </td>
                    @if ($move_sum->total_jumbo != 0)
                        <td><span class="jumbo">{{ $move_sum->total_jumbo }}</span></td>
                    @endif
                </tr>
            </tbody>
        </table>
        <table class="total__quantity">
            <thead>
                <tr>
                    <th>رقم النقلة </th>
                    <th>وقت التحميل</th>
                    <th>باركود</th>
                    <th>رقم السيارة</th>
                    <th>الوزن</th>
                    <th>جامبو</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loadings as $loading)
                    <tr>
                        <td>{{ $total_moves }}</td>
                        <td>{{ $loading->date }}</td>
                        <td>{{ $loading->sn }}</td>
                        <td>{{ $loading->car_no }}</td>
                        @if ($loading->qnt == 0)
                            <td class="qnt">{{ $loading->qnt }}</td>
                        @else
                            <td>{{ $loading->qnt }}</td>
                        @endif
                        <td>{{ $loading->jumbo }}</td>
                        @php
                            $total_moves--;
                        @endphp
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <script>
        var Pchannel = pusher.subscribe('quantity');
        Pchannel.bind('report', function(data) {
            if (data.message == JSON.parse("{{ json_encode($vessel->vessel_id) }}")) {
                location.reload();
            }
        });
    </script>
</body>

</html>
