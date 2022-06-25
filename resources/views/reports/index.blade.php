@extends('layouts.app')
@section('title', 'منظومة التفريغ')
@section('style')
    <link href="{{ asset('css/list.css') }}" rel="stylesheet">
    <style>
        .header {
            visibility: hidden !important;
            opacity: 0 !important;
        }

    </style>
@endsection
@section('content')
    <table class="vessel__detail">
        <tr>
            <td> اسم الباخره : {{ $vessel->name }}</td>
            <td> رقم الرصيف : {{ $vessel->quay }}</td>
        </tr>
        <tr>
            <td> الصنف : {{ $vessel->type }}</td>
            <td> الكمية : {{ $vessel->qnt }}</td>
        </tr>
        <tr>
            <td> العميل : {{ $vessel->client }}</td>
            <td> وقت البداية : {{ $vessel->start_date }}</td>
        </tr>
    </table>


    <div class="list">
        <ul>
          <!--  @canView('cars_analysis')
            <li><a href="/carsAnalysis/{{ $vessel->vessel_id }}"><i class="fas fa-file-invoice-dollar"></i> تقرير السيارات
                    النقل
                </a> </li>
            @endcanView-->
     @canView('car_owners')
            <li><a href="/carOwners/{{ $vessel->vessel_id }}"><i class="fas fa-file-invoice-dollar"></i>  حساب تكاليف النقل
                </a> </li>
            @endcanView
            @canView('stats')
            <li><a href="/RStats/{{ $vessel->vessel_id }}"><i class="fas fa-chart-line"></i> معدلات عملية التفريغ </a></li>
            @endcanView
            {{-- @canView('analysis')
            <li><a href="/DAnalysis/{{ $vessel->vessel_id }}"><i class="fas fa-poll"></i> بيان تحليلي بالكميات</a>
            </li>
            @endcanView --}}
            @canView('travels')
            <li><a href="/RTravels/{{ $vessel->vessel_id }}"><i class="fas fa-clock"></i> تقرير رحلات السيارات </a></li>
            @endcanView
            @canView('cars')
            <li><a href="/Rcars/{{ $vessel->vessel_id }}"><i class="fas fa-credit-card"></i> تقرير السيارات المُفعلة
                </a>
            </li>
            @endcanView
            @canView('stops')
            <li><a href="/RStops/{{ $vessel->vessel_id }}"><i class="fa fa-cloud-rain"></i> تقرير التوقفات </a></li>
            @endcanView
        </ul>
        <ul>
       

            {{-- @canView('quantity')
            <li> <a href="/RQuantity/{{ $vessel->vessel_id }}"><i class="fas fa-balance-scale"></i> مُطابقة الكميات مع
                    الموازين</a></li>
            @endcanView --}}
            @canView('loading')
            <li><a href="/RLoading/{{ $vessel->vessel_id }}"><i class="fas fa-warehouse"></i> تقرير التحميل على الرصيف
                </a>
                @endcanView
                @canView('direct')
            <li><a href="/RDirect/{{ $vessel->vessel_id }}"><i class="fas fa-warehouse"></i> تقرير الصرف المباشر
                </a>
                @endcanView
            </li>
            @canView('arrival')
            <li><a href="/RArrival/{{ $vessel->vessel_id }}"><i class="fas fa-truck-loading"></i> تقرير التعتيق فى المخزن
                </a>
            </li>
            @endcanView



            @canView('minus')
            <li><a href="/RMinus/{{ $vessel->vessel_id }}"><i class="fas fa-user-minus"></i> تقرير الخصومات للسيارات

                </a></li>
            @endcanView
        </ul>
    </div>
@endsection



@section('scripts')
    <script>


    </script>
@endsection
