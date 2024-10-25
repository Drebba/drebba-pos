<table class="table table-bordered wiz-table">
    <thead>
        <tr class="bg-secondary text-white">
            <th scope="col" class="text-center">{{__('pages.sl')}}</th>
            <th scope="col" class="text-center">{{__('pages.date')}}</th>
            {{-- @can('access_to_all_branch')
                <th>{{__('pages.branch')}}</th>
            @endcan --}}
            <th scope="col" class="text-center">{{__('pages.total_amount')}}</th>
        </tr>
    </thead>
    <tbody>
        @php
            $grand_total_amount = 0;
        @endphp
        @for($d=0; $d < count($sell_info); $d++)
            <tr>
                <td scope="row" width="5%" class="text-center">{{$d + 1}}</td>
                <td class="text-center">
                    @if(Request::get('year')){{Request::get('year')}} ,@endif

                    @if(Request::get('search_type') != 'year')
                        {{\Carbon\Carbon::parse($sell_info[$d]['sell_date'])->format(get_option('app_date_format'))}}
                    @else
                        {{ $sell_info[$d]['sell_date']}}
                    @endif

                </td>
                {{-- @can('access_to_all_branch')
                <td class="text-center">
                    @if(Request::get('business_id'))
                        {{\App\Models\Branch::findOrFail(Request::get('business_id'))->title}}
                    @else
                        All Branch
                    @endif
                </td>
                @endcan --}}
                <td class="text-center">{{get_option('app_currency')}}{{number_format($sell_info[$d]['total_sell_amount'], 2)}}</td>
                @php
                    $grand_total_amount += $sell_info[$d]['total_sell_amount'];
                @endphp
            </tr>
        @endfor
        <tr>
            <td></td>

                <td class="text-center"><strong>{{__('pages.grand_total')}}</strong></td>

            <td class="text-center"><strong>{{get_option('app_currency')}}{{number_format($grand_total_amount, 2)}}</strong></td>
        </tr>
    </tbody>
</table>
