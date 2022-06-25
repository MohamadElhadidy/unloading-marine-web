@extends('layouts.app')
@section('title', ' منظومة التفريغ')
@section('style')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection
@section('content')

    <div class="owl-carousel owl-theme" data-aos="fade">
        @foreach ($vessels as $vessel)
            <div class="item">
                <div class="card">

                    <div class="imgBx">
                        @if ($vessel->images)
                            <img src="{{ $vessel->images->link }}">
                        @else
                            <img src="/images/anchor.png">
                        @endif
                    </div>
                    <div class="contentBx">
                        <h2>{{ $vessel->name }}</h2>
                        <div class="size">
                            <h3>الكمية </h3>
                            <span>{{ $vessel->qnt }}</span>
                            <span style=" width: 25px;">طن</span>
                        </div>
                        <div class="size">
                            <h3>الصنف </h3>
                            <span>{{ $vessel->type }}</span>
                        </div>
                        <div class="size">
                            <h3>العميل </h3>
                            <span style=" width: 170px;">{{ $vessel->client }}</span>
                        </div>
                        <div class="size">
                            <h3>بدايه التفريغ </h3>
                            <span style=" width: 170px;">{{ $vessel->start_date }}</span>
                        </div>

                        <a href="vessels/{{ $vessel->vessel_id }}">التقارير</a>

                    </div>

                </div>
            </div>
        @endforeach
    </div>
@endsection



@section('scripts')
    <script src="{{ asset('js/owl.carousel.js') }}"></script>
    <script>
        //channel.publish('greeting', 'hello!');
        $('.owl-carousel').owlCarousel({
            rtl: true,
            loop: true,
            margin: 10,
            nav: true,
            autoplay: true,
            smartSpeed: 1000,
            responsive: {
                0: {
                    items: 1
                },
                700: {
                    items: 2
                },
                1340: {
                    items: 3
                },
                1400: {
                    items: 4
                },
                1500: {
                    items: 5
                }
            }
        })

        var Pchannel = pusher.subscribe('home');
        Pchannel.bind('add-vessel', function(data) {
            location.reload();
        });
    </script>




@endsection
