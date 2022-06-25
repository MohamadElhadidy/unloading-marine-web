<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/gif" sizes="16x16">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/layout.css') }}" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet">
    <style>
        .toast {
            font-size: 1.5rem;
        }

        .toast-success {
            background-color: #278a27 !important;
        }

    </style>
    @yield('style')
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('027ae0da475a8bfb329b', {
            cluster: 'eu'
        });
    </script>
</head>

<body>
    <div class="header">
        <img src="{{ asset('images/logo.png') }}">
        <p> منظومة التفريغ الجديدة</p>
    </div>

    <video autoplay muted loop id="myVideo">
        <source src="{{ asset('images/background.mp4') }}" type="video/mp4">
    </video>
    @auth
        <div class="nav">
            <div class="container_nav">
                <div id="mainListDiv" class="main_list">
                    <ul class="navlinks">
                        @canView('live')
                        <li><a href="http://ops.marine-co.live/"> <i class="fas fa-podcast" style="color: red"></i> Live </a></li>
                        @endcanView
                        <li><a href="/">  الرئيسية </a></li>
                        @userType(['admin'])
                        <li><a href="/admin">لوحة التحكم</a></li>
                        <li><a href="/notifications">الإشعارات</a></li>
                        @enduserType
                        @userType(['vessel', 'admin'])
                        <li><a href="/management">متابعة السفن</a></li>
                        @enduserType
                        @canView('archive')
                        <li><a href="/archive">أرشيف السفن</a></li>
                        @endcanView
                        {{-- @NoAccessWithType(['app','client'])
                        <li><a href="/">السفن الحاليه</a></li>
                        @endNoAccessWithType --}}
                        {{-- <li>
                            <div class="badge">
                                <i class="fa fa-bell"></i>
                                <span class="notification">10</span>
                            </div>
                        </li> --}}
                        <li><a href="/logout">تسجيل الخروج</a></li>
                    </ul>
                </div>
                <div class="logo">
                    <a href="/">الشَركةُ البَحرية</a>
                </div>
                <span class="navTrigger">
                    <i></i>
                    <i></i>
                    <i></i>
                </span>
            </div>
        </div>
    @endauth

    @yield('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/715e93c83e.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>

    {{-- <script src="{{ asset('js/toastr.js') }}"></script> --}}
    <script>
        /* toastr.options.closeButton = true;
                                                toastr.options.closeDuration = 6000;
                                                toastr.options.progressBar = true;
                                                toastr.options.rtl = true;
                                                toastr.options.onclick = function() {
                                                    alert('clicked');
                                                }

                                                toastr.options.positionClass = 'toast-bottom-right';
                                                const ably = new Ably.Realtime('{{ env('ABLY_KEY') }}');
                                                const channel = ably.channels.get('notify');

                                                channel.subscribe('greeting', (message) => {
                                                    toastr.success(message.data, 'منظومة التفريغ الجديدة', {
                                                        timeOut: 5000
                                                    })
                                                });*/

        $('.navTrigger').click(function() {
            $(this).toggleClass('active');
            $("#mainListDiv").toggleClass("show_list");
            $(".logo").toggleClass("logo_none");
            $("#mainListDiv").fadeIn();

        });
    </script>
    <script src="https://kit.fontawesome.com/715e93c83e.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/mgalante/jquery.redirect@master/jquery.redirect.js"></script>



    {{-- <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>

    <script>
        var firebaseConfig = {
            apiKey: "AIzaSyD7L3wopmKrU5bsxDnFDVi80MH-xIjyJvc",
            authDomain: "shipping-8eaed.firebaseapp.com",
            projectId: "shipping-8eaed",
            storageBucket: "shipping-8eaed.appspot.com",
            messagingSenderId: "1000456317200",
            appId: "1:1000456317200:web:b49955b30070fd49b25d95"
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();


        $(document).ready(function() {
            messaging
                .requestPermission()
                .then(function() {
                    return messaging.getToken()
                })
                .then(function(response) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ route('store.token') }}',
                        type: 'POST',
                        data: {
                            token: response
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            console.log('success');
                        },
                        error: function(error) {
                            console.log('error');
                        },
                    });

                }).catch(function(error) {
                    console.log('error');
                });
        });

        messaging.onMessage(function(payload) {
            const title = payload.notification.title;
            const options = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(title, options);
        });
    </script> --}}
    @yield('scripts')

</body>

</html>
