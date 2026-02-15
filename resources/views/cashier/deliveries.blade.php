<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>التوصيل - {{ config('app.name') }}</title>
    <link href="{{ asset('assets/fonts/cairo/cairo.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}">
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; overflow: hidden; }
        body { font-family: 'Cairo', sans-serif; background: #f1f5f9; color: #1e293b; }
        .app-container { display: flex; flex-direction: column; height: 100vh; overflow: hidden; }
        .header { background: #1e293b; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; }
        .header .logo { height: 80px; }
        .header .logo img { height: 100%; width: auto; }
        .header-left { display: flex; align-items: center; gap: 12px; }
        .back-btn { display: flex; align-items: center; gap: 6px; padding: 8px 16px; background: #3b82f6; border: none; border-radius: 8px; color: #fff; font-weight: 600; cursor: pointer; font-family: inherit; font-size: 14px; text-decoration: none; transition: all 0.2s; }
        .back-btn:hover { background: #2563eb; color: #fff; }
        .user-info { display: flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(255,255,255,0.1); border-radius: 6px; font-weight: 600; font-size: 14px; color: #fff; }
        .main-content { flex: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding: 16px; overflow: hidden; min-height: 0; }
        .left-panel { display: flex; flex-direction: column; gap: 16px; overflow: hidden; min-height: 0; }
        .search-section { background: #fff; border-radius: 12px; padding: 16px; flex-shrink: 0; }
        .search-row { display: flex; gap: 12px; }
        .search-input { flex: 1; padding: 14px 18px; font-size: 16px; font-weight: 600; border: 2px solid #e2e8f0; border-radius: 10px; font-family: inherit; }
        .search-input:focus { outline: none; border-color: #10b981; }
        .search-btn { padding: 14px 24px; background: #10b981; color: #fff; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; font-family: inherit; font-size: 14px; }
        .search-btn:hover { background: #059669; }
        .refresh-btn { padding: 14px 16px; background: #f1f5f9; color: #64748b; border: 2px solid #e2e8f0; border-radius: 10px; font-weight: 700; cursor: pointer; font-family: inherit; font-size: 14px; }
        .refresh-btn:hover { background: #e2e8f0; }
        .list-section { background: #fff; border-radius: 12px; flex: 1; display: flex; flex-direction: column; overflow: hidden; min-height: 0; }
        .list-header { display: flex; justify-content: space-between; align-items: center; padding: 14px 16px; border-bottom: 1px solid #e2e8f0; flex-shrink: 0; }
        .list-title { font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
        .count-badge { background: #10b981; color: #fff; padding: 2px 10px; border-radius: 20px; font-size: 13px; font-weight: 700; }
        .list-body { flex: 1; overflow-y: auto; padding: 12px; min-height: 0; }
        .order-card { padding: 14px; border: 2px solid #e2e8f0; border-radius: 10px; margin-bottom: 10px; cursor: pointer; transition: all 0.15s; }
        .order-card:hover { border-color: #10b981; background: #f0fdf4; }
        .order-card.active { border-color: #10b981; background: #f0fdf4; }
        .order-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .order-number { font-weight: 800; font-size: 16px; color: #1e293b; }
        .order-total { font-weight: 800; font-size: 15px; color: #10b981; }
        .order-card-body { display: flex; flex-direction: column; gap: 4px; }
        .order-info { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #64748b; }
        .order-info i { font-size: 16px; }
        .order-phone { font-weight: 700; color: #1e293b; font-size: 14px; direction: ltr; }
        .right-panel { display: flex; flex-direction: column; gap: 16px; overflow: hidden; min-height: 0; }
        .detail-section { background: #fff; border-radius: 12px; flex: 1; display: flex; flex-direction: column; overflow: hidden; min-height: 0; }
        .detail-header { padding: 14px 16px; border-bottom: 1px solid #e2e8f0; flex-shrink: 0; }
        .detail-title { font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
        .detail-body { flex: 1; overflow-y: auto; padding: 16px; min-height: 0; }
        .detail-info { margin-bottom: 20px; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; font-size: 14px; border-bottom: 1px solid #f1f5f9; }
        .detail-row:last-child { border-bottom: none; }
        .detail-row .label { color: #64748b; }
        .detail-row .value { font-weight: 700; }
        .detail-row.phone .value { font-size: 18px; color: #1e293b; direction: ltr; }
        .items-table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        .items-table th { background: #f8fafc; padding: 10px 12px; text-align: right; font-weight: 700; font-size: 13px; color: #64748b; }
        .items-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .payments-info { margin-top: 16px; padding-top: 16px; border-top: 1px solid #e2e8f0; }
        .payments-title { font-size: 13px; font-weight: 700; color: #64748b; margin-bottom: 8px; }
        .payment-row { display: flex; justify-content: space-between; padding: 8px 12px; background: #f0fdf4; border-radius: 6px; margin-bottom: 6px; }
        .payment-row .method { font-weight: 600; font-size: 13px; }
        .payment-row .amount { font-weight: 700; color: #059669; }
        .total-row { display: flex; justify-content: space-between; padding: 14px 16px; background: #1e293b; color: #fff; border-radius: 10px; margin-top: 16px; font-size: 16px; font-weight: 800; }
        .detail-footer { padding: 16px; border-top: 1px solid #e2e8f0; flex-shrink: 0; }
        .deliver-btn { width: 100%; padding: 16px; background: #10b981; color: #fff; border: none; border-radius: 10px; font-size: 18px; font-weight: 700; cursor: pointer; font-family: inherit; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.15s; }
        .deliver-btn:hover { background: #059669; }
        .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px; color: #94a3b8; }
        .empty-state i { font-size: 64px; margin-bottom: 16px; }
        .empty-state h4 { font-size: 18px; margin-bottom: 8px; color: #64748b; }
        .hidden { display: none !important; }
        .swal2-popup.swal-rtl { font-family: 'Cairo', sans-serif !important; direction: rtl !important; border-radius: 16px !important; border: 2px solid #e2e8f0 !important; }
        .swal2-popup .swal-title-rtl { font-family: 'Cairo', sans-serif !important; font-size: 20px !important; font-weight: 700 !important; }
        .weight-tag { display: inline-flex; align-items: center; gap: 2px; color: #8b5cf6; font-size: 11px; margin-right: 4px; }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="header">
            <div class="logo"><img src="{{ asset('logo-dark.png') }}" alt="Logo"></div>
            <div class="header-left">
                <a href="{{ route('cashier.index') }}" class="back-btn">
                    <i class="ti ti-arrow-right"></i>
                    العودة للكاشير
                </a>
                <div class="user-info">
                    <i class="ti ti-user"></i>
                    {{ auth()->user()->name }}
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="left-panel">
                <div class="search-section">
                    <div class="search-row">
                        <input type="text" class="search-input" id="searchInput" placeholder="بحث برقم الفاتورة أو رقم الهاتف..." autofocus>
                        <button class="search-btn" id="searchBtn"><i class="ti ti-search"></i> بحث</button>
                        <button class="refresh-btn" id="refreshBtn" title="تحديث"><i class="ti ti-refresh"></i></button>
                    </div>
                </div>

                <div class="list-section">
                    <div class="list-header">
                        <div class="list-title">
                            <i class="ti ti-truck-delivery"></i>
                            طلبات قيد التوصيل
                            <span class="count-badge" id="countBadge">0</span>
                        </div>
                    </div>
                    <div class="list-body" id="ordersList">
                        <div class="empty-state" id="emptyState">
                            <i class="ti ti-truck-delivery"></i>
                            <h4>لا توجد طلبات قيد التوصيل</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right-panel">
                <div class="detail-section">
                    <div class="detail-header">
                        <div class="detail-title">
                            <i class="ti ti-file-description"></i>
                            تفاصيل الطلب
                        </div>
                    </div>
                    <div class="detail-body" id="detailBody">
                        <div class="empty-state" id="detailEmpty">
                            <i class="ti ti-click"></i>
                            <h4>اختر طلب من القائمة</h4>
                        </div>
                        <div class="hidden" id="detailContent">
                            <div class="detail-info">
                                <div class="detail-row">
                                    <span class="label">رقم الفاتورة</span>
                                    <span class="value" id="detailOrderNumber">-</span>
                                </div>
                                <div class="detail-row phone">
                                    <span class="label"><i class="ti ti-phone"></i> هاتف التوصيل</span>
                                    <span class="value" id="detailPhone">-</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">الزبون</span>
                                    <span class="value" id="detailCustomer">-</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">الكاشير</span>
                                    <span class="value" id="detailCashier">-</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">تاريخ الدفع</span>
                                    <span class="value" id="detailDate">-</span>
                                </div>
                            </div>

                            <table class="items-table">
                                <thead>
                                    <tr>
                                        <th>الصنف</th>
                                        <th style="text-align:center">الكمية</th>
                                        <th style="text-align:center">السعر</th>
                                        <th style="text-align:left">الإجمالي</th>
                                    </tr>
                                </thead>
                                <tbody id="detailItems"></tbody>
                            </table>

                            <div class="payments-info">
                                <div class="payments-title">طرق الدفع</div>
                                <div id="detailPayments"></div>
                            </div>

                            <div class="hidden" id="detailDiscountRow" style="display:flex;justify-content:space-between;padding:10px 16px;background:#fef3c7;border-radius:8px;margin-top:12px;font-weight:700;">
                                <span style="color:#92400e;">الخصم</span>
                                <span style="color:#d97706;" id="detailDiscount">0.000</span>
                            </div>

                            <div class="total-row">
                                <span>الإجمالي</span>
                                <span id="detailTotal">0.000 د.ل</span>
                            </div>
                        </div>
                    </div>
                    <div class="detail-footer hidden" id="detailFooter">
                        <button class="deliver-btn" id="deliverBtn">
                            <i class="ti ti-check"></i>
                            تم التوصيل
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const BASE_URL = "{{ url('/') }}";
        let orders = [];
        let selectedOrder = null;

        document.addEventListener('DOMContentLoaded', function() {
            loadOrders();

            document.getElementById('searchBtn').addEventListener('click', loadOrders);
            document.getElementById('refreshBtn').addEventListener('click', function() {
                document.getElementById('searchInput').value = '';
                loadOrders();
            });
            document.getElementById('searchInput').addEventListener('keydown', function(e) {
                if (e.key === 'Enter') loadOrders();
            });
            document.getElementById('deliverBtn').addEventListener('click', markAsDelivered);

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    clearSelection();
                }
            });
        });

        async function loadOrders() {
            const search = document.getElementById('searchInput').value.trim();
            let url = BASE_URL + '/cashier/deliveries/data';
            if (search) url += '?search=' + encodeURIComponent(search);

            try {
                const res = await fetch(url);
                const data = await res.json();
                if (data.success) {
                    orders = data.data;
                    renderOrders();
                }
            } catch (err) {
                console.error('loadOrders error:', err);
                toast('خطأ في تحميل البيانات', 'error');
            }
        }

        function renderOrders() {
            const container = document.getElementById('ordersList');
            document.getElementById('countBadge').textContent = orders.length;

            if (orders.length === 0) {
                container.innerHTML = `<div class="empty-state"><i class="ti ti-truck-delivery"></i><h4>لا توجد طلبات قيد التوصيل</h4></div>`;
                clearSelection();
                return;
            }

            container.innerHTML = orders.map(order => `
                <div class="order-card ${selectedOrder && selectedOrder.id === order.id ? 'active' : ''}" onclick="selectOrder(${order.id})">
                    <div class="order-card-header">
                        <span class="order-number">#${order.order_number}</span>
                        <span class="order-total">${parseFloat(order.total).toFixed(3)} د.ل</span>
                    </div>
                    <div class="order-card-body">
                        <div class="order-info">
                            <i class="ti ti-phone"></i>
                            <span class="order-phone">${order.delivery_phone || '-'}</span>
                        </div>
                        ${order.customer_name ? `<div class="order-info"><i class="ti ti-user"></i> ${order.customer_name}</div>` : ''}
                        <div class="order-info">
                            <i class="ti ti-clock"></i>
                            ${order.paid_at || '-'}
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function selectOrder(id) {
            selectedOrder = orders.find(o => o.id === id);
            if (!selectedOrder) return;

            renderOrders();

            document.getElementById('detailEmpty').classList.add('hidden');
            document.getElementById('detailContent').classList.remove('hidden');
            document.getElementById('detailFooter').classList.remove('hidden');

            document.getElementById('detailOrderNumber').textContent = '#' + selectedOrder.order_number;
            document.getElementById('detailPhone').textContent = selectedOrder.delivery_phone || '-';
            document.getElementById('detailCustomer').textContent = selectedOrder.customer_name || 'زبون غير مسجل';
            document.getElementById('detailCashier').textContent = selectedOrder.cashier_name || '-';
            document.getElementById('detailDate').textContent = selectedOrder.paid_at || '-';

            const itemsBody = document.getElementById('detailItems');
            itemsBody.innerHTML = selectedOrder.items.map(item => {
                const qty = item.is_weight ? parseFloat(item.quantity).toFixed(3) + ' كجم' : item.quantity;
                return `<tr>
                    <td>${item.product_name}${item.is_weight ? '<span class="weight-tag"><i class="ti ti-scale"></i></span>' : ''}</td>
                    <td style="text-align:center">${qty}</td>
                    <td style="text-align:center">${parseFloat(item.price).toFixed(3)}</td>
                    <td style="text-align:left">${parseFloat(item.total).toFixed(3)}</td>
                </tr>`;
            }).join('');

            const paymentsDiv = document.getElementById('detailPayments');
            paymentsDiv.innerHTML = selectedOrder.payments.map(p => `
                <div class="payment-row">
                    <span class="method">${p.method}</span>
                    <span class="amount">${parseFloat(p.amount).toFixed(3)} د.ل</span>
                </div>
            `).join('');

            const discountVal = parseFloat(selectedOrder.discount) || 0;
            const discountRow = document.getElementById('detailDiscountRow');
            if (discountVal > 0) {
                discountRow.classList.remove('hidden');
                discountRow.style.display = 'flex';
                document.getElementById('detailDiscount').textContent = '-' + discountVal.toFixed(3) + ' د.ل';
            } else {
                discountRow.classList.add('hidden');
            }

            document.getElementById('detailTotal').textContent = parseFloat(selectedOrder.total).toFixed(3) + ' د.ل';
        }

        function clearSelection() {
            selectedOrder = null;
            document.getElementById('detailEmpty').classList.remove('hidden');
            document.getElementById('detailContent').classList.add('hidden');
            document.getElementById('detailFooter').classList.add('hidden');
        }

        async function markAsDelivered() {
            if (!selectedOrder) return;

            const confirm = await Swal.fire({
                title: '<i class="ti ti-check" style="color:#10b981;font-size:28px;"></i><br>تأكيد التوصيل',
                html: `<div style="font-family:Cairo,sans-serif;direction:rtl;">هل تم توصيل الفاتورة <b>#${selectedOrder.order_number}</b>؟</div>`,
                showCancelButton: true,
                confirmButtonText: '<i class="ti ti-check"></i> نعم، تم التوصيل',
                cancelButtonText: 'لا',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                customClass: {
                    popup: 'swal-rtl',
                    title: 'swal-title-rtl'
                }
            });

            if (!confirm.isConfirmed) return;

            try {
                const res = await fetch(BASE_URL + '/cashier/deliveries/mark-delivered', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ order_id: selectedOrder.id })
                });
                const data = await res.json();
                if (data.success) {
                    toast(data.message, 'success');
                    clearSelection();
                    loadOrders();
                } else {
                    toast(data.message, 'error');
                }
            } catch (err) {
                console.error('markAsDelivered error:', err);
                toast('خطأ في الاتصال', 'error');
            }
        }

        function toast(msg, type = 'info') {
            Swal.fire({
                toast: true,
                position: 'top',
                icon: type,
                title: msg,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                customClass: { popup: 'swal-rtl' }
            });
        }
    </script>
</body>
</html>
