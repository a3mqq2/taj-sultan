<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ستيكر - {{ $orderNumber }}</title>
    <script src="{{ asset('assets/js/jsbarcode.min.js') }}"></script>
    <style>
        * {
            margin: 0 !important;
            padding: 0 !important;
            box-sizing: border-box;
        }

        html, body {
            width: 38mm !important;
            height: 25mm !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        svg {
            max-width: 36mm;
            height: auto;
        }

        .print-btn {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: sans-serif;
            z-index: 1000;
        }

        .print-btn:hover {
            background: #2563eb;
        }

        @media print {
            .print-btn {
                display: none !important;
            }

            @page {
                size: 38mm 25mm;
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">طباعة</button>
    <svg id="barcode"></svg>

    <script>
        JsBarcode("#barcode", "{{ $orderNumber }}", {
            format: "CODE128",
            width: 1.5,
            height: 50,
            displayValue: true,
            margin: 2,
            fontSize: 10
        });
    </script>
</body>
</html>
