<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>ستيكر - {{ $orderNumber }}</title>
<script src="{{ asset('assets/js/jsbarcode.min.js') }}"></script>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
}

@page{
size:38mm 25mm;
margin:0;
}

html,body{
width:38mm;
height:25mm;
overflow:hidden;
background:#fff;
}

body{
display:flex;
align-items:center;
justify-content:center;
}

.container{
width:38mm;
height:25mm;
display:flex;
flex-direction:column;
align-items:center;
justify-content:center;
}

svg{
width:34mm;
height:14mm;
}

.print-btn{
position:fixed;
top:5px;
right:5px;
padding:6px 12px;
background:#3b82f6;
color:#fff;
border:none;
border-radius:4px;
font-size:12px;
cursor:pointer;
z-index:999;
}

@media print{
.print-btn{
display:none;
}
}

</style>
</head>

<body>

<button class="print-btn" onclick="window.print()">طباعة</button>

<div class="container">
<svg id="barcode"></svg>
<div style="font-size:8px;margin-top:2mm">{{ $orderNumber }}</div>
</div>

<script>

JsBarcode("#barcode","{{ $orderNumber }}",{
format:"CODE128",
width:1,
height:14,
displayValue:false,
margin:0
});

</script>

</body>
</html>