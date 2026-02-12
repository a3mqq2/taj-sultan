<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('messages.print_barcode') }} - {{ $item['name'] }}</title>

<style>
*{
margin:0 !important;
padding:0 !important;
box-sizing:border-box;
}

html,body{
margin:0 !important;
padding:0 !important;
}

body{
font-family:Arial,sans-serif;
direction:{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
}

.barcode-item{
width:25mm;
height:35mm;
padding:0.5mm !important;
text-align:center;
display:flex;
flex-direction:column;
justify-content:space-between;
page-break-after:always;
overflow:hidden;
}

.barcode-item:last-child{
page-break-after:auto;
}

.product-name{
font-size:7px;
font-weight:bold;
white-space:nowrap;
overflow:hidden;
text-overflow:ellipsis;
}

.barcode{
flex:1;
display:flex;
align-items:center;
justify-content:center;
}

.barcode svg{
width:22mm;
height:18mm;
}

.product-code{
font-size:7px;
font-weight:bold;
font-family:monospace;
}

.product-price{
font-size:8px;
font-weight:bold;
}

.no-print{
text-align:center;
padding:20px !important;
}

@media print{
.no-print{display:none !important;}

html,body{
width:25mm !important;
height:35mm !important;
margin:0 !important;
padding:0 !important;
}

.barcode-item{
width:25mm !important;
height:35mm !important;
padding:0.5mm !important;
}

@page{
size:25mm 35mm;
margin:0 !important;
}
}

@media screen{
.barcode-item{
border:1px dashed #ccc;
margin:5px auto !important;
background:white;
}
}
</style>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
</head>

<body>

<div class="no-print">
<button onclick="window.print()" style="padding:10px 30px;font-size:16px;cursor:pointer;margin-bottom:20px;">
{{ __('messages.print') }}
</button>
<p style="margin-top:10px;color:#666;">25mm x 35mm</p>
</div>

<div>
@for($i = 0; $i < $quantity; $i++)
<div class="barcode-item">
<div class="product-name">{{ $item['name'] }}</div>
<div class="barcode">
<svg id="barcode-{{ $i }}"></svg>
</div>
<div class="product-code">{{ $item['code'] }}</div>
<div class="product-price">{{ number_format($item['price'],2) }} {{ __('messages.currency') }}</div>
</div>
@endfor
</div>

<script>
document.addEventListener('DOMContentLoaded',function(){
@for($i = 0; $i < $quantity; $i++)
JsBarcode("#barcode-{{ $i }}","{{ $item['code'] }}",{
format:"CODE128",
width:1,
height:30,
displayValue:false,
margin:0
});
@endfor
setTimeout(function(){window.print();},400);
});
</script>

</body>
</html>