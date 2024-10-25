@foreach($purchases as $key=> $purchase)
    <table class="table table-bordered text-center wiz-table mw-col-width-skip-first mb-4">
        <thead>
            <tr class="bg-secondary text-white">
                <th colspan="9">{{$key}}</th>
            </tr>
            <tr class="bg-secondary text-white">
                <td class="text-nowrap">{{__('pages.sl')}}</td>
                <td class="text-nowrap">{{__('pages.invoice_id')}}</td>

                <td class="text-nowrap">{{__('pages.supplier')}}</td>
                <td class="text-nowrap">{{__('pages.purchase_date')}}</td>
                <td class="text-nowrap">{{__('pages.total_amount')}}</td>
                <td class="text-nowrap">{{__('pages.paid_amount')}}</td>
                <td class="text-nowrap">{{__('pages.due_amount')}}</td>
            </tr>
        </thead>
        <tbody>
            @php
                $total_grand_total_amount = 0;
                $total_paid_amount = 0;
                $total_due_amount = 0;
            @endphp
            @foreach($purchase as $key => $single_purchase)
                <tr>
                    <td>{{$key + 1}}</td>
                    <td>
                        <a href="{{route('purchase.show', [$single_purchase->id])}}" target="_blank">{{$single_purchase->invoice_id}}</a>
                    </td>
                    <td>{{$single_purchase->supplier->company_name}}</td>
                    <td>{{$single_purchase->purchase_date->format(get_option('app_date_format'))}}</td>
                    <td> {{get_option('app_currency')}}{{number_format($single_purchase->total_amount, 2)}} </td>
                    <td> {{get_option('app_currency')}}{{number_format($single_purchase->paid_amount, 2)}} </td>
                    <td> {{get_option('app_currency')}}{{number_format($single_purchase->due_amount, 2)}} </td>
                </tr>

                @php
                    $total_grand_total_amount += $single_purchase->total_amount;
                    $total_paid_amount += $single_purchase->paid_amount;
                    $total_due_amount += $single_purchase->due_amount;
                @endphp
            @endforeach


            <tr>

                    <td colspan="4" class="text-right pr-3"><strong>{{__('pages.total')}}</strong></td>
                <td><strong>{{get_option('app_currency')}}{{number_format($total_grand_total_amount, 2)}}</strong></td>
                <td><strong>{{get_option('app_currency')}}{{number_format($total_paid_amount, 2)}}</strong></td>
                <td><strong>{{get_option('app_currency')}}{{number_format($total_due_amount, 2)}}</strong></td>
            </tr>
        </tbody>
    </table>
@endforeach
