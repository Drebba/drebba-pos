@foreach($sells as $key=> $sell)
    <table class="table table-bordered wiz-table mw-col-width-skip-first">
        <thead>
        <tr class="bg-secondary text-white">
            <th colspan="9">
                @if(Request::get('by_duration') == 'Y-m-d' or Request::get('by_duration') == '')
                @formatdate($key)
                @else
                    {{$key}}
                @endif
            </th>
        </tr>
        <tr class="bg-secondary text-white">
            <th>{{__('pages.sl')}}</th>
            <th class="text-center">{{__('pages.invoice_id')}}</th>
            @if(Request::get('by_duration') != 'Y-m-d')
            <th class="text-center">{{__('pages.sell_date')}}</th>
            @endif

            <th class="text-center">{{__('pages.sub_total')}}</th>
            <th class="text-center">{{__('pages.discount')}}</th>
            <th class="text-center">{{__('pages.grand_total')}}</th>
            <th class="text-center">{{__('pages.paid_amount')}}</th>
            <th class="text-center">{{__('pages.due_amount')}}</th>
        </tr>
        </thead>
        <tbody>
        @php
            $total_sub_total = 0;
            $total_discount = 0;
            $total_grand_total_price = 0;
            $total_paid_amount = 0;
            $total_due_amount = 0;
        @endphp
        @foreach($sell as $key => $single_sell)
            <tr>
                <td>{{$key + 1}}</td>
                <td class="text-center">
                    <a href="{{route('sell.show', [$single_sell->id])}}" target="_blank" class="text-primary">{{$single_sell->invoice_id}}</a>
                </td>
                @if(Request::get('by_duration') != 'Y-m-d')
                <td class="text-center">@formatdate($single_sell->sell_date)</td>
                @endif

                <td class="text-center"> {{get_option('app_currency')}}{{number_format($single_sell->sub_total, 2)}} </td>
                <td class="text-center"> {{get_option('app_currency')}}{{number_format($single_sell->discount, 2)}} </td>
                <td class="text-center"> {{get_option('app_currency')}}{{number_format($single_sell->grand_total_price, 2)}} </td>
                <td class="text-center"> {{get_option('app_currency')}}{{number_format($single_sell->paid_amount, 2)}} </td>
                <td class="text-center"> {{get_option('app_currency')}}{{number_format($single_sell->due_amount, 2)}} </td>
            </tr>

            @php
                $total_sub_total += $single_sell->sub_total;
                $total_discount += $single_sell->discount;
                $total_grand_total_price += $single_sell->grand_total_price;
                $total_paid_amount += $single_sell->paid_amount;
                $total_due_amount += $single_sell->due_amount;
            @endphp
        @endforeach


        <tr>

             <td colspan="{{Request::get('by_duration') == 'Y-m-d' ? 2 : 3}}" class="text-right pr-3"><strong>Total</strong></td>
            <td><strong>{{get_option('app_currency')}}{{number_format($total_sub_total, 2)}}</strong></td>
            <td><strong>{{get_option('app_currency')}}{{number_format($total_discount, 2)}}</strong></td>
            <td><strong>{{get_option('app_currency')}}{{number_format($total_grand_total_price, 2)}}</strong></td>
            <td><strong>{{get_option('app_currency')}}{{number_format($total_paid_amount, 2)}}</strong></td>
            <td><strong>{{get_option('app_currency')}}{{number_format($total_due_amount, 2)}}</strong></td>
        </tr>
        </tbody>
    </table>
@endforeach
