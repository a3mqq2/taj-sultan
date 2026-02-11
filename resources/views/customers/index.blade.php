@extends('layouts.app')

@section('title', 'إدارة الزبائن')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">الزبائن</li>
@endsection

@push('styles')
<style>
    .customers-table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
        background-color: #fff;
    }

    .customers-table .table {
        margin-bottom: 0;
    }

    .customers-table th {
        background: #f8fafc;
        font-weight: 600;
        padding: 14px 16px;
        border-bottom: 2px solid #d1d5db;
        border-top: none;
        white-space: nowrap;
        color: #374151;
        font-size: 14px;
    }

    .customers-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #e5e7eb;
        border-top: none;
    }

    .customers-table tbody tr {
        transition: all 0.15s ease;
    }

    .customers-table tbody tr:hover {
        background-color: #f0f9ff;
    }

    [data-bs-theme="dark"] .customers-table {
        background-color: #1f2937;
        border-color: #374151;
    }

    [data-bs-theme="dark"] .customers-table th {
        background-color: #111827;
        border-color: #4b5563;
        color: #f3f4f6;
    }

    [data-bs-theme="dark"] .customers-table td {
        border-color: #374151;
    }

    [data-bs-theme="dark"] .customers-table tbody tr:hover {
        background-color: #374151;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .action-btn.btn-statement {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
        border-color: rgba(99, 102, 241, 0.2);
    }

    .action-btn.btn-statement:hover {
        background: #6366f1;
        color: white;
        border-color: #6366f1;
    }

    .action-btn.btn-payment {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border-color: rgba(16, 185, 129, 0.2);
    }

    .action-btn.btn-payment:hover {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }

    .action-btn.btn-edit {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border-color: rgba(59, 130, 246, 0.2);
    }

    .action-btn.btn-edit:hover {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .action-btn.btn-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border-color: rgba(239, 68, 68, 0.2);
    }

    .action-btn.btn-delete:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }

    .status-switch {
        width: 50px;
        height: 26px;
        cursor: pointer;
    }

    .status-switch:checked {
        background-color: #10b981;
        border-color: #10b981;
    }

    .filters-card {
        background: var(--bs-body-bg);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e5e7eb;
    }

    [data-bs-theme="dark"] .filters-card {
        border-color: #374151;
    }

    .search-input {
        border-radius: 10px;
        padding: 12px 16px;
        padding-right: 45px;
        border: 2px solid #d1d5db;
        transition: all 0.2s ease;
        font-size: 15px;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .search-input:hover {
        border-color: #9ca3af;
    }

    .search-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        outline: none;
    }

    .search-icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
    }

    .filter-select {
        border-radius: 10px;
        padding: 12px 16px;
        border: 2px solid #d1d5db;
        min-width: 150px;
        cursor: pointer;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .filter-select:hover {
        border-color: #9ca3af;
    }

    .filter-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        outline: none;
    }

    [data-bs-theme="dark"] .search-input,
    [data-bs-theme="dark"] .filter-select {
        background-color: #1f2937;
        border-color: #4b5563;
        color: #f9fafb;
    }

    [data-bs-theme="dark"] .search-input:hover,
    [data-bs-theme="dark"] .filter-select:hover {
        border-color: #6b7280;
    }

    [data-bs-theme="dark"] .search-icon {
        color: #9ca3af;
    }

    .btn-add {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.3);
    }

    .customer-name {
        font-weight: 600;
        color: var(--bs-body-color);
        font-size: 15px;
    }

    .customer-phone {
        font-size: 14px;
        color: #6b7280;
    }

    .balance-display {
        font-weight: 600;
        font-size: 15px;
    }

    .balance-positive {
        color: #10b981;
    }

    .balance-negative {
        color: #ef4444;
    }

    .balance-zero {
        color: #6b7280;
    }

    .modal-content {
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 25px 50px rgba(0,0,0,0.2);
        background-color: #fff;
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 2px solid #e5e7eb;
        background-color: #f9fafb;
    }

    .modal-title {
        font-weight: 700;
        font-size: 18px;
        color: #111827;
    }

    .modal-body {
        padding: 24px;
        background-color: #fff;
    }

    .modal-footer {
        padding: 16px 24px;
        border-top: 2px solid #e5e7eb;
        background-color: #f9fafb;
    }

    [data-bs-theme="dark"] .modal-content {
        background-color: #1f2937;
        border-color: #374151;
    }

    [data-bs-theme="dark"] .modal-header {
        background-color: #111827;
        border-color: #374151;
    }

    [data-bs-theme="dark"] .modal-title {
        color: #f9fafb;
    }

    [data-bs-theme="dark"] .modal-body {
        background-color: #1f2937;
    }

    [data-bs-theme="dark"] .modal-footer {
        background-color: #111827;
        border-color: #374151;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--bs-body-color);
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 12px 16px;
        border: 2px solid #d1d5db;
        font-size: 15px;
        transition: all 0.2s ease;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .form-control:hover, .form-select:hover {
        border-color: #9ca3af;
    }

    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        outline: none;
    }

    .form-control::placeholder {
        color: #9ca3af;
    }

    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select {
        background-color: #1f2937;
        border-color: #4b5563;
        color: #f9fafb;
    }

    [data-bs-theme="dark"] .form-control:hover,
    [data-bs-theme="dark"] .form-select:hover {
        border-color: #6b7280;
    }

    [data-bs-theme="dark"] .form-control:focus,
    [data-bs-theme="dark"] .form-select:focus {
        border-color: #3b82f6;
        background-color: #1f2937;
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #ef4444 !important;
    }

    .form-control.is-invalid:focus, .form-select.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15) !important;
    }

    .invalid-feedback {
        font-size: 13px;
        margin-top: 6px;
        color: #ef4444;
    }

    .status-box {
        background-color: #f3f4f6;
        border: 2px solid #e5e7eb;
    }

    [data-bs-theme="dark"] .status-box {
        background-color: #374151;
        border-color: #4b5563;
    }

    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px;
        flex-wrap: wrap;
        gap: 16px;
        border-top: 1px solid #e5e7eb;
    }

    [data-bs-theme="dark"] .pagination-wrapper {
        border-color: #374151;
    }

    .pagination-info {
        color: #6b7280;
        font-size: 14px;
    }

    .pagination {
        margin: 0;
        gap: 4px;
    }

    .page-link {
        border-radius: 8px;
        padding: 8px 14px;
        border: 1px solid #e5e7eb;
        color: var(--bs-body-color);
        font-weight: 500;
    }

    .page-link:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
    }

    .page-item.active .page-link {
        background: var(--bs-primary);
        border-color: var(--bs-primary);
        color: white;
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
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
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
        border: 3px solid #e5e7eb;
        border-top-color: var(--bs-primary);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .empty-state-icon i {
        font-size: 36px;
        color: #9ca3af;
    }

    [data-bs-theme="dark"] .loading-overlay {
        background: rgba(0,0,0,0.7);
    }

    [data-bs-theme="dark"] .empty-state-icon {
        background: #374151;
    }
</style>
@endpush

@section('content')
<div class="">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <button type="button" class="btn btn-primary btn-add" onclick="openAddModal()">
            <i class="ti ti-plus fs-18"></i>
            إضافة زبون
        </button>
    </div>

    <div class="filters-card">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label">البحث</label>
                <div class="position-relative">
                    <input type="text" class="form-control search-input" id="searchInput"
                           placeholder="ابحث بالاسم أو رقم الهاتف...">
                    <i class="ti ti-search search-icon"></i>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">الحالة</label>
                <select class="form-select filter-select" id="statusFilter">
                    <option value="">الكل</option>
                    <option value="active">نشط</option>
                    <option value="inactive">موقوف</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">الترتيب</label>
                <select class="form-select filter-select" id="sortFilter">
                    <option value="created_at-desc">الأحدث</option>
                    <option value="created_at-asc">الأقدم</option>
                    <option value="name-asc">الاسم (أ-ي)</option>
                    <option value="name-desc">الاسم (ي-أ)</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-light w-100" onclick="resetFilters()" title="إعادة تعيين" style="height: 50px;">
                    <i class="ti ti-refresh fs-18"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="card customers-table position-relative">
        <div id="loadingOverlay" class="loading-overlay d-none">
            <div class="spinner"></div>
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width: 30%">الزبون</th>
                        <th>الهاتف</th>
                        <th>الرصيد</th>
                        <th>الحالة</th>
                        <th style="width: 180px">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                </tbody>
            </table>
        </div>

        <div id="emptyState" class="empty-state d-none">
            <div class="empty-state-icon">
                <i class="ti ti-users-off"></i>
            </div>
            <h5>لا يوجد زبائن</h5>
            <p class="text-muted mb-3">لم يتم العثور على أي زبائن. أضف زبونك الأول الآن!</p>
            <button type="button" class="btn btn-primary" onclick="openAddModal()">
                <i class="ti ti-plus me-1"></i>
                إضافة زبون
            </button>
        </div>

        <div id="paginationWrapper" class="pagination-wrapper d-none">
            <div class="pagination-info">
                عرض <span id="paginationFrom">0</span> - <span id="paginationTo">0</span>
                من <span id="paginationTotal">0</span>
            </div>
            <nav>
                <ul class="pagination" id="paginationNav"></ul>
            </nav>
        </div>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">إضافة زبون جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="dataForm">
                <div class="modal-body">
                    <input type="hidden" id="itemId">

                    <div class="mb-3">
                        <label class="form-label">اسم الزبون <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="itemName" name="name" placeholder="أدخل اسم الزبون" autofocus>
                        <div class="invalid-feedback" id="nameError"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">رقم الهاتف</label>
                        <input type="text" class="form-control" id="itemPhone" name="phone" placeholder="أدخل رقم الهاتف">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">العنوان</label>
                        <textarea class="form-control" id="itemAddress" name="address" rows="2" placeholder="أدخل العنوان"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea class="form-control" id="itemNotes" name="notes" rows="2" placeholder="ملاحظات إضافية"></textarea>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3 status-box">
                        <div>
                            <span class="fw-semibold">حالة الزبون</span>
                            <p class="text-muted mb-0 small">تفعيل أو إيقاف الزبون</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input status-switch" type="checkbox" id="itemStatus" name="is_active" checked>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn" style="padding: 12px 32px;">
                        <span class="btn-text">حفظ</span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            جاري الحفظ...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة دفعة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="paymentForm">
                <div class="modal-body">
                    <input type="hidden" id="paymentCustomerId">

                    <div class="mb-3">
                        <label class="form-label">المبلغ <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="paymentAmount" name="amount" step="0.01" min="0.01" placeholder="0.00">
                            <span class="input-group-text" style="border: 2px solid #d1d5db; border-right: none; border-radius: 10px 0 0 10px;">د.ل</span>
                        </div>
                        <div class="invalid-feedback" id="amountError"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">طريقة الدفع <span class="text-danger">*</span></label>
                        <select class="form-select" id="paymentMethodId" name="payment_method_id">
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea class="form-control" id="paymentNotes" name="notes" rows="2" placeholder="ملاحظات إضافية"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success" id="savePaymentBtn" style="padding: 12px 32px;">
                        <span class="btn-text">حفظ الدفعة</span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            جاري الحفظ...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="ti ti-alert-triangle text-danger" style="font-size: 48px;"></i>
                </div>
                <h5 class="mb-2">تأكيد الحذف</h5>
                <p class="text-muted mb-0">
                    هل أنت متأكد من حذف الزبون
                    <strong id="deleteItemName"></strong>؟
                </p>
            </div>
            <div class="modal-footer justify-content-center border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" onclick="confirmDelete()">
                    <span class="btn-text">حذف</span>
                    <span class="btn-loading d-none">
                        <span class="spinner-border spinner-border-sm me-1"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="toast-container" id="toastContainer"></div>
@endsection

@push('scripts')
<script>
let currentPage = 1;
let deleteItemId = null;
let isSubmitting = false;

const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const formModal = new bootstrap.Modal(document.getElementById('formModal'));
const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

document.addEventListener('DOMContentLoaded', function() {
    loadData();

    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            loadData();
        }, 300);
    });

    ['statusFilter', 'sortFilter'].forEach(id => {
        document.getElementById(id).addEventListener('change', function() {
            currentPage = 1;
            loadData();
        });
    });

    document.getElementById('dataForm').addEventListener('submit', handleSubmit);
    document.getElementById('paymentForm').addEventListener('submit', handlePaymentSubmit);
    document.getElementById('formModal').addEventListener('hidden.bs.modal', resetForm);

    document.addEventListener('keydown', function(e) {
        if (e.key === '1' && !e.ctrlKey && !e.altKey && !e.metaKey) {
            const activeElement = document.activeElement;
            if (activeElement.tagName !== 'INPUT' && activeElement.tagName !== 'TEXTAREA' && activeElement.tagName !== 'SELECT') {
                e.preventDefault();
                openAddModal();
            }
        }
        if (e.key === '/' && !e.ctrlKey && !e.altKey && !e.metaKey) {
            const activeElement = document.activeElement;
            if (activeElement.tagName !== 'INPUT' && activeElement.tagName !== 'TEXTAREA') {
                e.preventDefault();
                document.getElementById('searchInput').focus();
            }
        }
    });
});

async function loadData() {
    showLoading(true);

    const params = new URLSearchParams({
        page: currentPage,
        search: document.getElementById('searchInput').value,
        status: document.getElementById('statusFilter').value,
    });

    const sortValue = document.getElementById('sortFilter').value.split('-');
    params.append('sort', sortValue[0]);
    params.append('direction', sortValue[1]);

    try {
        const response = await fetch(`{{ route('customers.data') }}?${params}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });

        const result = await response.json();

        if (result.success) {
            renderData(result.data);
            renderPagination(result.pagination);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('حدث خطأ في تحميل البيانات', 'error');
    } finally {
        showLoading(false);
    }
}

function renderData(items) {
    const tbody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');
    const paginationWrapper = document.getElementById('paginationWrapper');

    if (items.length === 0) {
        tbody.innerHTML = '';
        emptyState.classList.remove('d-none');
        paginationWrapper.classList.add('d-none');
        return;
    }

    emptyState.classList.add('d-none');
    paginationWrapper.classList.remove('d-none');

    tbody.innerHTML = items.map(item => {
        const balance = parseFloat(item.balance || 0);
        let balanceClass = 'balance-zero';
        if (balance > 0) balanceClass = 'balance-positive';
        else if (balance < 0) balanceClass = 'balance-negative';

        const formattedBalance = Math.abs(balance).toLocaleString('en-US', { minimumFractionDigits: 2 });
        const balanceLabel = balance > 0 ? `له ${formattedBalance}` : (balance < 0 ? `عليه ${formattedBalance}` : formattedBalance);

        return `
        <tr data-id="${item.id}">
            <td>
                <div class="customer-name">${escapeHtml(item.name)}</div>
            </td>
            <td>
                <span class="customer-phone">${item.phone || '-'}</span>
            </td>
            <td>
                <span class="balance-display ${balanceClass}">${balanceLabel} د.ل</span>
            </td>
            <td>
                <div class="form-check form-switch">
                    <input class="form-check-input status-switch" type="checkbox"
                           ${item.is_active ? 'checked' : ''}
                           onchange="toggleStatus(${item.id}, this)">
                </div>
            </td>
            <td>
                <div class="d-flex gap-2">
                    <a href="{{ url('customers') }}/${item.id}/statement" class="btn action-btn btn-statement" title="كشف الحساب">
                        <i class="ti ti-receipt fs-16"></i>
                    </a>
                    <button type="button" class="btn action-btn btn-payment" onclick="openPaymentModal(${item.id})" title="إضافة دفعة">
                        <i class="ti ti-plus fs-16"></i>
                    </button>
                    <button type="button" class="btn action-btn btn-edit" onclick="openEditModal(${item.id})" title="تعديل">
                        <i class="ti ti-pencil fs-16"></i>
                    </button>
                    <button type="button" class="btn action-btn btn-delete" onclick="openDeleteModal(${item.id}, '${escapeHtml(item.name)}')" title="حذف">
                        <i class="ti ti-trash fs-16"></i>
                    </button>
                </div>
            </td>
        </tr>
    `}).join('');
}

function renderPagination(pagination) {
    document.getElementById('paginationFrom').textContent = pagination.from || 0;
    document.getElementById('paginationTo').textContent = pagination.to || 0;
    document.getElementById('paginationTotal').textContent = pagination.total;

    const nav = document.getElementById('paginationNav');
    let html = '';

    html += `<li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
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
    loadData();
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'إضافة زبون جديد';
    document.getElementById('itemId').value = '';
    document.getElementById('saveBtn').querySelector('.btn-text').textContent = 'حفظ';
    formModal.show();
    setTimeout(() => document.getElementById('itemName').focus(), 300);
}

async function openEditModal(id) {
    try {
        const response = await fetch(`{{ url('customers') }}/${id}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });

        const result = await response.json();

        if (result.success) {
            const item = result.data;
            document.getElementById('modalTitle').textContent = 'تعديل الزبون';
            document.getElementById('itemId').value = item.id;
            document.getElementById('itemName').value = item.name;
            document.getElementById('itemPhone').value = item.phone || '';
            document.getElementById('itemAddress').value = item.address || '';
            document.getElementById('itemNotes').value = item.notes || '';
            document.getElementById('itemStatus').checked = item.is_active;
            document.getElementById('saveBtn').querySelector('.btn-text').textContent = 'تحديث';
            formModal.show();
            setTimeout(() => document.getElementById('itemName').focus(), 300);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('حدث خطأ في تحميل البيانات', 'error');
    }
}

async function handleSubmit(e) {
    e.preventDefault();
    if (isSubmitting) return;

    clearErrors();

    const itemId = document.getElementById('itemId').value;
    const isEdit = !!itemId;

    const data = {
        name: document.getElementById('itemName').value,
        phone: document.getElementById('itemPhone').value || null,
        address: document.getElementById('itemAddress').value || null,
        notes: document.getElementById('itemNotes').value || null,
        is_active: document.getElementById('itemStatus').checked,
    };

    setSubmitting(true);

    try {
        const url = isEdit ? `{{ url('customers') }}/${itemId}` : '{{ route('customers.store') }}';
        const method = isEdit ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok && result.success) {
            formModal.hide();
            loadData();
            showToast(result.message, 'success');
        } else if (response.status === 422) {
            displayErrors(result.errors);
        } else {
            showToast(result.message || 'حدث خطأ', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('حدث خطأ في الحفظ', 'error');
    } finally {
        setSubmitting(false);
    }
}

function openPaymentModal(customerId) {
    document.getElementById('paymentCustomerId').value = customerId;
    document.getElementById('paymentAmount').value = '';
    document.getElementById('paymentNotes').value = '';
    paymentModal.show();
    setTimeout(() => document.getElementById('paymentAmount').focus(), 300);
}

async function handlePaymentSubmit(e) {
    e.preventDefault();
    if (isSubmitting) return;

    const customerId = document.getElementById('paymentCustomerId').value;
    const data = {
        amount: document.getElementById('paymentAmount').value,
        payment_method_id: document.getElementById('paymentMethodId').value,
        notes: document.getElementById('paymentNotes').value || null,
    };

    const btn = document.getElementById('savePaymentBtn');
    btn.querySelector('.btn-text').classList.add('d-none');
    btn.querySelector('.btn-loading').classList.remove('d-none');
    btn.disabled = true;

    try {
        const response = await fetch(`{{ url('customers') }}/${customerId}/payment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok && result.success) {
            paymentModal.hide();
            loadData();
            showToast(result.message, 'success');
        } else {
            showToast(result.message || 'حدث خطأ', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('حدث خطأ في الحفظ', 'error');
    } finally {
        btn.querySelector('.btn-text').classList.remove('d-none');
        btn.querySelector('.btn-loading').classList.add('d-none');
        btn.disabled = false;
    }
}

function openDeleteModal(id, name) {
    deleteItemId = id;
    document.getElementById('deleteItemName').textContent = name;
    deleteModal.show();
}

async function confirmDelete() {
    if (!deleteItemId || isSubmitting) return;

    const btn = document.getElementById('confirmDeleteBtn');
    btn.querySelector('.btn-text').classList.add('d-none');
    btn.querySelector('.btn-loading').classList.remove('d-none');
    btn.disabled = true;

    try {
        const response = await fetch(`{{ url('customers') }}/${deleteItemId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();

        if (result.success) {
            deleteModal.hide();
            loadData();
            showToast(result.message, 'success');
        } else {
            showToast(result.message || 'حدث خطأ', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('حدث خطأ في الحذف', 'error');
    } finally {
        btn.querySelector('.btn-text').classList.remove('d-none');
        btn.querySelector('.btn-loading').classList.add('d-none');
        btn.disabled = false;
        deleteItemId = null;
    }
}

async function toggleStatus(id, checkbox) {
    try {
        const response = await fetch(`{{ url('customers') }}/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();

        if (result.success) {
            showToast(result.message, 'success');
        } else {
            checkbox.checked = !checkbox.checked;
            showToast(result.message || 'حدث خطأ', 'error');
        }
    } catch (error) {
        checkbox.checked = !checkbox.checked;
        showToast('حدث خطأ', 'error');
    }
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('sortFilter').value = 'created_at-desc';
    currentPage = 1;
    loadData();
}

function resetForm() {
    document.getElementById('dataForm').reset();
    document.getElementById('itemId').value = '';
    document.getElementById('itemStatus').checked = true;
    clearErrors();
}

function displayErrors(errors) {
    for (const [field, messages] of Object.entries(errors)) {
        const input = document.querySelector(`[name="${field}"]`);
        const errorDiv = document.getElementById(`${field}Error`);
        if (input) input.classList.add('is-invalid');
        if (errorDiv) {
            errorDiv.textContent = messages[0];
            errorDiv.style.display = 'block';
        }
    }
}

function clearErrors() {
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });
}

function setSubmitting(state) {
    isSubmitting = state;
    const btn = document.getElementById('saveBtn');
    btn.querySelector('.btn-text').classList.toggle('d-none', state);
    btn.querySelector('.btn-loading').classList.toggle('d-none', !state);
    btn.disabled = state;
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

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
@endpush
