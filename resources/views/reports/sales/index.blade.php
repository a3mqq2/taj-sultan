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

    <div class="section-divider"></div>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="action-btns">
            <button type="button" class="btn btn-success" onclick="exportExcel()">
                <i class="ti ti-file-spreadsheet"></i>
                تصدير Excel
            </button>
            <button type="button" class="btn btn-outline-secondary" onclick="printReport()">
                <i class="ti ti-printer"></i>
                طباعة
            </button>
        </div>
        <button type="button" class="btn btn-primary" onclick="loadData()">
            <i class="ti ti-refresh"></i>
            تحديث
        </button>
    </div>
</div>

<div class="summary-grid">
    <div class="summary-card">
        <div class="icon-box green">
            <i class="ti ti-currency-dollar"></i>
        </div>
        <div class="content">
            <div class="value" id="totalSales">0.000 د.ل</div>
            <div class="label">إجمالي المبيعات</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="icon-box blue">
            <i class="ti ti-receipt"></i>
        </div>
        <div class="content">
            <div class="value" id="ordersCount">0</div>
            <div class="label">عدد الطلبات</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="icon-box purple">
            <i class="ti ti-chart-bar"></i>
        </div>
        <div class="content">
            <div class="value" id="averageOrder">0.000 د.ل</div>
            <div class="label">متوسط الطلب</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="icon-box orange">
            <i class="ti ti-discount"></i>
        </div>
        <div class="content">
            <div class="value" id="totalDiscount">0.000 د.ل</div>
            <div class="label">إجمالي الخصومات</div>
        </div>
    </div>
</div>

<div class="charts-grid">
    <div class="report-card">
        <div class="report-card-header">
            <div class="report-card-title">
                <i class="ti ti-chart-line"></i>
                المبيعات اليومية
            </div>
        </div>
        <div id="salesChart" style="height: 300px;"></div>
    </div>
    <div class="report-card">
        <div class="report-card-header">
            <div class="report-card-title">
                <i class="ti ti-chart-pie"></i>
                طرق الدفع
            </div>
        </div>
        <div id="paymentChart" style="height: 300px;"></div>
    </div>
</div>

<div class="data-grid">
    <div class="data-table position-relative">
        <div id="tableLoading" class="loading-overlay d-none">
            <div class="spinner"></div>
        </div>

        <div class="report-card-header" style="padding: 20px; margin: 0; border-radius: 16px 16px 0 0;">
            <div class="report-card-title">
                <i class="ti ti-list-details"></i>
                طلبات نقاط البيع
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
                <tbody id="ordersTableBody">
                </tbody>
            </table>
        </div>

        <div id="emptyState" class="empty-state d-none">
            <div class="empty-state-icon">
                <i class="ti ti-receipt-off"></i>
            </div>
            <h5>لا توجد طلبات</h5>
            <p class="text-muted">لم يتم العثور على طلبات في الفترة المحددة</p>
        </div>

        <div id="paginationWrapper" class="pagination-wrapper d-none">
            <div class="pagination-info">
                عرض <span id="paginationFrom">0</span> - <span id="paginationTo">0</span>
                من <span id="paginationTotal">0</span> طلب
            </div>
            <nav>
                <ul class="pagination" id="paginationNav">
                </ul>
            </nav>
        </div>
    </div>

    <div class="report-card">
        <div class="report-card-header">
            <div class="report-card-title">
                <i class="ti ti-trophy"></i>
                أكثر المنتجات مبيعاً
            </div>
            <button type="button" class="btn btn-sm btn-outline-success" onclick="exportProductsExcel()">
                <i class="ti ti-file-spreadsheet me-1"></i>
                تصدير
            </button>
        </div>
        <ul class="products-list" id="topProductsList">
        </ul>
        <div id="productsEmpty" class="text-center py-4 text-muted d-none">
            لا توجد بيانات
        </div>
    </div>
</div>

<div class="report-card mt-4">
    <div class="report-card-header">
        <div class="report-card-title">
            <i class="ti ti-cake"></i>
            الطلبيات الخاصة المسلمة
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-success" onclick="exportSpecialOrdersExcel()">
                <i class="ti ti-file-spreadsheet me-1"></i>
                تصدير
            </button>
        </div>
    </div>

    <div class="summary-grid mb-4" style="padding: 0 20px;">
        <div class="summary-card" style="padding: 16px;">
            <div class="icon-box green" style="width: 44px; height: 44px;">
                <i class="ti ti-currency-dollar" style="font-size: 22px;"></i>
            </div>
            <div class="content">
                <div class="value" id="specialTotalSales" style="font-size: 20px;">0.000 د.ل</div>
                <div class="label" style="font-size: 12px;">إجمالي المبيعات</div>
            </div>
        </div>
        <div class="summary-card" style="padding: 16px;">
            <div class="icon-box blue" style="width: 44px; height: 44px;">
                <i class="ti ti-receipt" style="font-size: 22px;"></i>
            </div>
            <div class="content">
                <div class="value" id="specialOrdersCount" style="font-size: 20px;">0</div>
                <div class="label" style="font-size: 12px;">عدد الطلبات</div>
            </div>
        </div>
        <div class="summary-card" style="padding: 16px;">
            <div class="icon-box purple" style="width: 44px; height: 44px;">
                <i class="ti ti-chart-bar" style="font-size: 22px;"></i>
            </div>
            <div class="content">
                <div class="value" id="specialAverageOrder" style="font-size: 20px;">0.000 د.ل</div>
                <div class="label" style="font-size: 12px;">متوسط الطلب</div>
            </div>
        </div>
        <div class="summary-card" style="padding: 16px;">
            <div class="icon-box orange" style="width: 44px; height: 44px;">
                <i class="ti ti-cash" style="font-size: 22px;"></i>
            </div>
            <div class="content">
                <div class="value" id="specialTotalPaid" style="font-size: 20px;">0.000 د.ل</div>
                <div class="label" style="font-size: 12px;">إجمالي المدفوع</div>
            </div>
        </div>
    </div>

    <div class="table-responsive position-relative">
        <div id="specialTableLoading" class="loading-overlay d-none">
            <div class="spinner"></div>
        </div>

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
            <tbody id="specialOrdersTableBody">
            </tbody>
        </table>

        <div id="specialEmptyState" class="empty-state d-none">
            <div class="empty-state-icon">
                <i class="ti ti-cake-off"></i>
            </div>
            <h5>لا توجد طلبيات خاصة</h5>
            <p class="text-muted">لم يتم العثور على طلبيات خاصة مسلمة في الفترة المحددة</p>
        </div>

        <div id="specialPaginationWrapper" class="pagination-wrapper d-none">
            <div class="pagination-info">
                عرض <span id="specialPaginationFrom">0</span> - <span id="specialPaginationTo">0</span>
                من <span id="specialPaginationTotal">0</span> طلب
            </div>
            <nav>
                <ul class="pagination" id="specialPaginationNav">
                </ul>
            </nav>
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

document.addEventListener('DOMContentLoaded', function() {
    setPreset('today');

    document.querySelectorAll('.preset-btns .btn').forEach(btn => {
        btn.addEventListener('click', function() {
            setPreset(this.dataset.preset);
        });
    });

    ['dateFrom', 'dateTo', 'posPointFilter', 'paymentMethodFilter'].forEach(id => {
        document.getElementById(id).addEventListener('change', function() {
            document.querySelectorAll('.preset-btns .btn').forEach(b => b.classList.remove('active'));
            currentPage = 1;
            loadData();
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
            return;
        }

        if (e.ctrlKey && e.key === 'p') {
            e.preventDefault();
            printReport();
        }

        if (e.ctrlKey && e.key === 'e') {
            e.preventDefault();
            exportExcel();
        }

        if (e.key === 'Enter') {
            loadData();
        }
    });
});

function setPreset(preset) {
    const today = new Date();
    let dateFrom = '';
    let dateTo = '';

    document.querySelectorAll('.preset-btns .btn').forEach(b => b.classList.remove('active'));
    document.querySelector(`[data-preset="${preset}"]`)?.classList.add('active');

    switch(preset) {
        case 'today':
            dateFrom = dateTo = formatDate(today);
            break;
        case 'yesterday':
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            dateFrom = dateTo = formatDate(yesterday);
            break;
        case 'week':
            const weekStart = new Date(today);
            weekStart.setDate(today.getDate() - today.getDay());
            dateFrom = formatDate(weekStart);
            dateTo = formatDate(today);
            break;
        case 'month':
            const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
            dateFrom = formatDate(monthStart);
            dateTo = formatDate(today);
            break;
        case 'all':
            dateFrom = '';
            dateTo = '';
            break;
    }

    document.getElementById('dateFrom').value = dateFrom;
    document.getElementById('dateTo').value = dateTo;
    currentPage = 1;
    loadData();
}

function formatDate(date) {
    return date.toISOString().split('T')[0];
}

function getFilters() {
    return {
        date_from: document.getElementById('dateFrom').value,
        date_to: document.getElementById('dateTo').value,
        pos_point_id: document.getElementById('posPointFilter').value,
        payment_method_id: document.getElementById('paymentMethodFilter').value,
    };
}

async function loadData() {
    loadSummary();
    loadCharts();
    loadOrders();
    loadTopProducts();
    loadSpecialOrdersSummary();
    loadSpecialOrders();
}

async function loadSummary() {
    const params = new URLSearchParams(getFilters());

    try {
        const response = await fetch(`{{ route('reports.sales.summary') }}?${params}`);
        const result = await response.json();

        if (result.success) {
            document.getElementById('totalSales').textContent = result.data.total_sales + ' د.ل';
            document.getElementById('ordersCount').textContent = result.data.orders_count;
            document.getElementById('averageOrder').textContent = result.data.average_order + ' د.ل';
            document.getElementById('totalDiscount').textContent = result.data.total_discount + ' د.ل';
        }
    } catch (error) {
        console.error('Error loading summary:', error);
    }
}

async function loadCharts() {
    const params = new URLSearchParams(getFilters());

    try {
        const response = await fetch(`{{ route('reports.sales.chart') }}?${params}`);
        const result = await response.json();

        if (result.success) {
            renderSalesChart(result.data.daily_sales);
            renderPaymentChart(result.data.payment_distribution);
        }
    } catch (error) {
        console.error('Error loading charts:', error);
    }
}

function renderSalesChart(data) {
    const options = {
        series: [{
            name: 'المبيعات',
            data: data.map(d => d.total)
        }],
        chart: {
            type: 'area',
            height: 300,
            fontFamily: 'inherit',
            toolbar: { show: false },
            zoom: { enabled: false }
        },
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.05,
            }
        },
        xaxis: {
            categories: data.map(d => d.date),
            labels: {
                style: { fontFamily: 'inherit' }
            }
        },
        yaxis: {
            labels: {
                formatter: val => val.toFixed(0) + ' د.ل',
                style: { fontFamily: 'inherit' }
            }
        },
        tooltip: {
            y: {
                formatter: val => val.toFixed(3) + ' د.ل'
            }
        },
        colors: ['#10b981'],
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 4
        }
    };

    if (salesChart) {
        salesChart.updateOptions(options);
    } else {
        salesChart = new ApexCharts(document.getElementById('salesChart'), options);
        salesChart.render();
    }
}

function renderPaymentChart(data) {
    if (data.length === 0) {
        document.getElementById('paymentChart').innerHTML = '<div class="text-center text-muted py-5">لا توجد بيانات</div>';
        return;
    }

    const options = {
        series: data.map(d => d.total),
        chart: {
            type: 'donut',
            height: 300,
            fontFamily: 'inherit'
        },
        labels: data.map(d => d.name),
        legend: {
            position: 'bottom',
            fontFamily: 'inherit'
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return val.toFixed(1) + '%';
            }
        },
        tooltip: {
            y: {
                formatter: val => val.toFixed(3) + ' د.ل'
            }
        },
        colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'الإجمالي',
                            formatter: function(w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0).toFixed(3) + ' د.ل';
                            }
                        }
                    }
                }
            }
        }
    };

    if (paymentChart) {
        paymentChart.updateOptions(options);
    } else {
        paymentChart = new ApexCharts(document.getElementById('paymentChart'), options);
        paymentChart.render();
    }
}

async function loadOrders() {
    const tableLoading = document.getElementById('tableLoading');
    tableLoading.classList.remove('d-none');

    const params = new URLSearchParams({
        ...getFilters(),
        page: currentPage
    });

    try {
        const response = await fetch(`{{ route('reports.sales.data') }}?${params}`);
        const result = await response.json();

        if (result.success) {
            renderOrders(result.data);
            renderPagination(result.pagination);
        }
    } catch (error) {
        console.error('Error loading orders:', error);
    } finally {
        tableLoading.classList.add('d-none');
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
        <tr>
            <td><strong>${order.order_number}</strong></td>
            <td>${order.created_at}</td>
            <td>${order.pos_point}</td>
            <td>${order.total}</td>
            <td>${order.discount}</td>
            <td class="text-success fw-bold">${order.net_total}</td>
            <td><small class="text-muted">${order.payment_methods}</small></td>
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
    loadOrders();
}

async function loadTopProducts() {
    const params = new URLSearchParams(getFilters());

    try {
        const response = await fetch(`{{ route('reports.sales.products') }}?${params}`);
        const result = await response.json();

        if (result.success) {
            renderTopProducts(result.data);
        }
    } catch (error) {
        console.error('Error loading products:', error);
    }
}

function renderTopProducts(products) {
    const list = document.getElementById('topProductsList');
    const emptyEl = document.getElementById('productsEmpty');

    if (products.length === 0) {
        list.innerHTML = '';
        emptyEl.classList.remove('d-none');
        return;
    }

    emptyEl.classList.add('d-none');

    list.innerHTML = products.map((product, index) => `
        <li>
            <div class="product-name">
                <span class="product-rank">${index + 1}</span>
                ${product.name}
            </div>
            <div class="product-info">
                <div class="product-qty">${product.quantity}</div>
                <div class="product-total">${product.total} د.ل</div>
            </div>
        </li>
    `).join('');
}

function exportExcel() {
    const params = new URLSearchParams(getFilters());
    window.location.href = `{{ route('reports.sales.export.excel') }}?${params}`;
}

function exportProductsExcel() {
    const params = new URLSearchParams(getFilters());
    window.location.href = `{{ route('reports.sales.export.products') }}?${params}`;
}

function printReport() {
    const params = new URLSearchParams(getFilters());
    window.open(`{{ route('reports.sales.print') }}?${params}`, '_blank');
}
</script>
@endpush
