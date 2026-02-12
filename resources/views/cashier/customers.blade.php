<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الزبائن والديون - {{ config('app.name') }}</title>
    <link href="{{ asset('assets/fonts/cairo/cairo.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}">
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .app-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        .header {
            background: #fff;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 80px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #f8fafc;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
        }

        .back-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .back-btn:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
        }

        .main-content {
            flex: 1;
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 16px;
            padding: 16px;
            overflow: hidden;
            min-height: 0;
        }

        .left-panel {
            display: flex;
            flex-direction: column;
            gap: 16px;
            overflow: hidden;
            min-height: 0;
        }

        .search-box {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            flex-shrink: 0;
        }

        .search-input {
            width: 100%;
            padding: 12px 16px;
            font-size: 15px;
            font-weight: 600;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-family: inherit;
        }

        .search-input:focus {
            outline: none;
            border-color: #f97316;
        }

        .customers-list {
            background: #fff;
            border-radius: 12px;
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .customers-header {
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 700;
            font-size: 15px;
            color: #64748b;
            flex-shrink: 0;
        }

        .customers-body {
            flex: 1;
            overflow-y: auto;
            padding: 8px;
        }

        .customer-item {
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.15s;
        }

        .customer-item:hover {
            border-color: #f97316;
            background: #fff7ed;
        }

        .customer-item.active {
            border-color: #f97316;
            background: #ffedd5;
        }

        .customer-name {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .customer-phone {
            font-size: 12px;
            color: #64748b;
        }

        .customer-balance {
            font-size: 13px;
            font-weight: 700;
            margin-top: 6px;
        }

        .customer-balance.debt {
            color: #dc2626;
        }

        .customer-balance.credit {
            color: #22c55e;
        }

        .right-panel {
            display: flex;
            flex-direction: column;
            gap: 16px;
            overflow: hidden;
            min-height: 0;
        }

        .details-box {
            background: #fff;
            border-radius: 12px;
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .details-header {
            padding: 16px;
            border-bottom: 1px solid #e2e8f0;
            flex-shrink: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .details-title {
            font-weight: 700;
            font-size: 18px;
        }

        .pay-debt-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            font-size: 14px;
            transition: all 0.2s;
        }

        .pay-debt-btn:hover {
            background: linear-gradient(135deg, #16a34a, #15803d);
        }

        .pay-debt-btn:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
        }

        .details-body {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 16px;
        }

        .customer-info-box {
            background: #f8fafc;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #e2e8f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #64748b;
            font-weight: 600;
        }

        .info-value {
            font-weight: 700;
        }

        .info-value.debt {
            color: #dc2626;
            font-size: 18px;
        }

        .section-title {
            font-weight: 700;
            font-size: 15px;
            color: #64748b;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
        }

        .transactions-table th {
            background: #f8fafc;
            padding: 10px;
            text-align: right;
            font-weight: 700;
            font-size: 12px;
            color: #64748b;
        }

        .transactions-table td {
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }

        .transactions-table tr:hover {
            background: #f8fafc;
        }

        .type-debt {
            color: #dc2626;
            font-weight: 600;
        }

        .type-payment {
            color: #22c55e;
            font-weight: 600;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            width: 100%;
            max-width: 400px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border: none;
            background: #f1f5f9;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #64748b;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: #22c55e;
        }

        .form-select {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            font-weight: 600;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-family: inherit;
            background: #fff;
        }

        .form-select:focus {
            outline: none;
            border-color: #22c55e;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
        }

        .modal-btn {
            flex: 1;
            padding: 12px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            border: none;
        }

        .modal-btn-cancel {
            background: #f1f5f9;
            color: #64748b;
        }

        .modal-btn-confirm {
            background: #22c55e;
            color: #fff;
        }

        .debt-info {
            background: #fef2f2;
            border: 2px solid #fecaca;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 16px;
            text-align: center;
        }

        .debt-info-label {
            font-size: 12px;
            color: #991b1b;
            margin-bottom: 4px;
        }

        .debt-info-value {
            font-size: 24px;
            font-weight: 800;
            color: #dc2626;
        }

        .swal2-popup.swal-rtl {
            font-family: 'Cairo', sans-serif !important;
            direction: rtl !important;
            border-radius: 16px !important;
            padding: 24px !important;
        }

        .swal2-popup .swal-title-rtl {
            font-family: 'Cairo', sans-serif !important;
            font-size: 20px !important;
            font-weight: 700 !important;
            line-height: 1.6 !important;
        }

        .swal2-popup .swal2-html-container {
            font-family: 'Cairo', sans-serif !important;
            margin: 16px 0 !important;
        }

        .swal2-actions {
            flex-direction: row-reverse !important;
            gap: 12px !important;
            margin-top: 16px !important;
        }

        .swal2-confirm, .swal2-cancel {
            font-family: 'Cairo', sans-serif !important;
            font-weight: 600 !important;
            font-size: 15px !important;
            padding: 10px 24px !important;
            border-radius: 8px !important;
            min-width: 100px !important;
        }

        .swal2-icon {
            margin: 0 auto 16px !important;
        }

        .swal2-container {
            z-index: 99999 !important;
        }

        .swal2-backdrop-show {
            background: transparent !important;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="header">
            <div class="logo"><img src="{{ asset('logo-dark.png') }}" alt="Taj Alsultan"></div>
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
                <div class="search-box">
                    <input type="text" class="search-input" id="searchInput" placeholder="البحث عن زبون..." autofocus>
                </div>

                <div class="customers-list">
                    <div class="customers-header">
                        <i class="ti ti-users"></i>
                        الزبائن (<span id="customersCount">0</span>)
                    </div>
                    <div class="customers-body" id="customersBody"></div>
                </div>
            </div>

            <div class="right-panel">
                <div class="details-box">
                    <div class="details-header">
                        <div class="details-title" id="detailsTitle">تفاصيل الزبون</div>
                        <button class="pay-debt-btn" id="payDebtBtn" disabled>
                            <i class="ti ti-cash"></i>
                            تسديد دين
                        </button>
                    </div>
                    <div class="details-body" id="detailsBody">
                        <div class="empty-state">
                            <i class="ti ti-user-search"></i>
                            <span>اختر زبون لعرض التفاصيل</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="payDebtModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><i class="ti ti-cash" style="color:#22c55e;"></i> تسديد دين</div>
                <button class="modal-close" id="closePayDebtModal"><i class="ti ti-x"></i></button>
            </div>

            <div class="debt-info">
                <div class="debt-info-label">الدين المتبقي</div>
                <div class="debt-info-value" id="modalDebtAmount">0.000</div>
            </div>

            <div class="form-group">
                <label class="form-label">المبلغ المسدد</label>
                <input type="number" class="form-input" id="payAmount" step="0.001" min="0" placeholder="0.000">
            </div>

            <div class="form-group">
                <label class="form-label">طريقة الدفع</label>
                <select class="form-select" id="paymentMethodSelect">
                    @foreach($paymentMethods as $method)
                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="modal-actions">
                <button class="modal-btn modal-btn-cancel" id="cancelPayDebt">إلغاء</button>
                <button class="modal-btn modal-btn-confirm" id="confirmPayDebt">تسديد</button>
            </div>
        </div>
    </div>

    <script>
        const BASE_URL = "{{ url('/') }}";
        let customers = [];
        let selectedCustomer = null;

        document.addEventListener('DOMContentLoaded', init);

        function init() {
            loadCustomers();

            document.getElementById('searchInput').addEventListener('input', debounce(filterCustomers, 300));
            document.getElementById('payDebtBtn').addEventListener('click', openPayDebtModal);
            document.getElementById('closePayDebtModal').addEventListener('click', closePayDebtModal);
            document.getElementById('cancelPayDebt').addEventListener('click', closePayDebtModal);
            document.getElementById('confirmPayDebt').addEventListener('click', processPayDebt);
            document.getElementById('payAmount').addEventListener('keydown', e => {
                if (e.key === 'Enter') processPayDebt();
            });

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && document.getElementById('payDebtModal').classList.contains('active')) {
                    closePayDebtModal();
                }
            });
        }

        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        async function loadCustomers() {
            try {
                const res = await fetch(BASE_URL + '/cashier/customers/data');
                const data = await res.json();
                if (data.success) {
                    customers = data.data;
                    renderCustomers(customers);
                }
            } catch (err) {
                toast('خطأ في تحميل البيانات', 'error');
            }
        }

        function filterCustomers() {
            const q = document.getElementById('searchInput').value.trim().toLowerCase();
            const filtered = customers.filter(c =>
                c.name.toLowerCase().includes(q) ||
                (c.phone && c.phone.includes(q))
            );
            renderCustomers(filtered);
        }

        function renderCustomers(list) {
            const container = document.getElementById('customersBody');
            document.getElementById('customersCount').textContent = list.length;

            if (list.length === 0) {
                container.innerHTML = '<div style="text-align:center;color:#64748b;padding:24px;">لا يوجد زبائن</div>';
                return;
            }

            container.innerHTML = list.map(c => {
                const balanceClass = c.balance < 0 ? 'debt' : 'credit';
                const balanceText = c.balance < 0 ? `دين: ${Math.abs(c.balance).toFixed(3)}` : `رصيد: ${c.balance.toFixed(3)}`;
                const activeClass = selectedCustomer && selectedCustomer.id === c.id ? 'active' : '';
                return `
                    <div class="customer-item ${activeClass}" onclick="selectCustomer(${c.id}, this)">
                        <div class="customer-name">${c.name}</div>
                        <div class="customer-phone">${c.phone || '-'}</div>
                        <div class="customer-balance ${balanceClass}">${balanceText}</div>
                    </div>
                `;
            }).join('');
        }

        async function selectCustomer(id, el) {
            try {
                const res = await fetch(BASE_URL + `/cashier/customers/${id}`);
                const data = await res.json();
                if (data.success) {
                    selectedCustomer = data.data;
                    renderCustomerDetails(data.data);

                    document.querySelectorAll('.customer-item').forEach(item => item.classList.remove('active'));
                    if (el) el.classList.add('active');
                } else {
                    toast(data.message || 'خطأ في تحميل البيانات', 'error');
                }
            } catch (err) {
                toast('خطأ في تحميل البيانات', 'error');
            }
        }

        function renderCustomerDetails(customer) {
            document.getElementById('detailsTitle').textContent = customer.name;

            const hasDebt = customer.balance < 0;
            document.getElementById('payDebtBtn').disabled = !hasDebt;

            let html = `
                <div class="customer-info-box">
                    <div class="info-row">
                        <span class="info-label">الاسم</span>
                        <span class="info-value">${customer.name}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">الهاتف</span>
                        <span class="info-value">${customer.phone || '-'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">الرصيد</span>
                        <span class="info-value ${hasDebt ? 'debt' : ''}">${hasDebt ? 'دين: ' + Math.abs(customer.balance).toFixed(3) : customer.balance.toFixed(3)} د.ل</span>
                    </div>
                </div>
            `;

            if (customer.transactions && customer.transactions.length > 0) {
                html += `
                    <div class="section-title"><i class="ti ti-history"></i> آخر العمليات</div>
                    <table class="transactions-table">
                        <thead>
                            <tr>
                                <th>النوع</th>
                                <th>المبلغ</th>
                                <th>الرصيد بعد</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${customer.transactions.map(t => `
                                <tr>
                                    <td class="${t.type === 'payment' ? 'type-payment' : 'type-debt'}">${t.type_name}</td>
                                    <td>${parseFloat(t.amount).toFixed(3)}</td>
                                    <td>${parseFloat(t.balance_after).toFixed(3)}</td>
                                    <td>${t.created_at}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
            }

            document.getElementById('detailsBody').innerHTML = html;
        }

        function openPayDebtModal() {
            if (!selectedCustomer || selectedCustomer.balance >= 0) return;

            const debtAmount = Math.abs(selectedCustomer.balance);
            document.getElementById('modalDebtAmount').textContent = debtAmount.toFixed(3);
            document.getElementById('payAmount').value = debtAmount.toFixed(3);
            document.getElementById('payDebtModal').classList.add('active');
            document.getElementById('payAmount').focus();
            document.getElementById('payAmount').select();
        }

        function closePayDebtModal() {
            document.getElementById('payDebtModal').classList.remove('active');
        }

        async function processPayDebt() {
            if (!selectedCustomer) return;

            const amount = parseFloat(document.getElementById('payAmount').value);
            const paymentMethodId = document.getElementById('paymentMethodSelect').value;
            const debtAmount = Math.abs(selectedCustomer.balance);

            if (!amount || amount <= 0) return toast('أدخل المبلغ', 'error');
            if (amount > debtAmount + 0.001) return toast('المبلغ أكبر من الدين', 'error');

            try {
                const res = await fetch(BASE_URL + `/cashier/customers/${selectedCustomer.id}/pay-debt`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        amount: amount,
                        payment_method_id: paymentMethodId
                    })
                });

                const data = await res.json();
                if (data.success) {
                    closePayDebtModal();
                    toast('تم التسديد بنجاح', 'success');
                    loadCustomers();
                    selectCustomer(selectedCustomer.id, null);
                } else {
                    toast(data.message, 'error');
                }
            } catch (err) {
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
                customClass: {
                    popup: 'swal-rtl'
                }
            });
        }
    </script>
</body>
</html>
