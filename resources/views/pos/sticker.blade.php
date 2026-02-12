<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">

<style>
@page{
size:38mm 25mm;
margin:0;
}

html,body{
width:38mm;
height:25mm;
margin:0;
padding:0;
display:flex;
align-items:center;
justify-content:center;
}

.sticker{
width:38mm;
height:25mm;
display:flex;
flex-direction:column;
align-items:center;
justify-content:center;
padding:0;
}

svg{
width:34mm;
height:14mm;
}

.order-num{
font-size:7pt;
margin-top:1mm;
font-weight:bold;
}
</style>

<script src="{{ asset('assets/js/jsbarcode.min.js') }}"></script>
</head>

<body>

<div class="sticker">
<svg id="barcode"></svg>
<div class="order-num">{{ $orderNumber }}</div>
</div>

<script>
JsBarcode("#barcode","{{ $orderNumber }}",{
format:"CODE128",
width:1.2,
height:24,
margin:0,
displayValue:false
});

window.onload=function(){
window.print();
};
</script>

</body>
</html>