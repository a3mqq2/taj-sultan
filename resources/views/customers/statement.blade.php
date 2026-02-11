@extends('layouts.app')

@section('title', 'كشف حساب - ' . $customer->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">الزبائن</a></li>
    <li class="breadcrumb-item active">كشف حساب</li>
@endsection

@push('styles')
<style>
    .customer-card {
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
        margin-bottom: 24px;
        background-color: #fff;
    }

    [data-bs-theme="dark"] .customer-card {
        background-color: #1f2937;
        border-color: #374151;
    }

    .customer-name {
        font-size: 24px;
        font-weight: 700;
        color: var(--bs-body-color);
    }

    .customer-info {
        color: #6b7280;
        font-size: 14px;
    }

    .balance-card {
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        border: 1px solid #e5e7eb;
    }

    [data-bs-theme="dark"] .balance-card {
        border-color: #374151;
    }

    .balance-label {
        font-size: 14px;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .balance-value {
        font-size: 28px;
        font-weight: 700;
    }

    .balance-positive { color: #10b981; }
    .balance-negative { color: #ef4444; }
    .balance-zero { color: #6b7280; }

    .statement-table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
        background-color: #fff;
    }

    [data-bs-theme="dark"] .statement-table {
        background-color: #1f2937;
        border-color: #374151;
    }

    .statement-table .table {
        margin-bottom: 0;
    }

    .statement-table th {
        background: #f8fafc;
        font-weight: 600;
        padding: 14px 16px;
        border-bottom: 2px solid #d1d5db;
        border-top: none;
        white-space: nowrap;
        color: #374151;
        font-size: 14px;
    }

    [data-bs-theme="dark"] .statement-table th {
        background-color: #111827;
        border-color: #4b5563;
        color: #f3f4f6;
    }

    .statement-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #e5e7eb;
        border-top: none;
    }

    [data-bs-theme="dark"] .statement-table td {
        border-color: #374151;
    }

    .statement-table tbody tr {
        transition: all 0.15s ease;
    }

    .statement-table tbody tr:hover {
        background-color: #f0f9ff;
    }

    [data-bs-theme="dark"] .statement-table tbody tr:hover {
        background-color: #374151;
    }

    .type-badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 13px;
        display: inline-block;
    }

    .badge-payment { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-order { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-refund { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }

    .amount-credit { color: #10b981; font-weight: 600; }
    .amount-debit { color: #ef4444; font-weight: 600; }

    .btn-add-payment {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-add-payment:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-print {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 500;
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

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 48px;
        color: #9ca3af;
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

    @media print {
        .no-print { display: none !important; }
        .customer-card, .statement-table { box-shadow: none; border: 1px solid #ddd; }
    }
</style>
@endpush

@section('content')
<div class="">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card customer-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="customer-name mb-1">{{ $customer->name }}</h2>
                        <div class="customer-info">
                            @if($customer->phone)
                                <i class="ti ti-phone me-1"></i> {{ $customer->phone }}
                            @endif
                            @if($customer->address)
                                <span class="mx-2">|</span>
                                <i class="ti ti-map-pin me-1"></i> {{ $customer->address }}
                            @endif
                        </div>
                    </div>
                    <div class="no-print d-flex gap-2">
                        <button type="button" class="btn btn-success btn-add-payment" onclick="openPaymentModal()">
                            <i class="ti ti-plus me-1"></i>
                            إضافة دفعة
                        </button>
                        <a href="{{ route('customers.print-statement', $customer->id) }}" target="_blank" class="btn btn-outline-secondary btn-print">
                            <i class="ti ti-printer me-1"></i>
                            طباعة
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            @php
                $balance = $customer->balance;
                $balanceClass = $balance > 0 ? 'balance-positive' : ($balance < 0 ? 'balance-negative' : 'balance-zero');
                $balanceLabel = $balance > 0 ? 'رصيد لصالح الزبون' : ($balance < 0 ? 'رصيد على الزبون' : 'الحساب مسدد');
            @endphp
            <div class="card balance-card {{ $balance >= 0 ? 'bg-success-subtle' : 'bg-danger-subtle' }}">
                <div class="balance-label">{{ $balanceLabel }}</div>
                <div class="balance-value {{ $balanceClass }}">
                    {{ number_format(abs($balance), 2) }} د.ل
                </div>
            </div>
        </div>
    </div>

    <div class="card statement-table">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>النوع</th>
                        <th>الوصف</th>
                        <th>مدين</th>
                        <th>دائن</th>
                        <th>الرصيد</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->created_at->format('Y/m/d H:i') }}</td>
                            <td>
                                <span class="type-badge badge-{{ $transaction->type }}">
                                    {{ $transaction->type_name }}
                                </span>
                            </td>
                            <td>{{ $transaction->description }}</td>
                            <td>
                                @if($transaction->isDebit())
                                    <span class="amount-debit">{{ number_format($transaction->amount, 2) }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($transaction->isCredit())
                                    <span class="amount-credit">{{ number_format($transaction->amount, 2) }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @php
                                    $bal = $transaction->balance_after;
                                    $balClass = $bal > 0 ? 'amount-credit' : ($bal < 0 ? 'amount-debit' : '');
                                @endphp
                                <span class="{{ $balClass }}">{{ number_format($bal, 2) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="ti ti-receipt-off"></i>
                                    <h5 class="mt-3">لا توجد حركات</h5>
                                    <p class="text-muted">لم يتم تسجيل أي حركات مالية لهذا الزبون بعد.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade no-print" id="paymentModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة دفعة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="paymentForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">المبلغ <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="paymentAmount" name="amount" step="0.01" min="0.01" placeholder="0.00" autofocus>
                            <span class="input-group-text" style="border: 2px solid #d1d5db; border-right: none; border-radius: 10px 0 0 10px;">د.ل</span>
                        </div>
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

<div class="toast-container no-print" id="toastContainer"></div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));

function openPaymentModal() {
    document.getElementById('paymentAmount').value = '';
    document.getElementById('paymentNotes').value = '';
    paymentModal.show();
    setTimeout(() => document.getElementById('paymentAmount').focus(), 300);
}

document.getElementById('paymentForm').addEventListener('submit', async function(e) {
    e.preventDefault();

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
        const response = await fetch('{{ route("customers.payment", $customer->id) }}', {
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
            showToast(result.message, 'success');
            setTimeout(() => location.reload(), 1000);
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
});

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
</script>
@endpush
