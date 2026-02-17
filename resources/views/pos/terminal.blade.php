<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $posPoint->name }} - نقطة البيع</title>
    <link href="{{ asset('assets/fonts/almarai/almarai.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Almarai', sans-serif;
            background: #f0f4f8;
            height: 100vh;
            overflow: hidden;
        }

        .pos-container {
            display: grid;
            grid-template-columns: 1fr 400px;
            height: 100vh;
        }

        .products-section {
            display: flex;
            flex-direction: column;
            background: #f0f4f8;
            position: relative;
        }

        .products-section::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            height: 500px;
            background-image: url('{{ asset("logo-dark.png") }}');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: contain;
            opacity: 0.15;
            pointer-events: none;
            z-index: 0;
        }

        .header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 28px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            position: relative;
            z-index: 1;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo {
            height: 42px;
        }

        .pos-name {
            font-size: 22px;
            font-weight: 800;
            color: #1a202c;
        }

        .session-info {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 14px;
            color: #64748b;
        }

        .session-info .status {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #ecfdf5;
            border-radius: 24px;
            color: #059669;
            font-weight: 700;
        }

        .session-info .status-dot {
            width: 10px;
            height: 10px;
            background: #10b981;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(0.9); }
        }

        .session-time {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 700;
            color: #475569;
        }

        .user-name {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 700;
            color: #475569;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #fee2e2;
            border: none;
            border-radius: 24px;
            color: #dc2626;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background: #fecaca;
        }

        .search-bar {
            padding: 20px 28px;
            position: relative;
            z-index: 1;
        }

        .search-input-wrapper {
            position: relative;
        }

        .search-input-wrapper i {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 22px;
            color: #94a3b8;
        }

        .search-input {
            width: 100%;
            padding: 18px 24px;
            padding-right: 56px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 18px;
            font-family: 'Almarai', sans-serif;
            background: #fff;
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .search-input::placeholder {
            color: #94a3b8;
        }

        .products-grid {
            flex: 1;
            overflow-y: auto;
            padding: 0 28px 28px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 16px;
            align-content: start;
            position: relative;
            z-index: 1;
        }

        .products-grid .product-card {
            position: relative;
            z-index: 1;
        }

        .product-card {
            background: #fff;
            border: 2px solid transparent;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .product-card:hover {
            border-color: #6366f1;
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.15);
        }

        .product-card.weight-product {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        }

        .product-card.weight-product:hover {
            border-color: #f59e0b;
            box-shadow: 0 8px 24px rgba(245, 158, 11, 0.15);
        }

        .product-name {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #1e293b;
            line-height: 1.4;
        }

        .product-price {
            font-size: 20px;
            font-weight: 800;
            color: #6366f1;
        }

        .product-card.weight-product .product-price {
            color: #d97706;
        }

        .product-type {
            font-size: 12px;
            color: #92400e;
            margin-top: 6px;
            font-weight: 700;
        }

        .cart-section {
            display: flex;
            flex-direction: column;
            background: #fff;
            border-right: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .cart-header {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
        }

        .cart-title {
            font-size: 24px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .cart-count {
            font-size: 15px;
            color: #64748b;
            font-weight: 400;
        }

        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 14px;
            margin-bottom: 10px;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-name {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .cart-item-price {
            font-size: 13px;
            color: #64748b;
        }

        .cart-item-qty {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qty-btn {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 10px;
            background: #e2e8f0;
            font-size: 20px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s;
            color: #475569;
        }

        .qty-btn:hover {
            background: #6366f1;
            color: white;
        }

        .qty-value {
            min-width: 36px;
            text-align: center;
            font-weight: 800;
            font-size: 16px;
            color: #1e293b;
        }

        .cart-item-total {
            font-weight: 800;
            font-size: 16px;
            color: #1e293b;
            min-width: 80px;
            text-align: left;
        }

        .cart-item-remove {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 10px;
            background: #fef2f2;
            color: #ef4444;
            cursor: pointer;
            transition: all 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-item-remove:hover {
            background: #ef4444;
            color: white;
        }

        .cart-footer {
            padding: 24px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .cart-total-label {
            font-size: 18px;
            color: #64748b;
            font-weight: 700;
        }

        .cart-total-value {
            font-size: 36px;
            font-weight: 800;
            color: #1e293b;
        }

        .cart-total-currency {
            font-size: 18px;
            color: #64748b;
            margin-right: 4px;
        }

        .cart-actions {
            display: flex;
            gap: 12px;
        }

        .btn-pay {
            flex: 1;
            padding: 18px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 14px;
            color: white;
            font-size: 18px;
            font-weight: 800;
            font-family: 'Almarai', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
        }

        .btn-pay:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-clear {
            padding: 18px 24px;
            background: #fff;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            color: #64748b;
            font-size: 18px;
            font-family: 'Almarai', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-clear:hover {
            border-color: #ef4444;
            color: #ef4444;
            background: #fef2f2;
        }

        .empty-cart {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            padding: 40px;
        }

        .empty-cart i {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-cart span {
            font-size: 18px;
            font-weight: 700;
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #fef2f2;
            border: none;
            border-radius: 12px;
            color: #ef4444;
            font-family: 'Almarai', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .user-btn:hover {
            background: #ef4444;
            color: white;
        }

        .end-session-btn {
            background: #fef3c7;
            color: #d97706;
        }

        .end-session-btn:hover {
            background: #d97706;
            color: white;
        }

        .weight-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .weight-modal-content {
            background: white;
            border-radius: 24px;
            padding: 40px;
            width: 420px;
            text-align: center;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }

        .weight-modal-title {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 8px;
            color: #1e293b;
        }

        .weight-modal-product {
            color: #64748b;
            margin-bottom: 28px;
            font-size: 16px;
        }

        .weight-input {
            width: 100%;
            padding: 20px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 32px;
            font-weight: 800;
            text-align: center;
            margin-bottom: 24px;
            font-family: 'Almarai', sans-serif;
            color: #1e293b;
        }

        .weight-input:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        }

        .weight-modal-actions {
            display: flex;
            gap: 12px;
        }

        .weight-modal-actions button {
            flex: 1;
            padding: 16px;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Almarai', sans-serif;
            border: none;
        }

        .btn-weight-cancel {
            background: #f1f5f9;
            color: #64748b;
        }

        .btn-weight-cancel:hover {
            background: #e2e8f0;
        }

        .btn-weight-add {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .btn-weight-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        }

        .confirm-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .confirm-modal-content {
            background: white;
            border-radius: 24px;
            padding: 40px;
            width: 420px;
            text-align: center;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }

        .confirm-modal-icon {
            width: 80px;
            height: 80px;
            background: #fef3c7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        .confirm-modal-icon i {
            font-size: 40px;
            color: #d97706;
        }

        .confirm-modal-title {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 12px;
            color: #1e293b;
        }

        .confirm-modal-message {
            color: #64748b;
            margin-bottom: 28px;
            font-size: 16px;
            line-height: 1.6;
        }

        .confirm-modal-actions {
            display: flex;
            gap: 12px;
        }

        .confirm-modal-actions button {
            flex: 1;
            padding: 16px;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Almarai', sans-serif;
            border: none;
        }

        .btn-confirm-cancel {
            background: #f1f5f9;
            color: #64748b;
        }

        .btn-confirm-cancel:hover {
            background: #e2e8f0;
        }

        .btn-confirm-ok {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-confirm-ok:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
        }

        .products-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 28px;
            background: #fff;
            border-top: 1px solid #e2e8f0;
            position: relative;
            z-index: 1;
        }

        .footer-credit {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 700;
            color: #475569;
        }

        .footer-credit img {
            height: 40px;
        }

        .shortcuts-hint {
            display: flex;
            gap: 16px;
            font-size: 12px;
            color: #94a3b8;
        }

        .shortcut {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .shortcut kbd {
            padding: 2px 6px;
            background: #f1f5f9;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            font-family: 'Almarai', sans-serif;
        }

        .refresh-products-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #eff6ff;
            border: none;
            border-radius: 24px;
            color: #3b82f6;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Almarai', sans-serif;
        }

        .refresh-products-btn:hover {
            background: #dbeafe;
        }

        .refresh-products-btn.spinning i {
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .hidden {
            display: none !important;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="pos-container">
        <div class="products-section">
            <div class="header-bar">
                <div class="header-right">
                    <img src="{{ asset('logo-dark.png') }}" alt="Logo" class="logo">
                    <div class="pos-name">{{ $posPoint->name }}</div>
                </div>
                <div class="session-info">
                    <button type="button" class="refresh-products-btn" id="refreshProductsBtn" onclick="refreshProducts()" title="تحديث المنتجات (F5)">
                        <i class="ti ti-refresh"></i>
                        <span>تحديث</span>
                    </button>
                    <div class="status">
                        <span class="status-dot"></span>
                        <span>متصل</span>
                    </div>
                    @if(auth()->check())
                    <div class="user-name">
                        <i class="ti ti-user"></i>
                        <span>{{ auth()->user()->name }}</span>
                    </div>
                    <form action="{{ route('pos.logout', $posPoint->slug) }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="ti ti-logout"></i>
                            <span>خروج</span>
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <div class="search-bar">
                <div class="search-input-wrapper">
                    <i class="ti ti-barcode"></i>
                    <input type="text" class="search-input" id="searchInput" placeholder="   البحث..." autofocus>
                </div>
            </div>

            <div class="products-grid" id="productsGrid">
                @foreach($products as $product)
                <div class="product-card {{ $product->type === 'weight' ? 'weight-product' : '' }}"
                     data-id="{{ $product->id }}"
                     data-name="{{ $product->name }}"
                     data-price="{{ $product->price }}"
                     data-type="{{ $product->type }}"
                     data-barcode="{{ $product->barcode }}">
                    <div class="product-name">{{ $product->name }}</div>
                    <div class="product-price">{{ number_format($product->price, 3) }}</div>
                    @if($product->type === 'weight')
                    <div class="product-type">بالوزن</div>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="products-footer">
                <div class="footer-credit">
                    <img src="{{ asset('hulul.jpg') }}" alt="Hulul">
                    <span>تم تنفيذ النظام بواسطة شركة حلول لتقنية المعلومات</span>
                </div>
                <div class="shortcuts-hint">
                    <div class="shortcut"><kbd>F5</kbd> تحديث</div>
                    <div class="shortcut"><kbd>F8</kbd> طباعة</div>
                    <div class="shortcut"><kbd>Esc</kbd> مسح</div>
                    <div class="shortcut"><kbd>Space</kbd> بحث</div>
                </div>
            </div>
        </div>

        <div class="cart-section">
            <div class="cart-header">
                <div class="cart-title">الفاتورة</div>
                <div class="cart-count"><span id="cartCount">0</span> صنف</div>
            </div>

            <div class="cart-items" id="cartItems">
                <div class="empty-cart" id="emptyCart">
                    <i class="ti ti-shopping-cart"></i>
                    <span>السلة فارغة</span>
                </div>
            </div>

            <div class="cart-footer">
                <div class="cart-total">
                    <span class="cart-total-label">الإجمالي</span>
                    <div>
                        <span class="cart-total-value" id="cartTotal">0.000</span>
                        <span class="cart-total-currency">د.ل</span>
                    </div>
                </div>
                <div class="cart-actions">
                    <button class="btn-clear" onclick="clearCart()" title="Esc">
                        <i class="ti ti-trash"></i>
                    </button>
                    <button class="btn-pay" id="payBtn" onclick="printInvoice()" disabled title="F8">
                        <i class="ti ti-printer"></i>
                        طباعة
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="weight-modal hidden" id="weightModal">
        <div class="weight-modal-content">
            <div class="weight-modal-title">أدخل الوزن</div>
            <div class="weight-modal-product" id="weightProductName"></div>
            <input type="number" class="weight-input" id="weightInput" step="0.001" min="0.001" placeholder="0.000">
            <div class="weight-modal-actions">
                <button class="btn-weight-cancel" onclick="closeWeightModal()">إلغاء</button>
                <button class="btn-weight-add" onclick="addWeightProduct()">إضافة</button>
            </div>
        </div>
    </div>

    <script>
        const BASE_URL = "{{ url('/') }}";
        const cart = [];
        let selectedProduct = null;
        const posSlug = '{{ $posPoint->slug }}';
        const currentUserName = {!! auth()->check() ? json_encode(auth()->user()->name) : 'null' !!};
        let qtyBuffer = '';
        let qtyTimeout = null;

        document.addEventListener('DOMContentLoaded', function() {
            initProductCards();
            initSearch();
            initKeyboardShortcuts();
            initQtyInput();
        });

        function initProductCards() {
            document.querySelectorAll('.product-card').forEach(card => {
                card.addEventListener('click', function() {
                    addToCart(this);
                });
            });
        }

        function initQtyInput() {
            document.addEventListener('keydown', function(e) {
                const searchInput = document.getElementById('searchInput');
                const weightModal = document.getElementById('weightModal');

                if (document.activeElement === searchInput) return;
                if (!weightModal.classList.contains('hidden')) return;
                if (cart.length === 0) return;

                if (e.key >= '0' && e.key <= '9') {
                    e.preventDefault();
                    qtyBuffer += e.key;

                    clearTimeout(qtyTimeout);
                    qtyTimeout = setTimeout(function() {
                        const qty = parseInt(qtyBuffer);
                        if (qty > 0 && cart.length > 0) {
                            const lastIndex = cart.length - 1;
                            if (!cart[lastIndex].isWeight) {
                                cart[lastIndex].qty = qty;
                                renderCart();
                            }
                        }
                        qtyBuffer = '';
                    }, 500);
                }
            });
        }

        function initSearch() {
            const searchInput = document.getElementById('searchInput');
            let timeout;

            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    const value = this.value.trim();

                    const barcodeProduct = document.querySelector('.product-card[data-barcode="' + value + '"]');
                    if (barcodeProduct && value.length >= 3) {
                        addToCart(barcodeProduct);
                        this.value = '';
                        return;
                    }

                    filterProductsBySearch(value);
                }, 200);
            });
        }

        function filterProductsBySearch(query) {
            const lowerQuery = query.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.dataset.name.toLowerCase();
                const barcode = card.dataset.barcode || '';
                if (name.includes(lowerQuery) || barcode.includes(query)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function addToCart(card) {
            const productId = card.dataset.id;
            const productName = card.dataset.name;
            const productPrice = parseFloat(card.dataset.price);
            const productType = card.dataset.type;

            if (productType === 'weight') {
                selectedProduct = {
                    id: productId,
                    name: productName,
                    price: productPrice,
                    type: productType
                };
                document.getElementById('weightProductName').textContent = productName;
                document.getElementById('weightInput').value = '';
                document.getElementById('weightModal').classList.remove('hidden');
                document.getElementById('weightInput').focus();
                return;
            }

            let existingIndex = -1;
            for (let i = 0; i < cart.length; i++) {
                if (cart[i].id === productId && !cart[i].isWeight) {
                    existingIndex = i;
                    break;
                }
            }

            if (existingIndex >= 0) {
                cart[existingIndex].qty = cart[existingIndex].qty + 1;
            } else {
                cart.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    type: productType,
                    qty: 1,
                    isWeight: false
                });
            }

            renderCart();
        }

        function closeWeightModal() {
            selectedProduct = null;
            document.getElementById('weightModal').classList.add('hidden');
            document.getElementById('searchInput').focus();
        }

        function addWeightProduct() {
            const weight = parseFloat(document.getElementById('weightInput').value);
            if (!weight || weight <= 0) return;

            cart.push({
                id: selectedProduct.id,
                name: selectedProduct.name,
                price: selectedProduct.price,
                type: selectedProduct.type,
                qty: weight,
                isWeight: true
            });

            closeWeightModal();
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cartItems');
            const emptyCartEl = document.getElementById('emptyCart');

            if (cart.length === 0) {
                emptyCartEl.style.display = 'flex';
                const itemsHtml = document.querySelectorAll('.cart-item');
                itemsHtml.forEach(el => el.remove());
                document.getElementById('cartCount').textContent = '0';
                document.getElementById('cartTotal').textContent = '0.000';
                document.getElementById('payBtn').disabled = true;
                return;
            }

            emptyCartEl.style.display = 'none';

            let html = '';
            let total = 0;

            for (let i = 0; i < cart.length; i++) {
                const item = cart[i];
                const itemTotal = item.price * item.qty;
                total += itemTotal;

                const qtyDisplay = item.isWeight ? item.qty.toFixed(3) + ' كجم' : item.qty;

                html += '<div class="cart-item">';
                html += '<div class="cart-item-info">';
                html += '<div class="cart-item-name">' + item.name + '</div>';
                html += '<div class="cart-item-price">' + item.price.toFixed(3) + ' × ' + qtyDisplay + '</div>';
                html += '</div>';

                if (!item.isWeight) {
                    html += '<div class="cart-item-qty">';
                    html += '<button type="button" class="qty-btn" data-index="' + i + '" data-action="decrease">-</button>';
                    html += '<span class="qty-value">' + item.qty + '</span>';
                    html += '<button type="button" class="qty-btn" data-index="' + i + '" data-action="increase">+</button>';
                    html += '</div>';
                }

                html += '<div class="cart-item-total">' + itemTotal.toFixed(3) + '</div>';
                html += '<button type="button" class="cart-item-remove" data-index="' + i + '" data-action="remove">';
                html += '<i class="ti ti-x"></i>';
                html += '</button>';
                html += '</div>';
            }

            container.innerHTML = '<div class="empty-cart" id="emptyCart" style="display:none;"><i class="ti ti-shopping-cart"></i><span>السلة فارغة</span></div>' + html;

            document.getElementById('cartCount').textContent = cart.length;
            document.getElementById('cartTotal').textContent = total.toFixed(3);
            document.getElementById('payBtn').disabled = false;

            container.querySelectorAll('.qty-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const idx = parseInt(this.dataset.index);
                    const action = this.dataset.action;
                    if (action === 'increase') {
                        cart[idx].qty = cart[idx].qty + 1;
                    } else if (action === 'decrease') {
                        cart[idx].qty = cart[idx].qty - 1;
                        if (cart[idx].qty <= 0) {
                            cart.splice(idx, 1);
                        }
                    }
                    renderCart();
                });
            });

            container.querySelectorAll('.cart-item-remove').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const idx = parseInt(this.dataset.index);
                    cart.splice(idx, 1);
                    renderCart();
                });
            });
        }

        function clearCart() {
            cart.length = 0;
            renderCart();
            document.getElementById('searchInput').focus();
        }

        async function printInvoice() {
            if (cart.length === 0) return;

            var total = 0;
            var items = [];

            for (var i = 0; i < cart.length; i++) {
                var item = cart[i];
                var itemTotal = item.price * item.qty;
                total += itemTotal;
                items.push({
                    id: parseInt(item.id),
                    name: item.name,
                    price: item.price,
                    qty: item.qty,
                    isWeight: item.isWeight
                });
            }

            var orderNumber;
            try {
                var response = await fetch(BASE_URL + '/pos/' + posSlug + '/orders', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        items: items,
                        total: total
                    })
                });
                var result = await response.json();
                if (result.success) {
                    orderNumber = result.data.order_number;
                } else {
                    orderNumber = Date.now().toString().slice(-8);
                }
            } catch (e) {
                orderNumber = Date.now().toString().slice(-8);
            }

            window.open(BASE_URL + '/pos/' + posSlug + '/sticker/' + orderNumber, '_blank');

            cart.length = 0;
            renderCart();
        }

        async function refreshProducts() {
            const btn = document.getElementById('refreshProductsBtn');
            btn.classList.add('spinning');
            btn.disabled = true;

            try {
                const res = await fetch(BASE_URL + '/pos/' + posSlug + '/products', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();

                if (data.success) {
                    const grid = document.getElementById('productsGrid');
                    grid.innerHTML = '';

                    data.data.forEach(function(product) {
                        const card = document.createElement('div');
                        card.className = 'product-card' + (product.type === 'weight' ? ' weight-product' : '');
                        card.dataset.id = product.id;
                        card.dataset.name = product.name;
                        card.dataset.price = product.price;
                        card.dataset.type = product.type;
                        card.dataset.barcode = product.barcode || '';

                        let html = '<div class="product-name">' + product.name + '</div>';
                        html += '<div class="product-price">' + parseFloat(product.price).toFixed(3) + '</div>';
                        if (product.type === 'weight') {
                            html += '<div class="product-type">بالوزن</div>';
                        }
                        card.innerHTML = html;

                        card.addEventListener('click', function() {
                            addToCart(this);
                        });

                        grid.appendChild(card);
                    });

                    document.getElementById('searchInput').value = '';
                }
            } catch (err) {
            }

            btn.classList.remove('spinning');
            btn.disabled = false;
            document.getElementById('searchInput').focus();
        }

        function initKeyboardShortcuts() {
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (!document.getElementById('weightModal').classList.contains('hidden')) {
                        closeWeightModal();
                    } else {
                        clearCart();
                    }
                }

                if (e.key === 'F5') {
                    e.preventDefault();
                    refreshProducts();
                }

                if (e.key === 'F8') {
                    e.preventDefault();
                    printInvoice();
                }

                if (e.code === 'Space' && document.activeElement !== document.getElementById('searchInput') && document.getElementById('weightModal').classList.contains('hidden')) {
                    e.preventDefault();
                    document.getElementById('searchInput').focus();
                    document.getElementById('searchInput').value = '';
                }

                if (e.key === 'Enter') {
                    if (!document.getElementById('weightModal').classList.contains('hidden')) {
                        e.preventDefault();
                        addWeightProduct();
                    }
                }
            });
        }
    </script>
</body>
</html>
