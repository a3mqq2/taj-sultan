<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>ستيكر - {{ $barcode }}</title>
<script src="{{ asset('js/barcode/jsbarcode.min.js') }}"></script>

<style>
*{
margin:0 !important;
padding:0 !important;
box-sizing:border-box;
}

html,body{
margin:0 !important;
padding:0 !important;
width:35mm;
height:30mm;
}

body{
font-family:Arial,sans-serif;
}

.barcode-item{
width:35mm;
height:30mm;
display:flex;
flex-direction:column;
align-items:center;
justify-content:flex-start;
padding-top:3.5mm;
overflow:hidden;
}

.barcode{
margin-left:1.5mm;
display:flex;
align-items:center;
justify-content:center;
}

.barcode svg{
width:30mm;
height:15mm;
}

.barcode-text{
font-family:monospace;
font-size:9px;
font-weight:bold;
margin-top:0.5mm !important;
text-align:center;
}

.barcode-price{
font-family:Arial,sans-serif;
font-size:10px;
font-weight:bold;
margin-top:0.5mm !important;
text-align:center;
}

.no-print{
text-align:center;
padding:20px !important;
}

@media print{
.no-print{
display:none !important;
}

html,body{
width:35mm !important;
height:30mm !important;
margin:0 !important;
padding:0 !important;
}

.barcode-item{
width:35mm !important;
height:30mm !important;
padding-top:3.5mm !important;
}

@page{
size:35mm 30mm;
margin:0 !important;
}
}

@media screen{
.barcode-item{
border:1px dashed #ccc;
margin:10px auto !important;
background:white;
}
}
</style>
</head>

<body>

<div class="no-print">
<button onclick="window.print()" style="padding:10px 30px;font-size:16px;cursor:pointer;">
طباعة
</button>
<p style="margin-top:10px;color:#666;">35mm x 30mm</p>
</div>

<div class="barcode-item">

<div class="barcode">
<svg id="barcode"></svg>
</div>

<div class="barcode-text">{{ $barcode }}</div>

@if($price !== null)
<div class="barcode-price">{{ number_format($price, 3) }} د.ل</div>
@endif

</div>

<script>
JsBarcode("#barcode","{{ $barcode }}",{
format:"CODE128",
width:0.6,
height:15,
displayValue:false,
margin:0
});

window.onload=function(){
setTimeout(function(){
window.print();
},300);
};

window.onafterprint=function(){
window.close();
};
</script>

</body>
</html>