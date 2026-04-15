<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الكاشير - {{ config('app.name') }}</title>
    <link href="{{ asset('assets/fonts/cairo/cairo.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}">
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .app-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        .header {
            background: #fff;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 100px;
        }

        .header-left {
            display: flex;
            align-items: stretch;
            gap: 10px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 10px 14px;
            background: #f8fafc;
            border-radius: 12px;
            font-weight: 700;
            font-size: 13px;
        }

        .user-info i {
            font-size: 24px;
            color: #64748b;
        }

        .logout-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 10px 14px;
            background: #fee2e2;
            border: none;
            border-radius: 12px;
            color: #dc2626;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            font-size: 13px;
            min-width: 70px;
            position: relative;
        }

        .logout-btn i {
            font-size: 24px;
        }

        .logout-btn .shortcut-badge {
            position: absolute;
            top: -6px;
            left: -6px;
            background: #1e293b;
            color: #fff;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 6px;
            line-height: 1.3;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }

        .header-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 10px 18px;
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.2s;
            position: relative;
            min-width: 90px;
        }

        .header-btn:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .header-btn i {
            font-size: 28px;
        }

        .header-btn .shortcut-badge {
            position: absolute;
            top: -6px;
            left: -6px;
            background: #1e293b;
            color: #fff;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 6px;
            line-height: 1.3;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }

        .main-content {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 16px;
            padding: 16px;
            overflow: hidden;
            min-height: 0;
        }

        .left-panel {
            display: flex;
            flex-direction: column;
            gap: 16px;
            overflow: hidden;
            min-height: 0;
        }

        .invoice-section {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            flex-shrink: 0;
        }

        .invoice-input {
            width: 100%;
            padding: 14px 18px;
            font-size: 20px;
            font-weight: 700;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-family: inherit;
            text-align: center;
        }

        .invoice-input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .items-section {
            background: #fff;
            border-radius: 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            min-height: 0;
            position: relative;
        }

        .items-section::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            height: 400px;
            background: url('{{ asset("logo-dark.png") }}') no-repeat center center;
            background-size: contain;
            opacity: 0.06;
            pointer-events: none;
            z-index: 0;
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
            background: #fff;
        }

        .clear-all-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: #fee2e2;
            border-radius: 6px;
            color: #ef4444;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.15s;
        }

        .clear-all-btn:hover {
            background: #ef4444;
            color: #fff;
        }

        .items-title {
            font-size: 16px;
            font-weight: 700;
        }

        .items-body {
            flex: 1;
            overflow-y: auto;
            min-height: 0;
            position: relative;
            z-index: 1;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th {
            background: #f8fafc;
            padding: 10px 12px;
            text-align: right;
            font-weight: 700;
            font-size: 13px;
            color: #64748b;
            position: sticky;
            top: 0;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .items-table tr:hover {
            background: #f8fafc;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 12px;
        }

        .item-remove-btn {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            padding: 4px;
            font-size: 16px;
            opacity: 0.6;
            transition: opacity 0.15s;
        }

        .item-remove-btn:hover {
            opacity: 1;
        }

        .weight-tag {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            color: #8b5cf6;
            font-size: 11px;
            margin-left: 4px;
        }

        .footer-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            background: #fff;
            border-radius: 12px;
            flex-shrink: 0;
            gap: 16px;
        }

        .footer-hints {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .hint {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: #64748b;
        }

        .hint kbd {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: 700;
            font-family: inherit;
            color: #475569;
        }

        .footer-credit {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-credit img {
            height: 24px;
            filter: grayscale(100%);
        }

        .footer-credit span {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
        }

        .right-panel {
            display: flex;
            flex-direction: column;
            gap: 16px;
            overflow: hidden;
            min-height: 0;
        }

        .total-card {
            background: #fff;
            border-radius: 16px;
            padding: 32px 24px;
            text-align: center;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .total-label {
            font-size: 16px;
            font-weight: 600;
            color: #64748b;
        }

        .total-amount {
            font-size: 56px;
            font-weight: 900;
            color: #1e293b;
            line-height: 1;
        }

        .total-currency {
            font-size: 18px;
            font-weight: 700;
            color: #64748b;
        }

        .items-count {
            font-size: 14px;
            color: #94a3b8;
            margin-top: 8px;
        }

        .discount-badge {
            display: none;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            padding: 6px 14px;
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #92400e;
        }

        .discount-badge.visible {
            display: flex;
        }

        .discount-badge .remove-btn {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            font-size: 16px;
            padding: 0 2px;
        }

        .net-badge {
            display: none;
            font-size: 22px;
            font-weight: 800;
            color: #059669;
            margin-top: 4px;
        }

        .net-badge.visible {
            display: block;
        }

        .pay-hint {
            display: none;
            margin-top: 16px;
            padding: 10px 20px;
            background: #f0fdf4;
            border: 2px solid #86efac;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #15803d;
        }

        .pay-hint.visible {
            display: block;
        }

        .pay-hint kbd {
            background: #dcfce7;
            border: 1px solid #86efac;
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 12px;
            font-weight: 700;
            font-family: inherit;
        }

        .wizard-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .wizard-overlay.active {
            display: flex;
        }

        .wizard-card {
            background: #fff;
            border-radius: 20px;
            padding: 36px;
            width: 520px;
            max-width: 90vw;
            text-align: center;
            animation: wizardIn 0.2s ease-out;
        }

        @keyframes wizardIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .wizard-step-title {
            font-size: 18px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 8px;
        }

        .wizard-total {
            font-size: 42px;
            font-weight: 900;
            color: #1e293b;
            margin-bottom: 24px;
        }

        .wizard-total span {
            font-size: 18px;
            font-weight: 700;
            color: #64748b;
        }

        .wizard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 24px;
        }

        .wizard-option {
            padding: 16px 12px;
            border: 3px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s;
            background: #f8fafc;
            color: #475569;
        }

        .wizard-option.selected {
            border-color: #3b82f6;
            background: #3b82f6;
            color: #fff;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .wizard-option.credit-option {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-color: #f59e0b;
            color: #92400e;
        }

        .wizard-option.credit-option.selected {
            background: linear-gradient(135deg, #f97316, #ea580c);
            border-color: #ea580c;
            color: #fff;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        }

        .wizard-delivery-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .wizard-delivery-option {
            padding: 28px 16px;
            border: 3px solid #e2e8f0;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.15s;
            background: #f8fafc;
        }

        .wizard-delivery-option i {
            font-size: 36px;
            display: block;
            margin-bottom: 8px;
            color: #94a3b8;
        }

        .wizard-delivery-option .delivery-label {
            font-size: 18px;
            font-weight: 700;
            color: #475569;
        }

        .wizard-delivery-option.selected {
            border-color: #10b981;
            background: #f0fdf4;
            transform: scale(1.03);
        }

        .wizard-delivery-option.selected i {
            color: #10b981;
        }

        .wizard-delivery-option.selected .delivery-label {
            color: #059669;
        }

        .wizard-phone-input {
            width: 100%;
            padding: 16px;
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            border: 3px solid #e2e8f0;
            border-radius: 12px;
            font-family: inherit;
            margin-bottom: 24px;
        }

        .wizard-phone-input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .wizard-hint {
            font-size: 13px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
        }

        .wizard-hint kbd {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 700;
            font-family: inherit;
            color: #475569;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            width: 100%;
            max-width: 360px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border: none;
            background: #f1f5f9;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #64748b;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: #f59e0b;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
        }

        .modal-btn {
            flex: 1;
            padding: 12px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            border: none;
        }

        .modal-btn-cancel {
            background: #f1f5f9;
            color: #64748b;
        }

        .modal-btn-confirm {
            background: #f59e0b;
            color: #fff;
        }

        .hidden {
            display: none !important;
        }

        .swal2-popup.swal-rtl {
            font-family: 'Cairo', sans-serif !important;
            direction: rtl !important;
            border-radius: 16px !important;
            padding: 24px !important;
            border: 2px solid #e2e8f0 !important;
        }

        .swal2-popup .swal-title-rtl {
            font-family: 'Cairo', sans-serif !important;
            font-size: 20px !important;
            font-weight: 700 !important;
            line-height: 1.6 !important;
        }

        .swal2-popup .swal2-html-container {
            font-family: 'Cairo', sans-serif !important;
            margin: 16px 0 !important;
        }

        .swal2-actions {
            flex-direction: row-reverse !important;
            gap: 12px !important;
            margin-top: 16px !important;
        }

        .swal2-confirm, .swal2-cancel {
            font-family: 'Cairo', sans-serif !important;
            font-weight: 600 !important;
            font-size: 15px !important;
            padding: 10px 24px !important;
            border-radius: 8px !important;
            min-width: 100px !important;
        }

        .swal2-icon {
            margin: 0 auto 16px !important;
        }

        .swal2-container {
            z-index: 99999 !important;
        }

        .swal2-backdrop-show {
            background: transparent !important;
        }

        .shortcuts-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .shortcuts-modal.active {
            display: flex;
        }

        .shortcuts-modal-content {
            background: #fff;
            border-radius: 16px;
            padding: 28px;
            width: 100%;
            max-width: 420px;
            animation: wizardIn 0.2s ease-out;
        }

        .shortcuts-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .shortcuts-modal-title {
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .shortcuts-modal-title i {
            color: #3b82f6;
            font-size: 22px;
        }

        .shortcuts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .shortcut-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.15s;
            background: #f8fafc;
        }

        .shortcut-item:hover {
            border-color: #3b82f6;
            background: #eff6ff;
            transform: scale(1.02);
        }

        .shortcut-item.empty {
            opacity: 0.4;
            cursor: default;
        }

        .shortcut-item.empty:hover {
            border-color: #e2e8f0;
            background: #f8fafc;
            transform: none;
        }

        .shortcut-item-number {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #3b82f6;
            color: #fff;
            font-weight: 800;
            font-size: 16px;
            border-radius: 8px;
            flex-shrink: 0;
        }

        .shortcut-item.empty .shortcut-item-number {
            background: #94a3b8;
        }

        .shortcut-item-name {
            flex: 1;
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .shortcut-item.empty .shortcut-item-name {
            color: #94a3b8;
        }

        .shortcuts-hint {
            margin-top: 16px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }

        .shortcuts-hint kbd {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 11px;
            font-weight: 700;
            font-family: inherit;
            color: #475569;
        }

        .hold-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.15s;
            flex-shrink: 0;
        }

        .hold-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245,158,11,0.4);
        }

        .hold-btn i {
            font-size: 20px;
        }

        .held-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.15s;
            background: #f8fafc;
        }

        .held-item:hover {
            border-color: #f59e0b;
            background: #fffbeb;
        }

        .held-item-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .held-item-total {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
        }

        .held-item-meta {
            font-size: 11px;
            color: #94a3b8;
        }

        .held-item-actions {
            display: flex;
            gap: 6px;
        }

        .held-item-delete {
            width: 30px;
            height: 30px;
            border: none;
            background: #fee2e2;
            border-radius: 6px;
            color: #ef4444;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .held-item-delete:hover {
            background: #ef4444;
            color: #fff;
        }

        .held-products-list {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .held-product-tag {
            display: inline-block;
            padding: 2px 8px;
            background: #e0f2fe;
            color: #0369a1;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .held-empty {
            text-align: center;
            padding: 24px;
            color: #94a3b8;
            font-size: 14px;
        }

        .numpad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .numpad-btn {
            padding: 14px;
            font-size: 20px;
            font-weight: 700;
            font-family: inherit;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: #fff;
            color: #1e293b;
            cursor: pointer;
            transition: all 0.1s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .numpad-btn:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .numpad-btn:active {
            background: #e2e8f0;
            transform: scale(0.96);
        }

        .numpad-dot {
            font-size: 26px;
            font-weight: 900;
        }

        .numpad-back {
            color: #f97316;
            font-size: 22px;
        }

        .numpad-clear {
            background: #fee2e2;
            color: #ef4444;
            border-color: #fecaca;
        }

        .numpad-clear:hover {
            background: #ef4444;
            color: #fff;
        }

        .numpad-confirm {
            grid-column: span 2;
            background: #10b981;
            color: #fff;
            border-color: #10b981;
            font-size: 18px;
            gap: 6px;
        }

        .numpad-confirm:hover {
            background: #059669;
            border-color: #059669;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="header">
            <div class="logo"><img src="{{ asset('logo-dark.png') }}" alt="Taj Alsultan"></div>
            <div class="header-left">
                <button type="button" class="logout-btn" onclick="location.reload()" style="background:#eff6ff;color:#3b82f6;" title="إعادة تحميل الصفحة">
                    <span class="shortcut-badge">F1</span>
                    <i class="ti ti-reload"></i>
                    تحديث
                </button>
                <button type="button" class="header-btn" style="background:linear-gradient(135deg,#06b6d4,#0891b2);" onclick="openTodayOrdersModal()">
                    <span class="shortcut-badge">F2</span>
                    <i class="ti ti-receipt-2"></i>
                    فواتير اليوم
                </button>
                <button type="button" class="header-btn" style="background:linear-gradient(135deg,#ef4444,#dc2626);" onclick="showCancelInvoiceModal()">
                    <span class="shortcut-badge">F3</span>
                    <i class="ti ti-file-x"></i>
                    إلغاء فاتورة
                </button>
                <a href="{{ route('cashier.customers') }}" class="header-btn" style="background:linear-gradient(135deg,#f97316,#ea580c);" id="btnCustomers">
                    <span class="shortcut-badge">F5</span>
                    <i class="ti ti-users"></i>
                    الزبائن والديون
                </a>
                <a href="{{ route('cashier.deliveries') }}" class="header-btn" style="background:linear-gradient(135deg,#10b981,#059669);" id="btnDeliveries">
                    <span class="shortcut-badge">F6</span>
                    <i class="ti ti-truck-delivery"></i>
                    التوصيل
                </a>
                <a href="{{ route('cashier.special-orders') }}" class="header-btn" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);" id="btnSpecialOrders">
                    <span class="shortcut-badge">F7</span>
                    <i class="ti ti-cake"></i>
                    طلبيات خاصة
                </a>
                <button type="button" class="header-btn" style="background:linear-gradient(135deg,#64748b,#475569);position:relative;" onclick="openPendingModal()" id="btnPending">
                    <span class="shortcut-badge">F8</span>
                    <i class="ti ti-clock-pause"></i>
                    غير مسددة
                    <span id="pendingCount" style="display:none;position:absolute;top:-8px;right:-8px;background:#ef4444;color:#fff;font-size:11px;font-weight:800;min-width:20px;height:20px;border-radius:10px;display:flex;align-items:center;justify-content:center;padding:0 5px;">0</span>
                </button>
                <button type="button" class="header-btn" style="background:linear-gradient(135deg,#0ea5e9,#0284c7);" onclick="openShortcutsModal()">
                    <span class="shortcut-badge">F9</span>
                    <i class="ti ti-keyboard"></i>
                    الاختصارات
                </button>
                <button type="button" class="header-btn" style="background:linear-gradient(135deg,#f59e0b,#d97706);position:relative;" onclick="openHeldModal()" id="btnHeld">
                    <span class="shortcut-badge">F10</span>
                    <i class="ti ti-player-pause"></i>
                    معلقة
                    <span id="heldCount" style="display:none;position:absolute;top:-8px;right:-8px;background:#ef4444;color:#fff;font-size:11px;font-weight:800;min-width:20px;height:20px;border-radius:10px;display:flex;align-items:center;justify-content:center;padding:0 5px;">0</span>
                </button>
                <div class="user-info">
                    <i class="ti ti-user"></i>
                    {{ auth()->user()->name }}
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="ti ti-logout"></i>
                        خروج
                    </button>
                </form>
            </div>
        </div>

        <div class="main-content">
            <div class="left-panel">
                <div class="invoice-section">
                    <input type="text" class="invoice-input" id="invoiceInput" placeholder="رقم الفاتورة أو الباركود" autofocus>
                </div>

                <div class="items-section">
                    <div class="items-header">
                        <div class="items-title">الأصناف</div>
                        <button type="button" class="clear-all-btn" id="clearAllBtn" onclick="cancelAllItems()" title="تصفير (Esc)">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="items-body">
                        <div class="empty-state" id="emptyState">
                            <i class="ti ti-receipt-off"></i>
                            <span>لا توجد أصناف</span>
                        </div>
                        <table class="items-table hidden" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width:30px"></th>
                                    <th>الصنف</th>
                                    <th style="text-align:center">الكمية</th>
                                    <th style="text-align:center">السعر</th>
                                    <th style="text-align:left">الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody"></tbody>
                        </table>
                    </div>
                </div>

                <div class="footer-section">
                    <div class="footer-hints">
                        <div class="hint"><kbd>F2</kbd> فواتير اليوم</div>
                        <div class="hint"><kbd>F4</kbd> خصم</div>
                        <div class="hint"><kbd>F8</kbd> غير مسددة</div>
                        <div class="hint"><kbd>F9</kbd> اختصارات</div>
                        <div class="hint"><kbd>F10</kbd> تعليق</div>
                        <div class="hint"><kbd>Enter</kbd> دفع</div>
                        <div class="hint"><kbd>Esc</kbd> إلغاء</div>
                    </div>
                    <div class="footer-credit">
                        <img src="{{ asset('hulul.jpg') }}" alt="Hulul">
                        <span>حلول لتقنية المعلومات</span>
                    </div>
                </div>
            </div>

            <div class="right-panel">
                <div class="total-card">
                    <div class="total-label">الإجمالي</div>
                    <div class="total-amount" id="totalDisplay">0.000</div>
                    <div class="total-currency">د.ل</div>
                    <div class="items-count" id="itemsCount">0 أصناف</div>
                    <div class="discount-badge" id="discountBadge">
                        <i class="ti ti-discount-2"></i>
                        <span>خصم: <span id="discountDisplay">0.000</span></span>
                        <button class="remove-btn" onclick="removeDiscount()"><i class="ti ti-x"></i></button>
                    </div>
                    <div class="net-badge" id="netBadge">الصافي: <span id="netDisplay">0.000</span> د.ل</div>
                    <div class="pay-hint" id="payHint">
                        اضغط <kbd>Enter</kbd> للدفع
                    </div>
                </div>
                <button type="button" class="hold-btn" onclick="holdCurrentInvoice()" id="holdBtn">
                    <i class="ti ti-player-pause"></i>
                    تعليق الفاتورة
                    <span style="font-size:11px;opacity:0.8;">F10</span>
                </button>
            </div>
        </div>
    </div>

    <div class="wizard-overlay" id="wizardOverlay">
        <div class="wizard-card">
            <div id="wizardContent"></div>
        </div>
    </div>

    <div class="modal" id="discountModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">إضافة خصم</div>
                <button class="modal-close" id="closeDiscountModal"><i class="ti ti-x"></i></button>
            </div>
            <div class="form-group">
                <label class="form-label">مبلغ الخصم (حد أقصى 5 د.ل)</label>
                <input type="number" class="form-input" id="discountInput" step="0.001" min="0" max="5" placeholder="0.000">
            </div>
            <div class="modal-actions">
                <button class="modal-btn modal-btn-cancel" id="cancelDiscountModal">إلغاء</button>
                <button class="modal-btn modal-btn-confirm" id="confirmDiscount">تطبيق</button>
            </div>
        </div>
    </div>

    <div class="shortcuts-modal" id="shortcutsModal">
        <div class="shortcuts-modal-content">
            <div class="shortcuts-modal-header">
                <div class="shortcuts-modal-title">
                    <i class="ti ti-keyboard"></i>
                    الاختصارات
                </div>
                <button class="modal-close" onclick="closeShortcutsModal()"><i class="ti ti-x"></i></button>
            </div>
            <div id="shortcutsStep1">
                <div class="shortcuts-grid" id="shortcutsGrid"></div>
                <div class="shortcuts-hint">
                    اضغط رقم <kbd>1</kbd> - <kbd>8</kbd> لاختيار المنتج &bull; <kbd>Esc</kbd> للإغلاق
                </div>
            </div>
            <div id="shortcutsStep2" style="display:none;">
                <div style="text-align:center;margin-bottom:12px;">
                    <div style="font-size:16px;font-weight:700;color:#1e293b;" id="shortcutSelectedName"></div>
                    <div style="font-size:13px;color:#64748b;margin-top:4px;" id="shortcutSelectedPrice"></div>
                </div>
                <div style="margin-bottom:12px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#64748b;margin-bottom:6px;text-align:center;" id="shortcutQtyLabel">الكمية</label>
                    <input type="text" id="shortcutQtyInput" readonly style="width:100%;padding:14px;font-size:26px;font-weight:800;text-align:center;border:2px solid #e2e8f0;border-radius:10px;font-family:inherit;background:#f8fafc;color:#1e293b;cursor:default;" placeholder="0">
                </div>
                <div class="numpad">
                    <button type="button" class="numpad-btn" onclick="numpadPress('1')">1</button>
                    <button type="button" class="numpad-btn" onclick="numpadPress('2')">2</button>
                    <button type="button" class="numpad-btn" onclick="numpadPress('3')">3</button>
                    <button type="button" class="numpad-btn" onclick="numpadPress('4')">4</button>
                    <button type="button" class="numpad-btn" onclick="numpadPress('5')">5</button>
                    <button type="button" class="numpad-btn" onclick="numpadPress('6')">6</button>
                    <button type="button" class="numpad-btn" onclick="numpadPress('7')">7</button>
                    <button type="button" class="numpad-btn" onclick="numpadPress('8')">8</button>
                    <button type="button" class="numpad-btn" onclick="numpadPress('9')">9</button>
                    <button type="button" class="numpad-btn numpad-dot" onclick="numpadPress('.')">.</button>
                    <button type="button" class="numpad-btn" onclick="numpadPress('0')">0</button>
                    <button type="button" class="numpad-btn numpad-back" onclick="numpadBack()"><i class="ti ti-backspace"></i></button>
                    <button type="button" class="numpad-btn numpad-clear" onclick="numpadClear()">C</button>
                    <button type="button" class="numpad-btn numpad-confirm" onclick="confirmShortcutAdd()"><i class="ti ti-check"></i> إضافة</button>
                </div>
                <div class="shortcuts-hint" style="margin-top:10px;">
                    <kbd>Esc</kbd> رجوع
                </div>
            </div>
        </div>
    </div>

    <div class="shortcuts-modal" id="heldModal">
        <div class="shortcuts-modal-content" style="max-width:460px;">
            <div class="shortcuts-modal-header">
                <div class="shortcuts-modal-title">
                    <i class="ti ti-player-pause" style="color:#f59e0b;"></i>
                    الفواتير المعلقة
                </div>
                <button class="modal-close" onclick="closeHeldModal()"><i class="ti ti-x"></i></button>
            </div>
            <div id="heldList"></div>
            <div class="shortcuts-hint" style="margin-top:12px;">
                <kbd>F10</kbd> تعليق الحالية &bull; <kbd>Esc</kbd> إغلاق
            </div>
        </div>
    </div>

    <div class="shortcuts-modal" id="todayOrdersModal">
        <div class="shortcuts-modal-content" style="max-width:560px;">
            <div class="shortcuts-modal-header">
                <div class="shortcuts-modal-title">
                    <i class="ti ti-receipt-2" style="color:#06b6d4;"></i>
                    فواتير اليوم
                </div>
                <button class="modal-close" onclick="closeTodayOrdersModal()"><i class="ti ti-x"></i></button>
            </div>
            <div id="todayOrdersList" style="max-height:450px;overflow-y:auto;"></div>
            <div class="shortcuts-hint" style="margin-top:12px;">
                انقر على فاتورة لإعادة طباعتها &bull; <kbd>Esc</kbd> إغلاق
            </div>
        </div>
    </div>

    <div class="shortcuts-modal" id="pendingModal">
        <div class="shortcuts-modal-content" style="max-width:520px;">
            <div class="shortcuts-modal-header">
                <div class="shortcuts-modal-title">
                    <i class="ti ti-clock-pause" style="color:#64748b;"></i>
                    فواتير غير مسددة
                </div>
                <button class="modal-close" onclick="closePendingModal()"><i class="ti ti-x"></i></button>
            </div>
            <div id="pendingList" style="max-height:400px;overflow-y:auto;"></div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px;">
                <div class="shortcuts-hint">انقر على فاتورة لفتحها &bull; <kbd>Esc</kbd> إغلاق</div>
                <button id="deleteAllPendingBtn" onclick="deleteAllPendingOrders()" style="display:none;background:#fee2e2;border:none;border-radius:8px;padding:6px 14px;color:#dc2626;font-family:inherit;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;"><i class="ti ti-trash"></i> حذف الكل</button>
            </div>
        </div>
    </div>

    <script>
        const BASE_URL = "{{ url('/') }}";
        const WEIGHT_PREFIX = '99';
        const MAX_DISCOUNT = 5;
        const PAYMENT_METHODS = [
            @foreach($paymentMethods as $method)
            { id: {{ $method->id }}, name: "{{ $method->name }}", isCredit: false },
            @endforeach
            { id: 'credit', name: 'آجل', isCredit: true }
        ];

        const PRODUCT_SHORTCUTS = {
            @foreach($shortcuts as $slot => $product)
            {{ $slot }}: { id: {{ $product['id'] }}, name: "{{ $product['name'] }}", price: {{ $product['price'] }}, barcode: "{{ $product['barcode'] }}", is_weight: {{ $product['is_weight'] ? 'true' : 'false' }} },
            @endforeach
        };

        let shortcutsModalActive = false;
        let shortcutStep = 1;
        let shortcutSelectedSlot = null;
        let currentOrder = null;
        let directItems = [];
        let isDirectMode = false;
        let discount = 0;
        let mergedOrderIds = [];
        let selectedCustomer = null;

        let wizardActive = false;
        let wizardStep = 0;
        let wizardMethodIndex = 0;
        let wizardDeliveryIndex = 0;
        let wizardSelectedMethod = null;
        let wizardPayments = [];
        let creditMode = false;
        let creditSearchResults = [];
        let creditResultIndex = -1;
        let creditShowNewForm = false;
        let creditPaidAmount = 0;
        let creditSearchQuery = '';
        let processingPayment = false;
        let todayOrdersData = [];
        let todayOrderIndex = -1;
        let pendingOrdersData = [];
        let pendingOrderIndex = -1;

        document.addEventListener('DOMContentLoaded', init);

        function init() {
            document.getElementById('invoiceInput').addEventListener('keydown', handleInvoiceInput);
            document.getElementById('closeDiscountModal').addEventListener('click', closeDiscountModal);
            document.getElementById('cancelDiscountModal').addEventListener('click', closeDiscountModal);
            document.getElementById('confirmDiscount').addEventListener('click', applyDiscount);
            document.getElementById('discountInput').addEventListener('keydown', e => {
                if (e.key === 'Enter') applyDiscount();
            });

            document.addEventListener('keydown', handleGlobalKeys);
            renderShortcutsGrid();
            updateHeldCount();
            loadPendingCount();
        }

        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        function handleGlobalKeys(e) {
            if (wizardActive) {
                handleWizardKeys(e);
                return;
            }

            if (shortcutsModalActive) {
                handleShortcutsKeys(e);
                return;
            }

            if (document.getElementById('todayOrdersModal').classList.contains('active')) {
                if (e.key === 'Escape') { e.preventDefault(); closeTodayOrdersModal(); return; }
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (todayOrdersData.length > 0) {
                        todayOrderIndex = Math.min(todayOrderIndex + 1, todayOrdersData.length - 1);
                        highlightTodayOrder();
                    }
                    return;
                }
                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (todayOrdersData.length > 0) {
                        todayOrderIndex = Math.max(todayOrderIndex - 1, 0);
                        highlightTodayOrder();
                    }
                    return;
                }
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (todayOrderIndex >= 0 && todayOrderIndex < todayOrdersData.length) {
                        reprintOrder(todayOrdersData[todayOrderIndex].order_number);
                    }
                    return;
                }
                return;
            }

            if (document.getElementById('pendingModal').classList.contains('active')) {
                if (e.key === 'Escape') { e.preventDefault(); closePendingModal(); return; }
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (pendingOrdersData.length > 0) {
                        pendingOrderIndex = Math.min(pendingOrderIndex + 1, pendingOrdersData.length - 1);
                        renderPendingList();
                    }
                    return;
                }
                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (pendingOrdersData.length > 0) {
                        pendingOrderIndex = Math.max(pendingOrderIndex - 1, 0);
                        renderPendingList();
                    }
                    return;
                }
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (pendingOrderIndex >= 0 && pendingOrderIndex < pendingOrdersData.length) {
                        loadPendingOrder(pendingOrdersData[pendingOrderIndex].order_number);
                    }
                    return;
                }
                return;
            }

            if (e.key === 'F1') {
                e.preventDefault();
                location.reload();
            }
            if (e.key === 'F2') {
                e.preventDefault();
                openTodayOrdersModal();
            }
            if (e.key === 'F3') {
                e.preventDefault();
                showCancelInvoiceModal();
            }
            if (e.key === 'F4') {
                e.preventDefault();
                openDiscountModal();
            }
            if (e.key === 'F5') {
                e.preventDefault();
                window.location.href = document.getElementById('btnCustomers').href;
            }
            if (e.key === 'F6') {
                e.preventDefault();
                window.location.href = document.getElementById('btnDeliveries').href;
            }
            if (e.key === 'F7') {
                e.preventDefault();
                window.location.href = document.getElementById('btnSpecialOrders').href;
            }
            if (e.key === 'F8') {
                e.preventDefault();
                openPendingModal();
            }
            if (e.key === 'F9') {
                e.preventDefault();
                openShortcutsModal();
            }
            if (e.key === 'F10') {
                e.preventDefault();
                if (hasItems()) {
                    holdCurrentInvoice();
                } else {
                    openHeldModal();
                }
            }
            if (e.key === 'Escape') {
                if (document.getElementById('todayOrdersModal').classList.contains('active')) {
                    closeTodayOrdersModal(); return;
                } else if (document.getElementById('pendingModal').classList.contains('active')) {
                    closePendingModal();
                } else if (document.getElementById('heldModal').classList.contains('active')) {
                    closeHeldModal();
                } else if (document.getElementById('discountModal').classList.contains('active')) {
                    closeDiscountModal();
                } else if (hasItems()) {
                    cancelAllItems();
                }
            }
        }

        function hasItems() {
            return isDirectMode ? directItems.length > 0 : (currentOrder && currentOrder.items && currentOrder.items.length > 0);
        }

        async function cancelAllItems() {
            if (!hasItems()) return;

            const confirm = await Swal.fire({
                title: '<i class="ti ti-trash" style="color:#ef4444;font-size:28px;"></i><br>إلغاء الأصناف',
                html: '<div style="font-family:Cairo,sans-serif;direction:rtl;">هل تريد إلغاء جميع الأصناف؟</div>',
                showCancelButton: true,
                confirmButtonText: '<i class="ti ti-x"></i> نعم، إلغاء',
                cancelButtonText: 'لا',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                customClass: { popup: 'swal-rtl', title: 'swal-title-rtl' }
            });

            if (!confirm.isConfirmed) return;
            resetAll();
            toast('تم إلغاء الأصناف', 'success');
        }

        function renderShortcutsGrid() {
            const grid = document.getElementById('shortcutsGrid');
            let html = '';
            for (let i = 1; i <= 8; i++) {
                const sc = PRODUCT_SHORTCUTS[i];
                if (sc) {
                    const typeLabel = sc.is_weight ? '<span style="color:#8b5cf6;font-size:11px;margin-right:4px;">⚖</span>' : '';
                    html += '<div class="shortcut-item" onclick="selectShortcut(' + i + ')">' +
                        '<div class="shortcut-item-number">' + i + '</div>' +
                        '<div class="shortcut-item-name">' + typeLabel + sc.name + '</div>' +
                    '</div>';
                } else {
                    html += '<div class="shortcut-item empty">' +
                        '<div class="shortcut-item-number">' + i + '</div>' +
                        '<div class="shortcut-item-name">— فارغ —</div>' +
                    '</div>';
                }
            }
            grid.innerHTML = html;
        }

        function openShortcutsModal() {
            shortcutsModalActive = true;
            shortcutStep = 1;
            shortcutSelectedSlot = null;
            document.getElementById('shortcutsStep1').style.display = '';
            document.getElementById('shortcutsStep2').style.display = 'none';
            document.getElementById('shortcutsModal').classList.add('active');
        }

        function closeShortcutsModal() {
            shortcutsModalActive = false;
            shortcutStep = 1;
            shortcutSelectedSlot = null;
            document.getElementById('shortcutsModal').classList.remove('active');
            document.getElementById('invoiceInput').focus();
        }

        function selectShortcut(num) {
            const sc = PRODUCT_SHORTCUTS[num];
            if (!sc) {
                toast('لا يوجد منتج لهذا الاختصار', 'error');
                return;
            }

            shortcutSelectedSlot = num;
            shortcutStep = 2;

            document.getElementById('shortcutSelectedName').textContent = sc.name;
            document.getElementById('shortcutSelectedPrice').textContent = parseFloat(sc.price).toFixed(3) + ' د.ل';
            document.getElementById('shortcutQtyLabel').textContent = sc.is_weight ? 'الوزن (كجم)' : 'الكمية';
            document.getElementById('shortcutQtyInput').value = '';
            document.getElementById('shortcutQtyInput').placeholder = sc.is_weight ? '0.000' : '1';

            document.getElementById('shortcutsStep1').style.display = 'none';
            document.getElementById('shortcutsStep2').style.display = '';
        }

        function numpadPress(char) {
            const input = document.getElementById('shortcutQtyInput');
            let val = input.value;
            if (char === '.' && val.includes('.')) return;
            input.value = val + char;
        }

        function numpadBack() {
            const input = document.getElementById('shortcutQtyInput');
            input.value = input.value.slice(0, -1);
        }

        function numpadClear() {
            document.getElementById('shortcutQtyInput').value = '';
        }

        function handleShortcutsKeys(e) {
            if (e.key === 'Escape') {
                e.preventDefault();
                if (shortcutStep === 2) {
                    shortcutStep = 1;
                    shortcutSelectedSlot = null;
                    document.getElementById('shortcutsStep1').style.display = '';
                    document.getElementById('shortcutsStep2').style.display = 'none';
                } else {
                    closeShortcutsModal();
                }
                return;
            }

            if (shortcutStep === 1) {
                const num = parseInt(e.key);
                if (num >= 1 && num <= 8) {
                    e.preventDefault();
                    selectShortcut(num);
                }
            }

            if (shortcutStep === 2) {
                e.preventDefault();
                if (e.key === 'Enter') {
                    confirmShortcutAdd();
                } else if (e.key >= '0' && e.key <= '9') {
                    numpadPress(e.key);
                } else if (e.key === '.' || e.key === ',') {
                    numpadPress('.');
                } else if (e.key === 'Backspace') {
                    numpadBack();
                } else if (e.key === 'Delete') {
                    numpadClear();
                }
            }
        }

        async function confirmShortcutAdd() {
            const sc = PRODUCT_SHORTCUTS[shortcutSelectedSlot];
            if (!sc) return;

            const qtyInput = document.getElementById('shortcutQtyInput');
            const qty = parseFloat(qtyInput.value);

            if (!qty || qty <= 0) {
                toast(sc.is_weight ? 'أدخل الوزن' : 'أدخل الكمية', 'error');
                qtyInput.focus();
                return;
            }

            closeShortcutsModal();

            if (currentOrder) {
                const res = await fetch(BASE_URL + '/cashier/add-item-to-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        order_id: currentOrder.id,
                        product_id: sc.id,
                        quantity: qty
                    })
                });
                const data = await res.json();
                if (data.success) {
                    currentOrder = data.data.order;
                    renderOrder();
                    toast('تم إضافة ' + sc.name, 'success');
                } else {
                    toast(data.message, 'error');
                }
            } else {
                if (!isDirectMode) {
                    isDirectMode = true;
                    directItems = [];
                }
                const existing = directItems.find(i => i.product_id === sc.id && !sc.is_weight);
                if (existing) {
                    existing.quantity = parseFloat(existing.quantity) + qty;
                    existing.total = existing.price * existing.quantity;
                } else {
                    directItems.push({
                        product_id: sc.id,
                        product_name: sc.name,
                        price: parseFloat(sc.price),
                        quantity: qty,
                        total: parseFloat(sc.price) * qty,
                        is_weight: sc.is_weight
                    });
                }
                renderItems();
                updateSummary();
                toast('تم إضافة ' + sc.name, 'success');
            }
        }

        async function handleInvoiceInput(e) {
            if (e.key !== 'Enter') return;
            if (wizardActive) return;
            const val = e.target.value.trim();

            if (!val) {
                if (hasItems()) {
                    e.preventDefault();
                    e.stopPropagation();
                    document.getElementById('invoiceInput').blur();
                    openWizard();
                }
                return;
            }

            document.getElementById('invoiceInput').value = '';

            if (val.length === 13 && val.startsWith(WEIGHT_PREFIX)) {
                await handleWeightBarcode(val);
            } else {
                const found = await handleProductBarcode(val);
                if (!found) {
                    await fetchOrder(val);
                }
            }
        }

        async function handleProductBarcode(barcode) {
            try {
                const res = await fetch(BASE_URL + '/cashier/find-by-barcode', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ barcode })
                });
                if (!res.ok) return false;
                const data = await res.json();
                if (!data.success) return false;

                const item = data.data;

                if (currentOrder) {
                    const addRes = await fetch(BASE_URL + '/cashier/add-item-to-order', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            order_id: currentOrder.id,
                            product_id: item.product_id,
                            quantity: item.quantity
                        })
                    });
                    const addData = await addRes.json();
                    if (addData.success) {
                        currentOrder = addData.data.order;
                        renderOrder();
                        toast('تم إضافة الصنف', 'success');
                    } else {
                        toast(addData.message, 'error');
                    }
                } else {
                    if (!isDirectMode) {
                        isDirectMode = true;
                        directItems = [];
                    }
                    const existing = directItems.find(i => i.product_id === item.product_id && !item.is_weight);
                    if (existing) {
                        existing.quantity = parseFloat(existing.quantity) + 1;
                        existing.total = existing.price * existing.quantity;
                    } else {
                        directItems.push(item);
                    }
                    renderItems();
                    updateSummary();
                    toast('تم إضافة الصنف', 'success');
                }
                return true;
            } catch (err) {
                return false;
            }
        }

        async function handleWeightBarcode(barcode) {
            try {
                const res = await fetch(BASE_URL + '/cashier/add-weight-barcode', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ barcode })
                });
                const data = await res.json();
                if (data.success) {
                    if (currentOrder) {
                        const addRes = await fetch(BASE_URL + '/cashier/add-weight-item', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                order_id: currentOrder.id,
                                product_id: data.data.product_id,
                                quantity: data.data.quantity
                            })
                        });
                        const addData = await addRes.json();
                        if (addData.success) {
                            currentOrder = addData.data.order;
                            renderOrder();
                            toast('تم إضافة الصنف', 'success');
                        } else {
                            toast(addData.message, 'error');
                        }
                    } else {
                        if (!isDirectMode) {
                            isDirectMode = true;
                            directItems = [];
                        }
                        directItems.push(data.data);
                        renderItems();
                        updateSummary();
                        toast('تم إضافة الصنف', 'success');
                    }
                } else {
                    toast(data.message, 'error');
                }
            } catch (err) {
                toast('خطأ في الاتصال: ' + err.message, 'error');
            }
        }

        async function fetchOrder(num) {
            try {
                const res = await fetch(BASE_URL + '/cashier/fetch-order-for-merge', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ order_number: num })
                });
                const data = await res.json();
                if (data.success) {
                    const fetchedOrder = data.data;

                    if (mergedOrderIds.includes(fetchedOrder.id)) {
                        toast('هذه الفاتورة مضافة مسبقاً', 'error');
                        return;
                    }

                    if (!isDirectMode && directItems.length === 0 && !currentOrder) {
                        isDirectMode = true;
                    }

                    mergedOrderIds.push(fetchedOrder.id);

                    fetchedOrder.items.forEach(item => {
                        directItems.push({
                            product_id: item.product_id,
                            product_name: item.product_name,
                            price: item.price,
                            quantity: item.quantity,
                            is_weight: item.is_weight,
                            total: item.total,
                        });
                    });

                    discount += parseFloat(fetchedOrder.discount) || 0;
                    if (discount > MAX_DISCOUNT) discount = MAX_DISCOUNT;

                    currentOrder = null;
                    isDirectMode = true;
                    document.getElementById('invoiceInput').disabled = false;
                    renderItems();
                    updateSummary();
                    toast('تم إضافة الفاتورة #' + fetchedOrder.order_number, 'success');
                } else {
                    toast(data.message, 'error');
                }
            } catch (err) {
                toast('خطأ في الاتصال: ' + err.message, 'error');
            }
        }

        function renderOrder() {
            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('itemsTable').classList.remove('hidden');

            const tbody = document.getElementById('itemsBody');
            tbody.innerHTML = '';
            currentOrder.items.forEach(item => {
                const qty = item.is_weight ? parseFloat(item.quantity).toFixed(3) + ' كجم' : item.quantity;
                const itemTotal = item.is_weight ? Math.floor(parseFloat(item.total)) : parseFloat(item.total).toFixed(3);
                tbody.innerHTML += `<tr>
                    <td><button class="item-remove-btn" onclick="removeOrderItem(${item.id})"><i class="ti ti-x"></i></button></td>
                    <td>${item.product_name}${item.is_weight ? '<span class="weight-tag"><i class="ti ti-scale"></i></span>' : ''}</td>
                    <td style="text-align:center">${qty}</td>
                    <td style="text-align:center">${parseFloat(item.price).toFixed(3)}</td>
                    <td style="text-align:left">${itemTotal}</td>
                </tr>`;
            });

            updateSummary();
        }

        async function removeOrderItem(itemId) {
            try {
                const res = await fetch(BASE_URL + '/cashier/remove-item', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        order_id: currentOrder.id,
                        item_id: itemId
                    })
                });
                const data = await res.json();
                if (data.success) {
                    currentOrder = data.data.order;
                    if (currentOrder.items.length === 0) {
                        resetAll();
                    } else {
                        renderOrder();
                    }
                    toast('تم حذف الصنف', 'success');
                } else {
                    toast(data.message, 'error');
                }
            } catch (err) {
                toast('خطأ في الاتصال: ' + err.message, 'error');
            }
        }

        function renderItems() {
            if (directItems.length === 0) {
                document.getElementById('emptyState').classList.remove('hidden');
                document.getElementById('itemsTable').classList.add('hidden');
                isDirectMode = false;
            } else {
                document.getElementById('emptyState').classList.add('hidden');
                document.getElementById('itemsTable').classList.remove('hidden');

                const tbody = document.getElementById('itemsBody');
                tbody.innerHTML = '';
                directItems.forEach((item, index) => {
                    const qty = item.is_weight ? parseFloat(item.quantity).toFixed(3) + ' كجم' : item.quantity;
                    const itemTotal = item.is_weight ? Math.floor(parseFloat(item.total)) : parseFloat(item.total).toFixed(3);
                    tbody.innerHTML += `<tr>
                        <td><button class="item-remove-btn" onclick="removeDirectItem(${index})"><i class="ti ti-x"></i></button></td>
                        <td>${item.product_name}${item.is_weight ? '<span class="weight-tag"><i class="ti ti-scale"></i></span>' : ''}</td>
                        <td style="text-align:center">${qty}</td>
                        <td style="text-align:center">${parseFloat(item.price).toFixed(3)}</td>
                        <td style="text-align:left">${itemTotal}</td>
                    </tr>`;
                });
            }
            updateSummary();
        }

        function removeDirectItem(index) {
            directItems.splice(index, 1);
            renderItems();
            toast('تم حذف الصنف', 'success');
        }

        function round3(n) {
            return Math.round(n * 1000) / 1000;
        }

        function getGrossTotal() {
            if (isDirectMode) {
                return round3(directItems.reduce((s, i) => s + parseFloat(i.total), 0));
            }
            return currentOrder ? parseFloat(currentOrder.total) : 0;
        }

        function getTotal() {
            return round3(Math.max(0, getGrossTotal() - discount));
        }

        function getItemsCount() {
            if (isDirectMode) return directItems.length;
            if (currentOrder && currentOrder.items) return currentOrder.items.length;
            return 0;
        }

        function updateSummary() {
            const grossTotal = getGrossTotal();
            const netTotal = getTotal();
            const count = getItemsCount();

            document.getElementById('totalDisplay').textContent = grossTotal.toFixed(3);
            document.getElementById('itemsCount').textContent = count + ' أصناف';

            const discountBadge = document.getElementById('discountBadge');
            const netBadge = document.getElementById('netBadge');
            if (discount > 0) {
                discountBadge.classList.add('visible');
                document.getElementById('discountDisplay').textContent = discount.toFixed(3);
                netBadge.classList.add('visible');
                document.getElementById('netDisplay').textContent = netTotal.toFixed(3);
            } else {
                discountBadge.classList.remove('visible');
                netBadge.classList.remove('visible');
            }

            const payHint = document.getElementById('payHint');
            if (count > 0) {
                payHint.classList.add('visible');
            } else {
                payHint.classList.remove('visible');
            }
        }

        function openWizard() {
            wizardActive = true;
            wizardStep = 1;
            wizardMethodIndex = 0;
            wizardDeliveryIndex = 0;
            wizardSelectedMethod = null;
            wizardPayments = [];
            document.getElementById('wizardOverlay').classList.add('active');
            renderWizardStep();
        }

        function closeWizard() {
            wizardActive = false;
            wizardStep = 0;
            creditMode = false;
            creditSearchResults = [];
            creditResultIndex = -1;
            creditShowNewForm = false;
            creditPaidAmount = 0;
            creditSearchQuery = '';
            selectedCustomer = null;
            document.getElementById('wizardOverlay').classList.remove('active');
            document.getElementById('invoiceInput').focus();
        }

        function getWizardPaid() {
            return round3(wizardPayments.reduce((s, p) => s + p.amount, 0));
        }

        function getWizardRemaining() {
            return round3(Math.max(0, getTotal() - getWizardPaid()));
        }

        function renderWizardStep() {
            const content = document.getElementById('wizardContent');
            const total = getTotal();
            const remaining = getWizardRemaining();

            if (wizardStep === 1) {
                let methodsHtml = '';
                PAYMENT_METHODS.forEach((m, i) => {
                    const selectedClass = i === wizardMethodIndex ? 'selected' : '';
                    const creditClass = m.isCredit ? 'credit-option' : '';
                    const icon = m.isCredit ? '<i class="ti ti-clock-dollar" style="margin-left:4px;"></i>' : '';
                    methodsHtml += `<div class="wizard-option ${selectedClass} ${creditClass}" data-index="${i}" onclick="wizardSelectMethod(${i})">${icon}${m.name}</div>`;
                });

                let paidHtml = '';
                if (wizardPayments.length > 0) {
                    let paidRows = wizardPayments.map((p, i) => `<div style="display:flex;justify-content:space-between;align-items:center;padding:6px 10px;background:#f0fdf4;border-radius:6px;margin-bottom:4px;"><span style="font-weight:600;font-size:13px;">${p.method_name}</span><span style="font-weight:700;color:#059669;">${p.amount.toFixed(3)} د.ل</span></div>`).join('');
                    paidHtml = `<div style="margin-bottom:16px;padding:12px;background:#f8fafc;border-radius:10px;border:2px solid #e2e8f0;">
                        <div style="font-size:12px;font-weight:600;color:#64748b;margin-bottom:6px;">المدفوعات السابقة</div>
                        ${paidRows}
                        <div style="display:flex;justify-content:space-between;padding:8px 10px;background:#fef2f2;border-radius:6px;margin-top:6px;font-weight:700;"><span style="color:#991b1b;">المتبقي</span><span style="color:#dc2626;">${remaining.toFixed(3)} د.ل</span></div>
                    </div>`;
                }

                const grossTotal = getGrossTotal();
                let discountHtml = '';
                if (wizardPayments.length === 0) {
                    const discountActive = discount > 0;
                    discountHtml = `<div style="display:flex;align-items:center;justify-content:center;gap:12px;margin-bottom:20px;padding:10px 16px;background:${discountActive ? '#fef3c7' : '#f8fafc'};border:2px solid ${discountActive ? '#f59e0b' : '#e2e8f0'};border-radius:10px;">
                        <span style="font-size:13px;font-weight:600;color:${discountActive ? '#92400e' : '#64748b'};"><i class="ti ti-discount-2"></i> خصم</span>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="font-size:11px;color:#94a3b8;">↓</span>
                            <span style="font-size:22px;font-weight:800;color:${discountActive ? '#f59e0b' : '#94a3b8'};min-width:60px;text-align:center;">${discount.toFixed(3)}</span>
                            <span style="font-size:11px;color:#94a3b8;">↑</span>
                        </div>
                        <span style="font-size:11px;color:#94a3b8;">حد أقصى ${MAX_DISCOUNT}</span>
                    </div>`;
                }

                content.innerHTML = `
                    <div class="wizard-step-title">طريقة الدفع</div>
                    <div class="wizard-total">${(wizardPayments.length > 0 ? remaining : total).toFixed(3)} <span>د.ل</span></div>
                    ${discountHtml}
                    ${paidHtml}
                    <div class="wizard-grid">${methodsHtml}</div>
                    <div class="wizard-hint">
                        <span><kbd>→</kbd> <kbd>←</kbd> طرق الدفع</span>
                        <span><kbd>↑</kbd> <kbd>↓</kbd> خصم</span>
                        <span><kbd>Enter</kbd> تأكيد</span>
                        <span><kbd>Esc</kbd> إلغاء</span>
                    </div>
                `;
            } else if (wizardStep === 2) {
                content.innerHTML = `
                    <div class="wizard-step-title">المبلغ - ${wizardSelectedMethod.name}</div>
                    <div class="wizard-total">${remaining.toFixed(3)} <span>د.ل</span></div>
                    <input type="number" class="wizard-phone-input" id="wizardAmountInput" step="0.001" value="${remaining.toFixed(3)}" placeholder="0.000" style="font-size:28px;">
                    <div style="font-size:12px;color:#94a3b8;margin-top:8px;margin-bottom:16px;">اضغط Enter للمبلغ الكامل أو عدّل المبلغ لتقسيم الدفع</div>
                    <div class="wizard-hint">
                        <span><kbd>Enter</kbd> تأكيد</span>
                        <span><kbd>Esc</kbd> رجوع</span>
                    </div>
                `;
                setTimeout(() => {
                    const input = document.getElementById('wizardAmountInput');
                    if (input) { input.focus(); input.select(); }
                }, 50);
            } else if (wizardStep === 3) {
                content.innerHTML = `
                    <div class="wizard-step-title">نوع الاستلام</div>
                    <div class="wizard-delivery-grid">
                        <div class="wizard-delivery-option ${wizardDeliveryIndex === 0 ? 'selected' : ''}" onclick="wizardSelectDelivery(0)">
                            <i class="ti ti-building-store"></i>
                            <div class="delivery-label">استلام</div>
                        </div>
                        <div class="wizard-delivery-option ${wizardDeliveryIndex === 1 ? 'selected' : ''}" onclick="wizardSelectDelivery(1)">
                            <i class="ti ti-truck-delivery"></i>
                            <div class="delivery-label">توصيل</div>
                        </div>
                    </div>
                    <div class="wizard-hint">
                        <span><kbd>→</kbd> <kbd>←</kbd> للتنقل</span>
                        <span><kbd>Enter</kbd> تأكيد</span>
                        <span><kbd>Esc</kbd> رجوع</span>
                    </div>
                `;
            } else if (wizardStep === 4) {
                content.innerHTML = `
                    <div class="wizard-step-title">رقم هاتف التوصيل</div>
                    <input type="text" class="wizard-phone-input" id="wizardPhoneInput" placeholder="رقم الهاتف">
                    <div class="wizard-hint">
                        <span><kbd>Enter</kbd> تأكيد وطباعة</span>
                        <span><kbd>Esc</kbd> رجوع</span>
                    </div>
                `;
                setTimeout(() => {
                    const phoneInput = document.getElementById('wizardPhoneInput');
                    if (phoneInput) phoneInput.focus();
                }, 50);
            } else if (wizardStep === 5) {
                if (creditShowNewForm) {
                    content.innerHTML = `
                        <div class="wizard-step-title"><i class="ti ti-user-plus" style="color:#f59e0b;"></i> إضافة زبون جديد</div>
                        <div style="margin:20px 0;text-align:right;">
                            <div style="margin-bottom:12px;">
                                <label style="display:block;font-size:13px;font-weight:600;color:#64748b;margin-bottom:4px;">الاسم *</label>
                                <input type="text" class="wizard-phone-input" id="wizardNewName" placeholder="اسم الزبون" style="font-size:16px;text-align:right;">
                            </div>
                            <div>
                                <label style="display:block;font-size:13px;font-weight:600;color:#64748b;margin-bottom:4px;">الهاتف</label>
                                <input type="text" class="wizard-phone-input" id="wizardNewPhone" placeholder="رقم الهاتف (اختياري)" style="font-size:16px;text-align:right;">
                            </div>
                        </div>
                        <div class="wizard-hint">
                            <span><kbd>Tab</kbd> التالي</span>
                            <span><kbd>Enter</kbd> حفظ</span>
                            <span><kbd>Esc</kbd> رجوع</span>
                        </div>
                    `;
                    setTimeout(() => {
                        const nameInput = document.getElementById('wizardNewName');
                        if (nameInput) nameInput.focus();
                    }, 50);
                } else {
                    let resultsHtml = '';
                    if (creditSearchResults.length > 0) {
                        resultsHtml = creditSearchResults.map((c, i) => {
                            const hl = i === creditResultIndex;
                            return `<div style="padding:10px;border:2px solid ${hl ? '#3b82f6' : '#e2e8f0'};background:${hl ? '#eff6ff' : '#fff'};border-radius:8px;margin-bottom:6px;cursor:pointer;text-align:right;" onclick="wizardSelectCustomer(${i})">
                                <div style="font-weight:600;">${c.name}</div>
                                <div style="font-size:12px;color:#64748b;">${c.phone || '-'}</div>
                                <div style="font-size:12px;color:${c.balance < 0 ? '#dc2626' : '#22c55e'};">الرصيد: ${parseFloat(c.balance).toFixed(3)}</div>
                            </div>`;
                        }).join('');
                    } else if (creditSearchQuery) {
                        resultsHtml = '<div style="text-align:center;color:#64748b;padding:12px;">لا توجد نتائج</div>';
                    }

                    content.innerHTML = `
                        <div class="wizard-step-title"><i class="ti ti-clock-dollar" style="color:#f97316;"></i> البيع بالآجل - اختر زبون</div>
                        <div class="wizard-total">${getTotal().toFixed(3)} <span>د.ل</span></div>
                        <input type="text" class="wizard-phone-input" id="wizardCustomerSearch" placeholder="ابحث بالاسم أو رقم الهاتف..." style="font-size:16px;text-align:right;margin-bottom:16px;" value="${creditSearchQuery}">
                        <div id="wizardCustomerResults" style="max-height:200px;overflow-y:auto;margin-bottom:16px;">${resultsHtml}</div>
                        <div class="wizard-hint">
                            <span><kbd>↑</kbd> <kbd>↓</kbd> تنقل</span>
                            <span><kbd>Enter</kbd> اختيار</span>
                            <span><kbd>Tab</kbd> زبون جديد</span>
                            <span><kbd>Esc</kbd> رجوع</span>
                        </div>
                    `;
                    setTimeout(() => {
                        const searchInput = document.getElementById('wizardCustomerSearch');
                        if (searchInput) {
                            searchInput.focus();
                            searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
                            searchInput.addEventListener('input', debounce(wizardSearchCustomers, 300));
                        }
                    }, 50);
                }
            } else if (wizardStep === 6) {
                const total = getTotal();
                content.innerHTML = `
                    <div class="wizard-step-title">المبلغ المدفوع</div>
                    <div style="background:#f0fdf4;border:2px solid #22c55e;border-radius:10px;padding:12px;margin-bottom:16px;text-align:right;">
                        <div style="font-weight:700;color:#15803d;font-size:16px;">${selectedCustomer.name}</div>
                        <div style="font-size:12px;color:#64748b;">${selectedCustomer.phone || '-'}</div>
                    </div>
                    <div style="font-size:14px;color:#64748b;margin-bottom:8px;">الإجمالي: ${total.toFixed(3)} د.ل</div>
                    <input type="number" class="wizard-phone-input" id="wizardCreditPaid" step="0.001" min="0" value="0" placeholder="0.000" style="font-size:28px;margin-bottom:12px;">
                    <div style="display:flex;justify-content:space-between;padding:12px;background:#fef2f2;border:2px solid #fecaca;border-radius:8px;margin-bottom:16px;">
                        <span style="color:#991b1b;font-weight:700;">المبلغ الآجل:</span>
                        <span style="color:#dc2626;font-weight:800;font-size:18px;" id="wizardCreditRemaining">${total.toFixed(3)} د.ل</span>
                    </div>
                    <div class="wizard-hint">
                        <span><kbd>Enter</kbd> تأكيد</span>
                        <span><kbd>Esc</kbd> رجوع</span>
                    </div>
                `;
                setTimeout(() => {
                    const input = document.getElementById('wizardCreditPaid');
                    if (input) {
                        input.focus();
                        input.select();
                        input.addEventListener('input', () => {
                            const paid = parseFloat(input.value) || 0;
                            const rem = Math.max(0, getTotal() - paid);
                            document.getElementById('wizardCreditRemaining').textContent = rem.toFixed(3) + ' د.ل';
                        });
                    }
                }, 50);
            }
        }

        function wizardSelectMethod(index) {
            wizardMethodIndex = index;
            renderWizardStep();
            confirmWizardPayment();
        }

        function wizardSelectDelivery(index) {
            wizardDeliveryIndex = index;
            renderWizardStep();
            confirmWizardDelivery();
        }

        function handleWizardKeys(e) {
            if (e.key === 'Escape') {
                e.preventDefault();
                if (wizardStep === 6) {
                    wizardStep = 5;
                    renderWizardStep();
                } else if (wizardStep === 5) {
                    if (creditShowNewForm) {
                        creditShowNewForm = false;
                        renderWizardStep();
                    } else {
                        creditMode = false;
                        creditSearchResults = [];
                        creditResultIndex = -1;
                        creditSearchQuery = '';
                        wizardStep = 1;
                        renderWizardStep();
                    }
                } else if (wizardStep === 4) {
                    wizardStep = 3;
                    wizardDeliveryIndex = 1;
                    renderWizardStep();
                } else if (wizardStep === 3) {
                    if (creditMode) {
                        wizardStep = 6;
                    } else {
                        wizardStep = 2;
                    }
                    renderWizardStep();
                } else if (wizardStep === 2) {
                    wizardStep = 1;
                    renderWizardStep();
                } else {
                    closeWizard();
                }
                return;
            }

            if (wizardStep === 1) {
                const count = PAYMENT_METHODS.length;
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    wizardMethodIndex = (wizardMethodIndex + 1) % count;
                    renderWizardStep();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    wizardMethodIndex = (wizardMethodIndex - 1 + count) % count;
                    renderWizardStep();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (wizardPayments.length === 0) {
                        const grossTotal = getGrossTotal();
                        discount = Math.min(discount + 0.25, MAX_DISCOUNT, grossTotal);
                        updateSummary();
                        renderWizardStep();
                    }
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (wizardPayments.length === 0) {
                        discount = Math.max(discount - 0.25, 0);
                        updateSummary();
                        renderWizardStep();
                    }
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    confirmWizardPayment();
                }
            } else if (wizardStep === 2) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    confirmWizardAmount();
                }
            } else if (wizardStep === 3) {
                if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                    e.preventDefault();
                    wizardDeliveryIndex = wizardDeliveryIndex === 0 ? 1 : 0;
                    renderWizardStep();
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    confirmWizardDelivery();
                }
            } else if (wizardStep === 4) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const phone = document.getElementById('wizardPhoneInput').value.trim();
                    if (!phone) {
                        toast('أدخل رقم الهاتف', 'error');
                        return;
                    }
                    processWizardPayment('delivery', phone);
                }
            } else if (wizardStep === 5) {
                if (creditShowNewForm) {
                    if (e.key === 'Enter') {
                        const focused = document.activeElement;
                        const nameInput = document.getElementById('wizardNewName');
                        const phoneInput = document.getElementById('wizardNewPhone');
                        if (focused === nameInput && nameInput.value.trim()) {
                            e.preventDefault();
                            phoneInput.focus();
                        } else if (focused === phoneInput || (focused === nameInput && nameInput.value.trim())) {
                            e.preventDefault();
                            wizardSaveNewCustomer();
                        }
                    }
                } else {
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        if (creditSearchResults.length > 0) {
                            creditResultIndex = Math.min(creditResultIndex + 1, creditSearchResults.length - 1);
                            updateCreditResultHighlight();
                        }
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        if (creditSearchResults.length > 0) {
                            creditResultIndex = Math.max(creditResultIndex - 1, 0);
                            updateCreditResultHighlight();
                        }
                    } else if (e.key === 'Enter') {
                        e.preventDefault();
                        if (creditResultIndex >= 0 && creditResultIndex < creditSearchResults.length) {
                            wizardSelectCustomer(creditResultIndex);
                        }
                    } else if (e.key === 'Tab') {
                        e.preventDefault();
                        creditShowNewForm = true;
                        renderWizardStep();
                    }
                }
            } else if (wizardStep === 6) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    creditPaidAmount = parseFloat(document.getElementById('wizardCreditPaid').value) || 0;
                    const total = getTotal();
                    if (creditPaidAmount > total) {
                        toast('المبلغ المدفوع أكبر من الإجمالي', 'error');
                        return;
                    }
                    wizardStep = 3;
                    wizardDeliveryIndex = 0;
                    renderWizardStep();
                }
            }
        }

        function confirmWizardPayment() {
            wizardSelectedMethod = PAYMENT_METHODS[wizardMethodIndex];

            if (wizardSelectedMethod.isCredit) {
                creditMode = true;
                creditSearchResults = [];
                creditResultIndex = -1;
                creditShowNewForm = false;
                creditSearchQuery = '';
                selectedCustomer = null;
                wizardStep = 5;
                renderWizardStep();
                return;
            }

            wizardStep = 2;
            renderWizardStep();
        }

        function confirmWizardAmount() {
            const input = document.getElementById('wizardAmountInput');
            const amount = parseFloat(input.value) || 0;
            const remaining = getWizardRemaining();

            if (amount <= 0) {
                toast('أدخل مبلغ صحيح', 'error');
                return;
            }
            if (amount > remaining + 0.001) {
                toast('المبلغ أكبر من المتبقي', 'error');
                return;
            }

            wizardPayments.push({
                payment_method_id: wizardSelectedMethod.id,
                method_name: wizardSelectedMethod.name,
                amount: round3(Math.min(amount, remaining))
            });

            const newRemaining = getWizardRemaining();
            if (newRemaining > 0.001) {
                wizardStep = 1;
                wizardMethodIndex = 0;
                renderWizardStep();
            } else {
                wizardStep = 3;
                wizardDeliveryIndex = 0;
                renderWizardStep();
            }
        }

        function confirmWizardDelivery() {
            if (wizardDeliveryIndex === 1) {
                wizardStep = 4;
                renderWizardStep();
            } else {
                processWizardPayment('pickup', '');
            }
        }

        async function processWizardPayment(deliveryType, deliveryPhone) {
            if (processingPayment) return;
            processingPayment = true;

            const total = getTotal();
            if (total <= 0) { processingPayment = false; return toast('لا يوجد مبلغ', 'error'); }

            const grossTotal = getGrossTotal();

            try {
                let orderId;

                if (isDirectMode) {
                    const createRes = await fetch(BASE_URL + '/cashier/new-invoice', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            items: directItems.map(i => ({ product_id: i.product_id, quantity: i.quantity, price: i.price })),
                            gross_total: grossTotal,
                            discount: discount,
                            total: total,
                            merged_order_ids: mergedOrderIds
                        })
                    });
                    const createData = await createRes.json();
                    if (!createData.success) { processingPayment = false; return toast(createData.message, 'error'); }
                    orderId = createData.data.id;
                } else {
                    orderId = currentOrder.id;
                }

                if (creditMode) {
                    const firstPaymentMethod = PAYMENT_METHODS.find(m => !m.isCredit);
                    const defaultPaymentMethodId = firstPaymentMethod ? firstPaymentMethod.id : 1;
                    const creditPayments = creditPaidAmount > 0 ? [{ payment_method_id: defaultPaymentMethodId, amount: creditPaidAmount }] : [];

                    const payRes = await fetch(BASE_URL + '/cashier/pay', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            discount: discount,
                            payments: creditPayments,
                            is_credit: true,
                            customer_id: selectedCustomer.id,
                            paid_amount: creditPaidAmount,
                            delivery_type: deliveryType,
                            delivery_phone: deliveryPhone
                        })
                    });

                    const payData = await payRes.json();
                    if (payData.success) {
                        closeWizard();
                        printCreditReceipt(payData.data);
                        toast('تم حفظ البيع بالآجل', 'success');
                        resetAll();
                    } else {
                        processingPayment = false;
                        toast(payData.message, 'error');
                    }
                } else {
                    const payRes = await fetch(BASE_URL + '/cashier/pay', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            discount: discount,
                            payments: wizardPayments.map(p => ({
                                payment_method_id: p.payment_method_id,
                                amount: p.amount
                            })),
                            delivery_type: deliveryType,
                            delivery_phone: deliveryPhone
                        })
                    });

                    const payData = await payRes.json();
                    if (payData.success) {
                        closeWizard();
                        printReceipt(payData.data);
                        toast('تم الدفع بنجاح', 'success');
                        resetAll();
                    } else {
                        processingPayment = false;
                        toast(payData.message, 'error');
                    }
                }
            } catch (err) {
                processingPayment = false;
                toast('خطأ في الاتصال: ' + err.message, 'error');
            }
        }

        function openDiscountModal() {
            document.getElementById('discountModal').classList.add('active');
            document.getElementById('discountInput').value = discount > 0 ? discount : '';
            document.getElementById('discountInput').focus();
        }

        function closeDiscountModal() {
            document.getElementById('discountModal').classList.remove('active');
            document.getElementById('invoiceInput').focus();
        }

        function applyDiscount() {
            let val = parseFloat(document.getElementById('discountInput').value) || 0;
            if (val < 0) val = 0;
            if (val > MAX_DISCOUNT) {
                toast('الحد الأقصى للخصم 5 د.ل', 'error');
                return;
            }
            const grossTotal = getGrossTotal();
            if (val > grossTotal) {
                toast('الخصم لا يمكن أن يتجاوز الإجمالي', 'error');
                return;
            }
            discount = val;
            closeDiscountModal();
            updateSummary();
            if (val > 0) {
                toast('تم تطبيق الخصم', 'success');
            }
        }

        function removeDiscount() {
            discount = 0;
            updateSummary();
        }

        async function showCancelInvoiceModal() {
            const { value: orderNumber } = await Swal.fire({
                title: 'إلغاء فاتورة بيع',
                input: 'text',
                inputLabel: 'أدخل رقم الفاتورة أو امسح الباركود',
                inputPlaceholder: 'رقم الفاتورة...',
                showCancelButton: true,
                confirmButtonText: '<i class="ti ti-search"></i> بحث',
                cancelButtonText: 'إغلاق',
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#64748b',
                customClass: { popup: 'swal-rtl', title: 'swal-title-rtl' },
                inputValidator: (value) => {
                    if (!value) return 'يرجى إدخال رقم الفاتورة';
                }
            });

            if (orderNumber) await searchAndDeleteInvoice(orderNumber);
        }

        async function searchAndDeleteInvoice(orderNumber) {
            try {
                const res = await fetch(BASE_URL + '/cashier/find-invoice', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ order_number: orderNumber })
                });
                const data = await res.json();

                if (!data.success) {
                    toast(data.message, 'error');
                    return;
                }

                const order = data.data;

                const { value: cancelCode } = await Swal.fire({
                    title: 'كود الإلغاء',
                    input: 'password',
                    inputLabel: 'أدخل كود إلغاء الفاتورة',
                    inputPlaceholder: 'كود الإلغاء...',
                    showCancelButton: true,
                    confirmButtonText: '<i class="ti ti-check"></i> تأكيد',
                    cancelButtonText: 'رجوع',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    customClass: { popup: 'swal-rtl', title: 'swal-title-rtl' },
                    inputValidator: (value) => {
                        if (!value) return 'يرجى إدخال كود الإلغاء';
                    }
                });

                if (!cancelCode) return;

                const verifyRes = await fetch(BASE_URL + '/cashier/verify-cancel-code', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ code: cancelCode })
                });
                const verifyData = await verifyRes.json();

                if (!verifyData.success) {
                    toast(verifyData.message, 'error');
                    return;
                }

                const deleteRes = await fetch(BASE_URL + '/cashier/delete-invoice/' + order.id, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const deleteData = await deleteRes.json();

                if (deleteData.success) {
                    toast(deleteData.message, 'success');
                } else {
                    toast(deleteData.message, 'error');
                }
            } catch (err) {
                toast('خطأ في الاتصال: ' + err.message, 'error');
            }
        }

        async function wizardSearchCustomers() {
            const input = document.getElementById('wizardCustomerSearch');
            if (!input) return;
            const q = input.value.trim();
            creditSearchQuery = q;
            if (!q) {
                creditSearchResults = [];
                creditResultIndex = -1;
                renderCreditResults();
                return;
            }
            try {
                const res = await fetch(BASE_URL + `/cashier/search-customers?q=${encodeURIComponent(q)}`);
                const data = await res.json();
                if (data.success) {
                    creditSearchResults = data.data;
                    creditResultIndex = creditSearchResults.length > 0 ? 0 : -1;
                    renderCreditResults();
                }
            } catch (err) {
            }
        }

        function renderCreditResults() {
            const container = document.getElementById('wizardCustomerResults');
            if (!container) return;
            if (creditSearchResults.length === 0) {
                container.innerHTML = creditSearchQuery ? '<div style="text-align:center;color:#64748b;padding:12px;">لا توجد نتائج</div>' : '';
                return;
            }
            container.innerHTML = creditSearchResults.map((c, i) => {
                const hl = i === creditResultIndex;
                return `<div style="padding:10px;border:2px solid ${hl ? '#3b82f6' : '#e2e8f0'};background:${hl ? '#eff6ff' : '#fff'};border-radius:8px;margin-bottom:6px;cursor:pointer;text-align:right;" onclick="wizardSelectCustomer(${i})">
                    <div style="font-weight:600;">${c.name}</div>
                    <div style="font-size:12px;color:#64748b;">${c.phone || '-'}</div>
                    <div style="font-size:12px;color:${c.balance < 0 ? '#dc2626' : '#22c55e'};">الرصيد: ${parseFloat(c.balance).toFixed(3)}</div>
                </div>`;
            }).join('');
        }

        function updateCreditResultHighlight() {
            const container = document.getElementById('wizardCustomerResults');
            if (!container) return;
            const items = container.children;
            for (let i = 0; i < items.length; i++) {
                if (i === creditResultIndex) {
                    items[i].style.borderColor = '#3b82f6';
                    items[i].style.background = '#eff6ff';
                } else {
                    items[i].style.borderColor = '#e2e8f0';
                    items[i].style.background = '#fff';
                }
            }
        }

        function wizardSelectCustomer(index) {
            const c = creditSearchResults[index];
            if (!c) return;
            selectedCustomer = { id: c.id, name: c.name, phone: c.phone, balance: c.balance };
            wizardStep = 6;
            renderWizardStep();
        }

        async function wizardSaveNewCustomer() {
            const name = document.getElementById('wizardNewName').value.trim();
            const phone = document.getElementById('wizardNewPhone').value.trim();

            if (!name) return toast('أدخل اسم الزبون', 'error');

            try {
                const res = await fetch(BASE_URL + '/cashier/quick-customer', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ name, phone })
                });
                const data = await res.json();
                if (data.success) {
                    selectedCustomer = { id: data.data.id, name: data.data.name, phone: data.data.phone, balance: data.data.balance };
                    toast('تم إضافة الزبون', 'success');
                    wizardStep = 6;
                    renderWizardStep();
                } else {
                    toast(data.message || 'خطأ في الحفظ', 'error');
                }
            } catch (err) {
                toast('خطأ في الاتصال: ' + err.message, 'error');
            }
        }

        function printReceipt(data) {
            const items = data.items || (isDirectMode ? directItems : currentOrder?.items) || [];
            let itemsHtml = '';
            items.forEach(i => {
                const qty = i.is_weight ? parseFloat(i.quantity).toFixed(3) + ' كجم' : i.quantity;
                const iTotal = i.is_weight ? Math.floor(parseFloat(i.total)) : parseFloat(i.total).toFixed(3);
                itemsHtml += `<tr><td>${i.product_name}</td><td style="text-align:center">${qty}</td><td style="text-align:center">${parseFloat(i.price).toFixed(3)}</td><td style="text-align:left">${iTotal}</td></tr>`;
            });

            let paymentsHtml = '';
            data.payments.forEach(p => {
                paymentsHtml += `<div class="info"><span>${p.method}</span><span>${parseFloat(p.amount).toFixed(3)} د.ل</span></div>`;
            });

            const discountVal = parseFloat(data.discount) || 0;
            const grossTotal = parseFloat(data.gross_total) || parseFloat(data.total);
            let totalsHtml = '';
            if (discountVal > 0) {
                totalsHtml = `
                    <div class="subtotal"><span>المجموع</span><span>${grossTotal.toFixed(3)} د.ل</span></div>
                    <div class="discount-box"><span>الخصم</span><span>- ${discountVal.toFixed(3)} د.ل</span></div>
                    <div class="total"><span>الصافي</span><span>${parseFloat(data.total).toFixed(3)} د.ل</span></div>
                `;
            } else {
                totalsHtml = `<div class="total"><span>الإجمالي</span><span>${parseFloat(data.total).toFixed(3)} د.ل</span></div>`;
            }

            let deliveryHtml = '';
            if (data.delivery_type === 'delivery') {
                deliveryHtml = `<div class="info"><span>نوع الطلب:</span><span>توصيل</span></div>${data.delivery_phone ? `<div class="info"><span>الهاتف:</span><span>${data.delivery_phone}</span></div>` : ''}`;
            } else if (data.delivery_type === 'pickup') {
                deliveryHtml = `<div class="info"><span>نوع الطلب:</span><span>استلام من المحل</span></div>${data.delivery_phone ? `<div class="info"><span>الهاتف:</span><span>${data.delivery_phone}</span></div>` : ''}`;
            }

            const barcodeValue = String(data.order_number).padStart(8, '0');
            const isDelivery = data.delivery_type === 'delivery';
            const copies = isDelivery ? 2 : 1;

            const receiptBody = `<div class="header">تاج السلطان</div><div class="barcode-section"><svg class="barcode-svg"></svg></div><div class="info"><span>#${data.order_number}</span><span>${data.paid_at}</span></div><div class="info"><span>الكاشير:</span><span>${data.cashier_name}</span></div>${deliveryHtml}<table><thead><tr><th>الصنف</th><th>الكمية</th><th>السعر</th><th>الإجمالي</th></tr></thead><tbody>${itemsHtml}</tbody></table>${totalsHtml}<div class="section"><div class="section-title">طرق الدفع</div>${paymentsHtml}</div><div class="thanks">شكراً لزيارتكم</div><div class="hulul-footer"><img src="{{ asset('hulul.jpg') }}" alt="Hulul"><p>حلول لتقنية المعلومات</p></div>`;

            let bodyContent = '';
            for (let c = 0; c < copies; c++) {
                bodyContent += `<div class="receipt">${receiptBody}</div>`;
            }

            const html = `<!DOCTYPE html><html dir="rtl" lang="ar"><head><meta charset="UTF-8"><link href="{{ asset('assets/fonts/cairo/cairo.css') }}" rel="stylesheet"><script src="{{ asset('js/barcode/jsbarcode.min.js') }}"><\/script><style>@page{margin:0;size:72mm auto}*{margin:0;padding:0;box-sizing:border-box}body{font-family:'Cairo',sans-serif;font-size:11px;width:72mm;margin:0 auto;padding:3mm;direction:rtl;text-align:right}.receipt{page-break-after:always}.receipt:last-child{page-break-after:auto}.header{text-align:center;font-size:15px;font-weight:800;padding:4px 0;border-bottom:1px dashed #000;margin-bottom:4px}.barcode-section{text-align:center;padding:3px 0;border-bottom:1px dashed #000;margin-bottom:4px}.barcode-svg{display:block;margin:0 auto}table{width:100%;border-collapse:collapse;margin:4px 0}th{background:#f0f0f0;padding:3px;font-size:10px;border-bottom:1px solid #000}td{padding:3px;font-size:10px;border-bottom:1px dashed #ccc}.subtotal{padding:4px 6px;display:flex;justify-content:space-between;font-size:11px;font-weight:600;border-top:1px dashed #000}.discount-box{padding:5px;margin:3px 0;display:flex;justify-content:space-between;font-size:12px;font-weight:800;border:2px dashed #000;background:#f5f5f5}.total{background:#000;color:#fff;padding:6px;margin:4px 0;display:flex;justify-content:space-between;font-size:13px;font-weight:800}.info{font-size:10px;display:flex;justify-content:space-between;padding:1px 0}.section{margin:4px 0;padding:4px 0;border-top:1px dashed #000}.section-title{font-size:10px;font-weight:700;margin-bottom:3px}.thanks{text-align:center;font-size:11px;font-weight:700;padding:5px 0;border-top:1px dashed #000}.hulul-footer{display:flex;align-items:center;justify-content:center;gap:6px;padding:4px 0;border-top:1px solid #000;margin-top:4px}.hulul-footer img{height:20px;filter:grayscale(100%) contrast(1.3)}.hulul-footer p{font-size:9px;font-weight:700;color:#000}</style></head><body>${bodyContent}<script>document.querySelectorAll(".barcode-svg").forEach(el=>JsBarcode(el,"${barcodeValue}",{format:"CODE128",width:1.5,height:30,displayValue:false,margin:2}));<\/script></body></html>`;

            const win = window.open('', '_blank', 'width=400,height=600');
            if (win) {
                win.document.write(html);
                win.document.close();
                setTimeout(() => {
                    win.focus();
                    if (window.printer && window.printer.print) {
                        window.printer.print();
                    } else {
                        win.print();
                    }
                    win.close();
                }, 250);
            }
        }

        function printCreditReceipt(data) {
            const items = data.items || (isDirectMode ? directItems : currentOrder?.items) || [];
            let itemsHtml = '';
            items.forEach(i => {
                const qty = i.is_weight ? parseFloat(i.quantity).toFixed(3) + ' كجم' : i.quantity;
                const iTotal = i.is_weight ? Math.floor(parseFloat(i.total)) : parseFloat(i.total).toFixed(3);
                itemsHtml += `<tr><td>${i.product_name}</td><td style="text-align:center">${qty}</td><td style="text-align:center">${parseFloat(i.price).toFixed(3)}</td><td style="text-align:left">${iTotal}</td></tr>`;
            });

            const discountVal = parseFloat(data.discount) || 0;
            const grossTotal = parseFloat(data.gross_total) || parseFloat(data.total);
            const creditAmount = parseFloat(data.credit_amount) || 0;
            const paidAmount = parseFloat(data.total) - creditAmount;

            let totalsHtml = '';
            if (discountVal > 0) {
                totalsHtml = `
                    <div class="subtotal"><span>المجموع</span><span>${grossTotal.toFixed(3)} د.ل</span></div>
                    <div class="discount-box"><span>الخصم</span><span>- ${discountVal.toFixed(3)} د.ل</span></div>
                    <div class="total"><span>الصافي</span><span>${parseFloat(data.total).toFixed(3)} د.ل</span></div>
                `;
            } else {
                totalsHtml = `<div class="total"><span>الإجمالي</span><span>${parseFloat(data.total).toFixed(3)} د.ل</span></div>`;
            }

            let creditHtml = '';
            if (creditAmount > 0) {
                creditHtml = `
                    <div class="credit-section">
                        <div class="credit-title"><i class="ti ti-clock-dollar"></i> بيع آجل</div>
                        <div class="credit-customer">${data.customer_name || '-'}</div>
                        <div class="credit-row"><span>المدفوع</span><span>${paidAmount.toFixed(3)} د.ل</span></div>
                        <div class="credit-row credit-amount"><span>المتبقي (آجل)</span><span>${creditAmount.toFixed(3)} د.ل</span></div>
                    </div>
                `;
            }

            let deliveryHtml = '';
            if (data.delivery_type === 'delivery') {
                deliveryHtml = `<div class="info"><span>نوع الطلب:</span><span>توصيل</span></div>${data.delivery_phone ? `<div class="info"><span>الهاتف:</span><span>${data.delivery_phone}</span></div>` : ''}`;
            } else if (data.delivery_type === 'pickup') {
                deliveryHtml = `<div class="info"><span>نوع الطلب:</span><span>استلام من المحل</span></div>${data.delivery_phone ? `<div class="info"><span>الهاتف:</span><span>${data.delivery_phone}</span></div>` : ''}`;
            }

            const barcodeValue = String(data.order_number).padStart(8, '0');

            const html = `<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
<meta charset="UTF-8">
<link href="{{ asset('assets/fonts/cairo/cairo.css') }}" rel="stylesheet">
<script src="{{ asset('js/barcode/jsbarcode.min.js') }}"><\/script>
<style>
.barcode-section{text-align:center;padding:5px 0;border-bottom:1px dashed #000;margin-bottom:8px}
.barcode-svg{display:block;margin:0 auto}
@page{margin:0;size:72mm auto}
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Cairo',sans-serif;font-size:13px;width:72mm;margin:0 auto;padding:6mm 4mm;direction:rtl;text-align:right}
.header{text-align:center;padding:8px 0;border-bottom:2px dashed #000;margin-bottom:10px}
.logo{max-width:180px;margin-bottom:6px;background:#000;padding:10px 15px;filter:invert(1) brightness(2) contrast(1.5)}
.info{display:flex;justify-content:space-between;font-size:11px;padding:2px 0}
table{width:100%;border-collapse:collapse;margin:8px 0}
th{background:#f0f0f0;padding:6px;font-size:11px;border-bottom:1px solid #000}
td{padding:6px;font-size:11px;border-bottom:1px dashed #ccc}
.subtotal{padding:8px 6px;display:flex;justify-content:space-between;font-size:13px;font-weight:600;border-top:1px dashed #000}
.discount-box{padding:8px;margin:6px 0;display:flex;justify-content:space-between;font-size:14px;font-weight:800;border:2px dashed #000;background:#f5f5f5}
.total{background:#000;color:#fff;padding:8px;margin:8px 0;display:flex;justify-content:space-between;font-size:15px;font-weight:800}
.credit-section{border:2px solid #000;padding:8px;margin:10px 0;background:#fff5f5}
.credit-title{font-size:14px;font-weight:800;text-align:center;margin-bottom:6px}
.credit-customer{text-align:center;font-weight:700;margin-bottom:6px;font-size:12px}
.credit-row{display:flex;justify-content:space-between;padding:3px 0;font-size:11px}
.credit-amount{font-weight:800;font-size:13px;border-top:1px dashed #000;padding-top:6px;margin-top:4px}
.thanks{text-align:center;font-size:14px;font-weight:700;padding:10px 0;border-top:2px dashed #000}
.hulul-footer{display:flex;align-items:center;justify-content:center;gap:8px;padding:10px 0;border-top:2px solid #000;margin-top:8px}
.hulul-footer img{height:32px}
.hulul-footer p{font-size:11px;font-weight:700;color:#000}
</style>
</head>
<body>
<div class="header">
<img src="{{ asset('logo-dark.png') }}" class="logo">
</div>
<div class="barcode-section">
<svg class="barcode-svg"></svg>
</div>
<div class="info"><span>رقم الفاتورة:</span><span>#${data.order_number}</span></div>
<div class="info"><span>التاريخ:</span><span>${data.paid_at}</span></div>
<div class="info"><span>الكاشير:</span><span>${data.cashier_name}</span></div>
${deliveryHtml}
<table>
<thead><tr><th>الصنف</th><th>الكمية</th><th>السعر</th><th>الإجمالي</th></tr></thead>
<tbody>${itemsHtml}</tbody>
</table>
${totalsHtml}
${creditHtml}
<div class="thanks">شكراً لزيارتكم</div>
<div class="hulul-footer">
<img src="{{ asset('hulul.jpg') }}">
<p>حلول لتقنية المعلومات</p>
</div>
<script>JsBarcode(".barcode-svg","${barcodeValue}",{format:"CODE128",width:1.8,height:40,displayValue:false,margin:5});<\/script>
</body>
</html>`;

            const win = window.open('', '_blank', 'width=400,height=600');
            if (win) {
                win.document.write(html);
                win.document.close();
                setTimeout(() => {
                    win.focus();
                    if (window.printer && window.printer.print) {
                        window.printer.print();
                    } else {
                        win.print();
                    }
                    win.close();
                }, 250);
            }
        }

        function holdCurrentInvoice() {
            if (!hasItems()) {
                toast('لا توجد أصناف لتعليقها', 'error');
                return;
            }

            const items = isDirectMode ? directItems : (currentOrder ? currentOrder.items : []);
            const total = items.reduce(function(sum, i) { return sum + parseFloat(i.total); }, 0);

            const held = {
                id: Date.now(),
                items: JSON.parse(JSON.stringify(items)),
                discount: discount,
                isDirectMode: isDirectMode,
                orderId: currentOrder ? currentOrder.id : null,
                orderNumber: currentOrder ? currentOrder.order_number : null,
                total: total,
                time: new Date().toLocaleTimeString('ar-LY', { hour: '2-digit', minute: '2-digit' }),
                count: items.length
            };

            const list = getHeldInvoices();
            list.push(held);
            localStorage.setItem('held_invoices', JSON.stringify(list));

            resetAll();
            updateHeldCount();
            toast('تم تعليق الفاتورة', 'success');
        }

        function getHeldInvoices() {
            try {
                return JSON.parse(localStorage.getItem('held_invoices') || '[]');
            } catch(e) {
                return [];
            }
        }

        function updateHeldCount() {
            const list = getHeldInvoices();
            const badge = document.getElementById('heldCount');
            if (list.length > 0) {
                badge.textContent = list.length;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        function openHeldModal() {
            const list = getHeldInvoices();
            const container = document.getElementById('heldList');

            if (list.length === 0) {
                container.innerHTML = '<div class="held-empty"><i class="ti ti-receipt-off" style="font-size:32px;display:block;margin-bottom:8px;"></i>لا توجد فواتير معلقة</div>';
            } else {
                container.innerHTML = list.map(function(h, idx) {
                    var itemsHtml = h.items.map(function(item) {
                        var qty = item.is_weight ? parseFloat(item.quantity).toFixed(3) + ' كجم' : parseInt(item.quantity) + 'x';
                        return '<span class="held-product-tag">' + qty + ' ' + item.product_name + '</span>';
                    }).join('');

                    return '<div class="held-item" onclick="resumeHeld(' + idx + ')">' +
                        '<div class="held-item-info" style="flex:1;min-width:0;">' +
                            '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">' +
                                '<div class="held-item-total">' + parseFloat(h.total).toFixed(3) + ' د.ل</div>' +
                                '<div class="held-item-meta">' + h.time + '</div>' +
                            '</div>' +
                            '<div class="held-products-list">' + itemsHtml + '</div>' +
                        '</div>' +
                        '<div class="held-item-actions">' +
                            '<button class="held-item-delete" onclick="event.stopPropagation();deleteHeld(' + idx + ')" title="حذف"><i class="ti ti-trash"></i></button>' +
                        '</div>' +
                    '</div>';
                }).join('');
            }

            document.getElementById('heldModal').classList.add('active');
        }

        function closeHeldModal() {
            document.getElementById('heldModal').classList.remove('active');
            document.getElementById('invoiceInput').focus();
        }

        function resumeHeld(idx) {
            const list = getHeldInvoices();
            const held = list[idx];
            if (!held) return;

            if (hasItems()) {
                holdCurrentInvoice();
            }

            isDirectMode = true;
            directItems = held.items.map(function(i) {
                return {
                    product_id: i.product_id,
                    product_name: i.product_name,
                    price: parseFloat(i.price),
                    quantity: parseFloat(i.quantity),
                    total: parseFloat(i.total),
                    is_weight: i.is_weight || false
                };
            });
            discount = held.discount || 0;

            list.splice(idx, 1);
            localStorage.setItem('held_invoices', JSON.stringify(list));

            renderItems();
            updateSummary();
            updateHeldCount();
            closeHeldModal();
            toast('تم استرجاع الفاتورة', 'success');
        }

        function deleteHeld(idx) {
            const list = getHeldInvoices();
            list.splice(idx, 1);
            localStorage.setItem('held_invoices', JSON.stringify(list));
            updateHeldCount();
            openHeldModal();
            toast('تم حذف الفاتورة المعلقة', 'success');
        }

        async function openTodayOrdersModal() {
            todayOrdersData = [];
            todayOrderIndex = -1;
            document.getElementById('todayOrdersModal').classList.add('active');
            document.getElementById('todayOrdersList').innerHTML = '<div style="text-align:center;padding:24px;"><div style="width:24px;height:24px;border:3px solid #e2e8f0;border-top-color:#3b82f6;border-radius:50%;animation:spin 0.8s linear infinite;display:inline-block;"></div></div>';

            try {
                const res = await fetch(BASE_URL + '/cashier/today-orders');
                const data = await res.json();
                const container = document.getElementById('todayOrdersList');

                if (data.success && data.data.length > 0) {
                    todayOrdersData = data.data;
                    todayOrderIndex = 0;
                    renderTodayOrdersList();
                } else {
                    container.innerHTML = '<div class="held-empty"><i class="ti ti-receipt-off" style="font-size:32px;display:block;margin-bottom:8px;"></i>لا توجد فواتير اليوم</div>';
                }
            } catch (err) {
                document.getElementById('todayOrdersList').innerHTML = '<div class="held-empty">خطأ في الاتصال</div>';
            }
        }

        function renderTodayOrdersList() {
            const container = document.getElementById('todayOrdersList');
            container.innerHTML = todayOrdersData.map(function(o, idx) {
                const isSelected = idx === todayOrderIndex;
                const deliveryIcon = o.delivery_type === 'delivery' ? '<span style="color:#10b981;font-size:11px;"><i class="ti ti-truck-delivery"></i></span>' : '';
                const discountInfo = parseFloat(o.discount) > 0 ? '<span style="color:#f59e0b;font-size:11px;">خصم ' + o.discount + '</span>' : '';
                return '<div class="held-item" id="todayOrder' + idx + '" onclick="reprintOrder(\'' + o.order_number + '\')" style="cursor:pointer;border-color:' + (isSelected ? '#06b6d4' : '#e2e8f0') + ';background:' + (isSelected ? '#ecfeff' : '#f8fafc') + ';">' +
                    '<div class="held-item-info" style="flex:1;">' +
                        '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">' +
                            '<div class="held-item-total">#' + o.order_number + ' ' + deliveryIcon + '</div>' +
                            '<div class="held-item-meta">' + o.paid_at + '</div>' +
                        '</div>' +
                        '<div style="display:flex;gap:12px;font-size:12px;color:#64748b;">' +
                            '<span><i class="ti ti-device-desktop"></i> ' + o.pos_point + '</span>' +
                            '<span><i class="ti ti-user"></i> ' + o.cashier + '</span>' +
                            '<span><i class="ti ti-box"></i> ' + o.items_count + '</span>' +
                            (discountInfo ? discountInfo : '') +
                            '<span style="font-weight:700;color:#059669;">' + o.total + ' د.ل</span>' +
                        '</div>' +
                    '</div>' +
                    '<div style="display:flex;align-items:center;color:' + (isSelected ? '#06b6d4' : '#cbd5e1') + ';font-size:20px;padding-right:8px;"><i class="ti ti-printer"></i></div>' +
                '</div>';
            }).join('');

            if (todayOrderIndex >= 0) {
                const el = document.getElementById('todayOrder' + todayOrderIndex);
                if (el) el.scrollIntoView({ block: 'nearest' });
            }
        }

        function highlightTodayOrder() {
            renderTodayOrdersList();
        }

        function closeTodayOrdersModal() {
            todayOrdersData = [];
            todayOrderIndex = -1;
            document.getElementById('todayOrdersModal').classList.remove('active');
            document.getElementById('invoiceInput').focus();
        }

        function reprintOrder(orderNumber) {
            closeTodayOrdersModal();
            window.open(BASE_URL + '/cashier/reprint/' + orderNumber, '_blank');
        }

        async function openPendingModal() {
            pendingOrdersData = [];
            pendingOrderIndex = -1;
            document.getElementById('pendingModal').classList.add('active');
            document.getElementById('pendingList').innerHTML = '<div style="text-align:center;padding:24px;"><div style="width:24px;height:24px;border:3px solid #e2e8f0;border-top-color:#3b82f6;border-radius:50%;animation:spin 0.8s linear infinite;display:inline-block;"></div></div>';

            try {
                const res = await fetch(BASE_URL + '/cashier/pending-orders');
                const data = await res.json();

                if (data.success && data.data.length > 0) {
                    const badge = document.getElementById('pendingCount');
                    badge.textContent = data.data.length;
                    badge.style.display = 'flex';
                    pendingOrdersData = data.data;
                    pendingOrderIndex = 0;
                    document.getElementById('deleteAllPendingBtn').style.display = 'flex';
                    renderPendingList();
                } else {
                    document.getElementById('pendingCount').style.display = 'none';
                    document.getElementById('deleteAllPendingBtn').style.display = 'none';
                    document.getElementById('pendingList').innerHTML = '<div class="held-empty"><i class="ti ti-checks" style="font-size:32px;display:block;margin-bottom:8px;color:#10b981;"></i>لا توجد فواتير غير مسددة</div>';
                }
            } catch (err) {
                document.getElementById('pendingList').innerHTML = '<div class="held-empty">خطأ في الاتصال</div>';
            }
        }

        function renderPendingList() {
            const container = document.getElementById('pendingList');
            container.innerHTML = pendingOrdersData.map(function(o, idx) {
                const isSelected = idx === pendingOrderIndex;
                return '<div class="held-item" id="pendingOrder' + idx + '" style="border-color:' + (isSelected ? '#64748b' : '#e2e8f0') + ';background:' + (isSelected ? '#f1f5f9' : '#f8fafc') + ';display:flex;align-items:center;gap:8px;">' +
                    '<div class="held-item-info" style="flex:1;cursor:pointer;" onclick="loadPendingOrder(\'' + o.order_number + '\')">' +
                        '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">' +
                            '<div class="held-item-total">#' + o.order_number + '</div>' +
                            '<div class="held-item-meta">' + o.date + ' ' + o.created_at + '</div>' +
                        '</div>' +
                        '<div style="display:flex;gap:12px;font-size:12px;color:#64748b;">' +
                            '<span><i class="ti ti-device-desktop"></i> ' + o.pos_point + '</span>' +
                            '<span><i class="ti ti-user"></i> ' + o.user + '</span>' +
                            '<span><i class="ti ti-box"></i> ' + o.items_count + ' أصناف</span>' +
                            '<span style="font-weight:700;color:#1e293b;">' + o.total + ' د.ل</span>' +
                        '</div>' +
                    '</div>' +
                    '<button onclick="deletePendingOrder(' + o.id + ', \'' + o.order_number + '\')" style="flex-shrink:0;background:#fee2e2;border:none;border-radius:8px;width:36px;height:36px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#dc2626;" title="حذف الفاتورة"><i class="ti ti-trash" style="font-size:18px;"></i></button>' +
                '</div>';
            }).join('');

            if (pendingOrderIndex >= 0) {
                const el = document.getElementById('pendingOrder' + pendingOrderIndex);
                if (el) el.scrollIntoView({ block: 'nearest' });
            }
        }

        async function deletePendingOrder(id, orderNumber) {
            const result = await Swal.fire({
                title: 'حذف الفاتورة',
                text: 'هل تريد حذف الفاتورة #' + orderNumber + '؟ لا يمكن التراجع.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'حذف',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                reverseButtons: true,
            });

            if (!result.isConfirmed) return;

            try {
                const res = await fetch(BASE_URL + '/cashier/delete-invoice/' + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                });
                const data = await res.json();

                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'تم الحذف', text: data.message, timer: 1500, showConfirmButton: false });
                    pendingOrdersData = pendingOrdersData.filter(function(o) { return o.id !== id; });
                    pendingOrderIndex = Math.min(pendingOrderIndex, pendingOrdersData.length - 1);
                    if (pendingOrdersData.length === 0) {
                        document.getElementById('pendingList').innerHTML = '<div class="held-empty"><i class="ti ti-checks" style="font-size:32px;display:block;margin-bottom:8px;color:#10b981;"></i>لا توجد فواتير غير مسددة</div>';
                        const badge = document.getElementById('pendingCount');
                        badge.style.display = 'none';
                    } else {
                        renderPendingList();
                        const badge = document.getElementById('pendingCount');
                        badge.textContent = pendingOrdersData.length;
                    }
                } else {
                    Swal.fire({ icon: 'error', title: 'خطأ', text: data.message });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'خطأ', text: 'تعذر الاتصال بالخادم' });
            }
        }

        async function deleteAllPendingOrders() {
            const result = await Swal.fire({
                title: 'حذف جميع الفواتير',
                text: 'هل تريد حذف جميع الفواتير غير المسددة (' + pendingOrdersData.length + ')؟ لا يمكن التراجع.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'حذف الكل',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                reverseButtons: true,
            });

            if (!result.isConfirmed) return;

            try {
                const res = await fetch(BASE_URL + '/cashier/delete-all-pending', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                });
                const data = await res.json();

                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'تم الحذف', text: data.message, timer: 1500, showConfirmButton: false });
                    pendingOrdersData = [];
                    pendingOrderIndex = -1;
                    document.getElementById('pendingList').innerHTML = '<div class="held-empty"><i class="ti ti-checks" style="font-size:32px;display:block;margin-bottom:8px;color:#10b981;"></i>لا توجد فواتير غير مسددة</div>';
                    document.getElementById('pendingCount').style.display = 'none';
                    document.getElementById('deleteAllPendingBtn').style.display = 'none';
                } else {
                    Swal.fire({ icon: 'error', title: 'خطأ', text: data.message });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'خطأ', text: 'تعذر الاتصال بالخادم' });
            }
        }

        function closePendingModal() {
            pendingOrdersData = [];
            pendingOrderIndex = -1;
            document.getElementById('pendingModal').classList.remove('active');
            document.getElementById('invoiceInput').focus();
        }

        async function loadPendingOrder(orderNumber) {
            closePendingModal();
            await fetchOrder(orderNumber);
        }

        async function loadPendingCount() {
            try {
                const res = await fetch(BASE_URL + '/cashier/pending-orders');
                const data = await res.json();
                const badge = document.getElementById('pendingCount');
                if (data.success && data.data.length > 0) {
                    badge.textContent = data.data.length;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            } catch (err) {}
        }

        function resetAll() {
            currentOrder = null;
            directItems = [];
            isDirectMode = false;
            discount = 0;
            mergedOrderIds = [];
            selectedCustomer = null;
            processingPayment = false;

            document.getElementById('invoiceInput').value = '';
            document.getElementById('invoiceInput').disabled = false;
            document.getElementById('emptyState').classList.remove('hidden');
            document.getElementById('itemsTable').classList.add('hidden');
            document.getElementById('itemsBody').innerHTML = '';
            updateSummary();
            document.getElementById('invoiceInput').focus();
        }

        function toast(msg, type = 'info') {
            Swal.fire({
                toast: true,
                position: 'top',
                icon: type,
                title: msg,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                customClass: { popup: 'swal-rtl' }
            });
        }
    </script>
</body>
</html>
