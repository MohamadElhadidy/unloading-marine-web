@extends('layouts.app')
@section('title', ' منظومة التفريغ')
@section('style')
    <link href="{{ asset('css/live.css') }}" rel="stylesheet">

@endsection


@section('content')

    <div class="wrapper" id="wrapper"></div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            loadData();
        });

        function loadData() {
            $('#wrapper').load('/RLive');
        }
        var Pchannel = pusher.subscribe('live');
        Pchannel.bind('add-vessel', function(data) {
            loadData();
        });
    </script>
@endsection
