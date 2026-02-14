@extends('layouts.app')

@section('title', 'إدارة نقاط البيع')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">نقاط البيع</li>
@endsection

@push('styles')
<style>
    .pos-points-table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
        background-color: #fff;
    }

    .pos-points-table .table {
        margin-bottom: 0;
    }

    .pos-points-table th {
        background: #f8fafc;
        font-weight: 600;
        padding: 14px 16px;
        border-bottom: 2px solid #d1d5db;
        border-top: none;
        white-space: nowrap;
        color: #374151;
        font-size: 14px;
    }

    .pos-points-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #e5e7eb;
        border-top: none;
    }

    .pos-points-table tbody tr {
        transition: all 0.15s ease;
    }

    .pos-points-table tbody tr:hover {
        background-color: #f0f9ff;
    }

    [data-bs-theme="dark"] .pos-points-table {
        background-color: #1f2937;
        border-color: #374151;
    }

    [data-bs-theme="dark"] .pos-points-table th {
        background-color: #111827;
        border-color: #4b5563;
        color: #f3f4f6;
    }

    [data-bs-theme="dark"] .pos-points-table td {
        border-color: #374151;
    }

    [data-bs-theme="dark"] .pos-points-table tbody tr:hover {
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

    .status-switch {
        width: 50px;
        height: 26px;
        cursor: pointer;
    }

    .status-switch:checked {
        background-color: #10b981;
        border-color: #10b981;
    }

    .pos-name {
        font-weight: 600;
        color: var(--bs-body-color);
        font-size: 15px;
    }

    .pos-slug {
        font-size: 13px;
        color: #6b7280;
        font-family: monospace;
    }

    .badge-active {
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 13px;
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .badge-inactive {
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 13px;
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .badge-yes {
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 13px;
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
    }

    .badge-no {
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 13px;
        background: rgba(156, 163, 175, 0.1);
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

    .form-control {
        border-radius: 10px;
        padding: 12px 16px;
        border: 2px solid #d1d5db;
        font-size: 15px;
        transition: all 0.2s ease;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .form-control:disabled {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }

    [data-bs-theme="dark"] .form-control {
        background-color: #1f2937;
        border-color: #4b5563;
        color: #f9fafb;
    }

    [data-bs-theme="dark"] .form-control:disabled {
        background-color: #374151;
    }

    .status-box {
        background-color: #f3f4f6;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
    }

    [data-bs-theme="dark"] .status-box {
        background-color: #374151;
        border-color: #4b5563;
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

    [data-bs-theme="dark"] .loading-overlay {
        background: rgba(0,0,0,0.7);
    }

    .page-description {
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 24px;
    }
</style>
@endpush

@section('content')
<div class="">
    <p class="page-description">
        إدارة نقاط البيع وتحديد حالتها وإعداداتها
    </p>

    <div class="card pos-points-table position-relative">
        <div id="loadingOverlay" class="loading-overlay d-none">
            <div class="spinner"></div>
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width: 25%">نقطة البيع</th>
                        <th>الأقسام</th>
                        <th>الحالة</th>
                        <th>يتطلب تسجيل دخول</th>
                        <th style="width: 100px">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل نقطة البيع</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <input type="hidden" id="itemId">

                    <div class="mb-4">
                        <label class="form-label">اسم نقطة البيع</label>
                        <input type="text" class="form-control" id="itemName" disabled>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-3 status-box mb-3">
                        <div>
                            <span class="fw-semibold">الحالة</span>
                            <p class="text-muted mb-0 small">تفعيل أو إيقاف نقطة البيع</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input status-switch" type="checkbox" id="itemActive">
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-3 status-box mb-3">
                        <div>
                            <span class="fw-semibold">يتطلب تسجيل دخول</span>
                            <p class="text-muted mb-0 small">إلزام المستخدم بتسجيل الدخول</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input status-switch" type="checkbox" id="itemRequireLogin">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الأقسام المتاحة</label>
                        <p class="text-muted small mb-2">اختر الأقسام التي ستظهر في نقطة البيع هذه (اتركها فارغة لإظهار جميع الأقسام)</p>
                        <div id="categoriesCheckboxes" style="max-height: 200px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px;">
                            @foreach($categories as $category)
                            <div class="form-check mb-2">
                                <input class="form-check-input category-checkbox" type="checkbox" value="{{ $category->id }}" id="cat_{{ $category->id }}">
                                <label class="form-check-label" for="cat_{{ $category->id }}">{{ $category->name }}</label>
                            </div>
                            @endforeach
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

<div class="toast-container" id="toastContainer"></div>
@endsection

@push('scripts')
<script>
let isSubmitting = false;

const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const editModal = new bootstrap.Modal(document.getElementById('editModal'));

document.addEventListener('DOMContentLoaded', function() {
    loadData();
    document.getElementById('editForm').addEventListener('submit', handleSubmit);
});

async function loadData() {
    showLoading(true);

    try {
        const response = await fetch(`{{ route('pos-points.data') }}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();

        if (result.success) {
            renderData(result.data);
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

    tbody.innerHTML = items.map(item => `
        <tr data-id="${item.id}">
            <td>
                <div class="pos-name">${escapeHtml(item.name)}</div>
                <div class="pos-slug">/${item.slug}</div>
            </td>
            <td>
                ${item.categories && item.categories.length > 0
                    ? item.categories.map(c => `<span class="badge bg-primary me-1 mb-1">${escapeHtml(c)}</span>`).join('')
                    : '<span class="text-muted">جميع الأقسام</span>'}
            </td>
            <td>
                <span class="badge-${item.active ? 'active' : 'inactive'}">
                    ${item.active ? 'مفعل' : 'متوقف'}
                </span>
            </td>
            <td>
                <span class="badge-${item.require_login ? 'yes' : 'no'}">
                    ${item.require_login ? 'نعم' : 'لا'}
                </span>
            </td>
            <td>
                <button type="button" class="btn action-btn btn-edit" onclick="openEditModal(${item.id})" title="تعديل">
                    <i class="ti ti-pencil fs-16"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

async function openEditModal(id) {
    try {
        const response = await fetch(`{{ url('pos-points') }}/${id}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();

        if (result.success) {
            const item = result.data;
            document.getElementById('itemId').value = item.id;
            document.getElementById('itemName').value = item.name;
            document.getElementById('itemActive').checked = item.active;
            document.getElementById('itemRequireLogin').checked = item.require_login;

            document.querySelectorAll('.category-checkbox').forEach(cb => {
                cb.checked = item.category_ids && item.category_ids.includes(parseInt(cb.value));
            });

            editModal.show();
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('حدث خطأ في تحميل البيانات', 'error');
    }
}

async function handleSubmit(e) {
    e.preventDefault();
    if (isSubmitting) return;

    const itemId = document.getElementById('itemId').value;

    const selectedCategories = [];
    document.querySelectorAll('.category-checkbox:checked').forEach(cb => {
        selectedCategories.push(parseInt(cb.value));
    });

    const data = {
        active: document.getElementById('itemActive').checked,
        require_login: document.getElementById('itemRequireLogin').checked,
        category_ids: selectedCategories,
    };

    setSubmitting(true);

    try {
        const response = await fetch(`{{ url('pos-points') }}/${itemId}`, {
            method: 'PUT',
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
            editModal.hide();
            loadData();
            showToast(result.message, 'success');
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
