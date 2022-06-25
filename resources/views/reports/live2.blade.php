@foreach ($vessels as $vessel)
    @canView($vessel->vessel_id)
    <table class="blue__detail small" onclick="javascript:location.href='vessels/{{ $vessel->vessel_id }}'">
        <tr>
            <td class='head'> اسم الباخره </td>
            <td>{{ $vessel->name }} </td>
        </tr>
        <tr>
            <td class='head'>العميل</td>
            <td>{{ $vessel->client }}</td>
        </tr>

        <tr>
            <td class='head'>الصنف</td>
            <td>{{ $vessel->type }}</td>
        </tr>
        
        <tr>
            <td class='head'>الكمية</td>
            <td> {{ $vessel->qnt }} طن </td>
        </tr>
        <tr>
            <td class='head'>رقم الرصيف</td>
            <td>{{ $vessel->quay }}</td>
        </tr>
        @if ($vessel->hours !=0)   
        <tr>
            <td class='head'>مُدة التشغيل</td>
            <td style="height:25px;">
                <span> {{ $vessel->hours }} ساعة </span> 
        </tr>
        @endif
        @if ($vessel->car_count !=0)    
        <tr>
            <td class='head'> السيارات الحالية</td>
            <td class="moves">{{ $vessel->car_count }}
            </td>
        </tr>
        @endif
        @if ($vessel->arrival_moves  != 0)
        <tr>
            <td class='head'>نقلات تخزين</td>
            <td class="moves">{{ $vessel->arrival_moves }}
            </td>
        </tr>
        @endif
        @if ($vessel->direct_moves  != 0)
        <tr>
            <td class='head'>نقلات صرف</td>
            <td class="moves">{{ $vessel->direct_moves }}
            </td>
        </tr>
        @endif
        @if ($vessel->all_qnt  != 0)
            <tr>
            <td class='head'> الرصيد الأن </td>
            <td class="qnt"><span style="color: red;">({{ $vessel->all_qnt }})</span>
                <span>({{ $vessel->all_count }})</span>
            </td>
        </tr> 
        @endif
    
    </table>
    @endcanView
@endforeach
