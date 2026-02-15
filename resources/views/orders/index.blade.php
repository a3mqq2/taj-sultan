@extends('layouts.app')

@section('title', 'الطلبيات')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">الطلبيات</li>
@endsection

@push('styles')
<style>
    .orders-table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
        background-color: #fff;
    }

    .orders-table th {
        background: #f8fafc;
        font-weight: 600;
        padding: 14px 16px;
        border-bottom: 2px solid #d1d5db;
        white-space: nowrap;
        color: #374151;
        font-size: 14px;
    }

    .orders-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #e5e7eb;
    }

    .orders-table tbody tr {
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .orders-table tbody tr:hover {
        background-color: #f0f9ff;
    }

    .filters-card {
        background: var(--bs-body-bg);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }

    .search-input, .filter-select, .filter-input {
        border-radius: 10px;
        padding: 12px 16px;
        border: 2px solid #d1d5db;
        font-size: 15px;
        background-color: #fff;
    }

    .search-input {
        padding-right: 45px;
    }

    .search-icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
    }

    .btn-export {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .badge-status {
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 13px;
    }

    .badge-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-paid { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-cancelled { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .badge-delivery { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-pickup { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

    .amount-total { font-weight: 700; }
    .amount-credit { font-weight: 600; color: #ef4444; }
    .amount-credit.zero { color: #10b981; }

    .modal-content {
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 25px 50px rgba(0,0,0,0.2);
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 2px solid #e5e7eb;
        background-color: #f9fafb;
    }

    .modal-title {
        font-weight: 700;
        font-size: 18px;
    }

    .modal-body {
        padding: 24px;
    }

    .info-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
    }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .summary-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
    }

    .summary-card.total .summary-value { color: var(--bs-body-color); }
    .summary-card.discount .summary-value { color: #f59e0b; }
    .summary-card.paid .summary-value { color: #10b981; }
    .summary-card.credit .summary-value { color: #ef4444; }

    .summary-label { font-size: 13px; color: var(--bs-secondary); }
    .summary-value { font-size: 20px; font-weight: 700; }

    .items-table {
        width: 100%;
        border-collapse: collapse;
    }

    .items-table th {
        background: #f3f4f6;
        padding: 10px 12px;
        font-weight: 600;
        font-size: 13px;
        border-bottom: 2px solid #d1d5db;
    }

    .items-table td {
        padding: 8px 12px;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
    }

    .payments-list { max-height: 200px; overflow-y: auto; }

    .payment-item {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .payment-amount { font-weight: 700; color: #10b981; }

    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 0;
    }

    .toast-container {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
    }

    .toast {
        border-radius: 12px;
        padding: 16px 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        animation: slideDown 0.3s ease;
    }

    .toast-success { background: #10b981; color: white; }
    .toast-error { background: #ef4444; color: white; }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .loading-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255,255,255,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        border-radius: 12px;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid var(--bs-light);
        border-top-color: var(--bs-primary);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: var(--bs-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .empty-state-icon i { font-size: 36px; color: var(--bs-secondary); }
</style>
@endpush

@section('content')
<div class="">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="mb-0">الطلبيات</h5>
        <button type="button" class="btn btn-success btn-export" onclick="exportOrders()">
            <i class="ti ti-file-export fs-18"></i>
            تصدير Excel
        </button>
    </div>

    <div class="filters-card">
        <div class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">البحث</label>
                <div class="position-relative">
                    <input type="text" class="form-control search-input" id="searchInput" placeholder="رقم الفاتورة أو الزبون...">
                    <i class="ti ti-search search-icon"></i>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">الحالة</label>
                <select class="form-select filter-select" id="statusFilter">
                    <option value="">الكل</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">نوع الاستلام</label>
                <select class="form-select filter-select" id="deliveryFilter">
                    <option value="">الكل</option>
                    <option value="pickup">استلام</option>
                    <option value="delivery">توصيل</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">طريقة الدفع</label>
                <select class="form-select filter-select" id="paymentFilter">
                    <option value="">الكل</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <label class="form-label">الآجل</label>
                <select class="form-select filter-select" id="creditFilter">
                    <option value="">الكل</option>
                    <option value="1">آجل</option>
                    <option value="0">نقدي</option>
                </select>
            </div>
            <div class="col-md-1">
                <label class="form-label">من</label>
                <input type="date" class="form-control filter-input" id="dateFromFilter">
            </div>
            <div class="col-md-1">
                <label class="form-label">إلى</label>
                <input type="date" class="form-control filter-input" id="dateToFilter">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-light w-100" onclick="resetFilters()" title="إعادة تعيين">
                    <i class="ti ti-refresh"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="card orders-table position-relative">
        <div id="loadingOverlay" class="loading-overlay d-none">
            <div class="spinner"></div>
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الزبون</th>
                        <th>الإجمالي</th>
                        <th>الخصم</th>
                        <th>الآجل</th>
                        <th>الاستلام</th>
                        <th>الحالة</th>
                        <th>الكاشير</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody"></tbody>
            </table>
        </div>

        <div id="emptyState" class="empty-state d-none">
            <div class="empty-state-icon">
                <i class="ti ti-receipt-off"></i>
            </div>
            <h5>لا توجد طلبيات</h5>
            <p class="text-muted">لم يتم العثور على أي طلبيات بناءً على الفلاتر المحددة</p>
        </div>

        <div id="paginationWrapper" class="pagination-wrapper px-3 d-none">
            <div class="pagination-info">
                عرض <span id="paginationFrom">0</span> - <span id="paginationTo">0</span>
                من <span id="paginationTotal">0</span> طلب
            </div>
            <nav>
                <ul class="pagination" id="paginationNav"></ul>
            </nav>
        </div>
    </div>
</div>

<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">فاتورة #<span id="detailsOrderNumber"></span></h5>
                <span class="badge badge-status ms-2" id="detailsStatusBadge"></span>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="summary-cards">
                    <div class="summary-card total">
                        <div class="summary-label">الإجمالي</div>
                        <div class="summary-value" id="detailsTotal">0.000 د.ل</div>
                    </div>
                    <div class="summary-card discount">
                        <div class="summary-label">الخصم</div>
                        <div class="summary-value" id="detailsDiscount">0.000 د.ل</div>
                    </div>
                    <div class="summary-card paid">
                        <div class="summary-label">المدفوع</div>
                        <div class="summary-value" id="detailsPaid">0.000 د.ل</div>
                    </div>
                    <div class="summary-card credit">
                        <div class="summary-label">الآجل</div>
                        <div class="summary-value" id="detailsCredit">0.000 د.ل</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label text-muted small">الزبون</div>
                            <div class="info-value fw-semibold" id="detailsCustomer">-</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label text-muted small">نوع الاستلام</div>
                            <div class="info-value fw-semibold" id="detailsDelivery">-</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label text-muted small">الكاشير</div>
                            <div class="info-value fw-semibold" id="detailsCashier">-</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label text-muted small">التاريخ</div>
                            <div class="info-value fw-semibold" id="detailsDate">-</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label text-muted small">نقطة البيع</div>
                            <div class="info-value fw-semibold" id="detailsPosPoint">-</div>
                        </div>
                    </div>
                </div>

                <div id="detailsPhoneCard" class="info-card" style="display:none;">
                    <div class="info-label text-muted small">رقم التوصيل</div>
                    <div class="info-value fw-semibold" id="detailsPhone">-</div>
                </div>

                <h6 class="mb-2">الأصناف</h6>
                <table class="items-table mb-3">
                    <thead>
                        <tr>
                            <th>الصنف</th>
                            <th>الكمية</th>
                            <th>السعر</th>
                            <th>الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody id="detailsItemsBody"></tbody>
                </table>

                <h6 class="mb-2">طرق الدفع</h6>
                <div class="payments-list" id="paymentsList"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<div class="toast-container" id="toastContainer"></div>
@endsection

@push('scripts')
<script>
let currentPage = 1;
let currentOrderId = null;

const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));

const statuses = @json($statuses);
const statusLabels = {
    'pending': 'قيد الانتظار',
    'paid': 'مدفوع',
    'cancelled': 'ملغي'
};

document.addEventListener('DOMContentLoaded', function() {
    loadOrders();

    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            loadOrders();
        }, 300);
    });

    ['statusFilter', 'deliveryFilter', 'paymentFilter', 'creditFilter', 'dateFromFilter', 'dateToFilter'].forEach(id => {
        document.getElementById(id).addEventListener('change', function() {
            currentPage = 1;
            loadOrders();
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') return;
        if (e.key === '/') {
            e.preventDefault();
            document.getElementById('searchInput').focus();
        }
    });
});

async function loadOrders() {
    showLoading(true);

    const params = new URLSearchParams({
        page: currentPage,
        search: document.getElementById('searchInput').value,
        status: document.getElementById('statusFilter').value,
        delivery_type: document.getElementById('deliveryFilter').value,
        payment_method: document.getElementById('paymentFilter').value,
        has_credit: document.getElementById('creditFilter').value,
        date_from: document.getElementById('dateFromFilter').value,
        date_to: document.getElementById('dateToFilter').value,
        sort: 'paid_at',
        direction: 'desc',
    });

    try {
        const response = await fetch(`{{ route('orders.data') }}?${params}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });

        const result = await response.json();
        if (result.success) {
            renderOrders(result.data);
            renderPagination(result.pagination);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('حدث خطأ في تحميل الطلبات', 'error');
    } finally {
        showLoading(false);
    }
}

function renderOrders(orders) {
    const tbody = document.getElementById('ordersTableBody');
    const emptyState = document.getElementById('emptyState');
    const paginationWrapper = document.getElementById('paginationWrapper');

    if (orders.length === 0) {
        tbody.innerHTML = '';
        emptyState.classList.remove('d-none');
        paginationWrapper.classList.add('d-none');
        return;
    }

    emptyState.classList.add('d-none');
    paginationWrapper.classList.remove('d-none');

    tbody.innerHTML = orders.map(order => `
        <tr data-id="${order.id}" onclick="openDetailsModal(${order.id})">
            <td><strong>${escapeHtml(order.order_number)}</strong></td>
            <td>
                <div class="fw-semibold">${escapeHtml(order.customer_name)}</div>
                ${order.customer_phone ? `<small class="text-muted">${escapeHtml(order.customer_phone)}</small>` : ''}
            </td>
            <td><span class="amount-total">${formatPrice(order.total)}</span></td>
            <td>${order.discount > 0 ? formatPrice(order.discount) : '-'}</td>
            <td><span class="amount-credit ${order.credit_amount <= 0 ? 'zero' : ''}">${order.credit_amount > 0 ? formatPrice(order.credit_amount) : '-'}</span></td>
            <td>
                <span class="badge badge-${order.delivery_type}">
                    ${order.delivery_type === 'delivery' ? 'توصيل' : 'استلام'}
                </span>
            </td>
            <td><span class="badge badge-status badge-${order.status}">${statusLabels[order.status]}</span></td>
            <td>${escapeHtml(order.cashier_name)}</td>
            <td>${order.paid_at || '-'}</td>
        </tr>
    `).join('');
}

function renderPagination(pagination) {
    document.getElementById('paginationFrom').textContent = pagination.from || 0;
    document.getElementById('paginationTo').textContent = pagination.to || 0;
    document.getElementById('paginationTotal').textContent = pagination.total;

    const nav = document.getElementById('paginationNav');
    let html = `<li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
        <a class="page-link" href="#" onclick="goToPage(${pagination.current_page - 1}); return false;"><i class="ti ti-chevron-right"></i></a>
    </li>`;

    for (let i = 1; i <= pagination.last_page; i++) {
        if (i === 1 || i === pagination.last_page || (i >= pagination.current_page - 2 && i <= pagination.current_page + 2)) {
            html += `<li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                <a class="page-link" href="#" onclick="goToPage(${i}); return false;">${i}</a>
            </li>`;
        } else if (i === pagination.current_page - 3 || i === pagination.current_page + 3) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
    }

    html += `<li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
        <a class="page-link" href="#" onclick="goToPage(${pagination.current_page + 1}); return false;"><i class="ti ti-chevron-left"></i></a>
    </li>`;

    nav.innerHTML = html;
}

function goToPage(page) {
    currentPage = page;
    loadOrders();
}

async function openDetailsModal(id) {
    try {
        const response = await fetch(`{{ url('orders') }}/${id}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });

        const result = await response.json();

        if (result.success) {
            const order = result.data;
            currentOrderId = order.id;

            document.getElementById('detailsOrderNumber').textContent = order.order_number;

            const statusBadge = document.getElementById('detailsStatusBadge');
            statusBadge.className = `badge badge-status badge-${order.status}`;
            statusBadge.textContent = statusLabels[order.status];

            const totalBeforeDiscount = parseFloat(order.total) + parseFloat(order.discount || 0);
            const paidAmount = totalBeforeDiscount - parseFloat(order.credit_amount || 0);

            document.getElementById('detailsTotal').textContent = formatPrice(totalBeforeDiscount);
            document.getElementById('detailsDiscount').textContent = formatPrice(order.discount || 0);
            document.getElementById('detailsPaid').textContent = formatPrice(paidAmount);
            document.getElementById('detailsCredit').textContent = formatPrice(order.credit_amount || 0);

            document.getElementById('detailsCustomer').textContent = order.customer ? order.customer.name : '-';
            document.getElementById('detailsDelivery').textContent = order.delivery_type === 'delivery' ? 'توصيل' : 'استلام من المحل';
            document.getElementById('detailsCashier').textContent = order.paid_by_user ? order.paid_by_user.name : '-';
            document.getElementById('detailsDate').textContent = order.paid_at ? formatDateTime(order.paid_at) : '-';
            document.getElementById('detailsPosPoint').textContent = order.pos_point ? order.pos_point.name : '-';

            const phoneCard = document.getElementById('detailsPhoneCard');
            if (order.delivery_type === 'delivery' && order.delivery_phone) {
                phoneCard.style.display = 'block';
                document.getElementById('detailsPhone').textContent = order.delivery_phone;
            } else {
                phoneCard.style.display = 'none';
            }

            if (order.items && order.items.length > 0) {
                document.getElementById('detailsItemsBody').innerHTML = order.items.map(item => `
                    <tr>
                        <td>${item.product ? item.product.name : item.product_name}</td>
                        <td>${item.is_weight ? parseFloat(item.quantity).toFixed(3) + ' كجم' : item.quantity}</td>
                        <td>${formatPrice(item.price)}</td>
                        <td>${formatPrice(item.total)}</td>
                    </tr>
                `).join('');
            } else {
                document.getElementById('detailsItemsBody').innerHTML = '<tr><td colspan="4" class="text-center text-muted">لا توجد أصناف</td></tr>';
            }

            renderPayments(order.payments || []);
            detailsModal.show();
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('حدث خطأ في تحميل بيانات الطلب', 'error');
    }
}

function renderPayments(payments) {
    const container = document.getElementById('paymentsList');

    if (payments.length === 0) {
        container.innerHTML = '<p class="text-muted text-center py-3">لا توجد مدفوعات</p>';
        return;
    }

    container.innerHTML = payments.map(payment => `
        <div class="payment-item">
            <div>
                <div class="payment-amount">${formatPrice(payment.amount)}</div>
                <div class="text-muted small">${payment.payment_method ? payment.payment_method.name : '-'}</div>
            </div>
        </div>
    `).join('');
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('deliveryFilter').value = '';
    document.getElementById('paymentFilter').value = '';
    document.getElementById('creditFilter').value = '';
    document.getElementById('dateFromFilter').value = '';
    document.getElementById('dateToFilter').value = '';
    currentPage = 1;
    loadOrders();
}

function exportOrders() {
    const params = new URLSearchParams({
        search: document.getElementById('searchInput').value,
        status: document.getElementById('statusFilter').value,
        date_from: document.getElementById('dateFromFilter').value,
        date_to: document.getElementById('dateToFilter').value,
    });

    window.location.href = `{{ route('orders.export') }}?${params}`;
}

function showLoading(show) {
    document.getElementById('loadingOverlay').classList.toggle('d-none', !show);
}

function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<i class="ti ti-${type === 'success' ? 'check' : 'x'} fs-18"></i><span>${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.animation = 'slideDown 0.3s ease reverse';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function formatPrice(price) {
    return parseFloat(price || 0).toFixed(3) + ' د.ل';
}

function formatDateTime(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('ar-LY') + ' ' + date.toLocaleTimeString('ar-LY', { hour: '2-digit', minute: '2-digit' });
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
@endpush
