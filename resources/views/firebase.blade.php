<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Document</title>
</head>
<body>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <button onclick="startFCM()" class="btn btn-danger btn-flat">Allow notification
                </button>

                <div class="card mt-3">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="{{ route('send.web-notification') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Message Body</label>
                                <textarea class="form-control" name="body"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Send Notification</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>

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

        function startFCM() {
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
                            alert('Token stored.');
                        },
                        error: function(error) {
                            alert(error);
                        },
                    });

                }).catch(function(error) {
                    alert(error);
                });
        }

        messaging.onMessage(function(payload) {
            const title = 'منظومة التشغيل';
            const options = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(title, options);
        });
    </script>

</body>
</html>
