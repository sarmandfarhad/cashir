@extends('layouts.pos', ['activeNav' => 'inventory'])

@section('title', __('inventory.title').' · '.__('brand.name'))
@section('body-class', 'inventory-page')
@section('main-class', 'inventory-workspace')

@push('styles')
<style>
        .inventory-page {
            --bg: var(--pos-bg);
            --panel: var(--pos-panel);
            --line: var(--pos-line);
            --text: var(--pos-text);
            --muted: var(--pos-muted);
            --blue: var(--pos-primary);
            --blue-deep: var(--pos-primary-deep);
            --blue-soft: var(--pos-primary-soft);
            --red: var(--pos-danger);
            --amber: #d59b18;
            --green: var(--pos-success);
            --shadow-sm: var(--pos-shadow);
            --shadow-md: var(--pos-shadow);
            --shadow-lg: 0 24px 60px rgba(0, 0, 0, 0.28);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
        }

        .inventory-page input,
        .inventory-page select,
        .inventory-page button {
            color: var(--pos-text);
        }

        html[data-theme="dark"] .inventory-page :is(.btn, .modal, .modal-close, .form-group input, .category-select, .variant-chip, .qty-input-wrap, .added-variants-container, .ap-panel, .ap-panel-close, .var-counter-btn, .toast) {
            background-color: var(--pos-panel);
        }

        html[data-theme="dark"] .inventory-page :is(th, .wizard-header-steps, .variant-builder, .qty-input-wrap button) {
            background-color: var(--pos-panel-2);
        }

        /* ── Design Tokens ─────────────────────────────── */
        /* ── Main Content ─────────────────────────────── */
        .inventory-workspace { padding: 24px; font-size: 13px; line-height: 1.5; }
        .topbar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-block-end: 16px;
        }
        .title h1 { margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.02em; }
        .title p  { margin: 4px 0 0; font-size: 13px; color: var(--pos-muted); }

        .actions { display: flex; gap: 10px; align-items: center; }
        .btn {
            border: 1px solid var(--pos-line);
            background: var(--pos-panel);
            border-radius: 10px;
            min-height: 38px;
            padding: 0 16px;
            font-size: 13px;
            font-weight: 600;
            color: var(--pos-text);
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s;
        }
        .btn:hover { background: var(--pos-panel-2); }
        .btn.primary {
            background: var(--pos-primary);
            border-color: var(--pos-primary);
            color: #fff;
            box-shadow: 0 4px 14px rgba(49,95,209,0.25);
        }
        .btn.primary:hover { background: var(--pos-primary-deep); border-color: var(--pos-primary-deep); }

        /* ── Alert ─────────────────────────────────── */
        .alert {
            border: 1px solid #f3df9e;
            background: var(--pos-panel)9e6;
            color: #8b6412;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 12px;
            margin-block-end: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── Table Card ─────────────────────────────── */
        .table-card {
            background: var(--pos-panel);
            border: 1px solid var(--pos-line);
            border-radius: var(--radius-md);
            box-shadow: var(--pos-shadow);
            overflow: hidden;
        }
        .toolbar {
            padding: 12px 14px;
            border-block-end: 1px solid var(--pos-line);
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .toolbar select, .toolbar input[type="search"] {
            min-height: 36px;
            border: 1px solid var(--pos-line);
            border-radius: var(--radius-sm);
            padding: 0 12px;
            font-size: 12px;
            color: var(--pos-text);
            outline: 0;
            transition: border-color 0.15s;
        }
        .toolbar select:focus, .toolbar input[type="search"]:focus { border-color: var(--pos-primary); }
        .toolbar input[type="search"] { flex: 1; min-width: 220px; }

        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { text-align: start; padding: 11px 14px; border-block-end: 1px solid var(--pos-line); white-space: nowrap; }
        th { background: var(--pos-panel-2); font-size: 11px; color: var(--pos-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; }
        tbody tr { cursor: pointer; transition: background 0.12s; }
        tbody tr:hover { background: var(--pos-panel-2); }
        tbody tr:last-child td { border-block-end: none; }
        .stock-low { color: var(--pos-danger); font-weight: 700; }
        .action-link { color: var(--pos-primary); text-decoration: none; font-weight: 700; }

        /* ══════════════════════════════════════════════
           ADD PRODUCT MODAL
        ══════════════════════════════════════════════ */
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(18, 30, 60, 0.50);
            backdrop-filter: blur(3px);
            z-index: 100;
            align-items: center;
            justify-content: center;
            padding: 16px;
            animation: fadeIn 0.18s ease;
        }
        .modal-backdrop.open { display: flex; }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }

        .modal {
            background: var(--pos-panel);
            border-radius: var(--radius-lg);
            width: min(560px, calc(100vw - 32px));
            max-height: 92vh;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: var(--shadow-lg);
            animation: slideUp 0.22s cubic-bezier(0.22, 1, 0.36, 1);
        }

        /* Modal Header */
        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 20px 22px 16px;
            border-block-end: 1px solid var(--pos-line);
        }
        .modal-header h2 { margin: 0; font-size: 17px; font-weight: 700; }
        .modal-close {
            width: 32px;
            height: 32px;
            border: 1px solid var(--pos-line);
            background: var(--pos-panel);
            border-radius: 50%;
            font-size: 16px;
            color: var(--pos-muted);
            cursor: pointer;
            display: grid;
            place-items: center;
            line-height: 1;
            transition: background 0.15s, color 0.15s;
        }
        .modal-close:hover { background: var(--pos-line); color: var(--pos-text); }

        /* Modal Body */
        .modal-body { padding: 16px 20px 4px; }
        .modal-subtitle {
            font-size: 12px;
            color: var(--pos-primary);
            font-weight: 600;
            margin: 0 0 16px;
        }

        /* Form Elements */
        .form-group { display: grid; gap: 5px; margin-block-end: 14px; }
        .form-group label {
            font-size: 12px;
            font-weight: 600;
            color: var(--pos-text);
        }
        .form-group.required label::after { content: ' *'; color: var(--pos-danger); }
        .form-group input[type="text"],
        .form-group input[type="number"] {
            min-height: 40px;
            border: 1.5px solid var(--pos-line);
            border-radius: var(--radius-sm);
            padding: 0 12px;
            font-size: 13px;
            color: var(--pos-text);
            outline: 0;
            transition: border-color 0.15s, box-shadow 0.15s;
            background: var(--pos-panel);
            width: 100%;
            min-width: 0;
        }
        .form-group input:focus {
            border-color: var(--pos-primary);
            box-shadow: 0 0 0 3px rgba(49,95,209,0.10);
        }
        .form-group input::placeholder { color: #a8b5c8; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        /* Image Upload */
        .image-upload-area {
            border: 2px dashed #c8d6ed;
            border-radius: 10px;
            padding: 16px 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--pos-panel-2);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-height: 155px;
        }
        .image-upload-area:hover { border-color: var(--pos-primary); background: var(--pos-primary-soft); }
        .image-upload-area.has-image { border-style: solid; border-color: var(--pos-line); padding: 8px; background: var(--pos-panel); }
        .upload-icon-wrap {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 1.5px solid #c8d6ed;
            background: var(--pos-panel);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: var(--pos-muted);
        }
        .upload-label { font-size: 11px; color: var(--pos-muted); font-weight: 500; line-height: 1.4; }
        .image-preview { width: 100%; height: 130px; object-fit: cover; border-radius: 6px; display: none; }
        .image-preview.show { display: block; }
        #product-image-input { display: none; }

        /* Category / Variants Trigger Buttons */
        .trigger-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            min-height: 40px;
            border: 1.5px solid var(--pos-line);
            border-radius: var(--radius-sm);
            padding: 0 12px;
            font-size: 13px;
            color: var(--pos-muted);
            background: var(--pos-panel);
            cursor: pointer;
            text-align: start;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .trigger-btn:hover, .trigger-btn:focus {
            border-color: var(--pos-primary);
            box-shadow: 0 0 0 3px rgba(49,95,209,0.10);
            outline: 0;
        }
        .trigger-btn.has-value { color: var(--pos-text); font-weight: 500; }
        .trigger-btn .t-label { flex: 1; }
        .trigger-btn .t-badge {
            background: var(--pos-primary);
            color: #fff;
            border-radius: 20px;
            padding: 2px 9px;
            font-size: 10px;
            font-weight: 700;
        }
        .trigger-btn .t-arrow { color: #a0aec0; font-size: 10px; flex-shrink: 0; margin-inline-start: auto; }

        /* Variants row (minus + trigger) */
        .variants-row { display: flex; gap: 8px; align-items: center; }
        .var-reset-btn {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            border: 1.5px solid var(--pos-line);
            border-radius: var(--radius-sm);
            background: var(--pos-panel);
            color: var(--pos-text);
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            line-height: 1;
            transition: background 0.15s, border-color 0.15s;
        }
        .var-reset-btn:hover { background: var(--pos-panel-2); border-color: var(--pos-primary); }

        /* Modal Footer */
        .modal-footer {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding: 14px 20px;
            border-block-start: 1px solid var(--pos-line);
            margin-block-start: 6px;
        }
        .modal-footer button {
            min-height: 44px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.15s;
            border: 0;
        }
        .modal-footer .btn-cancel {
            background: var(--pos-panel);
            border: 1.5px solid var(--pos-line);
            color: var(--pos-primary);
        }
        .modal-footer .btn-cancel:hover { background: var(--pos-panel-2); }
        .modal-footer .btn-save {
            background: var(--pos-primary);
            color: #fff;
            box-shadow: 0 4px 14px rgba(49,95,209,0.28);
        }
        .modal-footer .btn-save:hover { background: var(--pos-primary-deep); }
        .modal-footer .btn-save:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            box-shadow: none;
        }

        /* Loading spinner */
        .btn-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.4);
            border-block-start-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            vertical-align: middle;
            margin-inline-end: 6px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ══════════════════════════════════════════════
           FLOATING PANELS (Category & Variants)
        ══════════════════════════════════════════════ */
        .ap-panel-backdrop {
            position: fixed;
            inset: 0;
            z-index: 200;
            display: none;
        }
        .ap-panel-backdrop.open { display: block; }

        .ap-panel {
            position: fixed;
            background: var(--pos-panel);
            border: 1px solid var(--pos-line);
            border-radius: 14px;
            box-shadow: var(--shadow-lg);
            z-index: 201;
            width: 310px;
            padding: 18px 18px 14px;
            display: none;
            animation: slideUp 0.18s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .ap-panel.open { display: block; }

        .ap-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-block-end: 6px;
        }
        .ap-panel-header h4 { margin: 0; font-size: 15px; font-weight: 700; }
        .ap-panel-close {
            width: 28px;
            height: 28px;
            border: 1px solid var(--pos-line);
            background: var(--pos-panel);
            border-radius: 50%;
            font-size: 14px;
            cursor: pointer;
            display: grid;
            place-items: center;
            color: var(--pos-muted);
            transition: background 0.12s;
        }
        .ap-panel-close:hover { background: var(--pos-line); }
        .ap-panel-subtitle { margin: 0 0 12px; font-size: 11px; color: var(--pos-muted); }

        /* ── Category Panel ──────────────────── */
        .cat-search-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1.5px solid var(--pos-line);
            border-radius: 8px;
            padding: 7px 10px;
            margin-block-end: 12px;
            transition: border-color 0.15s;
        }
        .cat-search-wrap:focus-within { border-color: var(--pos-primary); }
        .cat-search-wrap span { color: var(--pos-muted); font-size: 14px; }
        .cat-search-wrap input {
            border: 0;
            outline: 0;
            font-size: 12px;
            color: var(--pos-text);
            width: 100%;
            background: transparent;
        }

        .cat-list {
            display: grid;
            gap: 2px;
            max-height: 230px;
            overflow-y: auto;
            margin-block-end: 12px;
        }
        .cat-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            transition: background 0.1s;
        }
        .cat-option:hover { background: var(--pos-primary-soft); }
        .cat-option input[type="radio"] {
            accent-color: var(--pos-primary);
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .panel-action-btn {
            width: 100%;
            min-height: 38px;
            background: var(--pos-primary);
            color: #fff;
            border: 0;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.15s;
        }
        .panel-action-btn:hover { background: var(--pos-primary-deep); }

        /* ── Variants Panel ──────────────────── */
        .var-section-label {
            font-size: 13px;
            font-weight: 700;
            margin-block-end: 10px;
            color: var(--pos-text);
        }
        .var-size-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-block-end: 10px;
        }
        .var-size-hint { font-size: 12px; color: var(--pos-muted); }
        .var-counter {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .var-counter-btn {
            width: 30px;
            height: 30px;
            border: 1.5px solid var(--pos-line);
            border-radius: 50%;
            background: var(--pos-panel);
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: 700;
            color: var(--pos-text);
            transition: all 0.12s;
        }
        .var-counter-btn:hover { border-color: var(--pos-primary); color: var(--pos-primary); background: var(--pos-primary-soft); }
        .var-counter-num { font-size: 15px; font-weight: 700; min-width: 22px; text-align: center; }

        .var-sizes-preview {
            background: var(--pos-primary-soft);
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 11px;
            color: var(--pos-primary);
            font-weight: 600;
            margin-block-end: 16px;
            text-align: center;
        }

        .color-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-block-end: 16px;
        }
        .color-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }
        .color-swatch {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 2.5px solid transparent;
            transition: border-color 0.15s, transform 0.12s;
            position: relative;
            outline: 2px solid rgba(0,0,0,0.07);
        }
        .color-swatch:hover { transform: scale(1.1); }
        .color-swatch.selected { border-color: var(--pos-primary); outline-color: transparent; }
        .color-swatch.selected::after {
            content: '✓';
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
            font-weight: 800;
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
        }
        .color-name { font-size: 10px; color: var(--pos-muted); font-weight: 500; }

        .var-summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--pos-primary-soft);
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 12px;
            color: var(--pos-primary);
            font-weight: 700;
            margin-block-end: 12px;
        }
        .var-summary-badge {
            background: var(--pos-primary);
            color: #fff;
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 10px;
            font-weight: 700;
        }

        /* ── Toast Notification ─────────────────── */
        .toast-container {
            position: fixed;
            inset-block-end: 28px;
            inset-inline-end: 28px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .toast {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--pos-panel);
            border: 1px solid var(--pos-line);
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: var(--pos-shadow);
            min-width: 260px;
            animation: slideUp 0.25s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .toast.success { border-inline-start: 4px solid var(--pos-success); }
        .toast.error   { border-inline-start: 4px solid var(--pos-danger); }
        .toast-icon    { font-size: 20px; flex-shrink: 0; }
        .toast p       { margin: 0; }
        .toast span    { display: block; font-size: 11px; color: var(--pos-muted); font-weight: 400; margin-block-start: 1px; }

        /* ── Multi-Step Wizard Styles ───────────── */
        .wizard-step {
            display: none;
        }
        .wizard-step.active {
            display: block;
        }
        .wizard-header-steps {
            display: flex;
            align-items: center;
            background: var(--pos-panel-2);
            padding: 12px 22px;
            border-block-end: 1px solid var(--pos-line);
            font-size: 12px;
            font-weight: 600;
            gap: 0;
        }
        .wizard-header-step {
            display: flex;
            align-items: center;
            gap: 7px;
            color: var(--pos-muted);
            white-space: nowrap;
        }
        .wizard-header-step.active {
            color: var(--pos-primary);
        }
        .wizard-header-step.done {
            color: var(--pos-success);
        }
        .wizard-header-step .num {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--pos-line);
            color: var(--pos-muted);
            display: grid;
            place-items: center;
            font-size: 10px;
            font-weight: 700;
            flex-shrink: 0;
            transition: background 0.2s;
        }
        .wizard-header-step.active .num {
            background: var(--pos-primary);
            color: #fff;
        }
        .wizard-header-step.done .num {
            background: var(--pos-success);
            color: #fff;
            font-size: 0; /* hide the number, show SVG instead */
        }
        .wizard-header-step.done .num::after {
            content: '';
            display: block;
            width: 13px;
            height: 13px;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='3' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M5 13l4 4L19 7'/%3E%3C/svg%3E") center/contain no-repeat;
        }
        .wizard-sep {
            flex: 1;
            height: 1.5px;
            background: var(--pos-line);
            margin: 0 10px;
        }
        .wizard-sep.active {
            background: var(--pos-primary);
        }
        .wizard-sep.done {
            background: var(--pos-success);
        }

        /* Step 1 two-column layout */
        .step1-layout {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 14px;
            align-items: start;
        }
        .step1-left {}
        .step1-right {
            display: flex;
            flex-direction: column;
            gap: 0;
            min-width: 0;
        }

        /* Category Select */
        .category-select {
            min-height: 40px;
            border: 1.5px solid var(--pos-line);
            border-radius: var(--radius-sm);
            padding: 0 12px;
            font-size: 13px;
            color: var(--pos-muted);
            background: var(--pos-panel);
            outline: 0;
            width: 100%;
            cursor: pointer;
            transition: border-color 0.15s;
            appearance: auto;
        }
        .category-select.has-value { color: var(--pos-text); }
        .category-select:focus {
            border-color: var(--pos-primary);
            box-shadow: 0 0 0 3px rgba(49,95,209,0.10);
        }

        /* Next button with arrow */
        .btn-next-arrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-next-arrow svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }

        /* Variant Builder */
        .variant-builder {
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: var(--pos-panel-2);
            border: 1px solid var(--pos-line);
            border-radius: 8px;
            padding: 14px;
            margin-block-end: 14px;
        }
        .variant-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        .variant-chip {
            border: 1px solid var(--pos-line);
            background: var(--pos-panel);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
        }
        .variant-chip:hover {
            border-color: var(--pos-primary);
            color: var(--pos-primary);
        }
        .variant-chip.selected {
            background: var(--pos-primary);
            border-color: var(--pos-primary);
            color: #fff;
        }
        .color-grid-wizard {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 8px;
        }
        .color-dot-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            cursor: pointer;
        }
        .color-dot {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 2.5px solid transparent;
            outline: 2px solid rgba(0,0,0,0.07);
            transition: transform 0.1s;
        }
        .color-dot-wrap:hover .color-dot {
            transform: scale(1.15);
        }
        .color-dot-wrap.selected .color-dot {
            border-color: var(--pos-primary);
            outline-color: transparent;
            transform: scale(1.15);
        }
        .color-dot-name {
            font-size: 9px;
            color: var(--pos-muted);
            font-weight: 500;
        }
        .qty-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            border-block-start: 1px solid #f1f5f9;
            padding-block-start: 12px;
            margin-block-start: 4px;
        }
        .qty-input-wrap {
            display: flex;
            align-items: center;
            border: 1.5px solid var(--pos-line);
            border-radius: 6px;
            background: var(--pos-panel);
            overflow: hidden;
            width: 120px;
        }
        .qty-input-wrap button {
            width: 32px;
            height: 32px;
            border: 0;
            background: var(--pos-panel-2);
            color: var(--pos-text);
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }
        .qty-input-wrap button:hover {
            background: var(--pos-soft);
        }
        .qty-input-wrap input {
            flex: 1;
            border: 0 !important;
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            width: 100%;
            outline: none;
            padding: 0 !important;
            height: 32px;
        }
        .add-variant-btn {
            background: var(--pos-primary-soft);
            color: var(--pos-primary);
            border: 1px solid rgba(49,95,209,0.2);
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.15s;
            height: 34px;
        }
        .add-variant-btn:hover {
            background: var(--pos-primary);
            color: #fff;
        }

        /* Added Variants List */
        .added-variants-container {
            border: 1px solid var(--pos-line);
            border-radius: 8px;
            background: var(--pos-panel);
            max-height: 150px;
            overflow-y: auto;
            margin-block-end: 12px;
        }
        .added-variant-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 12px;
            border-block-end: 1px solid var(--pos-line);
            font-size: 12px;
        }
        .added-variant-row:last-child {
            border-block-end: 0;
        }
        .added-variant-info {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }
        .added-variant-color-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 1px solid rgba(0,0,0,0.1);
        }
        .added-variant-qty {
            font-weight: 700;
            color: var(--pos-primary);
            background: var(--pos-primary-soft);
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        .remove-variant-btn {
            background: transparent;
            border: 0;
            color: var(--pos-danger);
            font-size: 16px;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .remove-variant-btn:hover {
            opacity: 0.8;
        }
        /* ── Review Step ─────────────────────────── */
        .review-hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 28px 16px 18px;
            text-align: center;
        }
        .review-hero-ring {
            position: relative;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-block-end: 16px;
        }
        .review-hero-ring::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: rgba(31, 157, 102, 0.12);
            animation: pulse-ring 2s ease-in-out infinite;
        }
        @keyframes pulse-ring {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.12); opacity: 0.6; }
        }
        .review-hero-circle {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: var(--pos-success);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(31,157,102,0.35);
            animation: pop-in 0.4s cubic-bezier(0.22,1,0.36,1);
        }
        @keyframes pop-in {
            from { transform: scale(0.4); opacity: 0; }
            to   { transform: scale(1);   opacity: 1; }
        }
        /* Sparkle dots */
        .sparkle {
            position: absolute;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            animation: sparkle-fade 2s ease-in-out infinite;
        }
        .sparkle.s1 { background: #f5c518; inset-block-start: 6px;  inset-inline-start: 18px; animation-delay: 0s; }
        .sparkle.s2 { background: #315fd1; inset-block-start: 12px; inset-inline-end: 12px; width:5px; height:5px; animation-delay: 0.3s; }
        .sparkle.s3 { background: #1f9d66; inset-block-end: 8px; inset-inline-end: 20px; animation-delay: 0.6s; }
        .sparkle.s4 { background: #cb4d4d; inset-block-end: 14px; inset-inline-start: 14px; width:5px; height:5px; animation-delay: 0.9s; }
        .sparkle.s5 { background: #f5c518; inset-block-start: 40px; inset-inline-start: 4px;  width:4px; height:4px; animation-delay: 0.45s; }
        .sparkle.s6 { background: #315fd1; inset-block-start: 38px; inset-inline-end: 4px; width:4px; height:4px; animation-delay: 0.75s; }
        @keyframes sparkle-fade {
            0%, 100% { opacity: 1;   transform: scale(1); }
            50%       { opacity: 0.3; transform: scale(0.5); }
        }
        .review-hero-title {
            margin: 0 0 6px;
            font-size: 20px;
            font-weight: 800;
            color: var(--pos-text);
            letter-spacing: -0.01em;
        }
        .review-hero-sub {
            margin: 0;
            font-size: 13px;
            color: var(--pos-muted);
            line-height: 1.6;
        }
        /* Summary table */
        .review-table {
            border: 1px solid var(--pos-line);
            border-radius: 10px;
            overflow: hidden;
            margin: 0 0 4px;
        }
        .review-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-block-end: 1px solid var(--pos-line);
            font-size: 13px;
        }
        .review-label {
            padding: 11px 16px;
            color: var(--pos-muted);
            font-weight: 500;
            border-inline-end: 1px solid var(--pos-line);
        }
        .review-value {
            padding: 11px 16px;
            font-weight: 700;
            color: var(--pos-text);
        }
        /* Save Product button (green) */
        .btn-save-product {
            background: var(--pos-success);
            color: #fff;
            border: 0;
            border-radius: 10px;
            min-height: 44px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.15s;
            box-shadow: 0 4px 14px rgba(31,157,102,0.28);
        }
        .btn-save-product:hover { background: #178054; }
        .btn-save-product:disabled { opacity: 0.55; cursor: not-allowed; box-shadow: none; }
</style>
@endpush

@section('content')
        <div class="topbar">
            <div class="title">
                <h1>{{ __('inventory.productList') }}</h1>
                <p>{{ __('inventory.manageProducts') }}</p>
            </div>
            <div class="actions">
                <button class="btn" type="button">{{ __('inventory.exportPdf') }}</button>
                <button class="btn primary" type="button" id="add-product-btn">+ {{ __('inventory.addProduct') }}</button>
            </div>
        </div>

        @php
            $lowStockCount = 0;
            foreach ($products as $p) {
                if ($p['stock'] <= $p['min_stock']) $lowStockCount++;
            }
        @endphp
        @if ($lowStockCount > 0)
            <div class="alert">
                ⚠️ {{ __('inventory.lowStockAlert', ['count' => $lowStockCount]) }}
            </div>
        @endif

        <section class="table-card">
            <div class="toolbar">
                <select id="category-filter" aria-label="{{ __('inventory.filterByCategory') }}">
                    <option value="all">{{ __('inventory.allCategories') }}</option>
                    @php
                        $cats = array_unique(array_column($products, 'category'));
                    @endphp
                    @foreach ($cats as $cat)
                        <option value="{{ strtolower($cat) }}">{{ $cat }}</option>
                    @endforeach
                </select>
                <input id="inventory-search" type="search" placeholder="{{ __('inventory.searchPlaceholder') }}" aria-label="{{ __('inventory.searchProducts') }}">
            </div>

            <table>
                <thead>
                    <tr>
                        <th>{{ __('inventory.productCode') }}</th>
                        <th>{{ __('inventory.productName') }}</th>
                        <th>{{ __('inventory.category') }}</th>
                        <th>{{ __('inventory.variants') }}</th>
                        <th>{{ __('inventory.stock') }}</th>
                        <th>{{ __('inventory.minStock') }}</th>
                        <th>{{ __('inventory.price') }}</th>
                        <th>{{ __('inventory.action') }}</th>
                    </tr>
                </thead>
                <tbody id="inventory-tbody">
                    @foreach ($products as $product)
                        <tr
                            data-code="{{ strtolower($product['code']) }}"
                            data-name="{{ strtolower($product['name']) }}"
                            data-category="{{ strtolower($product['category']) }}"
                            onclick="window.location='{{ route('inventory.show', $product['code']) }}'"
                        >
                            <td>{{ $product['code'] }}</td>
                            <td>{{ $product['name'] }}</td>
                            <td>{{ $product['category'] }}</td>
                            <td>{{ $product['variants'] }}</td>
                            <td class="{{ $product['stock'] <= $product['min_stock'] ? 'stock-low' : '' }}">
                                {{ $product['stock'] }}
                            </td>
                            <td>{{ $product['min_stock'] }}</td>
                            <td>IQD {{ number_format($product['price'], 0, '.', ',') }}</td>
                            <td onclick="event.stopPropagation()">
                                <a class="action-link" href="{{ route('inventory.show', $product['code']) }}">{{ __('inventory.open') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
@endsection

<!-- ══════════════════════════════════════════════
     ADD PRODUCT WIZARD MODAL
══════════════════════════════════════════════ -->
@section('overlays')
<div id="add-product-modal" class="modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="modal">
        <!-- Header -->
        <div class="modal-header">
            <h2 id="modal-title">{{ __('inventory.addNewProduct') }}</h2>
            <button type="button" class="modal-close" id="modal-close-btn" aria-label="{{ __('messages.close') }}">×</button>
        </div>

        <!-- Step indicators in header -->
        <div class="wizard-header-steps">
            <div class="wizard-header-step active" id="step-1-header">
                <span class="num">1</span>
                <span>{{ __('inventory.basicInfo') }}</span>
            </div>
            <div class="wizard-sep" id="step-sep-1"></div>
            <div class="wizard-header-step" id="step-2-header">
                <span class="num">2</span>
                <span>{{ __('inventory.variants') }}</span>
            </div>
            <div class="wizard-sep" id="step-sep-2"></div>
            <div class="wizard-header-step" id="step-3-header">
                <span class="num">3</span>
                <span>{{ __('inventory.review') }}</span>
            </div>
        </div>

        <form id="add-product-form" novalidate>
            <div class="modal-body" style="padding-block-start: 14px;">
                
                <!-- ── STEP 1: Basic Info ── -->
                <div class="wizard-step active" id="wizard-step-1">
                    <p class="modal-subtitle">{{ __('inventory.enterBasicDetails') }}</p>
                    
                    <div class="step1-layout">
                        <!-- Left: Product Image -->
                        <div class="step1-left">
                            <div class="form-group" style="margin-block-end:0;">
                                <label>{{ __('inventory.image') }}</label>
                                <div class="image-upload-area" id="image-upload-area" role="button" tabindex="0"
                                     onclick="document.getElementById('product-image-input').click()"
                                     onkeydown="if(event.key==='Enter')this.click()">
                                    <img id="image-preview" class="image-preview" alt="{{ __('inventory.productPreview') }}" />
                                    <div id="upload-placeholder">
                                        <div class="upload-icon-wrap">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="20" height="20"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                                        </div>
                                        <div class="upload-label">{{ __('inventory.uploadImage') }}<br><span style="font-size:10px;">{{ __('inventory.imageRequirements') }}</span></div>
                                    </div>
                                </div>
                                <input id="product-image-input" type="file" name="image" accept="image/*">
                            </div>
                        </div>

                        <!-- Right: Fields -->
                        <div class="step1-right">
                            <!-- Product Name -->
                            <div class="form-group required">
                                <label for="product-name">{{ __('inventory.productName') }} <span style="color:var(--pos-danger);">*</span></label>
                                <input id="product-name" type="text" name="name" placeholder="{{ __('inventory.productNameExample') }}" required autocomplete="off">
                            </div>

                            <!-- Product Code -->
                            <div class="form-group">
                                <label for="product-code">{{ __('inventory.productCode') }}</label>
                                <input id="product-code" type="text" name="code" readonly style="background:#f8fafc; cursor:not-allowed;" placeholder="TS-001">
                            </div>

                            <!-- Barcode -->
                            <div class="form-group">
                                <label for="product-barcode">{{ __('inventory.barcodeOptional') }}</label>
                                <input id="product-barcode" type="text" name="barcode" placeholder="{{ __('inventory.barcodeExample') }}" autocomplete="off">
                            </div>

                            <!-- Stock + Price -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="product-min-stock">{{ __('inventory.stock') }}</label>
                                    <input id="product-min-stock" type="number" name="min_stock" value="0" min="0" placeholder="0">
                                </div>
                                <div class="form-group required">
                                    <label for="product-price">{{ __('inventory.priceIqd') }} <span style="color:var(--pos-danger);">*</span></label>
                                    <input id="product-price" type="number" name="price" placeholder="IQD 00.000" min="0" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category — full width below -->
                    <div class="form-group" style="margin-block-start: 14px; margin-block-end: 0;">
                        <label for="product-category">{{ __('inventory.selectCategory') }}</label>
                        <select id="product-category" name="category" required class="category-select">
                            <option value="" disabled selected>{{ __('inventory.select') }}</option>
                            @foreach (['T-Shirt', 'Shirt', 'Pants', 'Jeans', 'Jacket', 'Hoodie', 'Shorts', 'Accessories', 'Polo shirt'] as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- ── STEP 2: Variants & Stock ── -->
                <div class="wizard-step" id="wizard-step-2">
                    <p class="modal-subtitle">{{ __('inventory.configureVariants') }}</p>
                    
                    <div class="variant-builder">
                        <label style="font-size: 11px; font-weight: 700; color: var(--pos-text);">1. {{ __('inventory.selectSize') }}</label>
                        <div class="variant-chips" id="wizard-sizes">
                            <span class="variant-chip" data-size="S">S</span>
                            <span class="variant-chip" data-size="M">M</span>
                            <span class="variant-chip" data-size="L">L</span>
                            <span class="variant-chip" data-size="XL">XL</span>
                            <span class="variant-chip" data-size="XXL">XXL</span>
                            <span class="variant-chip" data-size="3XL">3XL</span>
                            <span class="variant-chip" data-size="Free Size">{{ __('inventory.freeSize') }}</span>
                        </div>
                        
                        <label style="font-size: 11px; font-weight: 700; color: var(--pos-text); margin-block-start: 4px;">2. {{ __('inventory.selectColor') }}</label>
                        <div class="color-grid-wizard" id="wizard-colors">
                            @php
                                $colorSwatches = [
                                    ['White', '#ffffff', '#ddd'],
                                    ['Black', '#1a1a1a', null],
                                    ['Gray',  '#8c8c8c', null],
                                    ['Navy',  '#1b2f6e', null],
                                    ['Blue',  '#315fd1', null],
                                    ['Red',   '#cb4d4d', null],
                                    ['Green', '#1f9d66', null],
                                    ['Yellow','#f5c518', null],
                                    ['Beige', '#f5e6c8', '#ddd'],
                                    ['Brown', '#7a4a1e', null],
                                    ['Purple','#7a3fbf', null],
                                    ['Pink',  '#f5a0b5', null],
                                ];
                            @endphp
                            @foreach ($colorSwatches as [$cName, $cHex, $cBorder])
                                <div class="color-dot-wrap" data-color="{{ $cName }}" data-hex="{{ $cHex }}">
                                    <span class="color-dot" style="background:{{ $cHex }};{{ $cBorder ? 'border-color:'.$cBorder.';' : '' }}"></span>
                                    <span class="color-dot-name">{{ $cName }}</span>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="qty-row">
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: var(--pos-text);">3. {{ __('inventory.stockQuantity') }}</label>
                                <div class="qty-input-wrap" style="margin-block-start: 4px;">
                                    <button type="button" id="wizard-qty-dec">−</button>
                                    <input type="number" id="wizard-qty-input" value="10" min="1">
                                    <button type="button" id="wizard-qty-inc">+</button>
                                </div>
                            </div>
                            <button type="button" class="add-variant-btn" id="wizard-add-variant-btn">
                                <span>+ {{ __('inventory.addVariant') }}</span>
                            </button>
                        </div>
                    </div>
                    
                    <label style="font-size: 12px; font-weight: 700; color: var(--pos-text); display: block; margin-block-end: 6px;">{{ __('inventory.addedCombinations') }}</label>
                    <div class="added-variants-container" id="added-variants-list">
                        <div style="padding: 16px; text-align: center; color: var(--pos-muted); font-size: 12px;">{{ __('inventory.noCombinations') }}</div>
                    </div>

                    <!-- Summary Row -->
                    <div class="var-summary-row" style="margin-block-start: 10px; margin-block-end: 0;">
                        <span id="var-summary-text">{{ __('inventory.variantSummary', ['stock' => 0, 'sizes' => 0, 'colors' => 0]) }}</span>
                        <span class="var-summary-badge" id="var-summary-badge">{{ __('inventory.totalQuantity', ['count' => 0]) }}</span>
                    </div>

                    <!-- Hidden inputs to submit standard payload to server -->
                    <input type="hidden" name="stock" id="product-stock" value="0">
                    <input type="hidden" name="variants" id="product-variants">
                </div>

                <!-- ── STEP 3: Review ── -->
                <div class="wizard-step" id="wizard-step-3">
                    <!-- Hero -->
                    <div class="review-hero">
                        <div class="review-hero-ring">
                            <div class="review-hero-circle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" width="36" height="36"><path d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <!-- Sparkle dots -->
                            <span class="sparkle s1"></span>
                            <span class="sparkle s2"></span>
                            <span class="sparkle s3"></span>
                            <span class="sparkle s4"></span>
                            <span class="sparkle s5"></span>
                            <span class="sparkle s6"></span>
                        </div>
                        <h3 class="review-hero-title">{{ __('inventory.productReady') }}</h3>
                        <p class="review-hero-sub">{{ __('inventory.reviewBeforeSaving') }}</p>
                    </div>

                    <!-- Summary Table -->
                    <div class="review-table">
                        <div class="review-row">
                            <span class="review-label">{{ __('inventory.productName') }}</span>
                            <span class="review-value" id="rv-name">—</span>
                        </div>
                        <div class="review-row">
                            <span class="review-label">{{ __('inventory.productCode') }}</span>
                            <span class="review-value" id="rv-code">—</span>
                        </div>
                        <div class="review-row">
                            <span class="review-label">{{ __('inventory.sizes') }}</span>
                            <span class="review-value" id="rv-sizes">—</span>
                        </div>
                        <div class="review-row">
                            <span class="review-label">{{ __('inventory.colors') }}</span>
                            <span class="review-value" id="rv-colors">—</span>
                        </div>
                        <div class="review-row">
                            <span class="review-label">{{ __('inventory.totalVariants') }}</span>
                            <span class="review-value" id="rv-variants">—</span>
                        </div>
                        <div class="review-row">
                            <span class="review-label">{{ __('inventory.totalStock') }}</span>
                            <span class="review-value" id="rv-stock">—</span>
                        </div>
                        <div class="review-row" style="border-block-end:0;">
                            <span class="review-label">{{ __('inventory.priceIqd') }}</span>
                            <span class="review-value" id="rv-price">—</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="modal-footer">
                <button type="button" class="btn-cancel" id="wizard-back-btn">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn-save" id="wizard-next-btn">
                    <span class="btn-next-arrow">
                        {{ __('inventory.next') }}
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><path d="M9 18l6-6-6-6"/></svg>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toast-container"></div>
@endsection

<!-- ══════════════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════════════ -->
@push('scripts')
<script>
'use strict';

const strings = {
    next: @json(__('inventory.next')),
    cancel: @json(__('messages.cancel')),
    back: @json(__('inventory.back')),
    save: @json(__('messages.save')),
    saveProduct: @json(__('inventory.saveProduct')),
    saving: @json(__('inventory.saving')),
    open: @json(__('inventory.open')),
    validationError: @json(__('inventory.validationError')),
    nameRequired: @json(__('inventory.nameRequired')),
    validPriceRequired: @json(__('inventory.validPriceRequired')),
    categoryRequired: @json(__('inventory.categoryRequired')),
    variantRequired: @json(__('inventory.variantRequired')),
    selectSize: @json(__('inventory.selectSize')),
    selectSizeFirst: @json(__('inventory.selectSizeFirst')),
    selectColor: @json(__('inventory.selectColor')),
    selectColorFirst: @json(__('inventory.selectColorFirst')),
    invalidQuantity: @json(__('inventory.invalidQuantity')),
    validQuantityRequired: @json(__('inventory.validQuantityRequired')),
    noCombinations: @json(__('inventory.noCombinations')),
    stock: @json(__('inventory.stock')),
    sizes: @json(__('inventory.sizes')),
    colors: @json(__('inventory.colors')),
    variants: @json(__('inventory.variants')),
    totalQuantity: @json(__('inventory.totalQuantity')),
    size: @json(__('inventory.size')),
    color: @json(__('inventory.color')),
    items: @json(__('sales.items')),
    addSuccess: @json(__('inventory.addSuccess')),
    liveInPos: @json(__('inventory.liveInPos')),
    addFailed: @json(__('inventory.addFailed')),
    checkRequired: @json(__('inventory.checkRequired')),
    networkError: @json(__('inventory.networkError')),
};
const locale = @json(app()->getLocale() === 'ar' ? 'ar-IQ' : 'en-US');

/* ─── Utility: show toast notification ───────── */
function showToast(type, title, subtitle = '') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    const icon = type === 'success' ? '✅' : '❌';
    toast.innerHTML = `
        <span class="toast-icon">${icon}</span>
        <p>${title}${subtitle ? `<span>${subtitle}</span>` : ''}</p>
    `;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.transition = 'opacity 0.4s, transform 0.4s';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        setTimeout(() => toast.remove(), 450);
    }, 3500);
}

/* ─── Format currency IQD ─────────────────────── */
function formatIQD(amount) {
    return 'IQD ' + Number(amount).toLocaleString(locale);
}

/* ══════════════════════════════════════════════
   MODAL OPEN / CLOSE
══════════════════════════════════════════════ */
const addProductModal = document.getElementById('add-product-modal');
const addProductForm  = document.getElementById('add-product-form');

function openAddModal() {
    addProductModal.classList.add('open');
    document.body.style.overflow = 'hidden';
    generateProductCode();
    showStep(1);
    setTimeout(() => document.getElementById('product-name').focus(), 80);
}

function closeAddModal() {
    addProductModal.classList.remove('open');
    document.body.style.overflow = '';
    resetForm();
}

document.getElementById('add-product-btn').addEventListener('click', openAddModal);
document.getElementById('modal-close-btn').addEventListener('click', closeAddModal);
// Cancel button is now wizard-back-btn, no separate modal-cancel-btn needed
addProductModal.addEventListener('click', (e) => { if (e.target === addProductModal) closeAddModal(); });

/* Keyboard: close on Escape */
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && addProductModal.classList.contains('open')) closeAddModal();
});

/* ══════════════════════════════════════════════
   IMAGE UPLOAD PREVIEW
══════════════════════════════════════════════ */
const imageInput        = document.getElementById('product-image-input');
const imagePreview      = document.getElementById('image-preview');
const uploadPlaceholder = document.getElementById('upload-placeholder');
const imageUploadArea   = document.getElementById('image-upload-area');

imageInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (ev) => {
        imagePreview.src = ev.target.result;
        imagePreview.classList.add('show');
        uploadPlaceholder.style.display = 'none';
        imageUploadArea.classList.add('has-image');
    };
    reader.readAsDataURL(file);
});

/* ══════════════════════════════════════════════
   WIZARD NAVIGATION & LOGIC
══════════════════════════════════════════════ */
let currentStep = 1;
let addedVariants = []; // array of { size, color, colorHex, qty }

let selectedSize = '';
let selectedColor = '';
let selectedColorHex = '';

const wizardStep1 = document.getElementById('wizard-step-1');
const wizardStep2 = document.getElementById('wizard-step-2');
const step1Header = document.getElementById('step-1-header');
const step2Header = document.getElementById('step-2-header');
const step3Header = document.getElementById('step-3-header');
const stepSep1    = document.getElementById('step-sep-1');
const stepSep2    = document.getElementById('step-sep-2');

const backBtn     = document.getElementById('wizard-back-btn');
const nextBtn     = document.getElementById('wizard-next-btn');

function setNextLabel(label) {
    if (label === strings.next) {
        nextBtn.innerHTML = `<span class="btn-next-arrow">${strings.next} <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><path d="M9 18l6-6-6-6"/></svg></span>`;
    } else {
        nextBtn.textContent = label;
    }
}

function populateReview() {
    const name  = document.getElementById('product-name').value.trim();
    const code  = document.getElementById('product-code').value.trim();
    const price = parseFloat(document.getElementById('product-price').value) || 0;

    const uniqueSizes  = [...new Set(addedVariants.map(v => v.size))];
    const uniqueColors = [...new Set(addedVariants.map(v => v.color))];
    const totalStock   = addedVariants.reduce((s, v) => s + v.qty, 0);
    const totalVariants = addedVariants.length;

    document.getElementById('rv-name').textContent    = name  || '—';
    document.getElementById('rv-code').textContent    = code  || '—';
    document.getElementById('rv-sizes').textContent   = uniqueSizes.length  ? `${uniqueSizes.length}(${uniqueSizes.join(',')})` : '—';
    document.getElementById('rv-colors').textContent  = uniqueColors.length ? `${uniqueColors.length}(${uniqueColors.join(',')})` : '—';
    document.getElementById('rv-variants').textContent = totalVariants;
    document.getElementById('rv-stock').textContent   = totalStock;
    document.getElementById('rv-price').textContent   = 'IQD ' + price.toLocaleString(locale);
}

function showStep(step) {
    currentStep = step;
    // Hide all steps
    wizardStep1.classList.remove('active');
    wizardStep2.classList.remove('active');
    const wizardStep3 = document.getElementById('wizard-step-3');
    if (wizardStep3) wizardStep3.classList.remove('active');
    // Reset headers
    [step1Header, step2Header, step3Header].forEach(h => { h.classList.remove('active','done'); });
    [stepSep1, stepSep2].forEach(s => { s.classList.remove('active','done'); });

    if (step === 1) {
        wizardStep1.classList.add('active');
        step1Header.classList.add('active');
        backBtn.textContent = strings.cancel;
        setNextLabel(strings.next);
        nextBtn.className = 'btn-save';
    } else if (step === 2) {
        wizardStep2.classList.add('active');
        step1Header.classList.add('done');
        step2Header.classList.add('active');
        stepSep1.classList.add('active');
        backBtn.textContent = strings.back;
        setNextLabel(strings.next);
        nextBtn.className = 'btn-save';
    } else if (step === 3) {
        if (wizardStep3) wizardStep3.classList.add('active');
        step1Header.classList.add('done');
        step2Header.classList.add('done');
        step3Header.classList.add('active');
        stepSep1.classList.add('done');
        stepSep2.classList.add('active');
        backBtn.textContent = strings.back;
        // Swap button to green Save Product
        nextBtn.className = 'btn-save-product';
        nextBtn.innerHTML = `
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><path d="M5 13l4 4L19 7"/></svg>
            ${strings.saveProduct}
        `;
        populateReview();
    }
}

// Generate Product Code Starting dynamically
function generateProductCode() {
    const tbody = document.getElementById('inventory-tbody');
    const existingRows = tbody ? tbody.querySelectorAll('tr') : [];
    let maxId = 0;
    
    existingRows.forEach(row => {
        const code = row.dataset.code || '';
        // Extract numeric sequence
        const numPart = code.replace(/[^0-9]/g, '');
        if (numPart) {
            const val = parseInt(numPart, 10);
            if (val > maxId) {
                maxId = val;
            }
        }
    });
    
    const nextId = maxId > 0 ? maxId + 1 : 1;
    document.getElementById('product-code').value = 'PRD-' + nextId;
}

// Step 1 Validation
function validateStep1() {
    const name = document.getElementById('product-name').value.trim();
    const category = document.getElementById('product-category').value;
    const price = document.getElementById('product-price').value.trim();

    if (!name) {
        showToast('error', strings.validationError, strings.nameRequired);
        document.getElementById('product-name').focus();
        return false;
    }
    if (!price || parseFloat(price) < 0) {
        showToast('error', strings.validationError, strings.validPriceRequired);
        document.getElementById('product-price').focus();
        return false;
    }
    if (!category) {
        showToast('error', strings.validationError, strings.categoryRequired);
        document.getElementById('product-category').focus();
        return false;
    }
    return true;
}

// Next / Save button action
nextBtn.addEventListener('click', (e) => {
    if (currentStep === 1) {
        if (validateStep1()) {
            showStep(2);
        }
    } else if (currentStep === 2) {
        if (addedVariants.length === 0) {
            showToast('error', strings.validationError, strings.variantRequired);
            return;
        }
        showStep(3);
    } else if (currentStep === 3) {
        // Step 3: prepare hidden inputs then submit
        let totalStock = 0;
        const uniqueSizes  = new Set();
        const uniqueColors = new Set();
        addedVariants.forEach(v => {
            totalStock += parseInt(v.qty, 10);
            uniqueSizes.add(v.size);
            uniqueColors.add(v.color);
        });
        document.getElementById('product-stock').value   = totalStock;
        document.getElementById('product-variants').value = `${uniqueSizes.size} S + ${uniqueColors.size} C`;
        const event = new Event('submit', { cancelable: true });
        addProductForm.dispatchEvent(event);
    }
});

// Back / Cancel button action
backBtn.addEventListener('click', () => {
    if (currentStep === 3) {
        showStep(2);
    } else if (currentStep === 2) {
        showStep(1);
    } else {
        closeAddModal();
    }
});

/* ─── Size & Color Selection Inside Builder ─── */
const sizeChips = document.getElementById('wizard-sizes');
const colorGrid = document.getElementById('wizard-colors');

sizeChips.addEventListener('click', (e) => {
    const chip = e.target.closest('.variant-chip');
    if (!chip) return;
    sizeChips.querySelectorAll('.variant-chip').forEach(c => c.classList.remove('selected'));
    chip.classList.add('selected');
    selectedSize = chip.dataset.size;
});

colorGrid.addEventListener('click', (e) => {
    const dot = e.target.closest('.color-dot-wrap');
    if (!dot) return;
    colorGrid.querySelectorAll('.color-dot-wrap').forEach(d => d.classList.remove('selected'));
    dot.classList.add('selected');
    selectedColor = dot.dataset.color;
    selectedColorHex = dot.dataset.hex;
});

/* Qty Counter Buttons */
const qtyInput = document.getElementById('wizard-qty-input');
document.getElementById('wizard-qty-dec').addEventListener('click', () => {
    let val = parseInt(qtyInput.value, 10) || 0;
    if (val > 1) qtyInput.value = val - 1;
});
document.getElementById('wizard-qty-inc').addEventListener('click', () => {
    let val = parseInt(qtyInput.value, 10) || 0;
    qtyInput.value = val + 1;
});

/* Add Variant Combination */
const addVariantBtn = document.getElementById('wizard-add-variant-btn');
addVariantBtn.addEventListener('click', () => {
    if (!selectedSize) {
        showToast('error', strings.selectSize, strings.selectSizeFirst);
        return;
    }
    if (!selectedColor) {
        showToast('error', strings.selectColor, strings.selectColorFirst);
        return;
    }
    const qty = parseInt(qtyInput.value, 10) || 0;
    if (qty <= 0) {
        showToast('error', strings.invalidQuantity, strings.validQuantityRequired);
        return;
    }

    // Check if combination already exists
    const duplicateIndex = addedVariants.findIndex(v => v.size === selectedSize && v.color === selectedColor);
    if (duplicateIndex > -1) {
        addedVariants[duplicateIndex].qty += qty;
    } else {
        addedVariants.push({
            size: selectedSize,
            color: selectedColor,
            colorHex: selectedColorHex,
            qty: qty
        });
    }

    updateVariantsList();
});

function updateVariantsList() {
    const container = document.getElementById('added-variants-list');
    container.innerHTML = '';

    if (addedVariants.length === 0) {
        container.innerHTML = `<div style="padding: 16px; text-align: center; color: var(--pos-muted); font-size: 12px;">${strings.noCombinations}</div>`;
        document.getElementById('var-summary-text').textContent = `0 ${strings.stock} · 0 ${strings.variants}`;
        document.getElementById('var-summary-badge').textContent = strings.totalQuantity.replace(':count', '0');
        return;
    }

    let totalStock = 0;
    const uniqueSizes = new Set();
    const uniqueColors = new Set();

    addedVariants.forEach((v, index) => {
        totalStock += v.qty;
        uniqueSizes.add(v.size);
        uniqueColors.add(v.color);

        const row = document.createElement('div');
        row.className = 'added-variant-row';
        row.innerHTML = `
            <div class="added-variant-info">
                <span class="added-variant-color-dot" style="background:${v.colorHex}"></span>
                <span>${strings.size}: <strong>${v.size}</strong> · ${strings.color}: <strong>${v.color}</strong></span>
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
                <span class="added-variant-qty">${v.qty} ${strings.items}</span>
                <button type="button" class="remove-variant-btn" onclick="removeVariant(${index})">×</button>
            </div>
        `;
        container.appendChild(row);
    });

    document.getElementById('var-summary-text').textContent = `${totalStock} ${strings.stock} · ${uniqueSizes.size} ${strings.sizes} · ${uniqueColors.size} ${strings.colors}`;
    document.getElementById('var-summary-badge').textContent = strings.totalQuantity.replace(':count', totalStock);
}

window.removeVariant = function(index) {
    addedVariants.splice(index, 1);
    updateVariantsList();
};

/* ══════════════════════════════════════════════
   FORM RESET
══════════════════════════════════════════════ */
function resetForm() {
    addProductForm.reset();
    addedVariants = [];
    selectedSize = '';
    selectedColor = '';
    selectedColorHex = '';
    sizeChips.querySelectorAll('.variant-chip').forEach(c => c.classList.remove('selected'));
    colorGrid.querySelectorAll('.color-dot-wrap').forEach(d => d.classList.remove('selected'));
    qtyInput.value = '10';
    updateVariantsList();
    
    /* Image */
    imagePreview.src = '';
    imagePreview.classList.remove('show');
    uploadPlaceholder.style.display = '';
    imageUploadArea.classList.remove('has-image');
    imageInput.value = '';
}

/* ══════════════════════════════════════════════
   FORM SUBMISSION — adds to BOTH Inventory & POS
══════════════════════════════════════════════ */
addProductForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    /* Client-side validation */
    const category = document.getElementById('product-category').value;
    const variants = document.getElementById('product-variants').value;

    if (!category.trim()) {
        showToast('error', strings.validationError, strings.categoryRequired);
        return;
    }
    if (!variants.trim()) {
        showToast('error', strings.validationError, strings.variantRequired);
        return;
    }

    const submitBtn = document.getElementById('wizard-next-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<span class="btn-spinner"></span>${strings.saving}`;

    const formData = new FormData(addProductForm);

    try {
        const response = await fetch('/inventory-management/add', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData,
        });

        if (!response.ok) throw new Error(`Server error: ${response.status}`);
        const result = await response.json();

        if (result.success) {
            const product = result.product;

            /* ── 1. Add row to Inventory table ─────────── */
            const tbody = document.getElementById('inventory-tbody');
            const tr = document.createElement('tr');
            tr.dataset.code     = product.code.toLowerCase();
            tr.dataset.name     = product.name.toLowerCase();
            tr.dataset.category = product.category.toLowerCase();
            tr.style.animation  = 'slideUp 0.3s ease';
            tr.onclick = () => window.location = `/inventory-management/${product.code}`;

            const price = parseFloat(product.price);
            tr.innerHTML = `
                <td>${product.code}</td>
                <td>${product.name}</td>
                <td>${product.category}</td>
                <td>${product.variants}</td>
                <td>${product.stock}</td>
                <td>${product.min_stock}</td>
                <td>${formatIQD(price)}</td>
                <td onclick="event.stopPropagation()">
                    <a class="action-link" href="/inventory-management/${product.code}">${strings.open}</a>
                </td>
            `;
            tbody.appendChild(tr);

            /* ── 2. Notify POS via BroadcastChannel so
                     the POS page (if open) reloads its
                     product grid without a full reload ── */
            if (typeof BroadcastChannel !== 'undefined') {
                const ch = new BroadcastChannel('pos_products');
                ch.postMessage({ type: 'product_added', product });
                ch.close();
            }

            /* ── 3. Close modal, reset, show toast ─────── */
            closeAddModal();
            showToast(
                'success',
                strings.addSuccess.replace(':product', product.name),
                strings.liveInPos
            );
        } else {
            showToast('error', strings.addFailed, strings.checkRequired);
        }

    } catch (err) {
        console.error('[AddProduct]', err);
        showToast('error', strings.networkError, err.message);
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = strings.save;
    }
});

/* ══════════════════════════════════════════════
   TABLE SEARCH & FILTER
══════════════════════════════════════════════ */
(() => {
    const searchInput    = document.getElementById('inventory-search');
    const categoryFilter = document.getElementById('category-filter');

    function getRows() {
        return Array.from(document.querySelectorAll('#inventory-tbody tr'));
    }

    function applyFilters() {
        const query    = searchInput.value.trim().toLowerCase();
        const category = categoryFilter.value;

        getRows().forEach(row => {
            const matchesCat   = category === 'all' || row.dataset.category === category;
            const matchesQuery = !query
                || row.dataset.code.includes(query)
                || row.dataset.name.includes(query)
                || row.dataset.category.includes(query);

            row.style.display = matchesCat && matchesQuery ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', applyFilters);
    categoryFilter.addEventListener('change', applyFilters);
})();
</script>
@endpush
