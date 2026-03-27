@extends('layouts.app')

@section('title', 'تقارير المبيعات')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">تقارير المبيعات</li>
@endsection

@push('styles')
<style>
    .report-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 4px 12px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.06);
        margin-bottom: 24px;
    }

    [data-bs-theme="dark"] .report-card {
        background: #1f2937;
        border-color: #374151;
    }

    .report-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    [data-bs-theme="dark"] .report-card-header {
        border-color: #374151;
    }

    .report-card-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    [data-bs-theme="dark"] .report-card-title {
        color: #f1f5f9;
    }

    .report-card-title i {
        font-size: 20px;
        color: #64748b;
    }

    .filters-section {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 4px 12px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.06);
        margin-bottom: 24px;
    }

    [data-bs-theme="dark"] .filters-section {
        background: #1f2937;
        border-color: #374151;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-label {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
    }

    .filter-input {
        border-radius: 10px;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        font-size: 14px;
        background-color: #fff;
        transition: all 0.2s;
    }

    .filter-input:hover {
        border-color: #cbd5e1;
    }

    .filter-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    [data-bs-theme="dark"] .filter-input {
        background-color: #111827;
        border-color: #374151;
        color: #f9fafb;
    }

    .preset-btns {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .preset-btns .btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #475569;
        transition: all 0.2s;
    }

    .preset-btns .btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .preset-btns .btn.active {
        background: #3b82f6;
        border-color: #3b82f6;
        color: #fff;
    }

    [data-bs-theme="dark"] .preset-btns .btn {
        background: #111827;
        border-color: #374151;
        color: #94a3b8;
    }

    [data-bs-theme="dark"] .preset-btns .btn:hover {
        background: #1f2937;
    }

    .action-btns {
        display: flex;
        gap: 10px;
    }

    .action-btns .btn {
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 500;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 992px) {
        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .summary-grid {
            grid-template-columns: 1fr;
        }
    }

    .summary-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 4px 12px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.06);
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    [data-bs-theme="dark"] .summary-card {
        background: #1f2937;
        border-color: #374151;
    }

    .summary-card .icon-box {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .summary-card .icon-box i {
        font-size: 26px;
    }

    .summary-card .icon-box.green {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
    }

    .summary-card .icon-box.blue {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: #fff;
    }

    .summary-card .icon-box.purple {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: #fff;
    }

    .summary-card .icon-box.orange {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: #fff;
    }

    .summary-card .content {
        flex: 1;
        min-width: 0;
    }

    .summary-card .value {
        font-size: 26px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
        margin-bottom: 4px;
    }

    [data-bs-theme="dark"] .summary-card .value {
        color: #f1f5f9;
    }

    .summary-card .label {
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    @media (max-width: 992px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    .data-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    @media (max-width: 992px) {
        .data-grid {
            grid-template-columns: 1fr;
        }
    }

    .data-table {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 4px 12px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.06);
        overflow: hidden;
    }

    [data-bs-theme="dark"] .data-table {
        background: #1f2937;
        border-color: #374151;
    }

    .data-table .table {
        margin-bottom: 0;
    }

    .data-table th {
        background: #f8fafc;
        font-weight: 600;
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
        border-top: none;
        white-space: nowrap;
        color: #475569;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    [data-bs-theme="dark"] .data-table th {
        background: #111827;
        border-color: #374151;
        color: #94a3b8;
    }

    .data-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        border-top: none;
        color: #334155;
        font-size: 14px;
    }

    [data-bs-theme="dark"] .data-table td {
        border-color: #1f2937;
        color: #e2e8f0;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .data-table tbody tr:hover {
        background: #f8fafc;
    }

    [data-bs-theme="dark"] .data-table tbody tr:hover {
        background: #111827;
    }

    .products-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .products-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    [data-bs-theme="dark"] .products-list li {
        border-color: #374151;
    }

    .products-list li:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .products-list li:first-child {
        padding-top: 0;
    }

    .products-list .product-rank {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        margin-left: 12px;
    }

    [data-bs-theme="dark"] .products-list .product-rank {
        background: #374151;
        color: #94a3b8;
    }

    .products-list .product-name {
        font-weight: 500;
        color: #334155;
        display: flex;
        align-items: center;
    }

    [data-bs-theme="dark"] .products-list .product-name {
        color: #e2e8f0;
    }

    .products-list .product-info {
        text-align: left;
    }

    .products-list .product-qty {
        font-size: 12px;
        color: #94a3b8;
    }

    .products-list .product-total {
        font-weight: 600;
        color: #10b981;
        font-size: 14px;
    }

    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-top: 1px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 16px;
    }

    [data-bs-theme="dark"] .pagination-wrapper {
        border-color: #374151;
    }

    .pagination-info {
        color: #64748b;
        font-size: 13px;
    }

    .pagination {
        margin: 0;
        gap: 4px;
    }

    .page-link {
        border-radius: 8px;
        padding: 8px 14px;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-weight: 500;
        font-size: 13px;
        background: #fff;
    }

    .page-link:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .page-item.active .page-link {
        background: #3b82f6;
        border-color: #3b82f6;
        color: #fff;
    }

    .page-item.disabled .page-link {
        background: #f8fafc;
        color: #cbd5e1;
    }

    [data-bs-theme="dark"] .page-link {
        background: #111827;
        border-color: #374151;
        color: #94a3b8;
    }

    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        border-radius: 16px;
    }

    [data-bs-theme="dark"] .loading-overlay {
        background: rgba(17, 24, 39, 0.9);
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #e2e8f0;
        border-top-color: #3b82f6;
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
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .empty-state-icon i {
        font-size: 36px;
        color: #94a3b8;
    }

    .empty-state h5 {
        color: #475569;
        margin-bottom: 8px;
    }

    .section-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 20px 0;
    }

    [data-bs-theme="dark"] .section-divider {
        background: #374151;
    }

    .report-tabs {
        display: flex;
        gap: 4px;
        margin-bottom: 24px;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 12px;
    }

    [data-bs-theme="dark"] .report-tabs {
        background: #1f2937;
    }

    .report-tab {
        flex: 1;
        padding: 12px 20px;
        border: none;
        background: transparent;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .report-tab:hover {
        color: #334155;
        background: rgba(255,255,255,0.5);
    }

    .report-tab.active {
        background: #fff;
        color: #1e293b;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    [data-bs-theme="dark"] .report-tab.active {
        background: #374151;
        color: #f1f5f9;
    }

    .report-tab i {
        font-size: 18px;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .product-search-wrapper {
        position: relative;
        margin-bottom: 20px;
    }

    .product-search-input {
        width: 100%;
        padding: 12px 16px 12px 44px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        font-family: inherit;
        background: #fff;
        transition: all 0.2s;
    }

    .product-search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }

    [data-bs-theme="dark"] .product-search-input {
        background: #111827;
        border-color: #374151;
        color: #f9fafb;
    }

    .product-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 18px;
    }

    .product-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 2px solid #3b82f6;
        border-radius: 12px;
        margin-top: 4px;
        max-height: 240px;
        overflow-y: auto;
        z-index: 100;
        display: none;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }

    [data-bs-theme="dark"] .product-search-results {
        background: #1f2937;
    }

    .product-search-results.show {
        display: block;
    }

    .product-search-item {
        padding: 10px 16px;
        cursor: pointer;
        font-size: 14px;
        transition: background 0.1s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .product-search-item:hover {
        background: #eff6ff;
    }

    [data-bs-theme="dark"] .product-search-item:hover {
        background: #374151;
    }

    .product-search-item .barcode {
        font-size: 11px;
        color: #94a3b8;
    }

    .all-products-table th.sortable {
        cursor: pointer;
        user-select: none;
    }

    .all-products-table th.sortable:hover {
        color: #3b82f6;
    }

    .percentage-bar {
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 4px;
    }

    .percentage-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6, #10b981);
        border-radius: 3px;
        transition: width 0.3s;
    }

    .single-product-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.06);
    }

    [data-bs-theme="dark"] .single-product-card {
        background: #1f2937;
        border-color: #374151;
    }

    .selected-product-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        background: linear-gradient(135deg, #eff6ff, #f0fdf4);
        border-radius: 12px;
        margin-bottom: 20px;
    }

    [data-bs-theme="dark"] .selected-product-header {
        background: linear-gradient(135deg, #1e3a5f, #1a3a2a);
    }

    .selected-product-name {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
    }

    [data-bs-theme="dark"] .selected-product-name {
        color: #f1f5f9;
    }

    .selected-product-meta {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }
</style>
@endpush

@section('content')
<div class="filters-section">
    <div class="row g-3 align-items-end">
        <div class="col-lg-2 col-md-4 col-6">
            <div class="filter-group">
                <label class="filter-label">من تاريخ</label>
                <input type="date" class="form-control filter-input" id="dateFrom">
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="filter-group">
                <label class="filter-label">إلى تاريخ</label>
                <input type="date" class="form-control filter-input" id="dateTo">
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="filter-group">
                <label class="filter-label">نقطة البيع</label>
                <select class="form-select filter-input" id="posPointFilter">
                    <option value="">الكل</option>
                    @foreach($posPoints as $point)
                        <option value="{{ $point->id }}">{{ $point->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="filter-group">
                <label class="filter-label">طريقة الدفع</label>
                <select class="form-select filter-input" id="paymentMethodFilter">
                    <option value="">الكل</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-md-8">
            <div class="filter-group">
                <label class="filter-label">فترة سريعة</label>
                <div class="preset-btns">
                    <button type="button" class="btn" data-preset="today">اليوم</button>
                    <button type="button" class="btn" data-preset="yesterday">أمس</button>
                    <button type="button" class="btn" data-preset="week">الأسبوع</button>
                    <button type="button" class="btn" data-preset="month">الشهر</button>
                    <button type="button" class="btn" data-preset="all">الكل</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="report-tabs">
    <button type="button" class="report-tab active" onclick="switchTab('invoices')">
        <i class="ti ti-receipt"></i>
        الفواتير
    </button>
    <button type="button" class="report-tab" onclick="switchTab('products')">
        <i class="ti ti-packages"></i>
        الأصناف
    </button>
    <button type="button" class="report-tab" onclick="switchTab('single-product')">
        <i class="ti ti-search"></i>
        صنف محدد
    </button>
    <button type="button" class="report-tab" onclick="switchTab('special')">
        <i class="ti ti-cake"></i>
        طلبيات خاصة
    </button>
</div>

<!-- TAB 1: الفواتير -->
<div class="tab-content active" id="tab-invoices">
    <div class="summary-grid">
        <div class="summary-card">
            <div class="icon-box green"><i class="ti ti-currency-dollar"></i></div>
            <div class="content">
                <div class="value" id="totalSales">0.000 د.ل</div>
                <div class="label">إجمالي المبيعات</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="icon-box blue"><i class="ti ti-receipt"></i></div>
            <div class="content">
                <div class="value" id="ordersCount">0</div>
                <div class="label">عدد الطلبات</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="icon-box purple"><i class="ti ti-chart-bar"></i></div>
            <div class="content">
                <div class="value" id="averageOrder">0.000 د.ل</div>
                <div class="label">متوسط الطلب</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="icon-box orange"><i class="ti ti-discount"></i></div>
            <div class="content">
                <div class="value" id="totalDiscount">0.000 د.ل</div>
                <div class="label">إجمالي الخصومات</div>
            </div>
        </div>
    </div>

    <div class="charts-grid">
        <div class="report-card">
            <div class="report-card-header">
                <div class="report-card-title"><i class="ti ti-chart-line"></i> المبيعات اليومية</div>
            </div>
            <div id="salesChart" style="height: 300px;"></div>
        </div>
        <div class="report-card">
            <div class="report-card-header">
                <div class="report-card-title"><i class="ti ti-chart-pie"></i> طرق الدفع</div>
            </div>
            <div id="paymentChart" style="height: 300px;"></div>
        </div>
    </div>

    <div class="data-table position-relative">
        <div id="tableLoading" class="loading-overlay d-none"><div class="spinner"></div></div>
        <div class="report-card-header" style="padding: 20px; margin: 0; border-radius: 16px 16px 0 0;">
            <div class="report-card-title"><i class="ti ti-list-details"></i> الفواتير</div>
            <div class="action-btns">
                <button type="button" class="btn btn-sm btn-success" onclick="exportExcel()"><i class="ti ti-file-spreadsheet me-1"></i> تصدير</button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="printReport()"><i class="ti ti-printer me-1"></i> طباعة</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>التاريخ</th>
                        <th>نقطة البيع</th>
                        <th>الإجمالي</th>
                        <th>الخصم</th>
                        <th>الصافي</th>
                        <th>طرق الدفع</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody"></tbody>
            </table>
        </div>
        <div id="emptyState" class="empty-state d-none">
            <div class="empty-state-icon"><i class="ti ti-receipt-off"></i></div>
            <h5>لا توجد طلبات</h5>
            <p class="text-muted">لم يتم العثور على طلبات في الفترة المحددة</p>
        </div>
        <div id="paginationWrapper" class="pagination-wrapper d-none">
            <div class="pagination-info">عرض <span id="paginationFrom">0</span> - <span id="paginationTo">0</span> من <span id="paginationTotal">0</span> طلب</div>
            <nav><ul class="pagination" id="paginationNav"></ul></nav>
        </div>
    </div>
</div>

<!-- TAB 2: الأصناف -->
<div class="tab-content" id="tab-products">
    <div class="summary-grid">
        <div class="summary-card">
            <div class="icon-box green"><i class="ti ti-currency-dollar"></i></div>
            <div class="content">
                <div class="value" id="productsGrandTotal">0.000 د.ل</div>
                <div class="label">إجمالي مبيعات الأصناف</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="icon-box blue"><i class="ti ti-packages"></i></div>
            <div class="content">
                <div class="value" id="productsCount">0</div>
                <div class="label">عدد الأصناف المباعة</div>
            </div>
        </div>
    </div>

    <div class="data-table position-relative">
        <div id="productsTableLoading" class="loading-overlay d-none"><div class="spinner"></div></div>
        <div class="report-card-header" style="padding: 20px; margin: 0; border-radius: 16px 16px 0 0;">
            <div class="report-card-title"><i class="ti ti-packages"></i> مبيعات جميع الأصناف</div>
            <div class="action-btns">
                <button type="button" class="btn btn-sm btn-success" onclick="exportProductsExcel()"><i class="ti ti-file-spreadsheet me-1"></i> تصدير</button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="printProductsReport()"><i class="ti ti-printer me-1"></i> طباعة</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table all-products-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الصنف</th>
                        <th class="sortable" onclick="sortProducts('orders_count')">عدد الفواتير <i class="ti ti-arrows-sort" style="font-size:12px;"></i></th>
                        <th class="sortable" onclick="sortProducts('total_quantity')">الكمية <i class="ti ti-arrows-sort" style="font-size:12px;"></i></th>
                        <th class="sortable" onclick="sortProducts('total_sales')">الإجمالي <i class="ti ti-arrows-sort" style="font-size:12px;"></i></th>
                        <th>النسبة</th>
                    </tr>
                </thead>
                <tbody id="allProductsBody"></tbody>
            </table>
        </div>
        <div id="productsEmptyState" class="empty-state d-none">
            <div class="empty-state-icon"><i class="ti ti-packages-off"></i></div>
            <h5>لا توجد بيانات</h5>
        </div>
    </div>
</div>

<!-- TAB 3: صنف محدد -->
<div class="tab-content" id="tab-single-product">
    <div class="single-product-card">
        <div class="product-search-wrapper">
            <i class="ti ti-search product-search-icon"></i>
            <input type="text" class="product-search-input" id="singleProductSearch" placeholder="ابحث عن صنف بالاسم أو الباركود..." autocomplete="off">
            <div class="product-search-results" id="singleProductResults"></div>
        </div>

        <div id="singleProductContent" style="display:none;">
            <div class="selected-product-header">
                <div>
                    <div class="selected-product-name" id="spName"></div>
                    <div class="selected-product-meta" id="spMeta"></div>
                </div>
                <div class="action-btns">
                    <button type="button" class="btn btn-sm btn-success" onclick="exportSingleProduct()"><i class="ti ti-file-spreadsheet me-1"></i> تصدير</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="printSingleProduct()"><i class="ti ti-printer me-1"></i> طباعة</button>
                </div>
            </div>

            <div class="summary-grid">
                <div class="summary-card">
                    <div class="icon-box blue"><i class="ti ti-receipt"></i></div>
                    <div class="content">
                        <div class="value" id="spOrdersCount">0</div>
                        <div class="label">عدد الفواتير</div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="icon-box purple"><i class="ti ti-scale"></i></div>
                    <div class="content">
                        <div class="value" id="spTotalQty">0</div>
                        <div class="label">الكمية المباعة</div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="icon-box green"><i class="ti ti-currency-dollar"></i></div>
                    <div class="content">
                        <div class="value" id="spTotalSales">0.000 د.ل</div>
                        <div class="label">إجمالي المبيعات</div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="icon-box orange"><i class="ti ti-chart-arrows"></i></div>
                    <div class="content">
                        <div class="value" id="spAvgPrice">0.000 د.ل</div>
                        <div class="label">متوسط السعر</div>
                    </div>
                </div>
            </div>

            <div class="charts-grid">
                <div class="report-card">
                    <div class="report-card-header">
                        <div class="report-card-title"><i class="ti ti-chart-line"></i> المبيعات اليومية</div>
                    </div>
                    <div id="spDailyChart" style="height: 280px;"></div>
                </div>
                <div class="report-card">
                    <div class="report-card-header">
                        <div class="report-card-title"><i class="ti ti-chart-bar"></i> الكميات اليومية</div>
                    </div>
                    <div id="spQtyChart" style="height: 280px;"></div>
                </div>
            </div>

            <div class="data-table">
                <div class="report-card-header" style="padding: 20px; margin: 0; border-radius: 16px 16px 0 0;">
                    <div class="report-card-title"><i class="ti ti-list-details"></i> آخر الفواتير</div>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>رقم الفاتورة</th>
                                <th>التاريخ</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody id="spTransactions"></tbody>
                    </table>
                </div>
                <div id="spTransactionsEmpty" class="empty-state d-none">
                    <div class="empty-state-icon"><i class="ti ti-receipt-off"></i></div>
                    <h5>لا توجد فواتير</h5>
                </div>
            </div>
        </div>

        <div id="singleProductEmpty" class="empty-state">
            <div class="empty-state-icon"><i class="ti ti-search"></i></div>
            <h5>ابحث عن صنف</h5>
            <p class="text-muted">اكتب اسم الصنف أو الباركود لعرض تقرير مبيعاته</p>
        </div>
    </div>
</div>

<!-- TAB 4: طلبيات خاصة -->
<div class="tab-content" id="tab-special">
    <div class="summary-grid">
        <div class="summary-card">
            <div class="icon-box green"><i class="ti ti-currency-dollar"></i></div>
            <div class="content">
                <div class="value" id="specialTotalSales">0.000 د.ل</div>
                <div class="label">إجمالي المبيعات</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="icon-box blue"><i class="ti ti-receipt"></i></div>
            <div class="content">
                <div class="value" id="specialOrdersCount">0</div>
                <div class="label">عدد الطلبات</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="icon-box purple"><i class="ti ti-chart-bar"></i></div>
            <div class="content">
                <div class="value" id="specialAverageOrder">0.000 د.ل</div>
                <div class="label">متوسط الطلب</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="icon-box orange"><i class="ti ti-cash"></i></div>
            <div class="content">
                <div class="value" id="specialTotalPaid">0.000 د.ل</div>
                <div class="label">إجمالي المدفوع</div>
            </div>
        </div>
    </div>

    <div class="data-table position-relative">
        <div id="specialTableLoading" class="loading-overlay d-none"><div class="spinner"></div></div>
        <div class="report-card-header" style="padding: 20px; margin: 0; border-radius: 16px 16px 0 0;">
            <div class="report-card-title"><i class="ti ti-cake"></i> الطلبيات الخاصة المسلمة</div>
            <div class="action-btns">
                <button type="button" class="btn btn-sm btn-success" onclick="exportSpecialOrdersExcel()"><i class="ti ti-file-spreadsheet me-1"></i> تصدير</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>العميل</th>
                        <th>المناسبة</th>
                        <th>تاريخ التسليم</th>
                        <th>الإجمالي</th>
                        <th>المدفوع</th>
                        <th>طرق الدفع</th>
                    </tr>
                </thead>
                <tbody id="specialOrdersTableBody"></tbody>
            </table>
        </div>
        <div id="specialEmptyState" class="empty-state d-none">
            <div class="empty-state-icon"><i class="ti ti-cake-off"></i></div>
            <h5>لا توجد طلبيات خاصة</h5>
            <p class="text-muted">لم يتم العثور على طلبيات خاصة مسلمة في الفترة المحددة</p>
        </div>
        <div id="specialPaginationWrapper" class="pagination-wrapper d-none">
            <div class="pagination-info">عرض <span id="specialPaginationFrom">0</span> - <span id="specialPaginationTo">0</span> من <span id="specialPaginationTotal">0</span> طلب</div>
            <nav><ul class="pagination" id="specialPaginationNav"></ul></nav>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script>
let currentPage = 1;
let specialCurrentPage = 1;
let salesChart = null;
let paymentChart = null;
let activeTab = 'invoices';
let productsSortField = 'total_sales';
let productsSortDir = 'desc';
let selectedProductId = null;
let spDailyChart = null;
let spQtyChart = null;
let searchTimer = null;

document.addEventListener('DOMContentLoaded', function() {
    setPreset('today');

    document.querySelectorAll('.preset-btns .btn').forEach(btn => {
        btn.addEventListener('click', function() { setPreset(this.dataset.preset); });
    });

    ['dateFrom', 'dateTo', 'posPointFilter', 'paymentMethodFilter'].forEach(id => {
        document.getElementById(id).addEventListener('change', function() {
            document.querySelectorAll('.preset-btns .btn').forEach(b => b.classList.remove('active'));
            currentPage = 1;
            loadData();
        });
    });

    const searchInput = document.getElementById('singleProductSearch');
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => searchProductsForReport(this.value), 300);
    });
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') document.getElementById('singleProductResults').classList.remove('show');
    });
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.product-search-wrapper')) {
            document.getElementById('singleProductResults').classList.remove('show');
        }
    });
});

function switchTab(tab) {
    activeTab = tab;
    document.querySelectorAll('.report-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    event.currentTarget.classList.add('active');
    document.getElementById('tab-' + tab).classList.add('active');
    loadTabData(tab);
}

function loadTabData(tab) {
    if (tab === 'invoices') { loadSummary(); loadCharts(); loadOrders(); }
    if (tab === 'products') { loadAllProducts(); }
    if (tab === 'single-product' && selectedProductId) { loadSingleProduct(selectedProductId); }
    if (tab === 'special') { loadSpecialOrdersSummary(); loadSpecialOrders(); }
}

function setPreset(preset) {
    const today = new Date();
    let dateFrom = '';
    let dateTo = '';

    document.querySelectorAll('.preset-btns .btn').forEach(b => b.classList.remove('active'));
    document.querySelector(`[data-preset="${preset}"]`)?.classList.add('active');

    switch(preset) {
        case 'today': dateFrom = dateTo = formatDate(today); break;
        case 'yesterday':
            const y = new Date(today); y.setDate(y.getDate() - 1);
            dateFrom = dateTo = formatDate(y); break;
        case 'week':
            const w = new Date(today); w.setDate(today.getDate() - today.getDay());
            dateFrom = formatDate(w); dateTo = formatDate(today); break;
        case 'month':
            dateFrom = formatDate(new Date(today.getFullYear(), today.getMonth(), 1));
            dateTo = formatDate(today); break;
        case 'all': dateFrom = ''; dateTo = ''; break;
    }

    document.getElementById('dateFrom').value = dateFrom;
    document.getElementById('dateTo').value = dateTo;
    currentPage = 1;
    loadData();
}

function formatDate(date) { return date.toISOString().split('T')[0]; }

function getFilters() {
    return {
        date_from: document.getElementById('dateFrom').value,
        date_to: document.getElementById('dateTo').value,
        pos_point_id: document.getElementById('posPointFilter').value,
        payment_method_id: document.getElementById('paymentMethodFilter').value,
    };
}

async function loadData() {
    loadTabData(activeTab);
}

async function loadSummary() {
    const params = new URLSearchParams(getFilters());
    try {
        const res = await fetch(`{{ route('reports.sales.summary') }}?${params}`);
        const r = await res.json();
        if (r.success) {
            document.getElementById('totalSales').textContent = r.data.total_sales + ' د.ل';
            document.getElementById('ordersCount').textContent = r.data.orders_count;
            document.getElementById('averageOrder').textContent = r.data.average_order + ' د.ل';
            document.getElementById('totalDiscount').textContent = r.data.total_discount + ' د.ل';
        }
    } catch (e) { console.error(e); }
}

async function loadCharts() {
    const params = new URLSearchParams(getFilters());
    try {
        const res = await fetch(`{{ route('reports.sales.chart') }}?${params}`);
        const r = await res.json();
        if (r.success) {
            renderSalesChart(r.data.daily_sales);
            renderPaymentChart(r.data.payment_distribution);
        }
    } catch (e) { console.error(e); }
}

function renderSalesChart(data) {
    const options = {
        series: [{ name: 'المبيعات', data: data.map(d => d.total) }],
        chart: { type: 'area', height: 300, fontFamily: 'inherit', toolbar: { show: false }, zoom: { enabled: false } },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
        xaxis: { categories: data.map(d => d.date), labels: { style: { fontFamily: 'inherit' } } },
        yaxis: { labels: { formatter: val => val.toFixed(0) + ' د.ل', style: { fontFamily: 'inherit' } } },
        tooltip: { y: { formatter: val => val.toFixed(3) + ' د.ل' } },
        colors: ['#10b981'],
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 }
    };
    if (salesChart) { salesChart.updateOptions(options); }
    else { salesChart = new ApexCharts(document.getElementById('salesChart'), options); salesChart.render(); }
}

function renderPaymentChart(data) {
    if (data.length === 0) {
        document.getElementById('paymentChart').innerHTML = '<div class="text-center text-muted py-5">لا توجد بيانات</div>';
        if (paymentChart) { paymentChart.destroy(); paymentChart = null; }
        return;
    }
    const options = {
        series: data.map(d => d.total),
        chart: { type: 'donut', height: 300, fontFamily: 'inherit' },
        labels: data.map(d => d.name),
        legend: { position: 'bottom', fontFamily: 'inherit' },
        dataLabels: { enabled: true, formatter: val => val.toFixed(1) + '%' },
        tooltip: { y: { formatter: val => val.toFixed(3) + ' د.ل' } },
        colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'الإجمالي', formatter: w => w.globals.seriesTotals.reduce((a,b) => a+b, 0).toFixed(3) + ' د.ل' } } } } }
    };
    if (paymentChart) { paymentChart.updateOptions(options); }
    else { paymentChart = new ApexCharts(document.getElementById('paymentChart'), options); paymentChart.render(); }
}

async function loadOrders() {
    document.getElementById('tableLoading').classList.remove('d-none');
    const params = new URLSearchParams({ ...getFilters(), page: currentPage });
    try {
        const res = await fetch(`{{ route('reports.sales.data') }}?${params}`);
        const r = await res.json();
        if (r.success) { renderOrders(r.data); renderPagination(r.pagination); }
    } catch (e) { console.error(e); }
    finally { document.getElementById('tableLoading').classList.add('d-none'); }
}

function renderOrders(orders) {
    const tbody = document.getElementById('ordersTableBody');
    const empty = document.getElementById('emptyState');
    const pag = document.getElementById('paginationWrapper');
    if (orders.length === 0) { tbody.innerHTML = ''; empty.classList.remove('d-none'); pag.classList.add('d-none'); return; }
    empty.classList.add('d-none'); pag.classList.remove('d-none');
    tbody.innerHTML = orders.map(o => `<tr><td><strong>${o.order_number}</strong></td><td>${o.created_at}</td><td>${o.pos_point}</td><td>${o.total}</td><td>${o.discount}</td><td class="text-success fw-bold">${o.net_total}</td><td><small class="text-muted">${o.payment_methods}</small></td></tr>`).join('');
}

function renderPagination(p) {
    document.getElementById('paginationFrom').textContent = p.from || 0;
    document.getElementById('paginationTo').textContent = p.to || 0;
    document.getElementById('paginationTotal').textContent = p.total;
    const nav = document.getElementById('paginationNav');
    let h = `<li class="page-item ${p.current_page===1?'disabled':''}"><a class="page-link" href="#" onclick="goToPage(${p.current_page-1});return false;"><i class="ti ti-chevron-right"></i></a></li>`;
    for (let i = 1; i <= p.last_page; i++) {
        if (i===1||i===p.last_page||(i>=p.current_page-2&&i<=p.current_page+2)) h += `<li class="page-item ${i===p.current_page?'active':''}"><a class="page-link" href="#" onclick="goToPage(${i});return false;">${i}</a></li>`;
        else if (i===p.current_page-3||i===p.current_page+3) h += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
    }
    h += `<li class="page-item ${p.current_page===p.last_page?'disabled':''}"><a class="page-link" href="#" onclick="goToPage(${p.current_page+1});return false;"><i class="ti ti-chevron-left"></i></a></li>`;
    nav.innerHTML = h;
}

function goToPage(page) { currentPage = page; loadOrders(); }

async function loadAllProducts() {
    document.getElementById('productsTableLoading').classList.remove('d-none');
    const params = new URLSearchParams({ ...getFilters(), sort: productsSortField, direction: productsSortDir });
    try {
        const res = await fetch(`{{ route('reports.sales.products.all') }}?${params}`);
        const r = await res.json();
        if (r.success) {
            document.getElementById('productsGrandTotal').textContent = r.grand_total + ' د.ل';
            document.getElementById('productsCount').textContent = r.products_count;
            renderAllProducts(r.data);
        }
    } catch (e) { console.error(e); }
    finally { document.getElementById('productsTableLoading').classList.add('d-none'); }
}

function renderAllProducts(products) {
    const tbody = document.getElementById('allProductsBody');
    const empty = document.getElementById('productsEmptyState');
    if (products.length === 0) { tbody.innerHTML = ''; empty.classList.remove('d-none'); return; }
    empty.classList.add('d-none');
    tbody.innerHTML = products.map((p, i) => `<tr>
        <td>${i+1}</td>
        <td><strong>${p.name}</strong></td>
        <td class="text-center">${p.orders_count}</td>
        <td class="text-center">${p.quantity}</td>
        <td class="text-success fw-bold">${p.total} د.ل</td>
        <td style="min-width:120px;">${p.percentage}%<div class="percentage-bar"><div class="percentage-bar-fill" style="width:${p.percentage}%"></div></div></td>
    </tr>`).join('');
}

function sortProducts(field) {
    if (productsSortField === field) { productsSortDir = productsSortDir === 'desc' ? 'asc' : 'desc'; }
    else { productsSortField = field; productsSortDir = 'desc'; }
    loadAllProducts();
}

async function searchProductsForReport(q) {
    const results = document.getElementById('singleProductResults');
    if (q.length < 1) { results.classList.remove('show'); return; }
    try {
        const res = await fetch(`{{ route('reports.sales.products.search') }}?q=${encodeURIComponent(q)}`);
        const products = await res.json();
        if (products.length === 0) {
            results.innerHTML = '<div style="padding:12px;color:#94a3b8;text-align:center;font-size:13px;">لا توجد نتائج</div>';
        } else {
            results.innerHTML = products.map(p => `<div class="product-search-item" onclick="selectProductForReport(${p.id},'${p.name.replace(/'/g,"\\'")}')"><span>${p.name}</span><span class="barcode">${p.barcode||''}</span></div>`).join('');
        }
        results.classList.add('show');
    } catch (e) { console.error(e); }
}

function selectProductForReport(id, name) {
    selectedProductId = id;
    document.getElementById('singleProductSearch').value = name;
    document.getElementById('singleProductResults').classList.remove('show');
    loadSingleProduct(id);
}

async function loadSingleProduct(productId) {
    const params = new URLSearchParams({ ...getFilters(), product_id: productId });
    document.getElementById('singleProductEmpty').style.display = 'none';
    document.getElementById('singleProductContent').style.display = '';

    try {
        const res = await fetch(`{{ route('reports.sales.products.single') }}?${params}`);
        const r = await res.json();
        if (r.success) {
            const d = r.data;
            document.getElementById('spName').textContent = d.product.name;
            document.getElementById('spMeta').textContent = (d.product.barcode ? 'باركود: ' + d.product.barcode + ' | ' : '') + 'السعر: ' + d.product.price + ' د.ل | ' + (d.product.type === 'weight' ? 'وزن' : 'قطعة');
            document.getElementById('spOrdersCount').textContent = d.stats.orders_count;
            document.getElementById('spTotalQty').textContent = d.stats.total_quantity;
            document.getElementById('spTotalSales').textContent = d.stats.total_sales + ' د.ل';
            document.getElementById('spAvgPrice').textContent = d.stats.avg_price + ' د.ل';

            renderSpDailyChart(d.daily_sales);
            renderSpQtyChart(d.daily_sales);

            const tbody = document.getElementById('spTransactions');
            const empty = document.getElementById('spTransactionsEmpty');
            if (d.transactions.length === 0) { tbody.innerHTML = ''; empty.classList.remove('d-none'); }
            else {
                empty.classList.add('d-none');
                tbody.innerHTML = d.transactions.map(t => `<tr><td><strong>${t.order_number}</strong></td><td>${t.date}</td><td>${t.quantity}</td><td>${t.price} د.ل</td><td class="text-success fw-bold">${t.total} د.ل</td></tr>`).join('');
            }
        }
    } catch (e) { console.error(e); }
}

function renderSpDailyChart(data) {
    const opts = {
        series: [{ name: 'المبيعات', data: data.map(d => d.total) }],
        chart: { type: 'area', height: 280, fontFamily: 'inherit', toolbar: { show: false }, zoom: { enabled: false } },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
        xaxis: { categories: data.map(d => d.date) },
        yaxis: { labels: { formatter: v => v.toFixed(0) + ' د.ل' } },
        tooltip: { y: { formatter: v => v.toFixed(3) + ' د.ل' } },
        colors: ['#10b981'],
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 }
    };
    if (spDailyChart) { spDailyChart.updateOptions(opts); }
    else { spDailyChart = new ApexCharts(document.getElementById('spDailyChart'), opts); spDailyChart.render(); }
}

function renderSpQtyChart(data) {
    const opts = {
        series: [{ name: 'الكمية', data: data.map(d => d.qty) }],
        chart: { type: 'bar', height: 280, fontFamily: 'inherit', toolbar: { show: false } },
        dataLabels: { enabled: false },
        xaxis: { categories: data.map(d => d.date) },
        yaxis: { labels: { formatter: v => v.toFixed(0) } },
        colors: ['#3b82f6'],
        plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 }
    };
    if (spQtyChart) { spQtyChart.updateOptions(opts); }
    else { spQtyChart = new ApexCharts(document.getElementById('spQtyChart'), opts); spQtyChart.render(); }
}

function exportExcel() { window.location.href = `{{ route('reports.sales.export.excel') }}?${new URLSearchParams(getFilters())}`; }
function exportProductsExcel() { window.location.href = `{{ route('reports.sales.export.products') }}?${new URLSearchParams(getFilters())}`; }
function printReport() { window.open(`{{ route('reports.sales.print') }}?${new URLSearchParams(getFilters())}`, '_blank'); }
function printProductsReport() { window.open(`{{ route('reports.sales.print.products') }}?${new URLSearchParams(getFilters())}`, '_blank'); }

function exportSingleProduct() {
    if (!selectedProductId) return;
    const params = new URLSearchParams({ ...getFilters(), product_id: selectedProductId });
    window.location.href = `{{ route('reports.sales.export.single-product') }}?${params}`;
}

function printSingleProduct() {
    if (!selectedProductId) return;
    const params = new URLSearchParams({ ...getFilters(), product_id: selectedProductId });
    window.open(`{{ route('reports.sales.print.single-product') }}?${params}`, '_blank');
}

async function loadSpecialOrdersSummary() {
    const params = new URLSearchParams(getFilters());
    try {
        const res = await fetch(`{{ route('reports.sales.special-orders.summary') }}?${params}`);
        const r = await res.json();
        if (r.success) {
            document.getElementById('specialTotalSales').textContent = r.data.total_sales + ' د.ل';
            document.getElementById('specialOrdersCount').textContent = r.data.orders_count;
            document.getElementById('specialAverageOrder').textContent = r.data.average_order + ' د.ل';
            document.getElementById('specialTotalPaid').textContent = r.data.total_paid + ' د.ل';
        }
    } catch (e) { console.error(e); }
}

async function loadSpecialOrders() {
    document.getElementById('specialTableLoading').classList.remove('d-none');
    const params = new URLSearchParams({ ...getFilters(), page: specialCurrentPage });
    try {
        const res = await fetch(`{{ route('reports.sales.special-orders.data') }}?${params}`);
        const r = await res.json();
        if (r.success) { renderSpecialOrders(r.data); renderSpecialPagination(r.pagination); }
    } catch (e) { console.error(e); }
    finally { document.getElementById('specialTableLoading').classList.add('d-none'); }
}

function renderSpecialOrders(orders) {
    const tbody = document.getElementById('specialOrdersTableBody');
    const empty = document.getElementById('specialEmptyState');
    const pag = document.getElementById('specialPaginationWrapper');
    if (orders.length === 0) { tbody.innerHTML = ''; empty.classList.remove('d-none'); pag.classList.add('d-none'); return; }
    empty.classList.add('d-none'); pag.classList.remove('d-none');
    tbody.innerHTML = orders.map(o => `<tr><td><strong>${o.customer}</strong></td><td>${o.event_type}</td><td>${o.delivery_date}</td><td>${o.total}</td><td class="text-success fw-bold">${o.paid}</td><td><small class="text-muted">${o.payment_methods}</small></td></tr>`).join('');
}

function renderSpecialPagination(p) {
    document.getElementById('specialPaginationFrom').textContent = p.from || 0;
    document.getElementById('specialPaginationTo').textContent = p.to || 0;
    document.getElementById('specialPaginationTotal').textContent = p.total;
    const nav = document.getElementById('specialPaginationNav');
    let h = `<li class="page-item ${p.current_page===1?'disabled':''}"><a class="page-link" href="#" onclick="goToSpecialPage(${p.current_page-1});return false;"><i class="ti ti-chevron-right"></i></a></li>`;
    for (let i = 1; i <= p.last_page; i++) {
        if (i===1||i===p.last_page||(i>=p.current_page-2&&i<=p.current_page+2)) h += `<li class="page-item ${i===p.current_page?'active':''}"><a class="page-link" href="#" onclick="goToSpecialPage(${i});return false;">${i}</a></li>`;
        else if (i===p.current_page-3||i===p.current_page+3) h += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
    }
    h += `<li class="page-item ${p.current_page===p.last_page?'disabled':''}"><a class="page-link" href="#" onclick="goToSpecialPage(${p.current_page+1});return false;"><i class="ti ti-chevron-left"></i></a></li>`;
    nav.innerHTML = h;
}

function goToSpecialPage(page) { specialCurrentPage = page; loadSpecialOrders(); }
function exportSpecialOrdersExcel() { window.location.href = `{{ route('reports.sales.special-orders.export') }}?${new URLSearchParams(getFilters())}`; }
</script>
@endpush
