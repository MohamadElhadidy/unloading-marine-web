<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=.2">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="https://marine-co.online/ops/favicon.png">
    <title> بيان تحليلي بكميات التحميل</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Changa:wght@600&display=swap");

        :root {
            --bs-font-sans-serif: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            --bs-font-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            --bs-gradient: linear-gradient(180deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0));
            --bs-body-font-size: 13px;
            --bs-body-font-weight: 600;
            --bs-body-line-height: 1.5;
            --bs-body-color: #212529;
            --bs-body-bg: #fff;
        }

        body {
            direction: rtl;
            margin: 2px;
            font-family: "Changa", sans-serif;
            font-size: var(--bs-body-font-size);
            font-weight: var(--bs-body-font-weight);
            line-height: var(--bs-body-line-height);
            color: var(--bs-body-color);
            text-align: var(--bs-body-text-align);
            background-color: var(--bs-body-bg);
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: transparent;
            text-align: center;
        }

        table td,
        table th {
            border: solid black 1px;
            text-align: center;
            font-weight: bold;
        }

        .no-border {
            border-bottom: 1px solid Transparent !important;
            ;
        }

        .right-border {
            border-right: 1px solid black !important;
        }

        .table>:not(caption)>*>* {
            background-color: var(--bs-table-bg);
            border-bottom-width: 1px;
            box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
        }

        table {
            caption-side: bottom;
            border-collapse: collapse;
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-accent-bg: transparent;
            --bs-table-striped-color: #212529;
            --bs-table-striped-bg: rgba(0, 0, 0, 0.05);
            --bs-table-active-color: #212529;
            --bs-table-active-bg: rgba(0, 0, 0, 0.1);
            --bs-table-hover-color: #212529;
            --bs-table-hover-bg: rgba(0, 0, 0, 0.075);
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            vertical-align: top;
            border-color: #dee2e6;
        }


        @media print {
            @page {
                margin-bottom: landscape !important
            }

            table {
                margin-right: 2.5vw;
                width: 95vw !important;
            }

            table td,
            table th {
                font-size: .5rem !important;
                padding: 0px !important;
            }
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

</body>
<script>
    var Pchannel = pusher.subscribe('analysis');
    Pchannel.bind('report', function(data) {
        if (data.message == JSON.parse("{{ request()->id }}")) {
            location.reload();
        }
    });
</script>

</html>
@php
$vessel_id = request()->id;
$timezone = date_default_timezone_set('Africa/Cairo');

$con = mysqli_connect('localhost', 'user', 'password', 'shipping');

$query1 = "select * from vessels_log  WHERE vessel_id = '$vessel_id'  ";
$result1 = mysqli_query($con, $query1);

if (mysqli_num_rows($result1) > 0) {
    $row1 = mysqli_fetch_assoc($result1);
    $vessel = $row1['name'];
    $type = $row1['type'];
    $quay = $row1['quay'];
    $client = $row1['client'];
    $start_date = $row1['start_date'];
    $qnt = $row1['qnt'];
    $done = $row1['done'];
}

if ($done == 0) {
    $search = ' done = 0 AND';
} else {
    $search = '';
}

$countAll = 0;
$query2 = "select * from cars where   vessel_id = '$vessel_id'   group by car_no  ";
$result2 = mysqli_query($con, $query2);

if (mysqli_num_rows($result2) > 0) {
    while ($row2 = mysqli_fetch_assoc($result2)) {
        $countAll++;
    }
}

$query3 = "select count(*) as count from cars where done =0 AND  vessel_id = '$vessel_id'     ";
$result3 = mysqli_query($con, $query3);

if (mysqli_num_rows($result3) > 0) {
    $row3 = mysqli_fetch_assoc($result3);
    $countNow = $row3['count'];
}

$queryMove = "select count(*) as count from move where arrival =1 AND  vessel_id = '$vessel_id'     ";
$resultMove = mysqli_query($con, $queryMove);

if (mysqli_num_rows($resultMove) > 0) {
    $rowMove = mysqli_fetch_assoc($resultMove);
    $countMove = $rowMove['count'];
}

$countTok = 0;
$queryTok = "select * from cars where   vessel_id = '$vessel_id' AND  car_type='سيارة توكتوك'   GROUP BY car_no";
$resultTok = mysqli_query($con, $queryTok);

if (mysqli_num_rows($resultTok) > 0) {
    while (mysqli_fetch_assoc($resultTok)) {
        $countTok++;
    }
}

$countCom = 0;
$queryCom = "select * from cars where vessel_id = '$vessel_id' AND car_type='سيارة الشركة'   GROUP BY car_no";
$resultCom = mysqli_query($con, $queryCom);

if (mysqli_num_rows($resultCom) > 0) {
    while (mysqli_fetch_assoc($resultCom)) {
        $countCom++;
    }
}

$countAlab = 0;
$queryAlab = "select * from cars where vessel_id = '$vessel_id' AND  car_type='سيارة قلاب'   GROUP BY car_no";
$resultAlab = mysqli_query($con, $queryAlab);

if (mysqli_num_rows($resultAlab) > 0) {
    while (mysqli_fetch_assoc($resultAlab)) {
        $countAlab++;
    }
}

$countGrar = 0;
$queryGrar = "select * from cars where  vessel_id = '$vessel_id' AND  car_type='سيارة جرار'    GROUP BY car_no";
$resultGrar = mysqli_query($con, $queryGrar);

if (mysqli_num_rows($resultGrar) > 0) {
    while (mysqli_fetch_assoc($resultGrar)) {
        $countGrar++;
    }
}

$cars = [];
$sns = [];
$i = 0;
$query4 = "select  * from cars where   vessel_id = '$vessel_id'  GROUP BY  car_no order by  id ASC ,  car_owner    ";
$result4 = mysqli_query($con, $query4);

while ($row4 = mysqli_fetch_assoc($result4)) {
    $cars['car_no'][$i] = $row4['car_no'];
    $car_no = $cars['car_no'][$i];
    $query5 = "select  * from cars where   vessel_id = '$vessel_id'  AND   car_no = '$car_no'  ";
    $result5 = mysqli_query($con, $query5);
    $sns[0] = ' ';
    while ($row5 = mysqli_fetch_assoc($result5)) {
        $sns[0] .= $row5['sn'] . ' ';
    }

    $cars['mehwer'][$i] = $row4['mehwer'];
    $cars['vacant'][$i] = $row4['vacant'];
    $cars['owner'][$i] = $row4['car_owner'];
    $cars['type'][$i] = $row4['car_type'];
    $cars['sn'][$i] = $sns[0];
    $i++;
}

echo '<img src="/images/print_header3.png" class="center" style=" display: block; margin-left: auto; margin-right: auto; width:100%;">';

$ouput111 = "<table class='table'>
        <thead style='background-color: #808080;color:white;font-size:13px;'>
            <td>اسم الباخره	</td>
            <td>رقم الرصيف	</td>
            <td>الصنف	</td>
            <td>الكمية</td>
           </thead>

         <thead style='background-color: #FFF8DC;color: #660033;'>
            <td>$vessel</td>
            <td>$quay</td>
            <td>$type</td>
            <td>$qnt</td>
            ";

$ouput111 .= '</thead>
        </table>';

$ouput222 = " <table class='table'>

         <thead style='background-color: #FFF8DC;color: #660033;'>
            <td>إجمالي السيارات :  <span style='color:#ff1a1a'> $countAll</span></td>
             ";
if ($countTok == 0) {
    $g = 0;
} else {
    $ouput222 .= "
            <td>سيارة توكتوك :  <span style='color:#ff1a1a;'>$countTok</span></td>
            ";
}
if ($countAlab == 0) {
    $g = 0;
} else {
    $ouput222 .= "
            <td>سيارة قلاب :  <span style='color:#ff1a1a;'>$countAlab</span></td>
            ";
}
if ($countCom == 0) {
    $g = 0;
} else {
    $ouput222 .= "
            <td>سيارة شركة :  <span style='color:#ff1a1a;'>$countCom</span></td>
            ";
}
if ($countGrar == 0) {
    $g = 0;
} else {
    $ouput222 .= "
            <td>سيارة جرار :  <span style='color:#ff1a1a;'>$countGrar</span></td>
            ";
}
$ouput222 .= "
            <td> عدد النقلات :  <span style='color:#ff1a1a;'>$countMove</span></td>


            ";

$ouput222 .= '</thead>
        </table>';

$ouput000 = ' <table class="table">
        <tbody style="background-color: #fff;">
            <td> المقاول/الشركة</td>
            ';
for ($i = 0; $i < $countAll; $i++) {
    $owner = $cars['owner'][$i];
    $ouput000 .= "<td>$owner</td>";
}

$ouput000 .= '</tbody>
        <tbody>
            <td>النوع</td>';

for ($i = 0; $i < $countAll; $i++) {
    $type = $cars['type'][$i];
    $ouput000 .= "<td>$type</td>";
}

$ouput000 .= ' </tbody><tbody><th>كود السيارة</th>';

for ($i = 0; $i < $countAll; $i++) {
    $sn = $cars['sn'][$i];
    $ouput000 .= "<td>$sn</td>";
}

$ouput000 .= ' </tbody>
       <thead style="background-color: #7EE8F2;"><th>رقم السيارة</th>';

for ($i = 0; $i < $countAll; $i++) {
    $car_no = $cars['car_no'][$i];
    $ouput000 .= "<td>$car_no</td>";
}

$ouput000 .= ' </thead>
        <tbody style="background-color: #fff;">
            <td>المحاور</td>
           ';
for ($i = 0; $i < $countAll; $i++) {
    $mehwar = $cars['mehwer'][$i];
    $ouput000 .= '<td>' . number_format((float) $mehwar, 3, '.', '') . '</td>';
}

$ouput000 .= ' </tbody>
        <tbody>
            <td>المسموح</td>
            ';
for ($i = 0; $i < $countAll; $i++) {
    $allow = $cars['mehwer'][$i] * 0.05;
    $ouput000 .= "<td>$allow</td>";
}

$ouput000 .= ' </tbody>
        <tbody>
            <td>الاجمالى</td>
           ';
for ($i = 0; $i < $countAll; $i++) {
    $allow = $cars['mehwer'][$i] * 0.05;
    $total = $cars['mehwer'][$i] + $allow;
    $ouput000 .= "<td>$total</td>";
}

$ouput000 .= ' </tbody>
        <tbody style="background-color: #fff;">
            <td>الوزن الفارغ</td>
            ';
for ($i = 0; $i < $countAll; $i++) {
    $vacant = $cars['vacant'][$i];
    $ouput000 .= '<td>' . number_format((float) $vacant, 3, '.', '') . '</td>';
}

$ouput000 .= '</tbody>

        <tbody style="background-color: #38c728;">
            <td>حد الحموله</td>
            ';
for ($i = 0; $i < $countAll; $i++) {
    $allow = $cars['mehwer'][$i] * 0.05;
    $total = $cars['mehwer'][$i] + $allow;
    $pure = $total - $cars['vacant'][$i];
    $ouput000 .= "<td>$pure</td>";
}

$ouput000 .= '</tbody>';

$counts = [];
$max = '';
for ($i = 0; $i < $countAll; $i++) {
    $car_no = $cars['car_no'][$i];
    $query7 = "select  * from cars where   vessel_id = '$vessel_id'  AND   car_no = '$car_no'  ";
    $result7 = mysqli_query($con, $query7);
    $counts['move'][$i] = 0;
    while ($row7 = mysqli_fetch_assoc($result7)) {
        $sn = $row7['sn'];
        $query5 = "select count(*) as count from move where sn = '$sn' AND  vessel_id = '$vessel_id' AND arrival ='1' ";
        $result5 = mysqli_query($con, $query5);
        $row5 = mysqli_fetch_assoc($result5);
        $counts['move'][$i] += $row5['count'];
    }
}
if (isset($counts['move'])) {
    $max = max($counts['move']);
} else {
    $max = 0;
}

$qnts = [];
$totals = [];
for ($i = 0; $i < $countAll; $i++) {
    $car_no = $cars['car_no'][$i];
    $query5 = "select  * from cars where   vessel_id = '$vessel_id'  AND   car_no = '$car_no'  ";
    $result5 = mysqli_query($con, $query5);
    while ($row5 = mysqli_fetch_assoc($result5)) {
        $sn = $row5['sn'];
        $qnts['sn'][$i] = $sn;
        $totals['sn'][$i] = $sn;
        $query6 = "select  * from move where  sn ='$sn' AND  vessel_id = '$vessel_id'   ";
        $result6 = mysqli_query($con, $query6);
        $j = 0;
        $totals['qnt'][$i] = 0;
        while ($row6 = mysqli_fetch_assoc($result6)) {
            $qnts['qnt'][$j][$i] = $row6['qnt'];
            $totals['qnt'][$i] += $row6['qnt'];
            $j++;
        }
    }
}

for ($i = 0; $i < $max; $i++) {
    $ouput000 .= '<tbody style="background-color:#fff">';
    if ($i == '0') {
        $ouput000 .= '<td>وزن التحميل</td>';
    } else {
        $ouput000 .= '<td class="no-border"></td>';
    }
    for ($j = 0; $j < $countAll; $j++) {
        $qnt = $qnts['qnt'][$i][$j] ?? null;
        $ouput000 .= "<td>$qnt</td>";
    }
    $ouput000 .= '</tbody>';
}

$ouput000 .= '  <tbody style="background-color: #FFFF7E;">
            <td>الاجمالى</td>';
for ($i = 0; $i < $countAll; $i++) {
    $all = $totals['qnt'][$i];
    $ouput000 .= "<td>$all</td>";
}
$ouput000 .= '  </tbody>
        <tbody style="background-color: #FB637E;">
            <td>عدد النقلات</td>
           ';
for ($i = 0; $i < $countAll; $i++) {
    $move = $counts['move'][$i];
    $ouput000 .= "<td>$move</td>";
}

$ouput000 .= '   </tbody>


    </table>';

echo $ouput111;
echo $ouput222;
echo $ouput000;
@endphp
