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
width:35mm;
height:30mm;
}

body{
font-family:Arial,sans-serif;
display:flex;
align-items:center;
justify-content:center;
}

.barcode-item{
width:35mm;
height:30mm;
display:flex;
flex-direction:column;
align-items:center;
justify-content:center;
padding:2mm;
overflow:hidden;
}

.barcode{
display:flex;
align-items:center;
justify-content:center;
}

.barcode svg{
width:30mm;
height:18mm;
}

.barcode-text{
font-family:monospace;
font-size:9px;
font-weight:bold;
margin-top:-1mm;
line-height:1;
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
display:flex;
align-items:center;
justify-content:center;
}

.barcode-item{
width:35mm !important;
height:30mm !important;
padding:2mm !important;
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

</div>

<script>
JsBarcode("#barcode","{{ $barcode }}",{
format:"CODE128",
width:0.7,
height:18,
displayValue:false,
margin:0
});

window.onload = function(){
setTimeout(function(){
window.print();
},300);
};

window.onafterprint = function(){
window.close();
};
</script>

</body>
</html>
