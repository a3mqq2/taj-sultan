@extends('layouts.app')

@section('title', 'إدارة المستخدمين')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">المستخدمين</li>
@endsection

@push('styles')
<style>
    .users-table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
        background-color: #fff;
    }

    .users-table .table {
        margin-bottom: 0;
    }

    .users-table th {
        background: #f8fafc;
        font-weight: 600;
        padding: 14px 16px;
        border-bottom: 2px solid #d1d5db;
        border-top: none;
        white-space: nowrap;
        color: #374151;
        font-size: 14px;
    }

    .users-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #e5e7eb;
        border-top: none;
    }

    .users-table tbody tr {
        transition: all 0.15s ease;
    }

    .users-table tbody tr:hover {
        background-color: #f0f9ff;
    }

    [data-bs-theme="dark"] .users-table {
        background-color: #1f2937;
        border-color: #374151;
    }

    [data-bs-theme="dark"] .users-table th {
        background-color: #111827;
        border-color: #4b5563;
        color: #f3f4f6;
    }

    [data-bs-theme="dark"] .users-table td {
        border-color: #374151;
    }

    [data-bs-theme="dark"] .users-table tbody tr:hover {
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

    .user-name {
        font-weight: 600;
        color: var(--bs-body-color);
        font-size: 15px;
    }

    .user-username {
        font-size: 13px;
        color: #6b7280;
    }

    .role-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 13px;
    }

    .badge-admin {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
    }

    .badge-cashier {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .badge-sales {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
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

    .permissions-section {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }

    [data-bs-theme="dark"] .permissions-section {
        border-color: #374151;
    }

    .permission-group-header {
        background: #f8fafc;
        padding: 12px 16px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }

    .permission-group-header:hover {
        background: #f1f5f9;
    }

    [data-bs-theme="dark"] .permission-group-header {
        background: #374151;
        border-color: #4b5563;
    }

    [data-bs-theme="dark"] .permission-group-header:hover {
        background: #4b5563;
    }

    .permission-group-title {
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .permission-group-content {
        padding: 16px;
        background: #fff;
    }

    [data-bs-theme="dark"] .permission-group-content {
        background: #1f2937;
    }

    .permission-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
    }

    .permission-item:not(:last-child) {
        border-bottom: 1px solid #f3f4f6;
    }

    [data-bs-theme="dark"] .permission-item:not(:last-child) {
        border-color: #374151;
    }

    .permission-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .permission-checkbox:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }

    .permission-label {
        font-size: 14px;
        cursor: pointer;
    }

    .select-all-btn {
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 6px;
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

    .group-toggle-icon {
        transition: transform 0.2s ease;
    }

    .group-toggle-icon.collapsed {
        transform: rotate(-90deg);
    }
</style>
@endpush

@section('content')
<div class="">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <button type="button" class="btn btn-primary btn-add" onclick="openAddModal()">
            <i class="ti ti-plus fs-18"></i>
            إضافة مستخدم
        </button>
    </div>

    <div class="filters-card">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label">البحث</label>
                <div class="position-relative">
                    <input type="text" class="form-control search-input" id="searchInput"
                           placeholder="ابحث بالاسم أو اسم المستخدم...">
                    <i class="ti ti-search search-icon"></i>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">الدور</label>
                <select class="form-select filter-select" id="roleFilter">
                    <option value="">الكل</option>
                    <option value="admin">مدير</option>
                    <option value="sales">موظف مبيعات</option>
                    <option value="cashier">كاشير</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">الحالة</label>
                <select class="form-select filter-select" id="statusFilter">
                    <option value="">الكل</option>
                    <option value="active">نشط</option>
                    <option value="inactive">موقوف</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-light w-100" onclick="resetFilters()" title="إعادة تعيين" style="height: 50px;">
                    <i class="ti ti-refresh fs-18"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="card users-table position-relative">
        <div id="loadingOverlay" class="loading-overlay d-none">
            <div class="spinner"></div>
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width: 30%">المستخدم</th>
                        <th>اسم المستخدم</th>
                        <th>الدور</th>
                        <th>الحالة</th>
                        <th style="width: 120px">الإجراءات</th>
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
            <h5>لا يوجد مستخدمين</h5>
            <p class="text-muted mb-3">لم يتم العثور على أي مستخدمين.</p>
            <button type="button" class="btn btn-primary" onclick="openAddModal()">
                <i class="ti ti-plus me-1"></i>
                إضافة مستخدم
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
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">إضافة مستخدم جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="dataForm">
                <div class="modal-body">
                    <input type="hidden" id="itemId">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الاسم <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="itemName" name="name" placeholder="أدخل الاسم الكامل">
                            <div class="invalid-feedback" id="nameError"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">اسم المستخدم <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="itemUsername" name="username" placeholder="أدخل اسم المستخدم" autocomplete="off">
                            <div class="invalid-feedback" id="usernameError"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">كلمة المرور <span class="text-danger password-required">*</span></label>
                            <input type="password" class="form-control" id="itemPassword" name="password" placeholder="أدخل كلمة المرور" autocomplete="new-password">
                            <div class="invalid-feedback" id="passwordError"></div>
                            <small class="text-muted password-hint d-none">اتركه فارغاً للإبقاء على كلمة المرور الحالية</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الدور <span class="text-danger">*</span></label>
                            <select class="form-select" id="itemRole" name="role" onchange="toggleRoleFields()">
                                <option value="admin">مدير</option>
                                <option value="sales">موظف مبيعات</option>
                                <option value="cashier">كاشير</option>
                            </select>
                        </div>
                    </div>

                    <div class="row" id="posPointSection" style="display: none;">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">نقطة البيع</label>
                            <select class="form-select" id="itemPosPoint" name="pos_point_id">
                                <option value="">-- اختر نقطة البيع --</option>
                                @foreach($posPoints as $posPoint)
                                <option value="{{ $posPoint->id }}">{{ $posPoint->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="pos_point_idError"></div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3 status-box mb-3">
                        <div>
                            <span class="fw-semibold">حالة المستخدم</span>
                            <p class="text-muted mb-0 small">تفعيل أو تعطيل الحساب</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input status-switch" type="checkbox" id="itemStatus" name="is_active" checked>
                        </div>
                    </div>

                    <div id="permissionsSection">
                        <label class="form-label mb-3">
                            <i class="ti ti-shield-check me-1"></i>
                            الصلاحيات
                        </label>
                        <div class="permissions-section">
                            @foreach($permissions as $group => $groupPermissions)
                            <div class="permission-group">
                                <div class="permission-group-header" onclick="togglePermissionGroup('{{ $group }}')">
                                    <div class="permission-group-title">
                                        <i class="ti ti-chevron-down group-toggle-icon" id="icon-{{ $group }}"></i>
                                        {{ $groupPermissions->first()->group_display_name }}
                                        <span class="badge bg-secondary" id="count-{{ $group }}">0</span>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary select-all-btn" onclick="event.stopPropagation(); toggleGroupPermissions('{{ $group }}')">
                                        تحديد الكل
                                    </button>
                                </div>
                                <div class="permission-group-content" id="group-{{ $group }}">
                                    @foreach($groupPermissions as $permission)
                                    <div class="permission-item">
                                        <input type="checkbox" class="form-check-input permission-checkbox"
                                               id="perm-{{ $permission->id }}"
                                               name="permissions[]"
                                               value="{{ $permission->id }}"
                                               data-group="{{ $group }}"
                                               onchange="updateGroupCount('{{ $group }}')">
                                        <label class="permission-label" for="perm-{{ $permission->id }}">{{ $permission->display_name }}</label>
                                    </div>
                                    @endforeach
                                </div>
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

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="ti ti-alert-triangle text-danger" style="font-size: 48px;"></i>
                </div>
                <h5 class="mb-2">تأكيد الحذف</h5>
                <p class="text-muted mb-0">
                    هل أنت متأكد من حذف المستخدم
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

    ['roleFilter', 'statusFilter'].forEach(id => {
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
        role: document.getElementById('roleFilter').value,
        status: document.getElementById('statusFilter').value,
    });

    try {
        const response = await fetch(`{{ route('users.data') }}?${params}`, {
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

    tbody.innerHTML = items.map(item => `
        <tr data-id="${item.id}">
            <td>
                <div class="user-name">${escapeHtml(item.name)}</div>
            </td>
            <td>
                <span class="user-username">${escapeHtml(item.username)}</span>
            </td>
            <td>
                <span class="role-badge badge-${item.role}">${item.role_name}</span>
                ${item.pos_point_name ? `<div class="small text-muted mt-1"><i class="ti ti-device-desktop me-1"></i>${escapeHtml(item.pos_point_name)}</div>` : ''}
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
                    <button type="button" class="btn action-btn btn-edit" onclick="openEditModal(${item.id})" title="تعديل">
                        <i class="ti ti-pencil fs-16"></i>
                    </button>
                    <button type="button" class="btn action-btn btn-delete" onclick="openDeleteModal(${item.id}, '${escapeHtml(item.name)}')" title="حذف">
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
    document.getElementById('modalTitle').textContent = 'إضافة مستخدم جديد';
    document.getElementById('itemId').value = '';
    document.getElementById('saveBtn').querySelector('.btn-text').textContent = 'حفظ';
    document.querySelector('.password-required').classList.remove('d-none');
    document.querySelector('.password-hint').classList.add('d-none');
    document.getElementById('itemPassword').required = true;

    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('itemPosPoint').value = '';
    updateAllGroupCounts();
    toggleRoleFields();

    formModal.show();
    setTimeout(() => document.getElementById('itemName').focus(), 300);
}

async function openEditModal(id) {
    try {
        const response = await fetch(`{{ url('users') }}/${id}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });

        const result = await response.json();

        if (result.success) {
            const item = result.data;
            document.getElementById('modalTitle').textContent = 'تعديل المستخدم';
            document.getElementById('itemId').value = item.id;
            document.getElementById('itemName').value = item.name;
            document.getElementById('itemUsername').value = item.username;
            document.getElementById('itemPassword').value = '';
            document.getElementById('itemRole').value = item.role;
            document.getElementById('itemStatus').checked = item.is_active;
            document.getElementById('saveBtn').querySelector('.btn-text').textContent = 'تحديث';
            document.querySelector('.password-required').classList.add('d-none');
            document.querySelector('.password-hint').classList.remove('d-none');
            document.getElementById('itemPassword').required = false;

            document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);

            if (item.permissions && item.permissions.length > 0) {
                item.permissions.forEach(permId => {
                    const cb = document.getElementById(`perm-${permId}`);
                    if (cb) cb.checked = true;
                });
            }

            document.getElementById('itemPosPoint').value = item.pos_point_id || '';

            updateAllGroupCounts();
            toggleRoleFields();

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
    const role = document.getElementById('itemRole').value;

    const data = {
        name: document.getElementById('itemName').value,
        username: document.getElementById('itemUsername').value,
        role: role,
        is_active: document.getElementById('itemStatus').checked,
    };

    const password = document.getElementById('itemPassword').value;
    if (password) {
        data.password = password;
    }

    if (role === 'admin') {
        const permissions = [];
        document.querySelectorAll('.permission-checkbox:checked').forEach(cb => {
            permissions.push(parseInt(cb.value));
        });
        data.permissions = permissions;
    } else {
        data.permissions = [];
    }

    if (role === 'sales' || role === 'cashier') {
        const posPointId = document.getElementById('itemPosPoint').value;
        if (posPointId) {
            data.pos_point_id = parseInt(posPointId);
        }
    }

    setSubmitting(true);

    try {
        const url = isEdit ? `{{ url('users') }}/${itemId}` : '{{ route('users.store') }}';
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
        const response = await fetch(`{{ url('users') }}/${deleteItemId}`, {
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
        const response = await fetch(`{{ url('users') }}/${id}/toggle-status`, {
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

function toggleRoleFields() {
    const role = document.getElementById('itemRole').value;
    const permissionsSection = document.getElementById('permissionsSection');
    const posPointSection = document.getElementById('posPointSection');

    if (role === 'admin') {
        permissionsSection.style.display = 'block';
        posPointSection.style.display = 'none';
    } else if (role === 'sales' || role === 'cashier') {
        permissionsSection.style.display = 'none';
        posPointSection.style.display = 'block';
    } else {
        permissionsSection.style.display = 'none';
        posPointSection.style.display = 'none';
    }
}

function togglePermissionGroup(group) {
    const content = document.getElementById(`group-${group}`);
    const icon = document.getElementById(`icon-${group}`);

    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.classList.remove('collapsed');
    } else {
        content.style.display = 'none';
        icon.classList.add('collapsed');
    }
}

function toggleGroupPermissions(group) {
    const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);

    checkboxes.forEach(cb => cb.checked = !allChecked);
    updateGroupCount(group);
}

function updateGroupCount(group) {
    const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
    const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
    document.getElementById(`count-${group}`).textContent = checkedCount;
}

function updateAllGroupCounts() {
    const groups = new Set();
    document.querySelectorAll('.permission-checkbox').forEach(cb => {
        groups.add(cb.dataset.group);
    });
    groups.forEach(group => updateGroupCount(group));
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('roleFilter').value = '';
    document.getElementById('statusFilter').value = '';
    currentPage = 1;
    loadData();
}

function resetForm() {
    document.getElementById('dataForm').reset();
    document.getElementById('itemId').value = '';
    document.getElementById('itemStatus').checked = true;
    document.getElementById('itemPosPoint').value = '';
    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
    updateAllGroupCounts();
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
