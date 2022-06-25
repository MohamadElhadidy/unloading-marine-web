<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>معدلات عملية التفريغ</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('027ae0da475a8bfb329b', {
            cluster: 'eu'
        });
    </script>
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

        .small {
            width: 30vw;
            margin-right: 2.5vw;
            text-align: center;
            display: inline-table
        }

        .small td,
        .small th {
            border: 1px solid #000;
        }

        .small th {
            color: #fff;
        }

        .small td {
            background-color: rgba(255, 255, 255, 0.439);
            color: #000000;
        }

        .blue__detail th {
            background-color: #0a8fdb;
        }

        .orange__detail th {
            background-color: #ff8000;
        }

        .green__detail th {
            background-color: #008000;
        }

        .total__cars__quantity {
            width: 95vw;
            margin-right: 2.5vw;
            text-align: center;
        }

        .total__cars__quantity td,
        .total__cars__quantity th {
            border: 1px solid #000;
        }

        .total__cars__quantity th {
            color: #fff;
            background-color: #008000;
        }

        .total__cars__quantity td {
            background-color: rgba(255, 255, 255, 0.439);
            color: #000000;
        }

        @media only screen and (max-width: 1020px) {
            .small {
                width: 40vw;
                margin-right: 5vw !important;
            }
        }

        @media only screen and (max-width: 750px) {
            .small {
                width: 70vw;
                margin-right: 15vw !important;
            }
        }

        @media only screen and (max-width: 685px) {
            .small {
                width: 90vw;
                margin-right: 2.5vw !important;
            }
        }

    </style>
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper" id="wrapper"></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            loadData()
        });

        function loadData() {
            $('#wrapper').load('/DStats/' + JSON.parse("{{ json_encode($vessel->vessel_id) }}"));
        }
        var Pchannel = pusher.subscribe('stats');
        Pchannel.bind('report', function(data) {
            if (data.message == JSON.parse("{{ json_encode($vessel->vessel_id) }}")) {
                loadData();
            }
        });
    </script>
</body>

</html>
