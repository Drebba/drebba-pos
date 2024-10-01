<div class="table-responsive">
    <table class="table table-bordered wiz-table text-center mw-col-width-skip-first">
        <thead>
        <tr class="bg-secondary text-white">
            <th scope="col" style="width: 60px">{{__('pages.sl')}}</th>
            <th scope="col">{{__('pages.date')}}</th>
            @can('access_to_all_branch')
                <th>{{__('pages.branch')}}</th>
            @endcan
            <th scope="col">{{__('pages.total_amount')}}</th>
        </tr>
        </thead>
        <tbody>
        @php
            $grand_total_amount = 0;
        @endphp
        @for($d=0; $d < count($purchase_info); $d++)
            <tr>
                <td scope="row">{{$d + 1}}</td>
                <td>
                    @if(Request::get('year')){{Request::get('year')}} ,@endif

                    @if(Request::get('search_type') != 'year')
                        {{\Carbon\Carbon::parse($purchase_info[$d]['purchase_date'])->format(get_option('app_date_format'))}}
                    @else
                        {{$purchase_info[$d]['purchase_date']}}
                    @endif
                </td>
                @can('access_to_all_branch')
                    <td>
                        @if(Request::get('business_id'))
                            {{\App\Models\Branch::findOrFail(Request::get('business_id'))->title}}
                        @else
                            All
                        @endif
                    </td>
                @endcan
                <td>{{get_option('app_currency')}}{{number_format($purchase_info[$d]['total_purchase_amount'], 2)}}</td>
                @php
                    $grand_total_amount += $purchase_info[$d]['total_purchase_amount'];
                @endphp
            </tr>
        @endfor
        <tr>
            @can('access_to_all_branch')
                <td colspan="3" class="text-right pr-2"><strong>{{__('pages.grand_total')}}</strong></td>
            @else
                <td colspan="2" class="text-right pr-2"><strong>{{__('pages.grand_total')}}</strong></td>
            @endcan
            <td><strong>{{get_option('app_currency')}}{{number_format($grand_total_amount, 2)}}</strong></td>
        </tr>
        </tbody>
    </table>
</div>
