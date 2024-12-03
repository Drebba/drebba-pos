<div class="table-responsive-xl">
    <table class="table table-bordered text-center wiz-table mw-col-width-skip-first">
        <thead class="sticky-top">
        <tr class="bg-secondary text-white">
            <th>{{__('pages.sl')}}</th>
            <th>{{__('pages.invoice_id')}}</th>
            <th>Serve BY</th>
            <th>Customer</th>
            <th>Order Mode</th>
            <th>Table No</th>
            <th>{{__('pages.sell_date')}}</th>
            <th>{{__('pages.grand_total')}}</th>
            <th>{{__('pages.paid_amount')}}</th>
            <th>{{__('pages.due_amount')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sells as $key => $sell)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$sell->invoice_id}}</td>
                <td>{{$sell->user?->name}}</td>
                <td>{{$sell->customer?->name}}</td>
                <td>{{$sell->orderMode?->name}}</td>
                <td>{{$sell->table?->name}}</td>

                <td> @formatdate($sell->sell_date) </td>
                <td> {{get_option('app_currency')}}{{number_format($sell->grand_total_price, 2)}} </td>
                <td> {{get_option('app_currency')}}{{number_format($sell->paid_amount, 2)}} </td>
                <td> {{get_option('app_currency')}}{{number_format($sell->due_amount, 2)}} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
