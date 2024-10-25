<table class="table table-bordered text-center wiz-table mw-col-width-skip-first">
    <thead>
    <tr class="bg-secondary text-white">
        <th style="width: 60px">{{__('pages.sl')}}</th>
        <th>{{__('pages.product')}}</th>
        <th>{{__('pages.quantity')}}</th>
        <th>{{__('pages.total_price')}}</th>
    </tr>
    </thead>
    <tbody>
        @php
            $i = 1;
        @endphp
        @foreach($purchase_products as $key => $purchase_product)
            <tr>
                <td>{{$i}}</td>
                <td>
                    <a href="{{route('product.show', [$key])}}" class="text-primary">
                        {{$purchase_product[0]->product->title}}
                    </a>
                </td>
                <td>{{$purchase_product->sum('quantity')}} {{$purchase_product[0]->product->unit->title ?? ''}}</td>
                <td>{{get_option('app_currency')}} {{number_format($purchase_product->sum('total_price'),2)}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
