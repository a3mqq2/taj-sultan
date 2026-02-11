@extends('layouts.app')

@section('title', 'الطلبيات الخاصة')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">الطلبيات الخاصة</li>
@endsection

@push('styles')
<link href="{{ asset('assets/plugins/tom-select/tom-select.bootstrap5.min.css') }}" rel="stylesheet">
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

    [data-bs-theme="dark"] .orders-table {
        background-color: #1f2937;
        border-color: #374151;
    }

    [data-bs-theme="dark"] .orders-table th {
        background-color: #111827;
        border-color: #4b5563;
        color: #f3f4f6;
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

    .btn-add {
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
    .badge-in_progress { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-ready { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .badge-delivered { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-cancelled { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .amount-total { font-weight: 700; }
    .amount-paid { font-weight: 600; color: #10b981; }
    .amount-remaining { font-weight: 600; color: #ef4444; }
    .amount-remaining.paid { color: #10b981; }

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

    .modal-footer {
        padding: 16px 24px;
        border-top: 2px solid #e5e7eb;
        background-color: #f9fafb;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
    }

    .modal .form-control, .modal .form-select {
        border-radius: 10px;
        padding: 12px 16px;
        border: 2px solid #d1d5db;
        font-size: 15px;
    }

    .modal .form-control:focus, .modal .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    }

    .ts-wrapper .ts-control {
        border: 2px solid #d1d5db !important;
        border-radius: 10px !important;
        padding: 8px 12px !important;
        min-height: 48px !important;
    }

    .ts-wrapper.focus .ts-control {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15) !important;
    }

    .ts-wrapper .ts-dropdown {
        border: 2px solid #d1d5db !important;
        border-radius: 10px !important;
    }

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

    .items-table .form-control, .items-table .form-select {
        padding: 8px 12px;
        font-size: 14px;
    }

    .btn-remove-item {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
    }

    .total-row {
        background: #f0f9ff;
        font-weight: 700;
    }

    .total-row td {
        border-top: 2px solid #3b82f6;
    }

    .info-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
    }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
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
    .summary-card.paid .summary-value { color: #10b981; }
    .summary-card.remaining .summary-value { color: #ef4444; }

    .summary-label { font-size: 13px; color: var(--bs-secondary); }
    .summary-value { font-size: 20px; font-weight: 700; }

    .status-btn {
        border: none;
        cursor: pointer;
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 13px;
    }

    .status-option.active .badge-status {
        box-shadow: 0 0 0 3px var(--bs-primary);
    }

    .payments-list { max-height: 300px; overflow-y: auto; }

    .payment-item {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .payment-amount { font-weight: 700; color: #10b981; }
    .payment-date { font-size: 13px; color: var(--bs-secondary); }

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

    .items-section {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
    }

    .items-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .items-section-title {
        font-weight: 600;
        font-size: 15px;
    }
</style>
@endpush

@section('content')
<div class="">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <button type="button" class="btn btn-primary btn-add" onclick="openAddModal()">
            <i class="ti ti-plus fs-18"></i>
            إضافة طلب خاص
        </button>
    </div>

    <div class="filters-card">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">البحث</label>
                <div class="position-relative">
                    <input type="text" class="form-control search-input" id="searchInput" placeholder="ابحث بالاسم أو الهاتف...">
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
                <label class="form-label">من تاريخ</label>
                <input type="date" class="form-control filter-input" id="dateFromFilter">
            </div>
            <div class="col-md-2">
                <label class="form-label">إلى تاريخ</label>
                <input type="date" class="form-control filter-input" id="dateToFilter">
            </div>
            <div class="col-md-2">
                <label class="form-label">الترتيب</label>
                <select class="form-select filter-select" id="sortFilter">
                    <option value="created_at-desc">الأحدث</option>
                    <option value="created_at-asc">الأقدم</option>
                    <option value="delivery_date-asc">التسليم (الأقرب)</option>
                    <option value="delivery_date-desc">التسليم (الأبعد)</option>
                </select>
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
                        <th>المناسبة</th>
                        <th>التسليم</th>
                        <th>الإجمالي</th>
                        <th>المدفوع</th>
                        <th>المتبقي</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody"></tbody>
            </table>
        </div>

        <div id="emptyState" class="empty-state d-none">
            <div class="empty-state-icon">
                <i class="ti ti-gift-off"></i>
            </div>
            <h5>لا توجد طلبيات خاصة</h5>
            <p class="text-muted mb-3">أضف طلبك الأول الآن!</p>
            <button type="button" class="btn btn-primary" onclick="openAddModal()">
                <i class="ti ti-plus me-1"></i>
                إضافة طلب خاص
            </button>
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

<div class="modal fade" id="orderModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalTitle">إضافة طلب خاص</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="orderForm">
                <div class="modal-body">
                    <input type="hidden" id="orderId">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الزبون <span class="text-danger">*</span></label>
                            <div class="d-flex gap-2">
                                <div class="flex-grow-1">
                                    <select id="customerId" name="customer_id" placeholder="اختر الزبون...">
                                        <option value="">اختر الزبون...</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" data-phone="{{ $customer->phone }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn btn-outline-primary" onclick="openQuickCustomerModal()" title="إضافة زبون جديد">
                                    <i class="ti ti-plus"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" id="customerIdError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">نوع المناسبة <span class="text-danger">*</span></label>
                            <select id="eventType" name="event_type" placeholder="اختر أو أضف نوع جديد...">
                                <option value="">اختر نوع المناسبة...</option>
                                @foreach($eventTypes as $eventType)
                                    <option value="{{ $eventType->name }}">{{ $eventType->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="eventTypeError"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ التسليم <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="deliveryDate" name="delivery_date">
                            <div class="invalid-feedback" id="deliveryDateError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ملاحظات</label>
                            <input type="text" class="form-control" id="notes" name="notes" placeholder="ملاحظات...">
                        </div>
                    </div>

                    <div class="items-section">
                        <div class="items-section-header">
                            <span class="items-section-title">الأصناف</span>
                            <button type="button" class="btn btn-sm btn-primary" onclick="addItemRow()">
                                <i class="ti ti-plus me-1"></i>
                                إضافة صنف
                            </button>
                        </div>
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th style="width: 40%">الصنف</th>
                                    <th style="width: 15%">الكمية</th>
                                    <th style="width: 20%">السعر</th>
                                    <th style="width: 20%">الإجمالي</th>
                                    <th style="width: 5%"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="3" class="text-start">الإجمالي</td>
                                    <td id="orderTotalDisplay">0.00 د.ل</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="text-muted small mt-2"><i class="ti ti-keyboard me-1"></i>اضغط Enter لإضافة صنف جديد</div>
                    </div>

                    <div id="depositSection">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="depositToggle" onchange="toggleDepositFields()">
                            <label class="form-check-label fw-semibold" for="depositToggle">إضافة عربون</label>
                        </div>
                        <div class="row" id="depositFields" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">مبلغ العربون</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="depositAmount" name="deposit_amount" step="0.01" min="0" placeholder="0.00">
                                    <span class="input-group-text">د.ل</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">طريقة الدفع</label>
                                <select class="form-select" id="depositPaymentMethod" name="deposit_payment_method_id">
                                    <option value="">اختر</option>
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger d-none" id="deleteOrderBtn" onclick="confirmDeleteFromModal()">
                        <i class="ti ti-trash me-1"></i>
                        حذف
                    </button>
                    <div class="d-flex gap-2 me-auto">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary" id="saveOrderBtn">
                            <span class="btn-text">حفظ الطلب</span>
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

<div class="modal fade" id="quickCustomerModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة زبون جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="quickCustomerForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">اسم الزبون <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="quickCustomerName" name="name" autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">رقم الهاتف</label>
                        <input type="text" class="form-control" id="quickCustomerPhone" name="phone">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="saveQuickCustomerBtn">
                        <span class="btn-text">حفظ</span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تفاصيل الطلب #<span id="detailsOrderId"></span></h5>
                <span class="badge badge-status ms-2" id="detailsStatusBadge"></span>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="summary-cards">
                    <div class="summary-card total">
                        <div class="summary-label">الإجمالي</div>
                        <div class="summary-value" id="detailsTotal">0.00 د.ل</div>
                    </div>
                    <div class="summary-card paid">
                        <div class="summary-label">المدفوع</div>
                        <div class="summary-value" id="detailsPaid">0.00 د.ل</div>
                    </div>
                    <div class="summary-card remaining">
                        <div class="summary-label">المتبقي</div>
                        <div class="summary-value" id="detailsRemaining">0.00 د.ل</div>
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
                            <div class="info-label text-muted small">الهاتف</div>
                            <div class="info-value fw-semibold" id="detailsPhone">-</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label text-muted small">نوع المناسبة</div>
                            <div class="info-value fw-semibold" id="detailsEventType">-</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label text-muted small">تاريخ التسليم</div>
                            <div class="info-value fw-semibold" id="detailsDeliveryDate">-</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label text-muted small">تاريخ الإنشاء</div>
                            <div class="info-value fw-semibold" id="detailsCreatedAt">-</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label text-muted small">أنشئ بواسطة</div>
                            <div class="info-value fw-semibold" id="detailsCreatedBy">-</div>
                        </div>
                    </div>
                </div>

                <div id="detailsItemsSection" class="mb-3">
                    <h6 class="mb-2">الأصناف</h6>
                    <table class="items-table">
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
                </div>

                <div class="info-card mb-3" id="detailsNotesCard" style="display: none;">
                    <div class="info-label text-muted small">ملاحظات</div>
                    <div class="info-value" id="detailsNotes">-</div>
                </div>

                <h6 class="mb-3">سجل الدفعات</h6>
                <div class="payments-list" id="paymentsList">
                    <p class="text-muted text-center py-3">لا توجد دفعات</p>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-danger" id="detailsDeleteBtn" onclick="confirmDeleteFromDetails()">
                    <i class="ti ti-trash me-1"></i>
                    حذف
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-success" id="addPaymentBtn" onclick="openPaymentModal()">
                        <i class="ti ti-plus me-1"></i>
                        إضافة دفعة
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="printOrder()">
                        <i class="ti ti-printer me-1"></i>
                        طباعة
                    </button>
                    <button type="button" class="btn btn-primary" onclick="openEditFromDetails()">
                        <i class="ti ti-pencil me-1"></i>
                        تعديل
                    </button>
                </div>
            </div>
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
                    <div class="info-card mb-3">
                        <div class="d-flex justify-content-between">
                            <span>المتبقي:</span>
                            <span class="fw-bold text-danger" id="paymentRemaining">0.00 د.ل</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">المبلغ <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="paymentAmount" name="amount" step="0.01" min="0" placeholder="0.00">
                            <span class="input-group-text">د.ل</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">طريقة الدفع <span class="text-danger">*</span></label>
                        <select class="form-select" id="paymentMethod" name="payment_method_id">
                            <option value="">اختر طريقة الدفع</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea class="form-control" id="paymentNotes" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success" id="savePaymentBtn">
                        <span class="btn-text">إضافة الدفعة</span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="ti ti-alert-triangle text-danger" style="font-size: 48px;"></i>
                </div>
                <h5 class="mb-2">تأكيد الحذف</h5>
                <p class="text-muted mb-0" id="deleteMessage">هل أنت متأكد من حذف هذا الطلب؟</p>
                <div class="alert alert-warning mt-3 mb-0" id="deleteRefundWarning" style="display: none;">
                    <i class="ti ti-info-circle me-1"></i>
                    <span id="deleteRefundAmount"></span>
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" onclick="confirmDelete()">حذف واسترجاع</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تغيير الحالة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="statusOrderId">
                <div class="status-options">
                    @foreach($statuses as $key => $label)
                    <button type="button" class="btn w-100 mb-2 status-option" data-status="{{ $key }}" onclick="selectStatus('{{ $key }}')">
                        <span class="badge badge-status badge-{{ $key }} w-100 py-2">{{ $label }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="toast-container" id="toastContainer"></div>
@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/tom-select/tom-select.complete.min.js') }}"></script>
<script>
let currentPage = 1;
let currentOrderId = null;
let currentOrder = null;
let deleteOrderId = null;
let isSubmitting = false;
let customerSelect;
let eventTypeSelect;
let itemRowIndex = 0;

const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const orderModal = new bootstrap.Modal(document.getElementById('orderModal'));
const quickCustomerModal = new bootstrap.Modal(document.getElementById('quickCustomerModal'));
const detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));

const products = @json($products);
const statuses = @json($statuses);

document.addEventListener('DOMContentLoaded', function() {
    customerSelect = new TomSelect('#customerId', {
        placeholder: 'اختر الزبون...',
        allowEmptyOption: true,
        searchField: ['text'],
    });

    eventTypeSelect = new TomSelect('#eventType', {
        placeholder: 'اختر أو أضف نوع جديد...',
        allowEmptyOption: true,
        create: async function(input, callback) {
            try {
                const response = await fetch('{{ route("special-orders.store-event-type") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ name: input })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showToast(result.message, 'success');
                    callback({ value: result.data.name, text: result.data.name });
                } else {
                    showToast(result.message || 'حدث خطأ', 'error');
                    callback();
                }
            } catch (error) {
                showToast('حدث خطأ', 'error');
                callback();
            }
        },
        createOnBlur: false,
        persist: true,
        render: {
            option_create: function(data, escape) {
                return '<div class="create">إضافة <strong>' + escape(data.input) + '</strong>...</div>';
            },
            no_results: function(data, escape) {
                return '<div class="no-results">لا توجد نتائج - اكتب للإضافة</div>';
            }
        }
    });

    loadOrders();

    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            loadOrders();
        }, 300);
    });

    ['statusFilter', 'dateFromFilter', 'dateToFilter', 'sortFilter'].forEach(id => {
        document.getElementById(id).addEventListener('change', function() {
            currentPage = 1;
            loadOrders();
        });
    });

    document.getElementById('orderForm').addEventListener('submit', handleOrderSubmit);
    document.getElementById('paymentForm').addEventListener('submit', handlePaymentSubmit);
    document.getElementById('quickCustomerForm').addEventListener('submit', handleQuickCustomerSubmit);

    document.getElementById('orderModal').addEventListener('hidden.bs.modal', resetOrderForm);

    document.getElementById('paymentModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('paymentForm').reset();
    });

    document.getElementById('itemsTableBody').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addItemRow();
            setTimeout(() => {
                const rows = document.querySelectorAll('#itemsTableBody tr');
                const lastRow = rows[rows.length - 1];
                if (lastRow) {
                    lastRow.querySelector('.item-product').focus();
                }
            }, 50);
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') return;

        if (e.key === '1') {
            e.preventDefault();
            openAddModal();
        }
        if (e.key === '/') {
            e.preventDefault();
            document.getElementById('searchInput').focus();
        }
    });
});

function addItemRow(productId = '', quantity = 1, unitPrice = '', notes = '') {
    const tbody = document.getElementById('itemsTableBody');
    const rowId = itemRowIndex++;

    const productOptions = products.map(p => {
        const selected = p.id == productId ? 'selected' : '';
        return `<option value="${p.id}" data-price="${p.price}" ${selected}>${p.name}</option>`;
    }).join('');

    const row = document.createElement('tr');
    row.id = `item-row-${rowId}`;
    row.innerHTML = `
        <td>
            <select id="item-product-${rowId}" class="item-product">
                <option value="">اختر الصنف...</option>
                ${productOptions}
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm item-quantity" value="${quantity}" min="0.01" step="0.01" oninput="calculateItemTotal(${rowId})">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm item-price" value="${unitPrice}" min="0" step="0.01" oninput="calculateItemTotal(${rowId})">
        </td>
        <td>
            <span class="item-total fw-semibold">0.00 د.ل</span>
        </td>
        <td>
            <button type="button" class="btn btn-outline-danger btn-remove-item" onclick="removeItemRow(${rowId})">
                <i class="ti ti-x"></i>
            </button>
        </td>
    `;

    tbody.appendChild(row);

    new TomSelect(`#item-product-${rowId}`, {
        placeholder: 'اختر الصنف...',
        allowEmptyOption: true,
        onChange: function() {
            updateItemPrice(rowId);
        }
    });

    calculateItemTotal(rowId);
}

function updateItemPrice(rowId) {
    const row = document.getElementById(`item-row-${rowId}`);
    const select = row.querySelector('.item-product');
    const priceInput = row.querySelector('.item-price');
    const productId = select.value;

    const product = products.find(p => p.id == productId);
    if (product) {
        priceInput.value = product.price;
    }

    calculateItemTotal(rowId);
}

function toggleDepositFields() {
    const toggle = document.getElementById('depositToggle');
    const fields = document.getElementById('depositFields');
    fields.style.display = toggle.checked ? 'flex' : 'none';

    if (!toggle.checked) {
        document.getElementById('depositAmount').value = '';
        document.getElementById('depositPaymentMethod').value = '';
    }
}

function calculateItemTotal(rowId) {
    const row = document.getElementById(`item-row-${rowId}`);
    if (!row) return;

    const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
    const price = parseFloat(row.querySelector('.item-price').value) || 0;
    const total = quantity * price;

    row.querySelector('.item-total').textContent = total.toFixed(2) + ' د.ل';
    calculateOrderTotal();
}

function removeItemRow(rowId) {
    const row = document.getElementById(`item-row-${rowId}`);
    if (row) {
        const select = document.getElementById(`item-product-${rowId}`);
        if (select && select.tomselect) {
            select.tomselect.destroy();
        }
        row.remove();
        calculateOrderTotal();
    }
}

function calculateOrderTotal() {
    let total = 0;
    document.querySelectorAll('#itemsTableBody tr').forEach(row => {
        const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        total += quantity * price;
    });
    document.getElementById('orderTotalDisplay').textContent = total.toFixed(2) + ' د.ل';
    return total;
}

function getItemsData() {
    const items = [];
    document.querySelectorAll('#itemsTableBody tr').forEach(row => {
        const productId = row.querySelector('.item-product').value;
        const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.item-price').value) || 0;

        if (productId && quantity > 0) {
            items.push({
                product_id: productId,
                quantity: quantity,
                unit_price: unitPrice,
            });
        }
    });
    return items;
}

async function loadOrders() {
    showLoading(true);

    const params = new URLSearchParams({
        page: currentPage,
        search: document.getElementById('searchInput').value,
        status: document.getElementById('statusFilter').value,
        date_from: document.getElementById('dateFromFilter').value,
        date_to: document.getElementById('dateToFilter').value,
    });

    const sortValue = document.getElementById('sortFilter').value.split('-');
    params.append('sort', sortValue[0]);
    params.append('direction', sortValue[1]);

    try {
        const response = await fetch(`{{ route('special-orders.data') }}?${params}`, {
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
            <td><strong>#${order.id}</strong></td>
            <td>
                <div class="fw-semibold">${escapeHtml(order.customer_name)}</div>
                ${order.phone ? `<small class="text-muted">${escapeHtml(order.phone)}</small>` : ''}
            </td>
            <td>${escapeHtml(order.event_type)}</td>
            <td>${formatDate(order.delivery_date)}</td>
            <td><span class="amount-total">${formatPrice(order.total_amount)}</span></td>
            <td><span class="amount-paid">${formatPrice(order.paid_amount)}</span></td>
            <td><span class="amount-remaining ${order.remaining_amount <= 0 ? 'paid' : ''}">${formatPrice(order.remaining_amount)}</span></td>
            <td>
                <button type="button" class="btn btn-sm badge-status badge-${order.status} status-btn" onclick="event.stopPropagation(); openStatusModal(${order.id}, '${order.status}')">
                    ${statuses[order.status]}
                </button>
            </td>
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

function openAddModal() {
    document.getElementById('orderModalTitle').textContent = 'إضافة طلب خاص';
    document.getElementById('orderId').value = '';
    document.getElementById('depositSection').style.display = 'block';
    document.getElementById('deleteOrderBtn').classList.add('d-none');
    document.getElementById('saveOrderBtn').querySelector('.btn-text').textContent = 'حفظ الطلب';

    const today = new Date().toISOString().split('T')[0];
    document.getElementById('deliveryDate').min = today;

    document.getElementById('itemsTableBody').innerHTML = '';
    addItemRow();

    orderModal.show();
    setTimeout(() => customerSelect.focus(), 300);
}

async function openEditModal(id) {
    try {
        const response = await fetch(`{{ url('special-orders') }}/${id}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });

        const result = await response.json();

        if (result.success) {
            const order = result.data;
            currentOrder = order;

            document.getElementById('orderModalTitle').textContent = 'تعديل الطلب';
            document.getElementById('orderId').value = order.id;

            if (order.customer_id) {
                customerSelect.setValue(order.customer_id);
            } else {
                customerSelect.clear();
            }

            if (order.event_type) {
                eventTypeSelect.setValue(order.event_type);
            } else {
                eventTypeSelect.clear();
            }
            document.getElementById('deliveryDate').value = order.delivery_date ? order.delivery_date.split('T')[0] : '';
            document.getElementById('notes').value = order.notes || '';

            document.getElementById('itemsTableBody').innerHTML = '';
            if (order.items && order.items.length > 0) {
                order.items.forEach(item => {
                    addItemRow(item.product_id, item.quantity, item.unit_price, item.notes);
                });
            } else {
                addItemRow();
            }

            document.getElementById('depositSection').style.display = 'none';
            document.getElementById('saveOrderBtn').querySelector('.btn-text').textContent = 'حفظ التعديلات';

            if (order.paid_amount > 0) {
                document.getElementById('deleteOrderBtn').classList.add('d-none');
            } else {
                document.getElementById('deleteOrderBtn').classList.remove('d-none');
            }

            orderModal.show();
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('حدث خطأ في تحميل بيانات الطلب', 'error');
    }
}

async function openDetailsModal(id) {
    try {
        const response = await fetch(`{{ url('special-orders') }}/${id}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });

        const result = await response.json();

        if (result.success) {
            const order = result.data;
            currentOrderId = order.id;
            currentOrder = order;

            document.getElementById('detailsOrderId').textContent = order.id;

            const statusBadge = document.getElementById('detailsStatusBadge');
            statusBadge.className = `badge badge-status badge-${order.status}`;
            statusBadge.textContent = statuses[order.status];

            document.getElementById('detailsTotal').textContent = formatPrice(order.total_amount);
            document.getElementById('detailsPaid').textContent = formatPrice(order.paid_amount);
            document.getElementById('detailsRemaining').textContent = formatPrice(order.remaining_amount);
            document.getElementById('detailsCustomer').textContent = order.customer ? order.customer.name : order.customer_name;
            document.getElementById('detailsPhone').textContent = order.customer ? (order.customer.phone || '-') : (order.phone || '-');
            document.getElementById('detailsEventType').textContent = order.event_type;
            document.getElementById('detailsDeliveryDate').textContent = formatDate(order.delivery_date);
            document.getElementById('detailsCreatedAt').textContent = formatDateTime(order.created_at);
            document.getElementById('detailsCreatedBy').textContent = order.user ? order.user.name : '-';

            const remaining = parseFloat(order.remaining_amount) || 0;
            const paid = parseFloat(order.paid_amount) || 0;

            const addPaymentBtn = document.getElementById('addPaymentBtn');
            addPaymentBtn.style.display = remaining <= 0 ? 'none' : 'inline-flex';

            if (order.items && order.items.length > 0) {
                document.getElementById('detailsItemsBody').innerHTML = order.items.map(item => `
                    <tr>
                        <td>${item.product ? item.product.name : '-'}</td>
                        <td>${item.quantity}</td>
                        <td>${formatPrice(item.unit_price)}</td>
                        <td>${formatPrice(item.total_price)}</td>
                    </tr>
                `).join('');
            } else {
                document.getElementById('detailsItemsBody').innerHTML = '<tr><td colspan="4" class="text-center text-muted">لا توجد أصناف</td></tr>';
            }

            if (order.notes) {
                document.getElementById('detailsNotesCard').style.display = 'block';
                document.getElementById('detailsNotes').textContent = order.notes;
            } else {
                document.getElementById('detailsNotesCard').style.display = 'none';
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
        container.innerHTML = '<p class="text-muted text-center py-3">لا توجد دفعات</p>';
        return;
    }

    container.innerHTML = payments.map(payment => `
        <div class="payment-item">
            <div>
                <div class="payment-amount">${formatPrice(payment.amount)}</div>
                <div class="payment-date">
                    ${formatDateTime(payment.created_at)}
                    ${payment.payment_method?.name ? ' - ' + payment.payment_method.name : ''}
                    ${payment.user?.name ? ' (' + payment.user.name + ')' : ''}
                </div>
                ${payment.notes ? '<div class="text-muted small">' + escapeHtml(payment.notes) + '</div>' : ''}
            </div>
            <a href="{{ url('special-orders/payments') }}/${payment.id}/print" target="_blank" class="btn btn-sm btn-outline-secondary">
                <i class="ti ti-printer"></i>
            </a>
        </div>
    `).join('');
}

function openEditFromDetails() {
    detailsModal.hide();
    setTimeout(() => openEditModal(currentOrderId), 300);
}

function confirmDeleteFromDetails() {
    if (!currentOrderId || !currentOrder) return;
    deleteOrderId = currentOrderId;

    const paid = parseFloat(currentOrder.paid_amount) || 0;
    const refundWarning = document.getElementById('deleteRefundWarning');
    const refundAmount = document.getElementById('deleteRefundAmount');
    const confirmBtn = document.getElementById('confirmDeleteBtn');

    refundWarning.style.display = 'none';
    confirmBtn.textContent = 'حذف';

    detailsModal.hide();
    setTimeout(() => deleteModal.show(), 300);
}

function openPaymentModal() {
    const remaining = parseFloat(currentOrder?.remaining_amount) || 0;

    if (!currentOrder || remaining <= 0) {
        showToast('لا يوجد مبلغ متبقي للدفع', 'error');
        return;
    }

    document.getElementById('paymentForm').reset();
    document.getElementById('paymentRemaining').textContent = formatPrice(remaining);
    document.getElementById('paymentAmount').value = '';
    document.getElementById('paymentAmount').max = remaining;

    detailsModal.hide();
    setTimeout(() => {
        paymentModal.show();
        document.getElementById('paymentAmount').focus();
    }, 300);
}

function openQuickCustomerModal() {
    document.getElementById('quickCustomerForm').reset();
    orderModal.hide();
    setTimeout(() => {
        quickCustomerModal.show();
        document.getElementById('quickCustomerName').focus();
    }, 300);
}

async function handleQuickCustomerSubmit(e) {
    e.preventDefault();

    const data = {
        name: document.getElementById('quickCustomerName').value,
        phone: document.getElementById('quickCustomerPhone').value || null,
    };

    const btn = document.getElementById('saveQuickCustomerBtn');
    btn.querySelector('.btn-text').classList.add('d-none');
    btn.querySelector('.btn-loading').classList.remove('d-none');
    btn.disabled = true;

    try {
        const response = await fetch('{{ route("customers.store") }}', {
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
            customerSelect.addOption({
                value: result.data.id,
                text: result.data.name,
            });
            customerSelect.setValue(result.data.id);

            quickCustomerModal.hide();
            setTimeout(() => orderModal.show(), 300);
            showToast('تم إضافة الزبون بنجاح', 'success');
        } else {
            showToast(result.message || 'حدث خطأ', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('حدث خطأ', 'error');
    } finally {
        btn.querySelector('.btn-text').classList.remove('d-none');
        btn.querySelector('.btn-loading').classList.add('d-none');
        btn.disabled = false;
    }
}

async function handleOrderSubmit(e) {
    e.preventDefault();
    if (isSubmitting) return;

    clearErrors();

    const orderId = document.getElementById('orderId').value;
    const isEdit = !!orderId;
    const items = getItemsData();

    if (items.length === 0) {
        showToast('يجب إضافة صنف واحد على الأقل', 'error');
        return;
    }

    const customerId = customerSelect.getValue();
    if (!customerId) {
        showToast('يجب اختيار الزبون', 'error');
        return;
    }

    const data = {
        customer_id: customerId,
        event_type: eventTypeSelect.getValue(),
        delivery_date: document.getElementById('deliveryDate').value,
        notes: document.getElementById('notes').value || null,
        items: items,
    };

    if (!isEdit) {
        const depositAmount = document.getElementById('depositAmount').value;
        if (depositAmount && parseFloat(depositAmount) > 0) {
            data.deposit_amount = depositAmount;
            data.deposit_payment_method_id = document.getElementById('depositPaymentMethod').value;
        }
    }

    setSubmitting(true, 'saveOrderBtn');

    try {
        const url = isEdit ? `{{ url('special-orders') }}/${orderId}` : '{{ route('special-orders.store') }}';
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
            orderModal.hide();
            loadOrders();
            showToast(result.message, 'success');
        } else if (response.status === 422) {
            displayErrors(result.errors);
        } else {
            showToast(result.message || 'حدث خطأ', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('حدث خطأ في حفظ الطلب', 'error');
    } finally {
        setSubmitting(false, 'saveOrderBtn');
    }
}

async function handlePaymentSubmit(e) {
    e.preventDefault();
    if (isSubmitting || !currentOrderId) return;

    const data = {
        amount: document.getElementById('paymentAmount').value,
        payment_method_id: document.getElementById('paymentMethod').value,
        notes: document.getElementById('paymentNotes').value || null,
    };

    setSubmitting(true, 'savePaymentBtn');

    try {
        const response = await fetch(`{{ url('special-orders') }}/${currentOrderId}/payments`, {
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
            currentOrder = result.data.order;
            loadOrders();
            showToast(result.message, 'success');
            setTimeout(() => openDetailsModal(currentOrderId), 300);
        } else {
            showToast(result.message || 'حدث خطأ', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('حدث خطأ', 'error');
    } finally {
        setSubmitting(false, 'savePaymentBtn');
    }
}

function confirmDeleteFromModal() {
    const orderId = document.getElementById('orderId').value;
    if (!orderId) return;
    deleteOrderId = orderId;
    orderModal.hide();
    deleteModal.show();
}

async function confirmDelete() {
    if (!deleteOrderId) return;

    try {
        const response = await fetch(`{{ url('special-orders') }}/${deleteOrderId}`, {
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
            loadOrders();
            showToast(result.message, 'success');
        } else {
            showToast(result.message || 'حدث خطأ', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('حدث خطأ', 'error');
    } finally {
        deleteOrderId = null;
    }
}

function printOrder() {
    if (!currentOrderId) return;
    window.open(`{{ url('special-orders') }}/${currentOrderId}/print`, '_blank');
}

function openStatusModal(orderId, currentStatus) {
    document.getElementById('statusOrderId').value = orderId;
    document.querySelectorAll('.status-option').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.status === currentStatus) btn.classList.add('active');
    });
    statusModal.show();
}

async function selectStatus(status) {
    const orderId = document.getElementById('statusOrderId').value;
    if (!orderId) return;

    try {
        const response = await fetch(`{{ url('special-orders') }}/${orderId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status: status })
        });

        const result = await response.json();

        if (result.success) {
            statusModal.hide();
            loadOrders();
            showToast(result.message, 'success');
        } else {
            showToast(result.message || 'حدث خطأ', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('حدث خطأ', 'error');
    }
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateFromFilter').value = '';
    document.getElementById('dateToFilter').value = '';
    document.getElementById('sortFilter').value = 'created_at-desc';
    currentPage = 1;
    loadOrders();
}

function resetOrderForm() {
    document.getElementById('orderForm').reset();
    document.getElementById('orderId').value = '';
    customerSelect.clear();
    eventTypeSelect.clear();
    document.getElementById('itemsTableBody').innerHTML = '';
    document.getElementById('depositToggle').checked = false;
    document.getElementById('depositFields').style.display = 'none';
    currentOrder = null;
    clearErrors();
}

function displayErrors(errors) {
    for (const [field, messages] of Object.entries(errors)) {
        const errorDiv = document.getElementById(`${field}Error`);
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

function setSubmitting(state, btnId) {
    isSubmitting = state;
    const btn = document.getElementById(btnId);
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

function formatPrice(price) {
    return parseFloat(price).toFixed(2) + ' د.ل';
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('ar-LY');
}

function formatDateTime(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('ar-LY') + ' ' + date.toLocaleTimeString('ar-LY', { hour: '2-digit', minute: '2-digit' });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
@endpush
