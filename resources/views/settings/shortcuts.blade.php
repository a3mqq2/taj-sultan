@extends('layouts.app')

@section('title', 'اختصارات المنتجات')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">اختصارات المنتجات</li>
@endsection

@push('styles')
<style>
    .shortcuts-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
        background-color: #fff;
        max-width: 700px;
    }

    .shortcuts-card .card-header {
        background: #f8fafc;
        padding: 16px 20px;
        border-bottom: 2px solid #e5e7eb;
        font-weight: 700;
        font-size: 16px;
    }

    .shortcuts-card .card-body {
        padding: 24px 20px;
    }

    [data-bs-theme="dark"] .shortcuts-card {
        background-color: #1f2937;
        border-color: #374151;
    }

    [data-bs-theme="dark"] .shortcuts-card .card-header {
        background-color: #111827;
        border-color: #374151;
    }

    .shortcut-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 10px;
        border: 2px solid #e5e7eb;
    }

    [data-bs-theme="dark"] .shortcut-row {
        background: #111827;
        border-color: #374151;
    }

    .shortcut-number {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #3b82f6;
        color: #fff;
        font-weight: 800;
        font-size: 18px;
        border-radius: 10px;
        flex-shrink: 0;
    }

    .shortcut-select-wrapper {
        flex: 1;
        position: relative;
    }

    .shortcut-search {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #d1d5db;
        border-radius: 10px;
        font-size: 14px;
        font-family: inherit;
        background: #fff;
        transition: all 0.2s;
    }

    [data-bs-theme="dark"] .shortcut-search {
        background: #1f2937;
        border-color: #4b5563;
        color: #f9fafb;
    }

    .shortcut-search:focus {
        outline: none;
        border-color: #3b82f6;
    }

    .shortcut-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 2px solid #3b82f6;
        border-radius: 10px;
        margin-top: 4px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 100;
        display: none;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }

    [data-bs-theme="dark"] .shortcut-dropdown {
        background: #1f2937;
        border-color: #3b82f6;
    }

    .shortcut-dropdown.show {
        display: block;
    }

    .shortcut-dropdown-item {
        padding: 10px 14px;
        cursor: pointer;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.1s;
    }

    .shortcut-dropdown-item:hover {
        background: #eff6ff;
    }

    [data-bs-theme="dark"] .shortcut-dropdown-item:hover {
        background: #374151;
    }

    .shortcut-dropdown-item .product-price {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
    }

    .shortcut-clear {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fee2e2;
        border: none;
        border-radius: 8px;
        color: #ef4444;
        cursor: pointer;
        font-size: 16px;
        flex-shrink: 0;
        transition: all 0.15s;
    }

    .shortcut-clear:hover {
        background: #ef4444;
        color: #fff;
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
    <div class="shortcuts-card card">
        <div class="card-header">
            <i class="ti ti-keyboard me-2"></i>
            اختصارات المنتجات (1 - 8)
        </div>
        <div class="card-body">
            <div class="mb-3" style="font-size:13px;color:#64748b;">
                حدد منتج لكل رقم (1-8). في الكاشير اضغط على أيقونة الاختصارات ثم اختر الرقم لإضافة المنتج مباشرة.
            </div>

            @for($i = 1; $i <= 8; $i++)
            <div class="shortcut-row">
                <div class="shortcut-number">{{ $i }}</div>
                <div class="shortcut-select-wrapper">
                    <input type="text"
                           class="shortcut-search"
                           id="search_{{ $i }}"
                           data-slot="{{ $i }}"
                           placeholder="ابحث عن منتج..."
                           value="{{ isset($products[$i]) ? $products[$i]['name'] : '' }}"
                           autocomplete="off">
                    <input type="hidden" id="product_{{ $i }}" value="{{ isset($products[$i]) ? $products[$i]['id'] : '' }}">
                    <div class="shortcut-dropdown" id="dropdown_{{ $i }}"></div>
                </div>
                <button type="button" class="shortcut-clear" onclick="clearSlot({{ $i }})" title="مسح">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            @endfor

            <button type="button" class="btn btn-primary mt-3" id="saveBtn" onclick="saveShortcuts()" style="padding: 12px 32px;">
                <span class="btn-text"><i class="ti ti-check me-1"></i> حفظ</span>
                <span class="btn-loading d-none">
                    <span class="spinner-border spinner-border-sm me-1"></span>
                    جاري الحفظ...
                </span>
            </button>
        </div>
    </div>
</div>

<div class="toast-container" id="toastContainer"></div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let searchTimers = {};

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.shortcut-search').forEach(function(input) {
        const slot = input.dataset.slot;

        input.addEventListener('input', function() {
            clearTimeout(searchTimers[slot]);
            searchTimers[slot] = setTimeout(function() {
                searchProducts(slot, input.value);
            }, 300);
        });

        input.addEventListener('focus', function() {
            if (input.value.length > 0 && !document.getElementById('product_' + slot).value) {
                searchProducts(slot, input.value);
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('dropdown_' + slot).classList.remove('show');
            }
        });
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.shortcut-select-wrapper')) {
            document.querySelectorAll('.shortcut-dropdown').forEach(function(d) {
                d.classList.remove('show');
            });
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            saveShortcuts();
        }
    });
});

async function searchProducts(slot, query) {
    if (query.length < 1) {
        document.getElementById('dropdown_' + slot).classList.remove('show');
        return;
    }

    try {
        const res = await fetch('{{ route("settings.search-products") }}?q=' + encodeURIComponent(query), {
            headers: { 'Accept': 'application/json' }
        });
        const products = await res.json();
        const dropdown = document.getElementById('dropdown_' + slot);

        if (products.length === 0) {
            dropdown.innerHTML = '<div style="padding:12px;color:#94a3b8;text-align:center;font-size:13px;">لا توجد نتائج</div>';
        } else {
            dropdown.innerHTML = products.map(function(p) {
                return '<div class="shortcut-dropdown-item" onclick="selectProduct(' + slot + ',' + p.id + ',\'' + p.name.replace(/'/g, "\\'") + '\')">' +
                    '<span>' + p.name + '</span>' +
                    '<span class="product-price">' + parseFloat(p.price).toFixed(3) + ' د.ل</span>' +
                '</div>';
            }).join('');
        }

        dropdown.classList.add('show');
    } catch (err) {
        console.error(err);
    }
}

function selectProduct(slot, id, name) {
    document.getElementById('search_' + slot).value = name;
    document.getElementById('product_' + slot).value = id;
    document.getElementById('dropdown_' + slot).classList.remove('show');
}

function clearSlot(slot) {
    document.getElementById('search_' + slot).value = '';
    document.getElementById('product_' + slot).value = '';
}

async function saveShortcuts() {
    const btn = document.getElementById('saveBtn');
    btn.querySelector('.btn-text').classList.add('d-none');
    btn.querySelector('.btn-loading').classList.remove('d-none');
    btn.disabled = true;

    const shortcuts = {};
    for (let i = 1; i <= 8; i++) {
        const val = document.getElementById('product_' + i).value;
        if (val) {
            shortcuts[i] = parseInt(val);
        }
    }

    try {
        const res = await fetch('{{ route("settings.shortcuts.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ shortcuts: shortcuts })
        });

        const result = await res.json();

        if (res.ok && result.success) {
            showToast(result.message, 'success');
        } else {
            showToast(result.message || 'حدث خطأ', 'error');
        }
    } catch (err) {
        showToast('حدث خطأ في الحفظ', 'error');
    } finally {
        btn.querySelector('.btn-text').classList.remove('d-none');
        btn.querySelector('.btn-loading').classList.add('d-none');
        btn.disabled = false;
    }
}

function showToast(message, type) {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = 'toast toast-' + type;
    toast.innerHTML = '<i class="ti ti-' + (type === 'success' ? 'check' : 'x') + ' fs-18"></i><span>' + message + '</span>';
    container.appendChild(toast);
    setTimeout(function() {
        toast.style.animation = 'slideDown 0.3s ease reverse';
        setTimeout(function() { toast.remove(); }, 300);
    }, 3000);
}
</script>
@endpush
