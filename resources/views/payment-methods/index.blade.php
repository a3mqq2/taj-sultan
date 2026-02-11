@extends('layouts.app')

@section('title', 'إدارة طرق الدفع')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">طرق الدفع</li>
@endsection

@push('styles')
<style>
    .payment-methods-table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }

    .payment-methods-table th {
        background: var(--bs-light);
        font-weight: 600;
        padding: 16px;
        border-bottom: 2px solid var(--bs-border-color);
        white-space: nowrap;
    }

    .payment-methods-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid var(--bs-border-color);
    }

    .payment-methods-table tbody tr:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.04);
    }

    .action-btn {
        width: 38px;
        height: 38px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s ease;
        border: none;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .action-btn.btn-edit {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .action-btn.btn-edit:hover {
        background: #3b82f6;
        color: white;
    }

    .action-btn.btn-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .action-btn.btn-delete:hover {
        background: #ef4444;
        color: white;
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
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }

    .search-input {
        border-radius: 10px;
        padding: 12px 16px;
        padding-right: 45px;
        border: 2px solid var(--bs-border-color);
        transition: all 0.2s ease;
        font-size: 15px;
    }

    .search-input:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 4px rgba(var(--bs-primary-rgb), 0.1);
    }

    .search-icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--bs-secondary);
    }

    .filter-select {
        border-radius: 10px;
        padding: 12px 16px;
        border: 2px solid var(--bs-border-color);
        min-width: 150px;
        cursor: pointer;
    }

    .filter-select:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 4px rgba(var(--bs-primary-rgb), 0.1);
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

    .method-name {
        font-weight: 600;
        color: var(--bs-body-color);
        font-size: 15px;
    }

    .method-description {
        font-size: 13px;
        color: var(--bs-secondary);
        margin-top: 2px;
    }

    .badge-order {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 13px;
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
    }

    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--bs-border-color);
    }

    .modal-title {
        font-weight: 700;
        font-size: 18px;
    }

    .modal-body {
        padding: 24px;
    }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid var(--bs-border-color);
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--bs-body-color);
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 12px 16px;
        border: 2px solid var(--bs-border-color);
        font-size: 15px;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 4px rgba(var(--bs-primary-rgb), 0.1);
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #ef4444;
    }

    .form-control.is-invalid:focus, .form-select.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    .invalid-feedback {
        font-size: 13px;
        margin-top: 6px;
    }

    .btn-modal-save {
        padding: 12px 32px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
    }

    .btn-modal-cancel {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 500;
    }

    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 0;
        flex-wrap: wrap;
        gap: 16px;
    }

    .pagination-info {
        color: var(--bs-secondary);
        font-size: 14px;
    }

    .pagination {
        margin: 0;
        gap: 4px;
    }

    .page-link {
        border-radius: 8px;
        padding: 8px 14px;
        border: none;
        color: var(--bs-body-color);
        font-weight: 500;
    }

    .page-link:hover {
        background: var(--bs-light);
    }

    .page-item.active .page-link {
        background: var(--bs-primary);
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

    .toast-success {
        background: #10b981;
        color: white;
    }

    .toast-error {
        background: #ef4444;
        color: white;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        border: 3px solid var(--bs-light);
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
        background: var(--bs-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .empty-state-icon i {
        font-size: 36px;
        color: var(--bs-secondary);
    }

    .empty-state-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .empty-state-text {
        color: var(--bs-secondary);
        margin-bottom: 20px;
    }

    [data-bs-theme="dark"] .payment-methods-table {
        background: var(--bs-body-bg);
    }

    [data-bs-theme="dark"] .loading-overlay {
        background: rgba(0,0,0,0.7);
    }
</style>
@endpush

@section('content')
<div class="">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <button type="button" class="btn btn-primary btn-add" onclick="openAddModal()">
            <i class="ti ti-plus fs-18"></i>
            إضافة طريقة دفع
        </button>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label">البحث</label>
                <div class="position-relative">
                    <input type="text" class="form-control search-input" id="searchInput"
                           placeholder="ابحث باسم طريقة الدفع...">
                    <i class="ti ti-search search-icon"></i>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">الحالة</label>
                <select class="form-select filter-select" id="statusFilter">
                    <option value="">الكل</option>
                    <option value="active">مفعل</option>
                    <option value="inactive">موقوف</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">الترتيب</label>
                <select class="form-select filter-select" id="sortFilter">
                    <option value="sort_order-asc">الترتيب</option>
                    <option value="name-asc">الاسم (أ-ي)</option>
                    <option value="name-desc">الاسم (ي-أ)</option>
                    <option value="created_at-desc">الأحدث</option>
                    <option value="created_at-asc">الأقدم</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-light w-100" onclick="resetFilters()" title="إعادة تعيين">
                    <i class="ti ti-refresh"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Payment Methods Table -->
    <div class="card payment-methods-table position-relative">
        <div id="loadingOverlay" class="loading-overlay d-none">
            <div class="spinner"></div>
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50%">طريقة الدفع</th>
                        <th>الترتيب</th>
                        <th>الحالة</th>
                        <th style="width: 150px">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="empty-state d-none">
            <div class="empty-state-icon">
                <i class="ti ti-credit-card-off"></i>
            </div>
            <h5 class="empty-state-title">لا توجد طرق دفع</h5>
            <p class="empty-state-text">لم يتم العثور على أي طرق دفع. أضف طريقة الدفع الأولى الآن!</p>
            <button type="button" class="btn btn-primary" onclick="openAddModal()">
                <i class="ti ti-plus me-1"></i>
                إضافة طريقة دفع
            </button>
        </div>

        <!-- Pagination -->
        <div id="paginationWrapper" class="pagination-wrapper px-3 d-none">
            <div class="pagination-info">
                عرض <span id="paginationFrom">0</span> - <span id="paginationTo">0</span>
                من <span id="paginationTotal">0</span>
            </div>
            <nav>
                <ul class="pagination" id="paginationNav">
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="formModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">إضافة طريقة دفع جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="dataForm">
                <div class="modal-body">
                    <input type="hidden" id="itemId">

                    <div class="mb-3">
                        <label class="form-label">اسم طريقة الدفع <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="itemName" name="name"
                               placeholder="مثال: كاش" autofocus>
                        <div class="invalid-feedback" id="nameError"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الوصف <span class="text-muted">(اختياري)</span></label>
                        <textarea class="form-control" id="itemDescription" name="description" rows="3"
                                  placeholder="وصف مختصر لطريقة الدفع..."></textarea>
                        <div class="invalid-feedback" id="descriptionError"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الترتيب</label>
                        <input type="number" class="form-control" id="itemSortOrder" name="sort_order"
                               min="0" placeholder="0">
                        <div class="invalid-feedback" id="sortOrderError"></div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                        <div>
                            <span class="fw-semibold">الحالة</span>
                            <p class="text-muted mb-0 small">تفعيل أو إيقاف طريقة الدفع</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input status-switch" type="checkbox"
                                   id="itemStatus" name="is_active" checked>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-modal-cancel" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary btn-modal-save" id="saveBtn">
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="ti ti-alert-triangle text-danger" style="font-size: 48px;"></i>
                </div>
                <h5 class="mb-2">تأكيد الحذف</h5>
                <p class="text-muted mb-0">
                    هل أنت متأكد من حذف
                    <strong id="deleteItemName"></strong>؟
                </p>
            </div>
            <div class="modal-footer justify-content-center border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" onclick="confirmDelete()">
                    <span class="btn-text">حذف</span>
                    <span class="btn-loading d-none">
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        جاري الحذف...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>
@endsection

@push('scripts')
<script>
let currentPage = 1;
let deleteItemId = null;
let isSubmitting = false;

const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const formModal = new bootstrap.Modal(document.getElementById('formModal'));
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
    document.getElementById('formModal').addEventListener('hidden.bs.modal', resetForm);
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
        const response = await fetch(`{{ route('payment-methods.data') }}?${params}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();

        if (result.success) {
            renderData(result.data);
            renderPagination(result.pagination);
        }
    } catch (error) {
        console.error('Error loading data:', error);
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

    tbody.innerHTML = items.map(item => `
        <tr data-id="${item.id}">
            <td>
                <div class="method-name">${escapeHtml(item.name)}</div>
                ${item.description ? `<div class="method-description">${escapeHtml(item.description)}</div>` : ''}
            </td>
            <td>
                <span class="badge-order">${item.sort_order}</span>
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
                    <button type="button" class="btn action-btn btn-edit"
                            onclick="openEditModal(${item.id})" title="تعديل">
                        <i class="ti ti-pencil fs-16"></i>
                    </button>
                    <button type="button" class="btn action-btn btn-delete"
                            onclick="openDeleteModal(${item.id}, '${escapeHtml(item.name)}')" title="حذف">
                        <i class="ti ti-trash fs-16"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function renderPagination(pagination) {
    document.getElementById('paginationFrom').textContent = pagination.from || 0;
    document.getElementById('paginationTo').textContent = pagination.to || 0;
    document.getElementById('paginationTotal').textContent = pagination.total;

    const nav = document.getElementById('paginationNav');
    let html = '';

    html += `
        <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="goToPage(${pagination.current_page - 1}); return false;">
                <i class="ti ti-chevron-right"></i>
            </a>
        </li>
    `;

    for (let i = 1; i <= pagination.last_page; i++) {
        if (i === 1 || i === pagination.last_page ||
            (i >= pagination.current_page - 2 && i <= pagination.current_page + 2)) {
            html += `
                <li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="goToPage(${i}); return false;">${i}</a>
                </li>
            `;
        } else if (i === pagination.current_page - 3 || i === pagination.current_page + 3) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
    }

    html += `
        <li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="goToPage(${pagination.current_page + 1}); return false;">
                <i class="ti ti-chevron-left"></i>
            </a>
        </li>
    `;

    nav.innerHTML = html;
}

function goToPage(page) {
    currentPage = page;
    loadData();
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'إضافة طريقة دفع جديدة';
    document.getElementById('itemId').value = '';
    document.getElementById('saveBtn').querySelector('.btn-text').textContent = 'حفظ';
    formModal.show();
    setTimeout(() => document.getElementById('itemName').focus(), 300);
}

async function openEditModal(id) {
    try {
        const response = await fetch(`{{ url('payment-methods') }}/${id}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();

        if (result.success) {
            const item = result.data;

            document.getElementById('modalTitle').textContent = 'تعديل طريقة الدفع';
            document.getElementById('itemId').value = item.id;
            document.getElementById('itemName').value = item.name;
            document.getElementById('itemDescription').value = item.description || '';
            document.getElementById('itemSortOrder').value = item.sort_order;
            document.getElementById('itemStatus').checked = item.is_active;
            document.getElementById('saveBtn').querySelector('.btn-text').textContent = 'تحديث';

            formModal.show();
            setTimeout(() => document.getElementById('itemName').focus(), 300);
        }
    } catch (error) {
        console.error('Error loading item:', error);
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
        description: document.getElementById('itemDescription').value || null,
        sort_order: document.getElementById('itemSortOrder').value || null,
        is_active: document.getElementById('itemStatus').checked
    };

    setSubmitting(true);

    try {
        const url = isEdit ? `{{ url('payment-methods') }}/${itemId}` : '{{ route('payment-methods.store') }}';
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
        console.error('Error saving:', error);
        showToast('حدث خطأ في الحفظ', 'error');
    } finally {
        setSubmitting(false);
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
        const response = await fetch(`{{ url('payment-methods') }}/${deleteItemId}`, {
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
        console.error('Error deleting:', error);
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
        const response = await fetch(`{{ url('payment-methods') }}/${id}/toggle-status`, {
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
        console.error('Error toggling status:', error);
        showToast('حدث خطأ في تحديث الحالة', 'error');
    }
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('sortFilter').value = 'sort_order-asc';
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
        const errorDiv = document.getElementById(`${field.replace('_', '')}Error`) ||
                        document.getElementById(`${field}Error`);

        if (input) {
            input.classList.add('is-invalid');
        }

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

    if (state) {
        btn.querySelector('.btn-text').classList.add('d-none');
        btn.querySelector('.btn-loading').classList.remove('d-none');
        btn.disabled = true;
    } else {
        btn.querySelector('.btn-text').classList.remove('d-none');
        btn.querySelector('.btn-loading').classList.add('d-none');
        btn.disabled = false;
    }
}

function showLoading(show) {
    document.getElementById('loadingOverlay').classList.toggle('d-none', !show);
}

function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <i class="ti ti-${type === 'success' ? 'check' : 'x'} fs-18"></i>
        <span>${message}</span>
    `;
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
