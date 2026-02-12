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
size:304px 200px;
margin:0;
}

html,body{
width:304px;
height:200px;
overflow:hidden;
background:#fff;
}

body{
display:flex;
align-items:center;
justify-content:center;
}

.container{
width:304px;
height:200px;
display:flex;
flex-direction:column;
align-items:center;
justify-content:center;
}

svg{
width:280px;
height:100px;
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
<div style="font-size:12px;margin-top:4px">{{ $orderNumber }}</div>
</div>

<script>

JsBarcode("#barcode","{{ $orderNumber }}",{
format:"CODE128",
width:2,
height:100,
displayValue:false,
margin:0
});

</script>

</body>
</html>