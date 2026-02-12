<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>ستيكر - {{ $barcode }}</title>
<script src="{{ asset('assets/js/jsbarcode.min.js') }}"></script>
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

.barcode-item{
width:38mm;
height:25mm;
display:flex;
align-items:center;
justify-content:center;
flex-direction:column;
}

.barcode svg{
width:35mm;
height:16mm;
}

.barcode-text{
font-family:monospace;
font-size:10px;
font-weight:bold;
margin-top:1mm;
}

.no-print{
text-align:center;
padding:20px !important;
}

@media print{
.no-print{display:none !important;}

html,body{
width:38mm !important;
height:25mm !important;
}

@page{
size:38mm 25mm;
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
<p style="margin-top:10px !important;color:#666;">38mm x 25mm</p>
</div>

<div class="barcode-item">
<div class="barcode">
<svg id="barcode"></svg>
</div>
<div class="barcode-text">{{ $barcode }}</div>
</div>

<script>
JsBarcode("#barcode","{{ $barcode }}",{
format:"CODE128",
width:1.5,
height:40,
displayValue:false,
margin:0
});
</script>

</body>
</html>
