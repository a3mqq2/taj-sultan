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

@media screen{
html,body{
width:100vw;
height:100vh;
display:flex;
align-items:center;
justify-content:center;
background:#f0f0f0;
}
.sticker{
width:152px;
height:100px;
background:#fff;
box-shadow:0 2px 10px rgba(0,0,0,0.2);
display:flex;
flex-direction:column;
align-items:center;
justify-content:center;
padding:4px;
}
svg{
max-width:140px;
height:auto;
}
.order-num{
font-family:Arial,sans-serif;
font-size:11px;
font-weight:bold;
margin-top:2px;
}
.print-btn{
position:fixed;
bottom:20px;
left:50%;
transform:translateX(-50%);
padding:12px 30px;
background:#10b981;
color:#fff;
border:none;
border-radius:8px;
font-size:16px;
font-weight:bold;
cursor:pointer;
font-family:Arial,sans-serif;
}
.print-btn:hover{
background:#059669;
}
}

@media print{
html,body{
width:38mm;
height:25mm;
background:#fff;
}
.sticker{
width:38mm;
height:25mm;
display:flex;
flex-direction:column;
align-items:center;
justify-content:center;
padding:1mm;
}
svg{
max-width:36mm;
height:auto;
}
.order-num{
font-family:Arial,sans-serif;
font-size:8pt;
font-weight:bold;
margin-top:1mm;
}
.print-btn{
display:none !important;
}
}
</style>
</head>
<body>

<div class="sticker">
<svg id="barcode"></svg>
<div class="order-num">{{ $orderNumber }}</div>
</div>

<button class="print-btn" onclick="window.print()">طباعة الستيكر</button>

<script>
JsBarcode("#barcode","{{ $orderNumber }}",{
format:"CODE128",
width:1.5,
height:40,
displayValue:false,
margin:0
});
</script>

</body>
</html>
