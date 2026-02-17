@extends('layouts.app')

@section('title', 'إدارة الاصناف')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">الاصناف</li>
@endsection

@push('styles')
<style>
    /* تصميم الجدول */
    .products-table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
        background-color: #fff;
    }

    .products-table .table {
        margin-bottom: 0;
    }

    .products-table th {
        background: #f8fafc;
        font-weight: 600;
        padding: 14px 16px;
        border-bottom: 2px solid #d1d5db;
        border-top: none;
        white-space: nowrap;
        color: #374151;
        font-size: 14px;
    }

    .products-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #e5e7eb;
        border-top: none;
    }

    .products-table tbody tr {
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .products-table tbody tr:hover {
        background-color: #f0f9ff;
    }

    .products-table tbody tr:active {
        background-color: #e0f2fe;
    }

    /* Dark mode للجدول */
    [data-bs-theme="dark"] .products-table {
        background-color: #1f2937;
        border-color: #374151;
    }

    [data-bs-theme="dark"] .products-table th {
        background-color: #111827;
        border-color: #4b5563;
        color: #f3f4f6;
    }

    [data-bs-theme="dark"] .products-table td {
        border-color: #374151;
    }

    [data-bs-theme="dark"] .products-table tbody tr:hover {
        background-color: #374151;
    }

    /* Toggle Switch */
    .status-switch {
        width: 50px;
        height: 26px;
        cursor: pointer;
    }

    .status-switch:checked {
        background-color: #10b981;
        border-color: #10b981;
    }

    /* شريط البحث والفلاتر */
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

    /* Dark mode للفلاتر */
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

    /* زر إضافة صنف */
    .btn-add-product {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-add-product:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.3);
    }

    /* Badges */
    .badge-type {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 13px;
    }

    .badge-piece {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .badge-weight {
        background: rgba(168, 85, 247, 0.1);
        color: #a855f7;
    }

    .badge-status {
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 13px;
    }

    .badge-active {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .badge-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    /* الصنف */
    .product-name {
        font-weight: 600;
        color: var(--bs-body-color);
        font-size: 15px;
    }

    .product-barcode {
        font-size: 12px;
        color: var(--bs-secondary);
        margin-top: 2px;
    }

    .product-price {
        font-weight: 700;
        color: var(--bs-success);
        font-size: 16px;
    }

    .product-category {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: var(--bs-light);
        border-radius: 6px;
        font-size: 13px;
        color: var(--bs-body-color);
    }

    /* Modal تصميم */
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

    /* Dark mode للـ Modal */
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

    /* حقول النموذج */
    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--bs-body-color);
    }

    #productModal .form-control,
    #productModal .form-select {
        border-radius: 10px;
        padding: 12px 16px;
        border: 2px solid #d1d5db;
        font-size: 15px;
        transition: all 0.2s ease;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    #productModal .form-control:hover,
    #productModal .form-select:hover {
        border-color: #9ca3af;
    }

    #productModal .form-control:focus,
    #productModal .form-select:focus {
        border-color: #3b82f6;
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        outline: none;
    }

    #productModal .form-control::placeholder {
        color: #9ca3af;
    }

    #productModal .input-group .form-control {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    #productModal .input-group .btn,
    #productModal .input-group .input-group-text {
        border: 2px solid #d1d5db;
        border-right: none;
        background-color: #f3f4f6;
        font-weight: 500;
    }

    #productModal .input-group .btn {
        border-radius: 10px 0 0 10px;
        border: 2px solid #d1d5db;
        border-right: none;
    }

    #productModal .input-group .btn:hover {
        background-color: #e5e7eb;
        border-color: #9ca3af;
    }

    #productModal .input-group .input-group-text {
        border-radius: 10px 0 0 10px;
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

    /* Dark mode للحقول */
    [data-bs-theme="dark"] #productModal .form-control,
    [data-bs-theme="dark"] #productModal .form-select {
        background-color: #1f2937;
        border-color: #4b5563;
        color: #f9fafb;
    }

    [data-bs-theme="dark"] #productModal .form-control:hover,
    [data-bs-theme="dark"] #productModal .form-select:hover {
        border-color: #6b7280;
    }

    [data-bs-theme="dark"] #productModal .form-control:focus,
    [data-bs-theme="dark"] #productModal .form-select:focus {
        border-color: #3b82f6;
        background-color: #1f2937;
    }

    [data-bs-theme="dark"] #productModal .input-group .btn,
    [data-bs-theme="dark"] #productModal .input-group .input-group-text {
        background-color: #374151;
        border-color: #4b5563;
        color: #f9fafb;
    }

    /* Status Box */
    .status-box {
        background-color: #f3f4f6;
        border: 2px solid #e5e7eb;
    }

    [data-bs-theme="dark"] .status-box {
        background-color: #374151;
        border-color: #4b5563;
    }

    /* أزرار Modal */
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

    /* Pagination */
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

    /* Toast */
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

    /* Loading Overlay */
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

    /* حالة فارغة */
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

    /* Responsive */
    @media (max-width: 768px) {
        .filters-card {
            padding: 16px;
        }

        .products-table th,
        .products-table td {
            padding: 12px;
        }

        .action-btn {
            width: 34px;
            height: 34px;
        }

        .pagination-wrapper {
            flex-direction: column;
            text-align: center;
        }
    }

    /* Dark mode */
    [data-bs-theme="dark"] .products-table {
        background: var(--bs-body-bg);
    }

    [data-bs-theme="dark"] .loading-overlay {
        background: rgba(0,0,0,0.7);
    }

    [data-bs-theme="dark"] .product-category {
        background: rgba(255,255,255,0.1);
    }
</style>
@endpush

@section('content')
<div class="">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary btn-add-product" onclick="openAddModal()">
                <i class="ti ti-plus fs-18"></i>
                إضافة صنف
            </button>
            <a href="{{ route('products.export') }}" class="btn btn-success" id="exportBtn">
                <i class="ti ti-file-spreadsheet fs-18 me-1"></i>
                تصدير Excel
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">البحث</label>
                <div class="position-relative">
                    <input type="text" class="form-control search-input" id="searchInput"
                           placeholder="ابحث بالاسم أو الباركود...">
                    <i class="ti ti-search search-icon"></i>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">نقطة البيع</label>
                <select class="form-select filter-select" id="posPointFilter">
                    <option value="">الكل</option>
                    @foreach($posPoints as $posPoint)
                        <option value="{{ $posPoint->id }}">{{ $posPoint->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
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
                    <option value="created_at-desc">الأحدث</option>
                    <option value="created_at-asc">الأقدم</option>
                    <option value="name-asc">الاسم (أ-ي)</option>
                    <option value="name-desc">الاسم (ي-أ)</option>
                    <option value="price-asc">السعر (الأقل)</option>
                    <option value="price-desc">السعر (الأعلى)</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-light w-100" onclick="resetFilters()" title="إعادة تعيين">
                    <i class="ti ti-refresh"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card products-table position-relative">
        <div id="loadingOverlay" class="loading-overlay d-none">
            <div class="spinner"></div>
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width: 30%">الصنف</th>
                        <th>السعر</th>
                        <th>المخزون</th>
                        <th>نقطة البيع</th>
                        <th>النوع</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody">
                    <!-- سيتم ملؤها بـ JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="empty-state d-none">
            <div class="empty-state-icon">
                <i class="ti ti-package-off"></i>
            </div>
            <h5 class="empty-state-title">لا توجد اصناف</h5>
            <p class="empty-state-text">لم يتم العثور على أي اصناف. أضف صنفك الأول الآن!</p>
            <button type="button" class="btn btn-primary" onclick="openAddModal()">
                <i class="ti ti-plus me-1"></i>
                إضافة صنف
            </button>
        </div>

        <!-- Pagination -->
        <div id="paginationWrapper" class="pagination-wrapper px-3 d-none">
            <div class="pagination-info">
                عرض <span id="paginationFrom">0</span> - <span id="paginationTo">0</span>
                من <span id="paginationTotal">0</span> صنف
            </div>
            <nav>
                <ul class="pagination" id="paginationNav">
                    <!-- سيتم ملؤها بـ JavaScript -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Add/Edit Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalTitle">إضافة صنف جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="productForm">
                <div class="modal-body">
                    <input type="hidden" id="productId">

                    <div class="mb-3">
                        <label class="form-label">اسم الصنف <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="productName" name="name"
                               placeholder="مثال: بقلاوة" autofocus>
                        <div class="invalid-feedback" id="nameError"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">السعر <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="productPrice" name="price"
                                       step="0.01" min="0" placeholder="0.00">
                                <span class="input-group-text" id="priceUnit">د.ل</span>
                            </div>
                            <div class="invalid-feedback" id="priceError"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">النوع <span class="text-danger">*</span></label>
                            <select class="form-select" id="productType" name="type">
                                <option value="piece">قطعة</option>
                                <option value="weight">وزن</option>
                            </select>
                            <div class="invalid-feedback" id="typeError"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">نقطة البيع <span class="text-danger">*</span></label>
                        <select class="form-select" id="productPosPoint" name="pos_point_id">
                            <option value="">اختر نقطة البيع</option>
                            @foreach($posPoints as $posPoint)
                                <option value="{{ $posPoint->id }}">{{ $posPoint->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="pos_point_idError"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الباركود <span class="text-muted">(اختياري)</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="productBarcode" name="barcode"
                                   placeholder="أدخل الباركود">
                            <button type="button" class="btn btn-outline-secondary" onclick="generateBarcode()" title="توليد باركود">
                                <i class="ti ti-refresh"></i>
                                توليد
                            </button>
                        </div>
                        <div class="invalid-feedback" id="barcodeError"></div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3 status-box">
                        <div>
                            <span class="fw-semibold">حالة الصنف</span>
                            <p class="text-muted mb-0 small">تفعيل أو إيقاف عرض الصنف</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input status-switch" type="checkbox"
                                   id="productStatus" name="is_active" checked>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger d-none" id="deleteProductBtn" onclick="confirmDeleteFromModal()">
                        <i class="ti ti-trash me-1"></i>
                        <span class="btn-text">حذف</span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                        </span>
                    </button>
                    <div class="d-flex gap-2 me-auto" id="modalActionsRight">
                        <button type="button" class="btn btn-outline-info d-none" id="stockLogBtn" onclick="openStockLogFromModal()">
                            <i class="ti ti-history me-1"></i>
                            سجل المخزون
                        </button>
                        <button type="button" class="btn btn-outline-secondary d-none" id="printBarcodeBtn" onclick="printBarcode()">
                            <i class="ti ti-barcode me-1"></i>
                            طباعة باركود
                        </button>
                        <button type="button" class="btn btn-light btn-modal-cancel" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary btn-modal-save" id="saveProductBtn">
                            <span class="btn-text">حفظ الصنف</span>
                            <span class="btn-loading d-none">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                جاري الحفظ...
                            </span>
                        </button>
                    </div>
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
                    هل أنت متأكد من حذف الصنف
                    <strong id="deleteProductName"></strong>؟
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

<!-- Stock Modal -->
<div class="modal fade" id="stockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة مخزون</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="stockForm" onsubmit="submitStock(event)">
                <div class="modal-body">
                    <p class="fw-bold mb-2" id="stockProductName"></p>
                    <p class="text-muted mb-3">المخزون الحالي: <span id="stockCurrentQty" class="fw-bold"></span></p>
                    <input type="hidden" id="stockProductId">
                    <div class="mb-3">
                        <label class="form-label">الكمية المضافة</label>
                        <input type="number" class="form-control" id="stockQuantity" min="1" required style="font-size:18px;text-align:center;font-weight:700;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ملاحظة <span class="text-muted">(اختياري)</span></label>
                        <input type="text" class="form-control" id="stockNotes" placeholder="مثال: توريد جديد">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="stockSubmitBtn">
                        <span class="btn-text">إضافة</span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Stock Log Modal -->
<div class="modal fade" id="stockLogModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">سجل حركة المخزون - <span id="stockLogProductName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height:400px;overflow-y:auto;">
                <table class="table table-sm" id="stockLogTable">
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>النوع</th>
                            <th>الكمية</th>
                            <th>قبل</th>
                            <th>بعد</th>
                            <th>المستخدم</th>
                            <th>ملاحظة</th>
                        </tr>
                    </thead>
                    <tbody id="stockLogBody"></tbody>
                </table>
                <div id="stockLogEmpty" class="text-center text-muted py-4 d-none">لا توجد حركات مخزون</div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>
@endsection

@push('scripts')
<script>
// المتغيرات العامة
let currentPage = 1;
let deleteProductId = null;
let isSubmitting = false;

// تهيئة CSRF
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Modals
const productModal = new bootstrap.Modal(document.getElementById('productModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
const stockModal = new bootstrap.Modal(document.getElementById('stockModal'));
const stockLogModal = new bootstrap.Modal(document.getElementById('stockLogModal'));

// عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    loadProducts();

    // البحث الفوري
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            loadProducts();
        }, 300);
    });

    // الفلاتر
    ['posPointFilter', 'statusFilter', 'sortFilter'].forEach(id => {
        document.getElementById(id).addEventListener('change', function() {
            currentPage = 1;
            loadProducts();
            updateExportUrl();
        });
    });

    document.getElementById('searchInput').addEventListener('change', updateExportUrl);

    // نموذج الصنف
    document.getElementById('productForm').addEventListener('submit', handleProductSubmit);

    // عند إغلاق Modal إعادة تعيين النموذج
    document.getElementById('productModal').addEventListener('hidden.bs.modal', resetForm);

    // عند فتح Modal التركيز على حقل الاسم
    document.getElementById('productModal').addEventListener('shown.bs.modal', function() {
        document.getElementById('productName').focus();
        document.getElementById('productName').select();
    });

    // تحديث وحدة السعر عند تغيير النوع
    document.getElementById('productType').addEventListener('change', updatePriceUnit);

    // اختصارات لوحة المفاتيح
    document.addEventListener('keydown', function(e) {
        // تجاهل إذا كان المستخدم يكتب في حقل إدخال
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
            return;
        }

        // 1 = فتح مودل إضافة صنف جديد
        if (e.key === '1') {
            e.preventDefault();
            openAddModal();
        }

        // / = الانتقال لحقل البحث
        if (e.key === '/') {
            e.preventDefault();
            document.getElementById('searchInput').focus();
            document.getElementById('searchInput').select();
        }
    });
});

// تحميل الاصناف
async function loadProducts() {
    showLoading(true);

    const params = new URLSearchParams({
        page: currentPage,
        search: document.getElementById('searchInput').value,
        pos_point_id: document.getElementById('posPointFilter').value,
        status: document.getElementById('statusFilter').value,
    });

    const sortValue = document.getElementById('sortFilter').value.split('-');
    params.append('sort', sortValue[0]);
    params.append('direction', sortValue[1]);

    try {
        const response = await fetch(`{{ route('products.data') }}?${params}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();

        if (result.success) {
            renderProducts(result.data);
            renderPagination(result.pagination);
        }
    } catch (error) {
        console.error('Error loading products:', error);
        showToast('حدث خطأ في تحميل الاصناف', 'error');
    } finally {
        showLoading(false);
    }
}

// عرض الاصناف
function renderProducts(products) {
    const tbody = document.getElementById('productsTableBody');
    const emptyState = document.getElementById('emptyState');
    const paginationWrapper = document.getElementById('paginationWrapper');
    const searchValue = document.getElementById('searchInput').value.trim();

    // فتح المودل تلقائياً عند وجود نتيجة واحدة فقط في البحث
    if (products.length === 1 && searchValue.length > 0) {
        openEditModal(products[0].id);
        return;
    }

    if (products.length === 0) {
        tbody.innerHTML = '';
        emptyState.classList.remove('d-none');
        paginationWrapper.classList.add('d-none');
        return;
    }

    emptyState.classList.add('d-none');
    paginationWrapper.classList.remove('d-none');

    tbody.innerHTML = products.map(product => `
        <tr data-id="${product.id}" onclick="openEditModal(${product.id})">
            <td>
                <div class="product-name">${escapeHtml(product.name)}</div>
                ${product.barcode ? `<div class="product-barcode">${escapeHtml(product.barcode)}</div>` : ''}
            </td>
            <td>
                <span class="product-price">${formatPrice(product.price)}${product.type === 'weight' ? ' / كجم' : ''}</span>
            </td>
            <td onclick="event.stopPropagation()">
                <span class="fw-bold ${parseFloat(product.stock) < 0 ? 'text-danger' : parseFloat(product.stock) == 0 ? 'text-warning' : 'text-success'}">${product.type === 'weight' ? parseFloat(product.stock).toFixed(3) + ' كجم' : parseFloat(product.stock).toFixed(0)}</span>
                <button class="btn btn-sm btn-outline-primary ms-1" onclick="openStockModal(${product.id}, '${escapeHtml(product.name)}', ${product.stock}, '${product.type}')" title="إضافة مخزون" style="padding:2px 6px;font-size:12px;">
                    <i class="ti ti-plus"></i>
                </button>
            </td>
            <td>
                <span class="product-category">
                    <i class="ti ti-device-desktop fs-14"></i>
                    ${escapeHtml(product.pos_point?.name || '-')}
                </span>
            </td>
            <td>
                <span class="badge badge-type ${product.type === 'piece' ? 'badge-piece' : 'badge-weight'}">
                    ${product.type === 'piece' ? 'قطعة' : 'وزن'}
                </span>
            </td>
            <td onclick="event.stopPropagation()">
                <div class="form-check form-switch">
                    <input class="form-check-input status-switch" type="checkbox"
                           ${product.is_active ? 'checked' : ''}
                           onchange="toggleStatus(${product.id}, this)">
                </div>
            </td>
        </tr>
    `).join('');
}

// عرض الـ Pagination
function renderPagination(pagination) {
    document.getElementById('paginationFrom').textContent = pagination.from || 0;
    document.getElementById('paginationTo').textContent = pagination.to || 0;
    document.getElementById('paginationTotal').textContent = pagination.total;

    const nav = document.getElementById('paginationNav');
    let html = '';

    // زر السابق
    html += `
        <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="goToPage(${pagination.current_page - 1}); return false;">
                <i class="ti ti-chevron-right"></i>
            </a>
        </li>
    `;

    // أرقام الصفحات
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

    // زر التالي
    html += `
        <li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="goToPage(${pagination.current_page + 1}); return false;">
                <i class="ti ti-chevron-left"></i>
            </a>
        </li>
    `;

    nav.innerHTML = html;
}

// الانتقال لصفحة
function goToPage(page) {
    currentPage = page;
    loadProducts();
}

// فتح modal الإضافة
function openAddModal() {
    document.getElementById('productModalTitle').textContent = 'إضافة صنف جديد';
    document.getElementById('productId').value = '';
    document.getElementById('saveProductBtn').querySelector('.btn-text').textContent = 'حفظ الصنف';
    document.getElementById('deleteProductBtn').classList.add('d-none');
    document.getElementById('printBarcodeBtn').classList.add('d-none');
    document.getElementById('stockLogBtn').classList.add('d-none');
    productModal.show();
}

// فتح modal التعديل
async function openEditModal(id) {
    try {
        const response = await fetch(`{{ url('products') }}/${id}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();

        if (result.success) {
            const product = result.data;

            document.getElementById('productModalTitle').textContent = 'تعديل الصنف';
            document.getElementById('productId').value = product.id;
            document.getElementById('productName').value = product.name;
            document.getElementById('productPrice').value = product.price;
            document.getElementById('productType').value = product.type;
            document.getElementById('productPosPoint').value = product.pos_point_id || '';
            document.getElementById('productBarcode').value = product.barcode || '';
            document.getElementById('productStatus').checked = product.is_active;
            document.getElementById('saveProductBtn').querySelector('.btn-text').textContent = 'حفظ التعديلات';
            document.getElementById('deleteProductBtn').classList.remove('d-none');
            document.getElementById('stockLogBtn').classList.remove('d-none');
            if (product.barcode) {
                document.getElementById('printBarcodeBtn').classList.remove('d-none');
            } else {
                document.getElementById('printBarcodeBtn').classList.add('d-none');
            }
            updatePriceUnit();

            productModal.show();
        }
    } catch (error) {
        console.error('Error loading product:', error);
        showToast('حدث خطأ في تحميل بيانات الصنف', 'error');
    }
}

// معالجة النموذج
async function handleProductSubmit(e) {
    e.preventDefault();

    if (isSubmitting) return;

    clearErrors();

    const productId = document.getElementById('productId').value;
    const isEdit = !!productId;

    const data = {
        name: document.getElementById('productName').value,
        price: document.getElementById('productPrice').value,
        type: document.getElementById('productType').value,
        pos_point_id: document.getElementById('productPosPoint').value,
        barcode: document.getElementById('productBarcode').value || null,
        is_active: document.getElementById('productStatus').checked,
    };

    setSubmitting(true);

    try {
        const url = isEdit ? `{{ url('products') }}/${productId}` : '{{ route('products.store') }}';
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
            productModal.hide();
            loadProducts();
            showToast(result.message, 'success');
        } else if (response.status === 422) {
            // Validation errors
            displayErrors(result.errors);
        } else {
            showToast(result.message || 'حدث خطأ', 'error');
        }
    } catch (error) {
        console.error('Error saving product:', error);
        showToast('حدث خطأ في حفظ الصنف', 'error');
    } finally {
        setSubmitting(false);
    }
}

// فتح modal الحذف
function openDeleteModal(id, name) {
    deleteProductId = id;
    document.getElementById('deleteProductName').textContent = name;
    deleteModal.show();
}

// تأكيد الحذف
async function confirmDelete() {
    if (!deleteProductId || isSubmitting) return;

    const btn = document.getElementById('confirmDeleteBtn');
    btn.querySelector('.btn-text').classList.add('d-none');
    btn.querySelector('.btn-loading').classList.remove('d-none');
    btn.disabled = true;

    try {
        const response = await fetch(`{{ url('products') }}/${deleteProductId}`, {
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
            loadProducts();
            showToast(result.message, 'success');
        } else {
            showToast(result.message || 'حدث خطأ', 'error');
        }
    } catch (error) {
        console.error('Error deleting product:', error);
        showToast('حدث خطأ في حذف الصنف', 'error');
    } finally {
        btn.querySelector('.btn-text').classList.remove('d-none');
        btn.querySelector('.btn-loading').classList.add('d-none');
        btn.disabled = false;
        deleteProductId = null;
    }
}

// فتح مودل تأكيد الحذف من مودل التعديل
function confirmDeleteFromModal() {
    const productId = document.getElementById('productId').value;
    const productName = document.getElementById('productName').value;
    if (!productId) return;

    deleteProductId = productId;
    document.getElementById('deleteProductName').textContent = productName;
    productModal.hide();
    deleteModal.show();
}

// تفعيل/إيقاف الصنف
async function toggleStatus(id, checkbox) {
    try {
        const response = await fetch(`{{ url('products') }}/${id}/toggle-status`, {
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

// إعادة تعيين الفلاتر
function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('posPointFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('sortFilter').value = 'created_at-desc';
    currentPage = 1;
    loadProducts();
    updateExportUrl();
}

function updateExportUrl() {
    const params = new URLSearchParams();
    const search = document.getElementById('searchInput').value;
    const posPointId = document.getElementById('posPointFilter').value;
    const status = document.getElementById('statusFilter').value;

    if (search) params.append('search', search);
    if (posPointId) params.append('pos_point_id', posPointId);
    if (status) params.append('status', status);

    const baseUrl = '{{ route('products.export') }}';
    const exportBtn = document.getElementById('exportBtn');
    exportBtn.href = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
}

// إعادة تعيين النموذج
function resetForm() {
    document.getElementById('productForm').reset();
    document.getElementById('productId').value = '';
    document.getElementById('productStatus').checked = true;
    document.getElementById('priceUnit').textContent = 'د.ل';
    document.getElementById('productPosPoint').value = '';
    clearErrors();
}

// عرض الأخطاء
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

// مسح الأخطاء
function clearErrors() {
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });
}

// حالة الإرسال
function setSubmitting(state) {
    isSubmitting = state;
    const btn = document.getElementById('saveProductBtn');

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

// عرض/إخفاء التحميل
function showLoading(show) {
    document.getElementById('loadingOverlay').classList.toggle('d-none', !show);
}

// Toast
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

// تنسيق السعر
function formatPrice(price) {
    return parseFloat(price).toFixed(2) + ' د.ل';
}

// Escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// توليد باركود عشوائي
function generateBarcode() {
    const timestamp = Date.now().toString().slice(-8);
    const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
    const barcode = timestamp + random;
    document.getElementById('productBarcode').value = barcode;
}

// تحديث وحدة السعر حسب النوع
function updatePriceUnit() {
    const type = document.getElementById('productType').value;
    const priceUnit = document.getElementById('priceUnit');
    priceUnit.textContent = type === 'weight' ? 'د.ل / كجم' : 'د.ل';
}

function printBarcode() {
    const name = document.getElementById('productName').value;
    const barcode = document.getElementById('productBarcode').value;
    const price = document.getElementById('productPrice').value;
    const type = document.getElementById('productType').value;

    if (!barcode) {
        showToast('لا يوجد باركود لهذا الصنف', 'error');
        return;
    }

    const priceText = parseFloat(price).toFixed(3) + ' د.ل';

    const html = `<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>Label</title>
<script src="{{ asset('js/barcode/jsbarcode.min.js') }}"><\/script>
<style>
*{margin:0;padding:0;box-sizing:border-box}

html,body{
width:40mm;
height:26mm;
display:flex;
justify-content:center;
align-items:center;
}

body{font-family:Arial,Helvetica,sans-serif}

.label{
width:38mm;
height:24mm;
display:flex;
flex-direction:column;
align-items:center;
justify-content:center;
text-align:center;
gap:0;
}

.top{
font-size:6px;
font-weight:bold;
line-height:1;
white-space:nowrap;
overflow:hidden;
text-overflow:ellipsis;
margin:0;
padding:0;
}

.barcode{
margin:0;
padding:0;
line-height:0;
}

.barcode svg{
width:34mm;
height:10mm;
display:block;
margin:0;
padding:0;
}

.barcode-text{
font-size:6px;
font-family:monospace;
font-weight:bold;
line-height:1;
margin:0;
padding:0;
}

@media print{
@page{size:40mm 26mm;margin:0}

html,body{
width:40mm;
height:26mm;
display:flex;
justify-content:center;
align-items:center;
}

.no-print{display:none}
}
</style>
</head>
<body>

<div class="no-print" style="position:absolute;top:5px;text-align:center">
<button onclick="window.print()" style="padding:4px 15px;font-size:12px">طباعة</button>
</div>

<div class="label">

<div class="top">تاج السلطان</div>
<div class="top">الصنف:${escapeHtml(name)} / ${priceText}</div>

<div class="barcode">
<svg id="barcode"></svg>
</div>

<div class="barcode-text">${escapeHtml(barcode)}</div>

</div>

<script>
JsBarcode("#barcode","${barcode}",{
format:"CODE128",
width:0.65,
height:10,
displayValue:false,
margin:0
});

window.onload=function(){
setTimeout(function(){
window.print();
},200);
};

window.onafterprint=function(){
window.close();
};
<\/script>

</body>
</html>`;

    const win = window.open('', '_blank', 'width=420,height=320');
    if (win) {
        win.document.write(html);
        win.document.close();
    }
}




function openStockModal(id, name, currentStock, type) {
    document.getElementById('stockProductId').value = id;
    document.getElementById('stockProductName').textContent = name;
    const isWeight = type === 'weight';
    document.getElementById('stockCurrentQty').textContent = isWeight ? parseFloat(currentStock).toFixed(3) + ' كجم' : parseFloat(currentStock).toFixed(0);
    const qtyInput = document.getElementById('stockQuantity');
    qtyInput.value = '';
    qtyInput.step = isWeight ? '0.001' : '1';
    qtyInput.min = isWeight ? '0.001' : '1';
    qtyInput.placeholder = isWeight ? '0.000 كجم' : '0';
    document.getElementById('stockNotes').value = '';
    stockModal.show();
    setTimeout(() => qtyInput.focus(), 300);
}

async function submitStock(e) {
    e.preventDefault();
    const id = document.getElementById('stockProductId').value;
    const quantity = document.getElementById('stockQuantity').value;
    const notes = document.getElementById('stockNotes').value;

    const btn = document.getElementById('stockSubmitBtn');
    btn.querySelector('.btn-text').classList.add('d-none');
    btn.querySelector('.btn-loading').classList.remove('d-none');
    btn.disabled = true;

    try {
        const response = await fetch(`{{ url('products') }}/${id}/add-stock`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ quantity: parseFloat(quantity), notes: notes || null })
        });

        const result = await response.json();
        if (result.success) {
            stockModal.hide();
            loadProducts();
            showToast(result.message, 'success');
        } else {
            showToast(result.message || 'حدث خطأ', 'error');
        }
    } catch (error) {
        console.error('Error adding stock:', error);
        showToast('حدث خطأ في إضافة المخزون', 'error');
    } finally {
        btn.querySelector('.btn-text').classList.remove('d-none');
        btn.querySelector('.btn-loading').classList.add('d-none');
        btn.disabled = false;
    }
}

function openStockLogFromModal() {
    const id = document.getElementById('productId').value;
    const name = document.getElementById('productName').value;
    if (!id) return;
    productModal.hide();
    openStockLog(id, name);
}

async function openStockLog(id, name) {
    document.getElementById('stockLogProductName').textContent = name;
    document.getElementById('stockLogBody').innerHTML = '';
    document.getElementById('stockLogEmpty').classList.add('d-none');
    document.getElementById('stockLogTable').classList.remove('d-none');
    stockLogModal.show();

    try {
        const response = await fetch(`{{ url('products') }}/${id}/stock-movements`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();
        if (result.success) {
            if (result.data.length === 0) {
                document.getElementById('stockLogTable').classList.add('d-none');
                document.getElementById('stockLogEmpty').classList.remove('d-none');
                return;
            }

            const typeColors = { addition: 'text-success', sale: 'text-danger', adjustment: 'text-warning' };

            document.getElementById('stockLogBody').innerHTML = result.data.map(m => `
                <tr>
                    <td style="font-size:12px;">${m.created_at}</td>
                    <td><span class="fw-bold ${typeColors[m.type] || ''}">${m.type_name}</span></td>
                    <td class="fw-bold">${parseFloat(m.quantity).toFixed(3)}</td>
                    <td>${parseFloat(m.stock_before).toFixed(3)}</td>
                    <td class="fw-bold">${parseFloat(m.stock_after).toFixed(3)}</td>
                    <td>${m.user_name || '-'}</td>
                    <td style="font-size:12px;">${m.notes || (m.reference_id ? 'فاتورة #' + m.reference_id : '-')}</td>
                </tr>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading stock movements:', error);
        showToast('حدث خطأ في تحميل سجل المخزون', 'error');
    }
}
</script>
@endpush
