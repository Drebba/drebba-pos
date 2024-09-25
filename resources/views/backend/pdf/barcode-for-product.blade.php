<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Barcode</title>
    <style>

        .amdmaininvoice table {
            border-collapse: collapse;
            width: 100%;
        }

        .amdmaininvoice td, .amdmaininvoice th {
            border: 1px solid #d3d3d3;
            text-align: left;
            padding: 8px;
        }

        .amdmaininvoice tr:nth-child(even) {
            background-color: #d3d3d3;
        }

        .barcode-col{
            width: 25%;
            height: 80px;
            float: left;
            border: 1px solid;
        }

        .barcode{
            margin: 0px auto;
        }

        @page {
            margin: 10px !important;
        }
    </style>

</head>

<body style="margin:0;padding:0; font-family: 'Open Sans', sans-serif; font-size:14px;">
<div class="amd-wrapper" style="margin:0 auto; padding:0; max-width:1024px; width:100%; background:#fff;">

    <div>
        @php $sl = 0 @endphp
        @for($n = 0; $n < $numberOfCode; $n++)
            <div style="width: 24.4%; float: left; padding: 3px;text-align: center">
                <div style="border: 1px solid #000000; padding-left: 15px; padding-bottom: 10px;">
                    <p style="margin-bottom: 0; padding-bottom:0; text-align: center; display: block"> {{$product->sku}}</p>
                    <div style="padding-left: 5px; ">
                        <?php  echo DNS1D::getBarcodeHTML($product->sku, "C39", 1,33);  ?>
                    </div>
                </div>
            </div>

            @php $sl += 1;@endphp

            @if($sl == 4)
                <div style="clear: both"> </div>
                @php $sl = 0;@endphp
            @endif
        @endfor
    </div>
</div>
</body>
</html>
