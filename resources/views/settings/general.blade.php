@extends('layouts.app')

@section('title', 'الإعدادات العامة')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">الإعدادات العامة</li>
@endsection

@push('styles')
<style>
    .settings-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
        background-color: #fff;
        max-width: 600px;
    }

    .settings-card .card-header {
        background: #f8fafc;
        padding: 16px 20px;
        border-bottom: 2px solid #e5e7eb;
        font-weight: 700;
        font-size: 16px;
    }

    .settings-card .card-body {
        padding: 24px 20px;
    }

    [data-bs-theme="dark"] .settings-card {
        background-color: #1f2937;
        border-color: #374151;
    }

    [data-bs-theme="dark"] .settings-card .card-header {
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

    [data-bs-theme="dark"] .form-control {
        background-color: #1f2937;
        border-color: #4b5563;
        color: #f9fafb;
    }

    .form-hint {
        font-size: 13px;
        color: #6b7280;
        margin-top: 6px;
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
</style>
@endpush

@section('content')
<div class="">
    <div class="settings-card card">
        <div class="card-header">
            <i class="ti ti-settings me-2"></i>
            الإعدادات العامة
        </div>
        <div class="card-body">
            <form id="settingsForm">
                <div class="mb-4">
                    <label class="form-label">كود إلغاء الفاتورة <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="cancelCode" value="{{ $cancelCode }}" placeholder="أدخل كود الإلغاء" autocomplete="off">
                    <div class="form-hint">يجب إدخال هذا الكود عند إلغاء فاتورة من الكاشير (4 أحرف على الأقل)</div>
                </div>

                <button type="submit" class="btn btn-primary" id="saveBtn" style="padding: 12px 32px;">
                    <span class="btn-text"><i class="ti ti-check me-1"></i> حفظ</span>
                    <span class="btn-loading d-none">
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        جاري الحفظ...
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="toast-container" id="toastContainer"></div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let isSubmitting = false;

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('settingsForm').addEventListener('submit', handleSubmit);

    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            handleSubmit(e);
        }
    });
});

async function handleSubmit(e) {
    e.preventDefault();
    if (isSubmitting) return;

    const cancelCode = document.getElementById('cancelCode').value.trim();

    if (!cancelCode) {
        showToast('كود الإلغاء مطلوب', 'error');
        return;
    }

    if (cancelCode.length < 4) {
        showToast('كود الإلغاء يجب أن يكون 4 أحرف على الأقل', 'error');
        return;
    }

    setSubmitting(true);

    try {
        const response = await fetch('{{ route("settings.general.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                cancel_invoice_code: cancelCode
            })
        });

        const result = await response.json();

        if (response.ok && result.success) {
            showToast(result.message, 'success');
        } else {
            if (result.errors) {
                const firstError = Object.values(result.errors)[0];
                showToast(Array.isArray(firstError) ? firstError[0] : firstError, 'error');
            } else {
                showToast(result.message || 'حدث خطأ', 'error');
            }
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
