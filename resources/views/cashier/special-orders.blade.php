<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الطلبيات الخاصة - {{ config('app.name') }}</title>
    <link href="{{ asset('assets/fonts/cairo/cairo.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}">
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; overflow: hidden; }
        body { font-family: 'Cairo', sans-serif; background: #f1f5f9; color: #1e293b; }
        .app-container { display: flex; flex-direction: column; height: 100vh; overflow: hidden; }
        .header { background: #fff; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; flex-shrink: 0; }
        .header .logo { display: flex; align-items: center; }
        .header .logo img { height: 80px; }
        .header-left { display: flex; align-items: center; gap: 12px; }
        .back-btn { display: flex; align-items: center; gap: 6px; padding: 10px 20px; background: linear-gradient(135deg, #3b82f6, #2563eb); border: none; border-radius: 10px; color: #fff; font-weight: 700; cursor: pointer; font-family: inherit; font-size: 15px; text-decoration: none; transition: all 0.2s; position: relative; }
        .back-btn:hover { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; }
        .back-btn i { font-size: 22px; }
        .shortcut-badge { position: absolute; top: -6px; left: -6px; background: #1e293b; color: #fff; font-size: 10px; font-weight: 800; padding: 2px 6px; border-radius: 6px; line-height: 1.3; box-shadow: 0 2px 6px rgba(0,0,0,0.3); }
        .user-info { display: flex; align-items: center; gap: 6px; padding: 6px 12px; background: #f8fafc; border-radius: 6px; font-weight: 600; font-size: 14px; }
        .shortcuts-bar { display: flex; align-items: center; gap: 16px; padding: 8px 24px; background: #1e293b; flex-shrink: 0; }
        .shortcut-hint { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #94a3b8; }
        .shortcut-hint kbd { background: #334155; color: #e2e8f0; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; font-family: inherit; }

        .main-area { flex: 1; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 24px; }

        .home-screen { text-align: center; }
        .home-screen i { font-size: 80px; color: #cbd5e1; margin-bottom: 16px; display: block; }
        .home-screen h2 { font-size: 24px; color: #64748b; margin-bottom: 24px; }
        .home-actions { display: flex; gap: 20px; justify-content: center; }
        .home-action { display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 24px 40px; background: #fff; border: 3px solid #e2e8f0; border-radius: 16px; cursor: pointer; transition: all 0.2s; font-family: inherit; font-size: 16px; font-weight: 700; color: #1e293b; }
        .home-action:hover { border-color: #8b5cf6; background: #faf5ff; transform: translateY(-2px); }
        .home-action i { font-size: 36px; color: #8b5cf6; }
        .home-action kbd { background: #1e293b; color: #fff; padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 800; font-family: inherit; }

        .view-panel { width: 100%; max-width: 900px; height: 100%; background: #fff; border-radius: 16px; overflow: hidden; display: none; flex-direction: column; }
        .view-panel.active { display: flex; }
        .view-header { padding: 16px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; }
        .view-title { font-size: 18px; font-weight: 800; display: flex; align-items: center; gap: 8px; }
        .view-body { flex: 1; overflow-y: auto; padding: 24px; }
        .view-footer { padding: 16px 24px; border-top: 1px solid #e2e8f0; flex-shrink: 0; display: flex; gap: 12px; }
        .view-btn { flex: 1; padding: 14px; border: none; border-radius: 10px; font-size: 16px; font-weight: 700; cursor: pointer; font-family: inherit; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .view-btn.primary { background: #8b5cf6; color: #fff; }
        .view-btn.success { background: #10b981; color: #fff; }
        .view-btn.secondary { background: #f1f5f9; color: #64748b; }

        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
        .detail-item { }
        .detail-label { font-size: 12px; color: #94a3b8; font-weight: 600; margin-bottom: 4px; }
        .detail-value { font-size: 16px; font-weight: 700; }
        .status-badge { display: inline-flex; padding: 4px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; }
        .status-badge.pending { background: #fef3c7; color: #d97706; }
        .status-badge.in_progress { background: #dbeafe; color: #2563eb; }
        .status-badge.ready { background: #e0e7ff; color: #4f46e5; }
        .status-badge.delivered { background: #d1fae5; color: #059669; }
        .status-badge.cancelled { background: #fee2e2; color: #dc2626; }

        .items-table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        .items-table th { background: #f8fafc; padding: 10px 12px; text-align: right; font-weight: 700; font-size: 13px; color: #64748b; }
        .items-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .weight-tag { display: inline-flex; align-items: center; gap: 2px; color: #8b5cf6; font-size: 11px; margin-right: 4px; }

        .summary-box { background: #f8fafc; border-radius: 12px; padding: 16px; margin-top: 16px; }
        .summary-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; }
        .summary-row.total { font-size: 18px; font-weight: 800; border-top: 2px solid #e2e8f0; padding-top: 12px; margin-top: 8px; }
        .summary-row.total .val { color: #10b981; }
        .summary-row.remaining .val { color: #ef4444; font-weight: 700; }
        .summary-row.paid .val { color: #3b82f6; font-weight: 700; }
        .payment-item { display: flex; justify-content: space-between; padding: 8px 12px; background: #f0fdf4; border-radius: 8px; margin-bottom: 6px; font-size: 13px; }
        .payment-item .amount { font-weight: 700; color: #059669; }

        .wizard-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1000; display: none; align-items: center; justify-content: center; }
        .wizard-overlay.active { display: flex; }
        .wizard-card { background: #fff; border-radius: 20px; padding: 32px; width: 100%; max-width: 520px; max-height: 85vh; overflow-y: auto; }
        .wizard-title { font-size: 14px; color: #94a3b8; font-weight: 700; text-align: center; margin-bottom: 8px; }
        .wizard-heading { font-size: 22px; font-weight: 800; text-align: center; margin-bottom: 24px; }

        .wizard-input { width: 100%; padding: 14px 18px; font-size: 18px; font-weight: 700; border: 2px solid #e2e8f0; border-radius: 12px; font-family: inherit; text-align: center; margin-bottom: 12px; }
        .wizard-input:focus { outline: none; border-color: #8b5cf6; }
        .wizard-input.right { text-align: right; font-size: 16px; }
        .wizard-select { width: 100%; padding: 14px 18px; font-size: 16px; font-weight: 700; border: 2px solid #e2e8f0; border-radius: 12px; font-family: inherit; background: #fff; margin-bottom: 12px; }
        .wizard-select:focus { outline: none; border-color: #8b5cf6; }

        .wizard-hint { text-align: center; font-size: 13px; color: #94a3b8; margin-top: 8px; }
        .wizard-hint kbd { background: #f1f5f9; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-family: inherit; border: 1px solid #e2e8f0; }

        .wizard-items { margin: 16px 0; }
        .wizard-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: #f8fafc; border-radius: 8px; margin-bottom: 8px; }
        .wizard-item .name { font-weight: 700; font-size: 14px; }
        .wizard-item .info { font-size: 12px; color: #64748b; }
        .wizard-item .total { font-weight: 800; color: #8b5cf6; }
        .wizard-item .remove { background: none; border: none; color: #ef4444; cursor: pointer; font-size: 18px; padding: 4px; }

        .wizard-total-bar { background: #1e293b; color: #fff; border-radius: 12px; padding: 14px 20px; display: flex; justify-content: space-between; font-size: 18px; font-weight: 800; margin: 16px 0; }

        .pm-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin: 16px 0; }
        .pm-option { padding: 14px; background: #f8fafc; border: 3px solid #e2e8f0; border-radius: 12px; text-align: center; font-size: 15px; font-weight: 700; cursor: pointer; transition: all 0.15s; }
        .pm-option:hover { border-color: #8b5cf6; background: #faf5ff; }
        .pm-option.selected { border-color: #8b5cf6; background: #8b5cf6; color: #fff; }

        .product-results { max-height: 250px; overflow-y: auto; margin: 12px 0; }
        .product-option { display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; border: 2px solid #e2e8f0; border-radius: 10px; margin-bottom: 8px; cursor: pointer; transition: all 0.15s; }
        .product-option:hover { border-color: #8b5cf6; background: #faf5ff; }
        .product-option.highlighted { border-color: #8b5cf6; background: #ede9fe; }
        .product-option .pname { font-weight: 700; }
        .product-option .pprice { font-size: 13px; color: #64748b; }

        .customer-dropdown { max-height: 150px; overflow-y: auto; border: 2px solid #e2e8f0; border-top: none; border-radius: 0 0 12px 12px; margin-top: -12px; margin-bottom: 12px; }
        .customer-dropdown-item { padding: 10px 14px; cursor: pointer; border-bottom: 1px solid #f1f5f9; }
        .customer-dropdown-item:hover { background: #f8fafc; }
        .customer-dropdown-item .cdname { font-weight: 600; }
        .customer-dropdown-item .cdphone { font-size: 12px; color: #64748b; }

        .hidden { display: none !important; }
        .swal2-popup.swal-rtl { font-family: 'Cairo', sans-serif !important; direction: rtl !important; border-radius: 16px !important; padding: 24px !important; }
        .swal2-popup .swal-title-rtl { font-family: 'Cairo', sans-serif !important; font-size: 20px !important; font-weight: 700 !important; line-height: 1.6 !important; }
        .swal2-popup .swal2-html-container { font-family: 'Cairo', sans-serif !important; margin: 16px 0 !important; }
        .swal2-actions { flex-direction: row-reverse !important; gap: 12px !important; margin-top: 16px !important; }
        .swal2-confirm, .swal2-cancel { font-family: 'Cairo', sans-serif !important; font-weight: 600 !important; font-size: 15px !important; padding: 10px 24px !important; border-radius: 8px !important; min-width: 100px !important; }
        .swal2-container { z-index: 99999 !important; }
        .swal2-backdrop-show { background: transparent !important; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="header">
            <div class="logo"><img src="{{ asset('logo-dark.png') }}" alt="تاج السلطان"></div>
            <div class="header-left">
                <a href="{{ route('cashier.index') }}" class="back-btn" id="backBtn">
                    <span class="shortcut-badge">Esc</span>
                    <i class="ti ti-arrow-right"></i>
                    العودة للكاشير
                </a>
                <div class="user-info">
                    <i class="ti ti-user"></i>
                    {{ auth()->user()->name }}
                </div>
            </div>
        </div>

        <div class="shortcuts-bar">
            <div class="shortcut-hint"><kbd>Space</kbd> بحث طلبية</div>
            <div class="shortcut-hint"><kbd>Enter</kbd> طلبية جديدة</div>
            <div class="shortcut-hint"><kbd>Esc</kbd> رجوع</div>
        </div>

        <div class="main-area">
            <div class="home-screen" id="homeScreen">
                <i class="ti ti-cake"></i>
                <h2>الطلبيات الخاصة</h2>
                <div class="home-actions">
                    <button class="home-action" id="btnNewOrder">
                        <i class="ti ti-plus"></i>
                        طلبية جديدة
                        <kbd>Enter</kbd>
                    </button>
                    <button class="home-action" id="btnSearch">
                        <i class="ti ti-search"></i>
                        بحث عن طلبية
                        <kbd>Space</kbd>
                    </button>
                </div>
            </div>

            <div class="view-panel" id="viewPanel">
                <div class="view-header">
                    <div class="view-title" id="viewTitle"><i class="ti ti-cake"></i> <span>طلبية</span></div>
                    <span class="status-badge" id="viewStatus"></span>
                </div>
                <div class="view-body" id="viewBody"></div>
                <div class="view-footer" id="viewFooter"></div>
            </div>
        </div>
    </div>

    <div class="wizard-overlay" id="wizardOverlay">
        <div class="wizard-card">
            <div id="wizardContent"></div>
        </div>
    </div>

    <script>
        const BASE_URL = "{{ url('/') }}";
        const PAYMENT_METHODS = [
            @foreach($paymentMethods as $method)
            { id: {{ $method->id }}, name: "{{ $method->name }}" },
            @endforeach
        ];
        const EVENT_TYPES = [
            @foreach($eventTypes as $type)
            "{{ $type->name }}",
            @endforeach
        ];

        let allProducts = [];
        let currentOrder = null;

        let wStep = 0;
        let wData = { customerName: '', customerPhone: '', customerId: null, eventType: '', deliveryDate: '', notes: '', items: [] };
        let wProductResults = [];
        let wProductIndex = -1;
        let wPmIndex = 0;
        let wViewPmIndex = 0;
        let wPayments = [];

        document.addEventListener('DOMContentLoaded', async function() {
            document.getElementById('btnNewOrder').addEventListener('click', startNewOrder);
            document.getElementById('btnSearch').addEventListener('click', openSearch);
            document.addEventListener('keydown', handleKeys);
            await loadProducts();
        });

        async function loadProducts() {
            try {
                const res = await fetch(BASE_URL + '/cashier/special-orders/products');
                const data = await res.json();
                if (data.success) allProducts = data.data;
            } catch (err) {}
        }

        function handleKeys(e) {
            if (wStep > 0) {
                handleWizardKeys(e);
                return;
            }

            if (currentOrder && document.getElementById('viewPanel').classList.contains('active')) {
                handleViewKeys(e);
                return;
            }

            if (e.key === 'Enter') {
                e.preventDefault();
                startNewOrder();
                return;
            }
            if (e.key === ' ') {
                e.preventDefault();
                openSearch();
                return;
            }
            if (e.key === 'Escape') {
                e.preventDefault();
                window.location.href = document.getElementById('backBtn').href;
                return;
            }
        }

        function startNewOrder() {
            wData = { customerName: '', customerPhone: '', customerId: null, eventType: '', deliveryDate: '', notes: '', items: [] };
            wPmIndex = 0;
            wPayments = [];
            wStep = 1;
            renderWizard();
        }

        async function openSearch() {
            const { value: search } = await Swal.fire({
                title: 'بحث عن طلبية',
                input: 'text',
                inputPlaceholder: 'رقم الطلبية أو اسم الزبون أو رقم الهاتف',
                showCancelButton: true,
                confirmButtonText: '<i class="ti ti-search"></i> بحث',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: '#64748b',
                customClass: { popup: 'swal-rtl', title: 'swal-title-rtl' },
                inputValidator: v => { if (!v) return 'أدخل كلمة البحث'; }
            });
            if (!search) return;

            try {
                const res = await fetch(BASE_URL + '/cashier/special-orders/fetch', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ search })
                });
                const data = await res.json();
                if (data.success) {
                    currentOrder = data.data;
                    showOrderView();
                    toast('تم جلب الطلبية', 'success');
                } else {
                    toast(data.message, 'error');
                }
            } catch (err) {
                toast('خطأ في الاتصال', 'error');
            }
        }

        function showOrderView() {
            document.getElementById('homeScreen').style.display = 'none';
            const panel = document.getElementById('viewPanel');
            panel.classList.add('active');

            document.getElementById('viewTitle').querySelector('span').textContent = `طلبية #${currentOrder.id}`;
            const badge = document.getElementById('viewStatus');
            badge.className = 'status-badge ' + currentOrder.status;
            badge.textContent = currentOrder.status_name;

            let itemsHtml = currentOrder.items.map(item => {
                const qty = item.is_weight ? parseFloat(item.quantity).toFixed(3) + ' كجم' : item.quantity;
                return `<tr><td>${item.product_name}${item.is_weight ? '<span class="weight-tag"><i class="ti ti-scale"></i></span>' : ''}</td><td style="text-align:center">${qty}</td><td style="text-align:center">${parseFloat(item.price).toFixed(3)}</td><td style="text-align:left">${parseFloat(item.total).toFixed(3)}</td></tr>`;
            }).join('');

            let paymentsHtml = currentOrder.payments.map(p => `<div class="payment-item"><span>${p.method} ${p.notes ? '(' + p.notes + ')' : ''}</span><span class="amount">${parseFloat(p.amount).toFixed(3)} د.ل</span></div>`).join('') || '<div style="color:#94a3b8;text-align:center;padding:8px;">لا توجد مدفوعات</div>';

            let notesHtml = currentOrder.notes ? `<div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:12px;margin-bottom:16px;font-size:14px;"><strong style="color:#d97706;"><i class="ti ti-note"></i> ملاحظات:</strong> ${currentOrder.notes}</div>` : '';

            document.getElementById('viewBody').innerHTML = `
                <div class="detail-grid">
                    <div class="detail-item"><div class="detail-label">الزبون</div><div class="detail-value">${currentOrder.customer_name}</div></div>
                    <div class="detail-item"><div class="detail-label">الهاتف</div><div class="detail-value">${currentOrder.phone || '-'}</div></div>
                    <div class="detail-item"><div class="detail-label">المناسبة</div><div class="detail-value">${currentOrder.event_type}</div></div>
                    <div class="detail-item"><div class="detail-label">تاريخ التسليم</div><div class="detail-value">${currentOrder.delivery_date}</div></div>
                </div>
                ${notesHtml}
                <table class="items-table"><thead><tr><th>الصنف</th><th style="text-align:center">الكمية</th><th style="text-align:center">السعر</th><th style="text-align:left">الإجمالي</th></tr></thead><tbody>${itemsHtml}</tbody></table>
                <div style="margin-top:16px;font-weight:700;font-size:14px;color:#64748b;margin-bottom:8px;"><i class="ti ti-cash"></i> المدفوعات</div>
                ${paymentsHtml}
                <div class="summary-box">
                    <div class="summary-row total"><span>الإجمالي</span><span class="val">${parseFloat(currentOrder.total_amount).toFixed(3)} د.ل</span></div>
                    <div class="summary-row paid"><span>المدفوع</span><span class="val">${parseFloat(currentOrder.paid_amount).toFixed(3)} د.ل</span></div>
                    <div class="summary-row remaining"><span>المتبقي</span><span class="val">${parseFloat(currentOrder.remaining_amount).toFixed(3)} د.ل</span></div>
                </div>
            `;

            let footerHtml = '<button class="view-btn secondary" onclick="closeView()"><i class="ti ti-arrow-right"></i> رجوع</button>';
            footerHtml += '<button class="view-btn primary" onclick="printOrder()"><i class="ti ti-printer"></i> طباعة</button>';
            if (currentOrder.status !== 'delivered' && currentOrder.status !== 'cancelled' && currentOrder.remaining_amount > 0) {
                footerHtml += '<button class="view-btn success" onclick="openAddPayment()"><i class="ti ti-cash"></i> إضافة دفعة</button>';
            }
            if (currentOrder.status !== 'delivered' && currentOrder.status !== 'cancelled') {
                footerHtml += '<button class="view-btn" style="background:#059669;color:#fff;" onclick="changeOrderStatus(\'delivered\')"><i class="ti ti-truck-delivery"></i> تم التسليم</button>';
            }
            document.getElementById('viewFooter').innerHTML = footerHtml;
            wViewPmIndex = 0;
        }

        function closeView() {
            currentOrder = null;
            document.getElementById('viewPanel').classList.remove('active');
            document.getElementById('homeScreen').style.display = '';
        }

        function handleViewKeys(e) {
            if (e.key === 'Escape') {
                e.preventDefault();
                closeView();
                return;
            }
            if (e.key === 'F4') {
                e.preventDefault();
                printOrder();
                return;
            }
            if (e.key === 'Enter') {
                e.preventDefault();
                if (currentOrder.remaining_amount > 0) openAddPayment();
                return;
            }
        }

        function openAddPayment() {
            if (!currentOrder || currentOrder.remaining_amount <= 0) return;
            wStep = 10;
            wViewPmIndex = 0;
            renderWizard();
        }

        function renderWizard() {
            const overlay = document.getElementById('wizardOverlay');
            const content = document.getElementById('wizardContent');
            overlay.classList.add('active');

            if (wStep === 1) {
                content.innerHTML = `
                    <div class="wizard-title">الخطوة 1 من 5</div>
                    <div class="wizard-heading"><i class="ti ti-user"></i> بيانات الزبون</div>
                    <input type="text" class="wizard-input right" id="wCustomerName" placeholder="اسم الزبون *" value="${wData.customerName}" autocomplete="off">
                    <div id="wCustomerDropdown"></div>
                    <input type="text" class="wizard-input right" id="wCustomerPhone" placeholder="رقم الهاتف (اختياري)" value="${wData.customerPhone}">
                    <div class="wizard-hint"><kbd>Enter</kbd> التالي &nbsp; <kbd>Esc</kbd> إلغاء</div>
                `;
                setTimeout(() => {
                    const nameEl = document.getElementById('wCustomerName');
                    nameEl.focus();
                    nameEl.addEventListener('input', debounce(wizardSearchCustomers, 300));
                }, 50);
            } else if (wStep === 2) {
                let optionsHtml = '<option value="">اختر المناسبة *</option>';
                EVENT_TYPES.forEach(t => { optionsHtml += `<option value="${t}" ${wData.eventType === t ? 'selected' : ''}>${t}</option>`; });
                optionsHtml += `<option value="other" ${wData.eventType === 'other' ? 'selected' : ''}>أخرى</option>`;

                content.innerHTML = `
                    <div class="wizard-title">الخطوة 2 من 5</div>
                    <div class="wizard-heading"><i class="ti ti-calendar-event"></i> تفاصيل المناسبة</div>
                    <select class="wizard-select" id="wEventType">${optionsHtml}</select>
                    <input type="date" class="wizard-input" id="wDeliveryDate" value="${wData.deliveryDate}" style="font-size:16px;">
                    <textarea class="wizard-input right" id="wNotes" placeholder="ملاحظات (اختياري)" rows="2" style="font-size:14px;resize:none;height:auto;text-align:right;">${wData.notes}</textarea>
                    <div class="wizard-hint"><kbd>Enter</kbd> التالي &nbsp; <kbd>Esc</kbd> السابق</div>
                `;
                setTimeout(() => document.getElementById('wEventType').focus(), 50);
            } else if (wStep === 3) {
                let itemsHtml = '';
                let total = 0;
                wData.items.forEach((item, i) => {
                    total += item.total;
                    const qty = item.is_weight ? parseFloat(item.quantity).toFixed(3) + ' كجم' : item.quantity;
                    itemsHtml += `<div class="wizard-item"><div><div class="name">${item.product_name}</div><div class="info">${qty} × ${parseFloat(item.price).toFixed(3)}</div></div><div style="display:flex;align-items:center;gap:12px;"><span class="total">${parseFloat(item.total).toFixed(3)}</span><button class="wizard-item .remove" onclick="removeWizardItem(${i})" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:18px;padding:4px;"><i class="ti ti-x"></i></button></div></div>`;
                });

                content.innerHTML = `
                    <div class="wizard-title">الخطوة 3 من 5</div>
                    <div class="wizard-heading"><i class="ti ti-box"></i> الأصناف</div>
                    <input type="text" class="wizard-input right" id="wProductSearch" placeholder="ابحث عن صنف...">
                    <div class="product-results" id="wProductList"></div>
                    <div class="wizard-items" id="wItemsList">${itemsHtml || '<div style="text-align:center;color:#94a3b8;padding:16px;">لا توجد أصناف بعد</div>'}</div>
                    ${total > 0 ? `<div class="wizard-total-bar"><span>الإجمالي</span><span>${total.toFixed(3)} د.ل</span></div>` : ''}
                    <div class="wizard-hint"><kbd>↑↓</kbd> تنقل &nbsp; <kbd>Enter</kbd> اختيار / التالي &nbsp; <kbd>Esc</kbd> السابق</div>
                `;
                wProductResults = [];
                wProductIndex = -1;
                setTimeout(() => {
                    document.getElementById('wProductSearch').focus();
                    document.getElementById('wProductSearch').addEventListener('input', wizardSearchProducts);
                }, 50);
            } else if (wStep === 4) {
                let total = wData.items.reduce((s, i) => s + i.total, 0);
                let paidSoFar = wPayments.reduce((s, p) => s + p.amount, 0);
                let remaining = total - paidSoFar;
                let pmHtml = PAYMENT_METHODS.map((m, i) => `<div class="pm-option ${i === wPmIndex ? 'selected' : ''}" data-idx="${i}" onclick="wPmIndex=${i};updatePmHighlight();">${m.name}</div>`).join('');

                let paymentsListHtml = '';
                if (wPayments.length > 0) {
                    paymentsListHtml = '<div class="wizard-items">' + wPayments.map((p, i) => `<div class="wizard-item"><div><div class="name">${p.method_name}</div><div class="info">${parseFloat(p.amount).toFixed(3)} د.ل</div></div><div><button onclick="removeWPayment(${i})" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:18px;padding:4px;"><i class="ti ti-x"></i></button></div></div>`).join('') + '</div>';
                }

                content.innerHTML = `
                    <div class="wizard-title">الخطوة 4 من 5</div>
                    <div class="wizard-heading"><i class="ti ti-cash"></i> الدفع</div>
                    <div class="wizard-total-bar"><span>الإجمالي</span><span>${total.toFixed(3)} د.ل</span></div>
                    ${paymentsListHtml}
                    ${remaining > 0.001 ? `
                    <div style="text-align:center;font-size:13px;color:#64748b;margin:8px 0;">المتبقي: <strong style="color:#ef4444;">${remaining.toFixed(3)} د.ل</strong></div>
                    <div class="pm-grid" id="wPmGrid">${pmHtml}</div>
                    <input type="number" class="wizard-input" id="wPayAmount" step="0.001" placeholder="المبلغ" value="${remaining.toFixed(3)}">
                    <div style="display:flex;gap:8px;margin-top:8px;">
                        <button type="button" onclick="addWPayment()" style="flex:1;padding:12px;background:#3b82f6;color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;font-family:inherit;"><i class="ti ti-plus"></i> إضافة دفعة</button>
                        <button type="button" onclick="saveNewOrder()" style="flex:1;padding:12px;background:#10b981;color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;font-family:inherit;"><i class="ti ti-check"></i> حفظ الطلبية</button>
                    </div>
                    <div class="wizard-hint"><kbd>←→</kbd> طريقة الدفع &nbsp; <kbd>Enter</kbd> إضافة دفعة &nbsp; <kbd>Ctrl+Enter</kbd> حفظ &nbsp; <kbd>Esc</kbd> السابق</div>
                    ` : `
                    <div style="text-align:center;padding:16px;color:#10b981;font-weight:700;font-size:16px;"><i class="ti ti-check"></i> تم تغطية المبلغ بالكامل</div>
                    <button type="button" onclick="saveNewOrder()" style="width:100%;padding:14px;background:#10b981;color:#fff;border:none;border-radius:10px;font-size:16px;font-weight:700;cursor:pointer;font-family:inherit;margin-top:8px;"><i class="ti ti-check"></i> حفظ الطلبية</button>
                    <div class="wizard-hint"><kbd>Enter</kbd> حفظ &nbsp; <kbd>Esc</kbd> السابق</div>
                    `}
                `;
                if (remaining > 0.001) {
                    setTimeout(() => document.getElementById('wPayAmount').focus(), 50);
                }
            } else if (wStep === 5) {
                content.innerHTML = `
                    <div class="wizard-title">الخطوة 3 من 5 - إضافة صنف</div>
                    <div class="wizard-heading"><i class="ti ti-box"></i> الكمية والسعر</div>
                    <div style="text-align:center;font-size:16px;font-weight:700;margin-bottom:16px;color:#8b5cf6;">${wData._selectedProduct.name}</div>
                    <input type="number" class="wizard-input" id="wQtyInput" step="0.001" placeholder="${wData._selectedProduct.type === 'weight' ? 'الوزن بالكيلو' : 'الكمية'}">
                    <input type="number" class="wizard-input" id="wPriceInput" step="0.001" value="${parseFloat(wData._selectedProduct.price).toFixed(3)}" placeholder="السعر">
                    <div class="wizard-hint"><kbd>Enter</kbd> إضافة &nbsp; <kbd>Esc</kbd> رجوع</div>
                `;
                setTimeout(() => document.getElementById('wQtyInput').focus(), 50);
            } else if (wStep === 10) {
                let remaining = parseFloat(currentOrder.remaining_amount);
                let pmHtml = PAYMENT_METHODS.map((m, i) => `<div class="pm-option ${i === wViewPmIndex ? 'selected' : ''}" data-idx="${i}" onclick="wViewPmIndex=${i};updatePmHighlight();">${m.name}</div>`).join('');

                content.innerHTML = `
                    <div class="wizard-heading"><i class="ti ti-cash"></i> إضافة دفعة</div>
                    <div style="text-align:center;background:#fef2f2;border:2px solid #fecaca;border-radius:12px;padding:12px;margin-bottom:16px;">
                        <div style="font-size:12px;color:#991b1b;">المتبقي</div>
                        <div style="font-size:28px;font-weight:800;color:#dc2626;">${remaining.toFixed(3)} د.ل</div>
                    </div>
                    <div class="pm-grid" id="wPmGrid">${pmHtml}</div>
                    <input type="number" class="wizard-input" id="wPayAmount" step="0.001" value="${remaining.toFixed(3)}" placeholder="المبلغ">
                    <div class="wizard-hint"><kbd>←→</kbd> طريقة الدفع &nbsp; <kbd>Enter</kbd> تسديد &nbsp; <kbd>Esc</kbd> إلغاء</div>
                `;
                setTimeout(() => { document.getElementById('wPayAmount').focus(); document.getElementById('wPayAmount').select(); }, 50);
            }
        }

        function handleWizardKeys(e) {
            if (e.key === 'Escape') {
                e.preventDefault();
                if (wStep === 1) { closeWizard(); }
                else if (wStep === 2) { saveWizardStep2(); wStep = 1; renderWizard(); }
                else if (wStep === 3) { wStep = 2; renderWizard(); }
                else if (wStep === 4) { wStep = 3; renderWizard(); }
                else if (wStep === 5) { wStep = 3; renderWizard(); }
                else if (wStep === 10) { closeWizard(); }
                return;
            }

            if (wStep === 1) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const focused = document.activeElement;
                    const nameEl = document.getElementById('wCustomerName');
                    const phoneEl = document.getElementById('wCustomerPhone');
                    if (focused === nameEl) {
                        document.getElementById('wCustomerDropdown').innerHTML = '';
                        phoneEl.focus();
                    } else {
                        wData.customerName = nameEl.value.trim();
                        wData.customerPhone = phoneEl.value.trim();
                        if (!wData.customerName) { toast('أدخل اسم الزبون', 'error'); nameEl.focus(); return; }
                        wStep = 2;
                        renderWizard();
                    }
                }
            } else if (wStep === 2) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const focused = document.activeElement;
                    if (focused === document.getElementById('wEventType')) {
                        document.getElementById('wDeliveryDate').focus();
                    } else if (focused === document.getElementById('wDeliveryDate')) {
                        document.getElementById('wNotes').focus();
                    } else {
                        saveWizardStep2();
                        if (!wData.eventType) { toast('اختر المناسبة', 'error'); document.getElementById('wEventType').focus(); return; }
                        if (!wData.deliveryDate) { toast('حدد تاريخ التسليم', 'error'); document.getElementById('wDeliveryDate').focus(); return; }
                        wStep = 3;
                        renderWizard();
                    }
                }
            } else if (wStep === 3) {
                const searchEl = document.getElementById('wProductSearch');
                if (document.activeElement === searchEl) {
                    if (e.key === 'ArrowDown' && wProductResults.length > 0) {
                        e.preventDefault();
                        wProductIndex = 0;
                        updateProductHighlight();
                        searchEl.blur();
                    } else if (e.key === 'Enter') {
                        e.preventDefault();
                        if (wProductResults.length > 0) {
                            wProductIndex = 0;
                            wizardSelectProduct(wProductResults[0]);
                        } else if (wData.items.length > 0) {
                            wStep = 4;
                            renderWizard();
                        }
                    }
                    return;
                }

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    wProductIndex = Math.min(wProductIndex + 1, wProductResults.length - 1);
                    updateProductHighlight();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (wProductIndex <= 0) { wProductIndex = -1; searchEl.focus(); return; }
                    wProductIndex--;
                    updateProductHighlight();
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (wProductIndex >= 0 && wProductIndex < wProductResults.length) {
                        wizardSelectProduct(wProductResults[wProductIndex]);
                    } else if (wData.items.length > 0) {
                        wStep = 4;
                        renderWizard();
                    }
                }
            } else if (wStep === 10) {
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    wViewPmIndex = (wViewPmIndex + 1) % PAYMENT_METHODS.length;
                    updatePmHighlight();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    wViewPmIndex = (wViewPmIndex - 1 + PAYMENT_METHODS.length) % PAYMENT_METHODS.length;
                    updatePmHighlight();
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    processViewPayment();
                }
            } else if (wStep === 4) {
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    wPmIndex = (wPmIndex + 1) % PAYMENT_METHODS.length;
                    updatePmHighlight();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    wPmIndex = (wPmIndex - 1 + PAYMENT_METHODS.length) % PAYMENT_METHODS.length;
                    updatePmHighlight();
                } else if (e.key === 'Enter' && e.ctrlKey) {
                    e.preventDefault();
                    saveNewOrder();
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    let total = wData.items.reduce((s, i) => s + i.total, 0);
                    let paidSoFar = wPayments.reduce((s, p) => s + p.amount, 0);
                    if (total - paidSoFar <= 0.001) { saveNewOrder(); return; }
                    addWPayment();
                }
            } else if (wStep === 5) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const qtyEl = document.getElementById('wQtyInput');
                    const priceEl = document.getElementById('wPriceInput');
                    if (document.activeElement === qtyEl) {
                        priceEl.focus();
                        priceEl.select();
                    } else {
                        const qty = parseFloat(qtyEl.value);
                        const price = parseFloat(priceEl.value);
                        if (!qty || qty <= 0) { toast('أدخل الكمية', 'error'); qtyEl.focus(); return; }
                        if (!price || price < 0) { toast('أدخل السعر', 'error'); priceEl.focus(); return; }
                        wData.items.push({
                            product_id: wData._selectedProduct.id,
                            product_name: wData._selectedProduct.name,
                            price: price,
                            quantity: qty,
                            total: price * qty,
                            is_weight: wData._selectedProduct.type === 'weight'
                        });
                        delete wData._selectedProduct;
                        toast('تم إضافة الصنف', 'success');
                        wStep = 3;
                        renderWizard();
                    }
                }
            }
        }

        function saveWizardStep2() {
            const et = document.getElementById('wEventType');
            const dd = document.getElementById('wDeliveryDate');
            const nt = document.getElementById('wNotes');
            if (et) wData.eventType = et.value;
            if (dd) wData.deliveryDate = dd.value;
            if (nt) wData.notes = nt.value.trim();
        }

        function closeWizard() {
            wStep = 0;
            document.getElementById('wizardOverlay').classList.remove('active');
        }

        function updatePmHighlight() {
            const idx = wStep === 10 ? wViewPmIndex : wPmIndex;
            document.querySelectorAll('.pm-option').forEach((el, i) => {
                el.classList.toggle('selected', i === idx);
            });
        }

        function updateProductHighlight() {
            document.querySelectorAll('.product-option').forEach((el, i) => {
                el.classList.toggle('highlighted', i === wProductIndex);
            });
            const items = document.querySelectorAll('.product-option');
            if (wProductIndex >= 0 && items[wProductIndex]) items[wProductIndex].scrollIntoView({ block: 'nearest' });
        }

        function wizardSearchProducts() {
            const search = document.getElementById('wProductSearch').value.toLowerCase().trim();
            const list = document.getElementById('wProductList');
            wProductIndex = -1;

            if (search.length < 1) { list.innerHTML = ''; wProductResults = []; return; }

            wProductResults = allProducts.filter(p => p.name.toLowerCase().includes(search));
            if (wProductResults.length === 0) { list.innerHTML = '<div style="text-align:center;padding:16px;color:#94a3b8;">لا توجد نتائج</div>'; return; }

            list.innerHTML = wProductResults.map((p, i) => `
                <div class="product-option" onclick="wizardSelectProduct(wProductResults[${i}])">
                    <span class="pname">${p.type === 'weight' ? '<span class="weight-tag"><i class="ti ti-scale"></i></span>' : ''}${p.name}</span>
                    <span class="pprice">${parseFloat(p.price).toFixed(3)} د.ل</span>
                </div>
            `).join('');
        }

        function wizardSelectProduct(product) {
            wData._selectedProduct = { id: product.id, name: product.name, price: parseFloat(product.price), type: product.type };
            wStep = 5;
            renderWizard();
        }

        function removeWizardItem(index) {
            wData.items.splice(index, 1);
            renderWizard();
        }

        async function wizardSearchCustomers() {
            const query = document.getElementById('wCustomerName').value;
            const dropdown = document.getElementById('wCustomerDropdown');
            if (query.length < 2) { dropdown.innerHTML = ''; return; }

            try {
                const res = await fetch(BASE_URL + `/cashier/special-orders/customers?q=${encodeURIComponent(query)}`);
                const data = await res.json();
                if (data.success && data.data.length > 0) {
                    dropdown.innerHTML = '<div class="customer-dropdown">' + data.data.map(c => `
                        <div class="customer-dropdown-item" onclick="pickCustomer(${c.id}, '${c.name}', '${c.phone || ''}')">
                            <div class="cdname">${c.name}</div>
                            <div class="cdphone">${c.phone || '-'}</div>
                        </div>
                    `).join('') + '</div>';
                } else {
                    dropdown.innerHTML = '';
                }
            } catch (err) {}
        }

        function pickCustomer(id, name, phone) {
            wData.customerId = id;
            wData.customerName = name;
            wData.customerPhone = phone;
            document.getElementById('wCustomerName').value = name;
            document.getElementById('wCustomerPhone').value = phone;
            document.getElementById('wCustomerDropdown').innerHTML = '';
            document.getElementById('wCustomerPhone').focus();
        }

        function addWPayment() {
            const amountEl = document.getElementById('wPayAmount');
            if (!amountEl) return;
            const amount = parseFloat(amountEl.value);
            if (!amount || amount <= 0) { toast('أدخل المبلغ', 'error'); amountEl.focus(); return; }

            const total = wData.items.reduce((s, i) => s + i.total, 0);
            const paidSoFar = wPayments.reduce((s, p) => s + p.amount, 0);
            const remaining = total - paidSoFar;

            if (amount > remaining + 0.001) { toast('المبلغ أكبر من المتبقي', 'error'); amountEl.focus(); return; }

            const pm = PAYMENT_METHODS[wPmIndex];
            wPayments.push({ payment_method_id: pm.id, method_name: pm.name, amount: amount });
            toast('تم إضافة الدفعة', 'success');
            renderWizard();
        }

        function removeWPayment(index) {
            wPayments.splice(index, 1);
            renderWizard();
        }

        async function saveNewOrder() {
            const total = wData.items.reduce((s, i) => s + i.total, 0);

            if (wData.items.length === 0) { toast('أضف صنف على الأقل', 'error'); return; }

            const amountEl = document.getElementById('wPayAmount');
            const pendingAmount = amountEl ? parseFloat(amountEl.value) || 0 : 0;
            if (pendingAmount > 0 && wPayments.length === 0) {
                const pm = PAYMENT_METHODS[wPmIndex];
                wPayments.push({ payment_method_id: pm.id, method_name: pm.name, amount: pendingAmount });
            }

            const payload = {
                customer_id: wData.customerId || null,
                customer_name: wData.customerName,
                phone: wData.customerPhone,
                event_type: wData.eventType,
                delivery_date: wData.deliveryDate,
                description: null,
                notes: wData.notes || null,
                items: wData.items.map(i => ({ product_id: i.product_id, quantity: i.quantity, price: i.price, total: i.total })),
                total_amount: total,
                payments: wPayments.length > 0 ? wPayments.map(p => ({ payment_method_id: p.payment_method_id, amount: p.amount })) : null
            };

            try {
                const res = await fetch(BASE_URL + '/cashier/special-orders/store', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.success) {
                    closeWizard();
                    toast('تم حفظ الطلبية بنجاح', 'success');
                    printNewOrder(data.data);
                } else {
                    toast(data.message, 'error');
                }
            } catch (err) {
                toast('خطأ في الاتصال', 'error');
            }
        }

        async function processViewPayment() {
            const amount = parseFloat(document.getElementById('wPayAmount').value);
            if (!amount || amount <= 0) { toast('أدخل المبلغ', 'error'); return; }
            if (amount > currentOrder.remaining_amount + 0.001) { toast('المبلغ أكبر من المتبقي', 'error'); return; }

            const pm = PAYMENT_METHODS[wViewPmIndex];

            try {
                const res = await fetch(BASE_URL + '/cashier/special-orders/payment', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ special_order_id: currentOrder.id, payment_method_id: pm.id, amount: amount })
                });
                const data = await res.json();
                if (data.success) {
                    currentOrder = data.data;
                    closeWizard();
                    showOrderView();
                    toast('تم إضافة الدفعة', 'success');
                    if (currentOrder.remaining_amount <= 0) toast('تم سداد الطلبية بالكامل!', 'success');
                } else {
                    toast(data.message, 'error');
                }
            } catch (err) {
                toast('خطأ في الاتصال', 'error');
            }
        }

        async function changeOrderStatus(newStatus) {
            if (!currentOrder) return;
            const labels = { ready: 'جاهز', delivered: 'تم التسليم' };
            const result = await Swal.fire({
                title: `تغيير الحالة إلى "${labels[newStatus]}"؟`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'نعم',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: '#64748b',
                customClass: { popup: 'swal-rtl', title: 'swal-title-rtl' }
            });
            if (!result.isConfirmed) return;

            try {
                const res = await fetch(BASE_URL + '/cashier/special-orders/' + currentOrder.id + '/status', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ status: newStatus })
                });
                const data = await res.json();
                if (data.success) {
                    currentOrder.status = data.status;
                    currentOrder.status_name = data.status_name;
                    showOrderView();
                    toast(data.message, 'success');
                } else {
                    toast(data.message, 'error');
                }
            } catch (err) {
                toast('خطأ في الاتصال', 'error');
            }
        }

        function printOrder() {
            if (!currentOrder) return;
            window.open(`${BASE_URL}/cashier/special-orders/${currentOrder.id}/print`, '_blank');
        }

        function printNewOrder(data) {
            let itemsHtml = '';
            data.items.forEach(i => {
                const qty = i.is_weight ? parseFloat(i.quantity).toFixed(3) + ' كجم' : i.quantity;
                itemsHtml += `<tr><td>${i.product_name}</td><td style="text-align:center">${qty}</td><td style="text-align:center">${parseFloat(i.price).toFixed(3)}</td><td style="text-align:left">${parseFloat(i.total).toFixed(3)}</td></tr>`;
            });

            let paymentsHtml = '';
            if (data.payments && data.payments.length > 0) {
                paymentsHtml = '<div class="payments-section"><div class="section-title">المدفوعات</div>';
                data.payments.forEach(p => { paymentsHtml += `<div class="payment-row"><span>${p.method} ${p.notes ? '(' + p.notes + ')' : ''}</span><span>${parseFloat(p.amount).toFixed(3)} د.ل</span></div>`; });
                paymentsHtml += '</div>';
            }

            const barcodeValue = String(data.id).padStart(8, '0');
            const notesReceiptHtml = data.notes ? `<div class="notes-box">${data.notes}</div>` : '';
            const receiptContent = `<div class="receipt"><div class="header"><div class="title">تاج السلطان - طلبية خاصة</div></div><div class="barcode-section"><svg class="barcode-svg"></svg><div class="order-id">#${data.id}</div></div><div class="section"><div class="info"><span class="label">التاريخ:</span><span>${data.created_at}</span></div><div class="info"><span class="label">الكاشير:</span><span>${data.cashier_name}</span></div><div class="info"><span class="label">الزبون:</span><span><strong>${data.customer_name}</strong></span></div><div class="info"><span class="label">الهاتف:</span><span>${data.phone || '-'}</span></div><div class="info"><span class="label">المناسبة:</span><span>${data.event_type}</span></div><div class="info"><span class="label">التسليم:</span><span>${data.delivery_date}</span></div></div><div class="status-box">${data.status_name || 'قيد الانتظار'}</div>${notesReceiptHtml}<table><thead><tr><th>الصنف</th><th>الكمية</th><th>السعر</th><th>الإجمالي</th></tr></thead><tbody>${itemsHtml}</tbody></table><div class="total-box"><span>الإجمالي</span><span>${parseFloat(data.total_amount).toFixed(3)} د.ل</span></div><div class="paid-box"><span>المدفوع</span><span>${parseFloat(data.paid_amount).toFixed(3)} د.ل</span></div><div class="remaining-box"><span>المتبقي</span><span>${parseFloat(data.remaining_amount).toFixed(3)} د.ل</span></div>${paymentsHtml}<div class="thanks">شكراً لتعاملكم معنا</div><div class="footer"><img src="${BASE_URL}/hulul.jpg"><span>حلول لتقنية المعلومات</span></div></div>`;

            const html = `<!DOCTYPE html><html dir="rtl"><head><meta charset="UTF-8"><link href="${BASE_URL}/assets/fonts/cairo/cairo.css" rel="stylesheet"><script src="${BASE_URL}/js/barcode/jsbarcode.min.js"><\/script><style>@page{margin:0;size:72mm auto}*{margin:0;padding:0;box-sizing:border-box}body{font-family:'Cairo',sans-serif;font-size:11px;line-height:1.3;padding:3mm;width:72mm;color:#000}.receipt{page-break-after:always}.receipt:last-child{page-break-after:auto}.header{text-align:center;border-bottom:1px dashed #000;padding-bottom:6px;margin-bottom:6px}.header .title{font-size:13px;font-weight:700}.barcode-section{text-align:center;border-bottom:1px dashed #000;padding:5px 0}.barcode-svg{display:block;margin:0 auto}.order-id{font-size:12px;font-weight:700;margin-top:3px}.info{font-size:11px;display:flex;justify-content:space-between;padding:2px 0}.info .label{font-weight:700}.section{border-bottom:1px dashed #000;padding-bottom:6px;margin-bottom:6px}.status-box{text-align:center;padding:5px;border:1px solid #000;font-weight:700;font-size:12px;margin:6px 0}table{width:100%;border-collapse:collapse;margin:6px 0}th{background:#000;color:#fff;padding:4px;font-size:10px}td{padding:4px;font-size:10px;border-bottom:1px dotted #ccc}.total-box{border:1px solid #000;padding:6px;margin:6px 0;display:flex;justify-content:space-between;font-size:14px;font-weight:800}.paid-box{display:flex;justify-content:space-between;font-size:11px;font-weight:700;padding:3px 0}.remaining-box{border:1px dashed #000;padding:5px;margin-top:4px;display:flex;justify-content:space-between;font-size:12px;font-weight:700}.payments-section{border-top:1px dashed #000;padding-top:6px;margin-top:6px}.section-title{background:#000;color:#fff;text-align:center;font-weight:700;font-size:10px;padding:3px}.payment-row{display:flex;justify-content:space-between;font-size:10px;padding:2px 0}.thanks{text-align:center;font-size:11px;font-weight:700;border-top:1px dashed #000;padding:6px 0}.footer{text-align:center;display:flex;align-items:center;justify-content:center;gap:5px;font-size:9px;color:#333;padding-top:4px}.notes-box{padding:5px;border:1px dashed #000;font-size:10px;margin:4px 0;font-weight:600}.footer img{height:22px;width:auto;filter:grayscale(100%)}</style></head><body>${receiptContent}${receiptContent}<script>JsBarcode(".barcode-svg","${barcodeValue}",{format:"CODE128",width:1.8,height:40,displayValue:false,margin:5,background:"#ffffff",lineColor:"#000000"});<\/script></body></html>`;

            const win = window.open('', '_blank', 'width=400,height=600');
            if (win) {
                win.document.write(html);
                win.document.close();
                setTimeout(() => { win.focus(); if (window.printer && window.printer.print) { window.printer.print(); } else { win.print(); } setTimeout(() => win.close(), 250); }, 250);
            }
        }

        function debounce(func, wait) {
            let timeout;
            return function(...args) { clearTimeout(timeout); timeout = setTimeout(() => func.apply(this, args), wait); };
        }

        function toast(msg, type = 'info') {
            Swal.fire({ toast: true, position: 'top', icon: type, title: msg, showConfirmButton: false, timer: 2000, timerProgressBar: true, customClass: { popup: 'swal-rtl' } });
        }
    </script>
</body>
</html>
