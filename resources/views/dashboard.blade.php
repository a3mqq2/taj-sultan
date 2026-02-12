@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('breadcrumb')
    <li class="breadcrumb-item active">لوحة التحكم</li>
@endsection

@push('styles')
<style>
    :root {
        --widget-bg: #ffffff;
        --widget-border: #e5e7eb;
    }
    [data-bs-theme="dark"] {
        --widget-bg: #1e1e1e;
        --widget-border: #2d2d2d;
    }

    .stat-widget {
        background: var(--widget-bg);
        border: 1px solid var(--widget-border);
        border-radius: 10px;
        padding: 1.25rem;
        height: 100%;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-widget:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .stat-widget .widget-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .stat-widget .widget-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--bs-body-color);
        margin: 0.5rem 0 0.25rem;
    }
    .stat-widget .widget-label {
        font-size: 0.85rem;
        color: var(--bs-secondary-color);
        margin-bottom: 0;
    }
    .stat-widget .widget-sub {
        font-size: 0.75rem;
        color: var(--bs-secondary-color);
        margin-top: 0.25rem;
    }

    .widget-icon.primary { background: rgba(var(--bs-primary-rgb), 0.12); color: var(--bs-primary); }
    .widget-icon.success { background: rgba(var(--bs-success-rgb), 0.12); color: var(--bs-success); }
    .widget-icon.warning { background: rgba(var(--bs-warning-rgb), 0.12); color: var(--bs-warning); }
    .widget-icon.danger { background: rgba(var(--bs-danger-rgb), 0.12); color: var(--bs-danger); }
    .widget-icon.info { background: rgba(var(--bs-info-rgb), 0.12); color: var(--bs-info); }
    .widget-icon.secondary { background: rgba(var(--bs-secondary-rgb), 0.12); color: var(--bs-secondary); }

    .chart-card {
        background: var(--widget-bg);
        border: 1px solid var(--widget-border);
        border-radius: 10px;
        height: 100%;
    }
    .chart-card .card-header {
        background: transparent;
        border-bottom: 1px solid var(--widget-border);
        padding: 1rem 1.25rem;
    }
    .chart-card .card-body {
        padding: 1.25rem;
    }
    .chart-card .card-title {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
        color: var(--bs-body-color);
    }

    .table-widget {
        background: var(--widget-bg);
        border: 1px solid var(--widget-border);
        border-radius: 10px;
        overflow: hidden;
    }
    .table-widget .widget-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--widget-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .table-widget .widget-title {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
        color: var(--bs-body-color);
    }
    .table-widget .table {
        margin: 0;
    }
    .table-widget .table th {
        background: var(--bs-tertiary-bg);
        font-weight: 600;
        font-size: 0.8rem;
        color: var(--bs-secondary-color);
        padding: 0.75rem 1rem;
        border: none;
    }
    .table-widget .table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-color: var(--widget-border);
        color: var(--bs-body-color);
    }

    .shortcut-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }
    .shortcut-item {
        background: var(--widget-bg);
        border: 1px solid var(--widget-border);
        border-radius: 10px;
        padding: 1.25rem;
        text-align: center;
        text-decoration: none;
        color: var(--bs-body-color);
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    }
    .shortcut-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        border-color: var(--bs-primary);
        color: var(--bs-primary);
    }
    .shortcut-item i {
        font-size: 2rem;
        margin-bottom: 0.75rem;
        display: block;
    }
    .shortcut-item span {
        font-size: 0.9rem;
        font-weight: 500;
    }

    .welcome-banner {
        background: var(--widget-bg);
        border: 1px solid var(--widget-border);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .welcome-banner h4 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--bs-body-color);
    }
    .welcome-banner p {
        color: var(--bs-secondary-color);
        margin-bottom: 0;
    }
    .welcome-banner .date-time {
        display: flex;
        gap: 1.5rem;
        margin-top: 0.5rem;
        font-size: 0.9rem;
        color: var(--bs-secondary-color);
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: var(--bs-secondary-color);
    }
    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        opacity: 0.5;
    }
</style>
@endpush

@section('content')
<div class="welcome-banner">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h4>مرحباً، {{ auth()->user()->name }}</h4>
            <p>مرحباً بك في لوحة تحكم تاج السلطان</p>
            <div class="date-time">
                <span><i class="ti ti-calendar me-1"></i> {{ now()->format('Y/m/d') }}</span>
                <span><i class="ti ti-clock me-1"></i> <span id="current-time">{{ now()->format('H:i') }}</span></span>
            </div>
        </div>
        <div>
            @if($pendingOrders > 0)
                <span class="badge bg-warning fs-6">
                    <i class="ti ti-clock me-1"></i>
                    {{ $pendingOrders }} طلب معلق
                </span>
            @endif
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-widget">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="widget-label">مبيعات اليوم</p>
                    <h3 class="widget-value">{{ number_format($todaySales, 3) }}</h3>
                    <p class="widget-sub">{{ $todayOrders }} طلب</p>
                </div>
                <div class="widget-icon success">
                    <i class="ti ti-cash"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-widget">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="widget-label">مبيعات الأسبوع</p>
                    <h3 class="widget-value">{{ number_format($weekSales, 3) }}</h3>
                    <p class="widget-sub">د.ل</p>
                </div>
                <div class="widget-icon primary">
                    <i class="ti ti-chart-bar"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-widget">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="widget-label">مبيعات الشهر</p>
                    <h3 class="widget-value">{{ number_format($monthSales, 3) }}</h3>
                    <p class="widget-sub">د.ل</p>
                </div>
                <div class="widget-icon info">
                    <i class="ti ti-report-money"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-widget">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="widget-label">طلبات خاصة معلقة</p>
                    <h3 class="widget-value">{{ $pendingSpecialOrders }}</h3>
                    <p class="widget-sub">{{ $todayDeliveries }} تسليم اليوم</p>
                </div>
                <div class="widget-icon warning">
                    <i class="ti ti-package"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-widget">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="widget-label">الأصناف</p>
                    <h3 class="widget-value">{{ $productsCount }}</h3>
                    <p class="widget-sub">{{ $activeProducts }} مفعل</p>
                </div>
                <div class="widget-icon secondary">
                    <i class="ti ti-box"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-widget">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="widget-label">العملاء</p>
                    <h3 class="widget-value">{{ $customersCount }}</h3>
                    <p class="widget-sub">{{ $activeCustomers }} نشط</p>
                </div>
                <div class="widget-icon info">
                    <i class="ti ti-users"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-widget">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="widget-label">نقاط البيع</p>
                    <h3 class="widget-value">{{ $activePosPoints }}</h3>
                    <p class="widget-sub">نشطة</p>
                </div>
                <div class="widget-icon danger">
                    <i class="ti ti-device-desktop"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-widget">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="widget-label">المستخدمين</p>
                    <h3 class="widget-value">{{ $usersCount }}</h3>
                    <p class="widget-sub">نشط</p>
                </div>
                <div class="widget-icon primary">
                    <i class="ti ti-user-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="chart-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">
                    <i class="ti ti-chart-line me-2"></i>
                    مبيعات آخر 7 أيام
                </h5>
            </div>
            <div class="card-body">
                <div id="sales-chart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="chart-card h-100">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="ti ti-link me-2"></i>
                    اختصارات سريعة
                </h5>
            </div>
            <div class="card-body">
                <div class="shortcut-grid">
                    <a href="{{ route('products.index') }}" class="shortcut-item">
                        <i class="ti ti-box"></i>
                        <span>الأصناف</span>
                    </a>
                    <a href="{{ route('special-orders.index') }}" class="shortcut-item">
                        <i class="ti ti-package"></i>
                        <span>الطلبات الخاصة</span>
                    </a>
                    <a href="{{ route('customers.index') }}" class="shortcut-item">
                        <i class="ti ti-users"></i>
                        <span>العملاء</span>
                    </a>
                    <a href="{{ route('reports.sales.index') }}" class="shortcut-item">
                        <i class="ti ti-report-analytics"></i>
                        <span>التقارير</span>
                    </a>
                    <a href="{{ route('users.index') }}" class="shortcut-item">
                        <i class="ti ti-user-circle"></i>
                        <span>المستخدمين</span>
                    </a>
                    <a href="{{ route('payment-methods.index') }}" class="shortcut-item">
                        <i class="ti ti-credit-card"></i>
                        <span>طرق الدفع</span>
                    </a>
                    <a href="javascript:void(0)" onclick="createBackup()" class="shortcut-item" id="backup-btn">
                        <i class="ti ti-database-export"></i>
                        <span>نسخ احتياطي</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="table-widget">
            <div class="widget-header">
                <h5 class="widget-title">
                    <i class="ti ti-receipt me-2"></i>
                    آخر الطلبات
                </h5>
                <a href="{{ route('cashier.index') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>نقطة البيع</th>
                            <th>الإجمالي</th>
                            <th>الوقت</th>
                        </tr>
                    </thead>
                    <tbody id="recent-orders-table">
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <div class="spinner-border spinner-border-sm text-primary"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="table-widget">
            <div class="widget-header">
                <h5 class="widget-title">
                    <i class="ti ti-truck-delivery me-2"></i>
                    التسليمات القادمة
                </h5>
                <a href="{{ route('special-orders.index') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>العميل</th>
                            <th>المناسبة</th>
                            <th>التسليم</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody id="upcoming-deliveries-table">
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <div class="spinner-border spinner-border-sm text-primary"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    updateTime();
    setInterval(updateTime, 60000);

    loadChart();
    loadRecentOrders();
    loadUpcomingDeliveries();
});

function updateTime() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    document.getElementById('current-time').textContent = hours + ':' + minutes;
}

function loadChart() {
    fetch('{{ route("dashboard.chart") }}')
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                renderChart(result.data);
            }
        });
}

function renderChart(data) {
    const options = {
        series: [{
            name: 'المبيعات',
            data: data.map(d => d.sales)
        }],
        chart: {
            type: 'area',
            height: 300,
            fontFamily: 'inherit',
            toolbar: { show: false },
            sparkline: { enabled: false }
        },
        colors: ['#6366f1'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        xaxis: {
            categories: data.map(d => d.date),
            labels: {
                style: { fontFamily: 'inherit' }
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return val.toFixed(0);
                },
                style: { fontFamily: 'inherit' }
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val.toFixed(3) + ' د.ل';
                }
            }
        },
        grid: {
            borderColor: '#e5e7eb',
            strokeDashArray: 4
        },
        dataLabels: { enabled: false }
    };

    const chart = new ApexCharts(document.querySelector("#sales-chart"), options);
    chart.render();
}

function loadRecentOrders() {
    fetch('{{ route("dashboard.recent-orders") }}')
        .then(response => response.json())
        .then(result => {
            const tbody = document.getElementById('recent-orders-table');
            if (result.success && result.data.length > 0) {
                tbody.innerHTML = result.data.map(order => `
                    <tr>
                        <td><strong>#${order.order_number}</strong></td>
                        <td>${order.pos_point}</td>
                        <td>${order.total} د.ل</td>
                        <td>${order.paid_at}</td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="ti ti-receipt-off d-block"></i>
                                <span>لا توجد طلبات حديثة</span>
                            </div>
                        </td>
                    </tr>
                `;
            }
        });
}

function loadUpcomingDeliveries() {
    fetch('{{ route("dashboard.upcoming-deliveries") }}')
        .then(response => response.json())
        .then(result => {
            const tbody = document.getElementById('upcoming-deliveries-table');
            if (result.success && result.data.length > 0) {
                tbody.innerHTML = result.data.map(order => `
                    <tr>
                        <td>${order.customer}</td>
                        <td>${order.event_type}</td>
                        <td>${order.delivery_date}</td>
                        <td><span class="badge ${order.status_class}">${order.status}</span></td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="ti ti-truck-off d-block"></i>
                                <span>لا توجد تسليمات قادمة</span>
                            </div>
                        </td>
                    </tr>
                `;
            }
        });
}

function createBackup() {
    const btn = document.getElementById('backup-btn');
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<div class="spinner-border spinner-border-sm"></div><span>جاري النسخ...</span>';
    btn.style.pointerEvents = 'none';

    fetch('{{ route("admin.backup") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(result => {
        btn.innerHTML = originalContent;
        btn.style.pointerEvents = 'auto';
        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'تم بنجاح',
                text: result.message,
                confirmButtonText: 'حسناً'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: result.message,
                confirmButtonText: 'حسناً'
            });
        }
    })
    .catch(error => {
        btn.innerHTML = originalContent;
        btn.style.pointerEvents = 'auto';
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: 'حدث خطأ في الاتصال',
            confirmButtonText: 'حسناً'
        });
    });
}
</script>
@endpush
