@extends('layouts.app')

@section('title', 'ETL Data Management | Superstore EIS')

@push('styles')
<style>
    /* =========================================
       ETL DATA MANAGEMENT — ENHANCED USER-FRIENDLY UI
       (Semua ID, elemen JS, route, & fungsi backend 100% utuh)
    ========================================= */
    .etl-shell {
        max-width: 1320px;
        margin: 0 auto;
        padding-bottom: 36px;
    }

    /* TOP NAVIGATION BAR */
    .etl-topbar-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 12px 18px;
        margin-bottom: 24px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(23, 43, 77, 0.03);
        flex-wrap: wrap;
    }

    .etl-breadcrumb-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .etl-home-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 8px;
        background: #F1F5F9;
        color: #172B4D;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }

    .etl-home-btn:hover {
        background: #EAF2FC;
        color: #285EA8;
        border-color: #BFD7F7;
    }

    .etl-crumb-sep {
        color: #CBD5E1;
        font-size: 14px;
    }

    .etl-crumb-current {
        font-size: 13px;
        font-weight: 600;
        color: #64748B;
    }

    .etl-toolbar-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .etl-tool-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 14px;
        border-radius: 8px;
        border: 1px solid #3976C5;
        background: #3976C5;
        color: #FFFFFF;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .etl-tool-btn:hover {
        border-color: #285EA8;
        color: #FFFFFF;
        background: #285EA8;
        transform: translateY(-1px);
    }

    .etl-user-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 14px;
        border-radius: 999px;
        background: #EAF7EF;
        color: #16834A;
        border: 1px solid #BCE5CC;
        font-size: 12.5px;
        font-weight: 700;
    }

    .etl-user-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #16834A;
    }

    /* HERO HEADER & DB STATS */
    .etl-hero-panel {
        background: linear-gradient(135deg, #172B4D 0%, #23477E 65%, #285EA8 100%);
        border-radius: 16px;
        padding: 28px 32px;
        color: #FFFFFF;
        margin-bottom: 24px;
        box-shadow: 0 10px 25px rgba(23, 43, 77, 0.12);
    }

    .etl-hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .etl-hero-eyebrow {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.14);
        color: #DCEAFB;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.1px;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .etl-hero-top h1 {
        font-size: 28px;
        font-weight: 800;
        color: #FFFFFF;
        letter-spacing: -0.6px;
        margin: 0 0 6px;
        line-height: 1.25;
    }

    .etl-hero-top p {
        font-size: 14px;
        color: #D6E4F5;
        margin: 0;
        max-width: 680px;
        line-height: 1.6;
    }

    .etl-hero-badge-info {
        background: rgba(255, 255, 255, 0.10);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 12.5px;
        color: #E2ECF9;
        line-height: 1.5;
        max-width: 280px;
    }

    .etl-db-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .etl-db-stat {
        background: rgba(255, 255, 255, 0.09);
        border: 1px solid rgba(255, 255, 255, 0.16);
        backdrop-filter: blur(6px);
        border-radius: 12px;
        padding: 15px 18px;
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .etl-db-stat:hover {
        background: rgba(255, 255, 255, 0.13);
        transform: translateY(-2px);
    }

    .etl-db-stat-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .etl-db-stat span {
        font-size: 12px;
        font-weight: 600;
        color: #C9DCF3;
    }

    .etl-db-stat-icon {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: rgba(255, 255, 255, 0.14);
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .etl-db-stat strong {
        display: block;
        font-size: 22px;
        font-weight: 800;
        color: #FFFFFF;
        letter-spacing: -0.4px;
    }

    /* PIPELINE CARD */
    .etl-pipeline-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(23, 43, 77, 0.04);
        margin-bottom: 28px;
        overflow: hidden;
    }

    /* STEPPER HEADER BAR */
    .etl-stepper-header {
        background: #F8FAFC;
        border-bottom: 1px solid #E2E8F0;
        padding: 24px 32px 22px;
    }

    .etl-stepper {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        position: relative;
        max-width: 880px;
        margin: 0 auto;
    }

    .etl-step {
        position: relative;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        z-index: 2;
    }

    .etl-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 20px;
        left: calc(50% + 24px);
        width: calc(100% - 48px);
        height: 3px;
        border-radius: 999px;
        background: #DCE3ED;
        z-index: 1;
        transition: background 0.3s ease;
    }

    .etl-step.completed:not(:last-child)::after {
        background: #16834A;
    }

    .etl-step.processing:not(:last-child)::after {
        background: linear-gradient(90deg, #3976C5 40%, #DCE3ED 100%);
    }

    .etl-step.failed:not(:last-child)::after {
        background: #FCA5A5;
    }

    .etl-step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 800;
        background: #FFFFFF;
        color: #64748B;
        border: 2px solid #CBD5E1;
        transition: all 0.25s ease;
        margin-bottom: 8px;
        box-shadow: 0 2px 5px rgba(15, 23, 42, 0.04);
    }

    .etl-step-label {
        font-size: 13px;
        font-weight: 700;
        color: #64748B;
        line-height: 1.3;
    }

    .etl-step-status-tag {
        display: inline-block;
        margin-top: 4px;
        padding: 2px 8px;
        border-radius: 999px;
        font-size: 10.5px;
        font-weight: 700;
        background: #EDF1F6;
        color: #64748B;
    }

    /* Step states */
    .etl-step.processing .etl-step-circle {
        background: #3976C5;
        color: #FFFFFF;
        border-color: #3976C5;
        box-shadow: 0 0 0 5px rgba(57, 118, 197, 0.16);
    }
    .etl-step.processing .etl-step-label {
        color: #1E4E8C;
    }
    .etl-step.processing .etl-step-status-tag {
        background: #E1EEFD;
        color: #285EA8;
    }

    .etl-step.completed .etl-step-circle {
        background: #16834A;
        color: #FFFFFF;
        border-color: #16834A;
    }
    .etl-step.completed .etl-step-label {
        color: #16834A;
    }
    .etl-step.completed .etl-step-status-tag {
        background: #EAF7EF;
        color: #16834A;
    }

    .etl-step.failed .etl-step-circle {
        background: #D64545;
        color: #FFFFFF;
        border-color: #D64545;
        box-shadow: 0 0 0 5px rgba(214, 69, 69, 0.16);
    }
    .etl-step.failed .etl-step-label {
        color: #D64545;
    }
    .etl-step.failed .etl-step-status-tag {
        background: #FDEEEE;
        color: #D64545;
    }

    /* STAGE BODY CONTAINER */
    .etl-stage-body {
        padding: 30px 32px;
    }

    .etl-stage-panel {
        display: none;
        animation: fadeInStage 0.25s ease;
    }

    .etl-stage-panel.active {
        display: block;
    }

    @keyframes fadeInStage {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* STAGE 1: TWO-COLUMN USER-FRIENDLY UPLOAD WORKSPACE */
    .upload-workspace-grid {
        display: grid;
        grid-template-columns: 1.15fr 0.85fr;
        gap: 24px;
        align-items: stretch;
    }

    .upload-col-left,
    .upload-col-right {
        display: flex;
        flex-direction: column;
    }

    .step-section-title {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 14.5px;
        font-weight: 800;
        color: #172B4D;
        margin-bottom: 12px;
    }

    .step-num-pill {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        background: #EAF2FC;
        color: #285EA8;
        font-size: 12px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .dropzone-area {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed #BFD3EE;
        border-radius: 14px;
        background: #F8FBFF;
        padding: 36px 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        min-height: 240px;
    }

    .dropzone-area:hover,
    .dropzone-area.dragover {
        border-color: #3976C5;
        background: #EEF5FE;
        box-shadow: inset 0 0 0 3px rgba(57, 118, 197, 0.08);
    }

    .dropzone-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 14px;
        border-radius: 16px;
        background: #E1EEFD;
        color: #285EA8;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease;
    }

    .dropzone-area:hover .dropzone-icon {
        transform: translateY(-3px);
    }

    .dropzone-title {
        font-size: 17px;
        font-weight: 800;
        color: #172B4D;
        margin-bottom: 6px;
    }

    .dropzone-sub {
        font-size: 13px;
        color: #64748B;
        margin-bottom: 18px;
        max-width: 380px;
        line-height: 1.55;
    }

    .dropzone-specs {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 14px;
        flex-wrap: wrap;
    }

    .spec-chip {
        padding: 4px 10px;
        border-radius: 6px;
        background: #FFFFFF;
        border: 1px solid #DCE3ED;
        color: #475569;
        font-size: 11.5px;
        font-weight: 600;
    }

    .file-selected-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 14px;
        padding: 14px 16px;
        background: #EFF6FF;
        border: 1.5px solid #93C5FD;
        border-radius: 12px;
    }

    .file-selected-info {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .file-badge-csv {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #285EA8;
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 12px;
        flex-shrink: 0;
    }

    .file-selected-meta strong {
        display: block;
        font-size: 14px;
        color: #172B4D;
        word-break: break-all;
    }

    .file-selected-meta span {
        font-size: 12px;
        color: #475569;
    }

    /* METHOD CARDS (STACKED CLEANLY ON RIGHT COLUMN) */
    .method-stack {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .method-card {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 15px 16px;
        border: 1.5px solid #DCE3ED;
        border-radius: 12px;
        background: #FFFFFF;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .method-card:hover {
        border-color: #93C5FD;
        background: #FAFBFD;
    }

    .method-card.selected {
        border-color: #3976C5;
        background: #F4F9FF;
        box-shadow: 0 0 0 3px rgba(57, 118, 197, 0.10);
    }

    .method-card input[type="radio"] {
        margin-top: 3px;
        accent-color: #3976C5;
        width: 17px;
        height: 17px;
        flex-shrink: 0;
    }

    .method-card-head {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 3px;
    }

    .method-card strong {
        font-size: 14.5px;
        color: #172B4D;
    }

    .method-tag {
        padding: 2px 7px;
        border-radius: 5px;
        font-size: 10.5px;
        font-weight: 700;
    }

    .method-tag.warn { background: #FEF3C7; color: #B45309; }
    .method-tag.safe { background: #EAF2FC; color: #285EA8; }

    .method-card span.method-desc {
        display: block;
        font-size: 12.5px;
        color: #64748B;
        line-height: 1.5;
    }

    .replace-confirm-box {
        margin-top: 12px;
        padding: 13px 15px;
        border-radius: 10px;
        background: #FFFBEB;
        border: 1px solid #FCD34D;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 12.5px;
        line-height: 1.55;
        color: #92400E;
        cursor: pointer;
    }

    .replace-confirm-box input[type="checkbox"] {
        margin-top: 2px;
        accent-color: #D97706;
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .etl-guide-box {
        margin-top: auto;
        padding-top: 14px;
    }

    .etl-info-banner {
        padding: 12px 14px;
        border-radius: 10px;
        background: #F1F5F9;
        border: 1px solid #E2E8F0;
        color: #475569;
        font-size: 12.5px;
        line-height: 1.55;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    /* BUTTONS */
    .btn-primary-etl {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 22px;
        border-radius: 9px;
        border: 1px solid #3976C5;
        background: #3976C5;
        color: #FFFFFF;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-primary-etl:hover:not(:disabled) {
        background: #285EA8;
        border-color: #285EA8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 94, 168, 0.22);
    }

    .btn-primary-etl:disabled {
        background: #94A3B8;
        border-color: #94A3B8;
        cursor: not-allowed;
        opacity: 0.72;
    }

    .btn-secondary-etl {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 9px;
        border: 1px solid #CBD5E1;
        background: #FFFFFF;
        color: #172B4D;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-secondary-etl:hover {
        border-color: #3976C5;
        color: #285EA8;
        background: #F8FAFC;
    }

    .stage-footer-actions {
        margin-top: 26px;
        padding-top: 20px;
        border-top: 1px solid #EDF1F6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .helper-hint-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #64748B;
        background: #F8FAFC;
        padding: 8px 14px;
        border-radius: 8px;
        border: 1px solid #E2E8F0;
    }

    /* STAGE 2: EXTRACT PROCESSING */
    .extract-progress-box {
        background: linear-gradient(180deg, #F4F8FE 0%, #FFFFFF 100%);
        border: 1px solid #D3E3FA;
        border-radius: 14px;
        padding: 36px 24px;
        text-align: center;
        margin-bottom: 22px;
    }

    .spinner-ring {
        width: 52px;
        height: 52px;
        margin: 0 auto 16px;
        border: 4px solid #D3E3FA;
        border-top-color: #3976C5;
        border-radius: 50%;
        animation: spinRing 0.85s linear infinite;
    }

    @keyframes spinRing {
        to { transform: rotate(360deg); }
    }

    .progress-bar-wrap {
        max-width: 560px;
        margin: 20px auto 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .progress-bar-track {
        flex: 1;
        height: 11px;
        background: #DCE7F7;
        border-radius: 999px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #3976C5, #285EA8);
        border-radius: 999px;
        transition: width 0.35s ease;
    }

    .info-kv-card {
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 20px 24px;
        background: #FFFFFF;
    }

    .info-kv-card h4 {
        font-size: 15px;
        font-weight: 800;
        color: #172B4D;
        margin-bottom: 14px;
    }

    .kv-grid-2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4px 32px;
    }

    .kv-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #F1F5F9;
        font-size: 13.5px;
        gap: 12px;
    }

    .kv-row:last-child {
        border-bottom: none;
    }

    .kv-key {
        color: #64748B;
        font-weight: 600;
    }

    .kv-val {
        color: #172B4D;
        font-weight: 700;
        text-align: right;
    }

    /* STAGE 3 & 4: STATUS BANNERS & METRICS */
    .status-banner {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 22px;
    }

    .status-banner.success { background: #EAF7EF; border: 1px solid #B7E4C7; color: #146C3B; }
    .status-banner.info    { background: #EEF5FE; border: 1px solid #C2DAFA; color: #1E4E8C; }
    .status-banner.warning { background: #FFFBEB; border: 1px solid #FDE68A; color: #92400E; }
    .status-banner.danger  { background: #FDEEEE; border: 1px solid #F8B4B4; color: #B42318; }

    .status-banner-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 16px;
        flex-shrink: 0;
    }

    .status-banner.success .status-banner-icon { background: #16834A; color: #FFFFFF; }
    .status-banner.info .status-banner-icon    { background: #3976C5; color: #FFFFFF; }
    .status-banner.warning .status-banner-icon { background: #D97706; color: #FFFFFF; }
    .status-banner.danger .status-banner-icon  { background: #D64545; color: #FFFFFF; }

    .validation-cards-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .val-metric-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
    }

    .val-metric-card.blue  { background: #F2F7FE; border-color: #C9DDF9; }
    .val-metric-card.green { background: #EFFAF3; border-color: #BCE8CC; }
    .val-metric-card.red   { background: #FEF2F2; border-color: #FECACA; }

    .val-metric-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 800;
        color: #FFFFFF;
        flex-shrink: 0;
    }

    .val-metric-card.blue .val-metric-icon  { background: #3976C5; }
    .val-metric-card.green .val-metric-icon { background: #16834A; }
    .val-metric-card.red .val-metric-icon   { background: #D64545; }

    .val-metric-card strong {
        display: block;
        font-size: 26px;
        font-weight: 800;
        line-height: 1.2;
    }

    .val-metric-card.blue strong  { color: #1E4E8C; }
    .val-metric-card.green strong { color: #16834A; }
    .val-metric-card.red strong   { color: #D64545; }

    .val-metric-card span {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .checklist-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px 28px;
        padding: 18px 22px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        margin-bottom: 18px;
    }

    .check-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13.5px;
        padding: 6px 0;
        border-bottom: 1px dashed #E2E8F0;
    }

    .check-item:nth-last-child(-n+2) {
        border-bottom: none;
    }

    .check-item-left {
        color: #172B4D;
        font-weight: 600;
    }

    .badge-pill {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-pill.green { background: #EAF7EF; color: #16834A; }
    .badge-pill.red   { background: #FDEEEE; color: #D64545; }
    .badge-pill.amber { background: #FEF3C7; color: #B45309; }
    .badge-pill.blue  { background: #EAF2FC; color: #285EA8; }

    /* REVIEW SPLIT GRID */
    .review-top-grid {
        display: grid;
        grid-template-columns: 330px 1fr;
        gap: 20px;
        margin-bottom: 22px;
    }

    .review-box {
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 20px;
        background: #FFFFFF;
    }

    .review-box h4 {
        font-size: 15px;
        font-weight: 800;
        color: #172B4D;
        margin-bottom: 12px;
    }

    .mini-table-wrap {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
    }

    .etl-data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .etl-data-table th {
        background: #F8FAFC;
        color: #64748B;
        font-weight: 700;
        text-align: left;
        padding: 11px 14px;
        border-bottom: 1px solid #E2E8F0;
        white-space: nowrap;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .etl-data-table td {
        padding: 11px 14px;
        border-bottom: 1px solid #F1F5F9;
        color: #172B4D;
        white-space: nowrap;
    }

    .etl-data-table tbody tr:hover {
        background: #F8FAFC;
    }

    .etl-data-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* STAGE 5: SUCCESS HERO */
    .load-success-hero {
        background: linear-gradient(180deg, #EFFAF3 0%, #FFFFFF 100%);
        border: 1px solid #BCE8CC;
        border-radius: 14px;
        padding: 36px 24px;
        text-align: center;
    }

    .load-success-badge {
        width: 60px;
        height: 60px;
        margin: 0 auto 14px;
        border-radius: 50%;
        background: #16834A;
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 800;
        box-shadow: 0 8px 20px rgba(22, 131, 74, 0.22);
    }

    .load-success-metrics {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        max-width: 840px;
        margin: 26px auto 0;
    }

    .load-metric-box {
        background: #FFFFFF;
        border: 1px solid #D1EEDB;
        border-radius: 12px;
        padding: 18px;
        text-align: left;
        box-shadow: 0 2px 6px rgba(22, 131, 74, 0.04);
    }

    .load-metric-box strong {
        display: block;
        font-size: 20px;
        font-weight: 800;
        color: #172B4D;
        margin-bottom: 4px;
    }

    .load-metric-box span {
        font-size: 12.5px;
        color: #64748B;
        font-weight: 600;
    }

    /* FAILED PANEL */
    .error-details-card {
        border: 1px solid #FECACA;
        background: #FFFDFD;
        border-radius: 12px;
        padding: 20px 24px;
    }

    .error-details-card h4 {
        font-size: 15px;
        font-weight: 800;
        color: #991B1B;
        margin-bottom: 10px;
    }

    .error-details-list {
        margin: 0;
        padding-left: 20px;
        color: #7F1D1D;
        font-size: 13.5px;
        line-height: 1.8;
    }

    /* IMPORT HISTORY SECTION */
    .history-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 26px 30px;
        box-shadow: 0 4px 16px rgba(23, 43, 77, 0.04);
    }

    .history-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .history-header h2 {
        font-size: 19px;
        font-weight: 800;
        color: #172B4D;
        margin: 0 0 3px;
    }

    .history-header p {
        font-size: 13px;
        color: #64748B;
        margin: 0;
    }

    .history-filters {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .history-filters input,
    .history-filters select {
        height: 40px;
        padding: 0 13px;
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        font-size: 13px;
        background: #FFFFFF;
        color: #172B4D;
        outline: none;
        transition: border-color 0.2s ease;
    }

    .history-filters input:focus,
    .history-filters select:focus {
        border-color: #3976C5;
    }

    @media (max-width: 1024px) {
        .upload-workspace-grid { grid-template-columns: 1fr; }
        .review-top-grid { grid-template-columns: 1fr; }
        .etl-db-stats { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .etl-hero-panel { padding: 22px 20px; }
        .etl-stepper-header { padding: 20px 16px; }
        .etl-stage-body { padding: 22px 18px; }
        .validation-cards-grid,
        .load-success-metrics,
        .checklist-grid,
        .kv-grid-2col { grid-template-columns: 1fr; }
        .etl-step-label { font-size: 11px; }
    }

    @media (max-width: 520px) {
        .etl-db-stats { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="etl-shell">

    {{-- TOP UTILITY BAR --}}
    <nav class="etl-topbar-nav" aria-label="Navigasi Modul ETL">
        <div class="etl-breadcrumb-wrap">
            <a href="{{ route('home') }}" class="etl-home-btn">
                Kembali ke Beranda
            </a>
            <span class="etl-crumb-sep">/</span>
            <span class="etl-crumb-current">Data Preparation &amp; ETL Pipeline</span>
        </div>

        <div class="etl-toolbar-right">
            <a href="{{ route('etl.manual.page') }}" class="etl-tool-btn">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Input Manual
            </a>
            <a href="{{ route('etl.condition') }}" class="etl-tool-btn">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Kondisi Data
            </a>
            <a href="{{ route('etl.export') }}" class="etl-tool-btn">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export CSV
            </a>
            <span class="etl-user-pill">
                <span class="etl-user-dot"></span>
                {{ Auth::user()->name ?? 'Administrator' }}
            </span>
        </div>
    </nav>

    {{-- HERO HEADER + LIVE DATABASE METRICS --}}
    <section class="etl-hero-panel">
        <div class="etl-hero-top">
            <div>
                <span class="etl-hero-eyebrow">DATA PREPARATION MODULE</span>
                <h1>ETL Data Management</h1>
                <p>Kelola proses ekstraksi, transformasi, validasi kualitas data, dan pemuatan dataset transaksi ke dalam Superstore Executive Information System.</p>
            </div>
            <div class="etl-hero-badge-info">
                <strong>Alur Semi-Otomatis:</strong><br>
                Extract &amp; Transform berjalan otomatis. Tahap <strong>Load</strong> membutuhkan tinjauan &amp; konfirmasi Anda.
            </div>
        </div>

        <div class="etl-db-stats" id="dbStatsGrid">
            <div class="etl-db-stat">
                <div class="etl-db-stat-head">
                    <span>Total Baris di Database</span>
                    <div class="etl-db-stat-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                    </div>
                </div>
                <strong id="statTotalRows">{{ number_format($summary['total_rows'] ?? 0, 0, ',', '.') }}</strong>
            </div>

            <div class="etl-db-stat">
                <div class="etl-db-stat-head">
                    <span>Total Pesanan (Orders)</span>
                    <div class="etl-db-stat-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                </div>
                <strong id="statTotalOrders">{{ number_format($summary['total_orders'] ?? 0, 0, ',', '.') }}</strong>
            </div>

            <div class="etl-db-stat">
                <div class="etl-db-stat-head">
                    <span>Total Sales Aktif</span>
                    <div class="etl-db-stat-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <strong id="statTotalSales">${{ number_format($summary['total_sales'] ?? 0, 2, ',', '.') }}</strong>
            </div>

            <div class="etl-db-stat">
                <div class="etl-db-stat-head">
                    <span>Total Profit Aktif</span>
                    <div class="etl-db-stat-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>
                <strong id="statTotalProfit">${{ number_format($summary['total_profit'] ?? 0, 2, ',', '.') }}</strong>
            </div>
        </div>
    </section>

    {{-- MAIN WORKSPACE PIPELINE CARD --}}
    <section class="etl-pipeline-card">

        {{-- 5-STAGE PROGRESS STEPPER --}}
        <div class="etl-stepper-header">
            <div class="etl-stepper" id="etlStepper">
                <div class="etl-step processing" data-step="1">
                    <div class="etl-step-circle" id="stepCircle1">1</div>
                    <div class="etl-step-label">Upload File</div>
                    <div class="etl-step-status-tag" id="stepTag1">Aktif</div>
                </div>
                <div class="etl-step pending" data-step="2">
                    <div class="etl-step-circle" id="stepCircle2">2</div>
                    <div class="etl-step-label">Extract</div>
                    <div class="etl-step-status-tag" id="stepTag2">Pending</div>
                </div>
                <div class="etl-step pending" data-step="3">
                    <div class="etl-step-circle" id="stepCircle3">3</div>
                    <div class="etl-step-label">Transform</div>
                    <div class="etl-step-status-tag" id="stepTag3">Pending</div>
                </div>
                <div class="etl-step pending" data-step="4">
                    <div class="etl-step-circle" id="stepCircle4">4</div>
                    <div class="etl-step-label">Review</div>
                    <div class="etl-step-status-tag" id="stepTag4">Pending</div>
                </div>
                <div class="etl-step pending" data-step="5">
                    <div class="etl-step-circle" id="stepCircle5">5</div>
                    <div class="etl-step-label">Load</div>
                    <div class="etl-step-status-tag" id="stepTag5">Pending</div>
                </div>
            </div>
        </div>

        <div class="etl-stage-body">

            {{-- ======================================================
                 PANEL 1: UPLOAD FILE & KONFIGURASI IMPORT
            ====================================================== --}}
            <div class="etl-stage-panel active" id="panelStage1">
                <div class="upload-workspace-grid">

                    {{-- KOLOM KIRI: DROPZONE FILE --}}
                    <div class="upload-col-left">
                        <div class="step-section-title">
                            <span class="step-num-pill">A</span>
                            <span>Unggah Dataset Transaksi CSV</span>
                        </div>

                        <div class="dropzone-area" id="dropzoneArea">
                            <input type="file" id="csvFileInput" accept=".csv,text/csv" hidden>
                            <div class="dropzone-icon">
                                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <div class="dropzone-title">Seret &amp; Lepas File CSV di Sini</div>
                            <div class="dropzone-sub">Atau klik tombol di bawah untuk memilih file transaksi Superstore dari komputer Anda.</div>
                            <button type="button" class="btn-primary-etl" id="chooseFileBtn">
                                Choose CSV File
                            </button>
                            <div class="dropzone-specs">
                                <span class="spec-chip">Format: .CSV</span>
                                <span class="spec-chip">Struktur: 21 Kolom</span>
                                <span class="spec-chip">Maks. 20 MB</span>
                            </div>
                        </div>

                        {{-- PREVIEW FILE TERPILIH --}}
                        <div class="file-selected-card" id="selectedFileCard" style="display: none;">
                            <div class="file-selected-info">
                                <div class="file-badge-csv">CSV</div>
                                <div class="file-selected-meta">
                                    <strong id="selectedFileName">-</strong>
                                    <span id="selectedFileMeta">-</span>
                                </div>
                            </div>
                            <button type="button" class="btn-secondary-etl" id="removeFileBtn" style="padding: 7px 13px; font-size: 12.5px;">Ganti File</button>
                        </div>

                        <div id="uploadValidationError" class="status-banner danger" style="display: none; margin-top: 14px; margin-bottom: 0;">
                            <div class="status-banner-icon">!</div>
                            <div id="uploadValidationErrorText" style="font-size: 13px; font-weight: 600; align-self: center;"></div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: PILIHAN METODE IMPORT --}}
                    <div class="upload-col-right">
                        <div class="step-section-title">
                            <span class="step-num-pill">B</span>
                            <span>Pilih Metode Pemuatan Data</span>
                        </div>

                        <div class="method-stack">
                            <label class="method-card selected" id="cardMethodReplace">
                                <input type="radio" name="import_method" value="replace" checked>
                                <div>
                                    <div class="method-card-head">
                                        <strong>Replace</strong>
                                        <span class="method-tag warn">Timpa Semua</span>
                                    </div>
                                    <span class="method-desc">Mengganti seluruh data transaksi lama di database dengan isi dataset CSV yang baru.</span>
                                </div>
                            </label>

                            <label class="method-card" id="cardMethodAppend">
                                <input type="radio" name="import_method" value="append">
                                <div>
                                    <div class="method-card-head">
                                        <strong>Append</strong>
                                        <span class="method-tag safe">Tambah Baru</span>
                                    </div>
                                    <span class="method-desc">Menambahkan baris transaksi baru tanpa menghapus data yang sudah tersimpan (Row ID harus unik).</span>
                                </div>
                            </label>
                        </div>

                        {{-- KONFIRMASI WAJIB UNTUK REPLACE --}}
                        <label class="replace-confirm-box" id="replaceConfirmBox">
                            <input type="checkbox" id="replaceConfirmCheckbox">
                            <span>
                                <strong>Konfirmasi Replace:</strong> Saya memahami bahwa seluruh data transaksi lama akan diganti setelah tahap Review disetujui.
                            </span>
                        </label>

                        <div class="etl-guide-box">
                            <div class="etl-info-banner">
                                <svg width="18" height="18" fill="none" stroke="#285EA8" stroke-width="2.2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Pastikan header CSV terdiri dari 21 kolom standar (<code>Row ID</code> hingga <code>Profit</code>) agar proses Extract &amp; Transform berjalan lancar.</span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="stage-footer-actions">
                    <div class="helper-hint-pill">
                        <svg width="15" height="15" fill="none" stroke="#3976C5" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span id="uploadHelperHint">Unggah file CSV transaksi Superstore terlebih dahulu.</span>
                    </div>
                    <button type="button" class="btn-primary-etl" id="startEtlBtn" disabled>
                        Mulai Proses ETL
                    </button>
                </div>
            </div>

            {{-- ======================================================
                 PANEL 2: EXTRACT (OTOMATIS)
            ====================================================== --}}
            <div class="etl-stage-panel" id="panelStage2">
                <div class="extract-progress-box">
                    <div class="spinner-ring" id="extractSpinner"></div>
                    <h3 style="font-size: 20px; font-weight: 800; color: #172B4D; margin-bottom: 6px;" id="extractStatusHeading">
                        Proses Extract Berjalan...
                    </h3>
                    <p style="font-size: 13.5px; color: #475569; max-width: 540px; margin: 0 auto;" id="extractStatusDesc">
                        Sistem sedang membaca file CSV, memeriksa header 21 kolom, dan memvalidasi kelengkapan struktur data.
                    </p>
                    <div class="progress-bar-wrap">
                        <div class="progress-bar-track">
                            <div class="progress-bar-fill" id="extractProgressBar"></div>
                        </div>
                        <strong style="font-size: 13.5px; color: #172B4D; min-width: 44px;" id="extractProgressPct">0%</strong>
                    </div>
                </div>

                <div class="info-kv-card">
                    <h4>Informasi Proses Extract</h4>
                    <div class="kv-grid-2col">
                        <div>
                            <div class="kv-row">
                                <span class="kv-key">Nama File</span>
                                <span class="kv-val" id="extractFileName">-</span>
                            </div>
                            <div class="kv-row">
                                <span class="kv-key">Metode Import</span>
                                <span class="kv-val" id="extractMethodLabel">-</span>
                            </div>
                            <div class="kv-row">
                                <span class="kv-key">Waktu Mulai Proses</span>
                                <span class="kv-val" id="extractStartTime">-</span>
                            </div>
                        </div>
                        <div>
                            <div class="kv-row">
                                <span class="kv-key">Total Baris Ditemukan</span>
                                <span class="kv-val" id="extractTotalRows">Sedang menghitung...</span>
                            </div>
                            <div class="kv-row">
                                <span class="kv-key">Total Kolom Terdeteksi</span>
                                <span class="kv-val" id="extractTotalCols">Memeriksa 21 kolom...</span>
                            </div>
                            <div class="kv-row">
                                <span class="kv-key">Status Extract</span>
                                <span class="kv-val"><span class="badge-pill blue" id="extractBadge">Processing</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ======================================================
                 PANEL 3: TRANSFORM & VALIDATE (RINGKASAN HASIL)
            ====================================================== --}}
            <div class="etl-stage-panel" id="panelStage3">
                <div class="status-banner success" id="transformBanner">
                    <div class="status-banner-icon" id="transformBannerIcon">✓</div>
                    <div>
                        <strong style="display: block; font-size: 15px;" id="transformBannerTitle">Transform &amp; Validasi Berhasil!</strong>
                        <span style="font-size: 13px;" id="transformBannerSub">Data telah dibersihkan, diseragamkan format tanggal/angkanya, dan divalidasi secara otomatis.</span>
                    </div>
                </div>

                <div class="validation-cards-grid">
                    <div class="val-metric-card blue">
                        <div class="val-metric-icon">≡</div>
                        <div>
                            <strong id="valTotalChecked">0</strong>
                            <span>Data Diperiksa</span>
                        </div>
                    </div>

                    <div class="val-metric-card green">
                        <div class="val-metric-icon">✓</div>
                        <div>
                            <strong id="valValidCount">0</strong>
                            <span id="valValidPct">Data Valid (0%)</span>
                        </div>
                    </div>

                    <div class="val-metric-card red">
                        <div class="val-metric-icon">!</div>
                        <div>
                            <strong id="valInvalidCount">0</strong>
                            <span id="valInvalidPct">Data Bermasalah (0%)</span>
                        </div>
                    </div>
                </div>

                <h4 style="font-size: 15px; font-weight: 800; color: #172B4D; margin-bottom: 10px;">Detail Pemeriksaan Kualitas Data</h4>
                <div class="checklist-grid">
                    <div class="check-item">
                        <span class="check-item-left">Struktur Kolom (21 kolom)</span>
                        <span class="badge-pill green" id="chkStructure">Sesuai</span>
                    </div>
                    <div class="check-item">
                        <span class="check-item-left">Nilai Kosong (Missing Values)</span>
                        <span class="badge-pill green" id="chkMissing">0</span>
                    </div>
                    <div class="check-item">
                        <span class="check-item-left">Validasi Tipe Data</span>
                        <span class="badge-pill green" id="chkDataType">Sesuai</span>
                    </div>
                    <div class="check-item">
                        <span class="check-item-left">Format Tanggal &amp; Angka</span>
                        <span class="badge-pill green" id="chkDateFormat">Sesuai</span>
                    </div>
                    <div class="check-item">
                        <span class="check-item-left">Duplikasi Row ID</span>
                        <span class="badge-pill green" id="chkDuplicates">0</span>
                    </div>
                    <div class="check-item">
                        <span class="check-item-left">Konsistensi Data</span>
                        <span class="badge-pill green" id="chkConsistency">Sesuai</span>
                    </div>
                </div>

                <div class="status-banner warning" id="transformWarningBox" style="display: none;">
                    <div class="status-banner-icon">!</div>
                    <div style="font-size: 13px;" id="transformWarningList"></div>
                </div>

                <div class="stage-footer-actions">
                    <button type="button" class="btn-secondary-etl" onclick="resetToUpload()">← Kembali ke Upload</button>
                    <button type="button" class="btn-primary-etl" onclick="goToReview()">Lanjut ke Review →</button>
                </div>
            </div>

            {{-- ======================================================
                 PANEL 4: REVIEW HASIL VALIDASI (KONTROL ADMIN)
            ====================================================== --}}
            <div class="etl-stage-panel" id="panelStage4">
                <div class="status-banner info" id="reviewBanner">
                    <div class="status-banner-icon">i</div>
                    <div>
                        <strong style="display: block; font-size: 15px;" id="reviewBannerTitle">Review Hasil Validasi</strong>
                        <span style="font-size: 13px;" id="reviewBannerDesc">Periksa ringkasan validasi dan pratinjau dataset sebelum memberikan konfirmasi penyimpanan ke database.</span>
                    </div>
                </div>

                <div class="review-top-grid">
                    {{-- A. RINGKASAN DATA --}}
                    <div class="review-box">
                        <h4>Ringkasan Validasi</h4>
                        <div class="kv-row">
                            <span class="kv-key">Total Data</span>
                            <span class="kv-val"><span class="badge-pill blue" id="revTotalData">0</span></span>
                        </div>
                        <div class="kv-row">
                            <span class="kv-key">Data Valid</span>
                            <span class="kv-val"><span class="badge-pill green" id="revValidData">0</span></span>
                        </div>
                        <div class="kv-row">
                            <span class="kv-key">Data Bermasalah</span>
                            <span class="kv-val"><span class="badge-pill red" id="revInvalidData">0</span></span>
                        </div>
                        <div class="kv-row">
                            <span class="kv-key">Metode Load</span>
                            <span class="kv-val" id="revMethodText" style="text-transform: uppercase;">-</span>
                        </div>
                    </div>

                    {{-- B. TABEL DATA BERMASALAH --}}
                    <div class="review-box">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                            <h4 style="margin:0;">Detail Data Bermasalah</h4>
                            <span class="badge-pill amber" id="problemCountLabel">0 temuan</span>
                        </div>
                        <div class="mini-table-wrap" style="max-height: 215px; overflow-y: auto;">
                            <table class="etl-data-table">
                                <thead>
                                    <tr>
                                        <th>Baris</th>
                                        <th>Order ID</th>
                                        <th>Kolom Bermasalah</th>
                                        <th>Nilai Ditemukan</th>
                                        <th>Keterangan Kesalahan</th>
                                    </tr>
                                </thead>
                                <tbody id="problemTableBody">
                                    <tr>
                                        <td colspan="5" style="text-align:center; color:#16834A; font-weight:600; padding:20px;">
                                            Tidak ditemukan kesalahan data. Seluruh baris siap dimuat.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- WARNING BISNIS (JIKA ADA) --}}
                <div class="status-banner warning" id="reviewWarningBox" style="display: none;">
                    <div class="status-banner-icon">!</div>
                    <div style="font-size: 13px;" id="reviewWarningContent"></div>
                </div>

                {{-- ALASAN BLOCK JIKA ADA ERROR FATAL --}}
                <div class="status-banner danger" id="reviewFatalBlockBox" style="display: none;">
                    <div class="status-banner-icon">✕</div>
                    <div style="font-size: 13.5px;">
                        <strong>Proses Load Diblokir:</strong> Ditemukan error validasi wajib pada file CSV. Perbaiki baris bermasalah terlebih dahulu lalu unggah ulang file. Sistem tidak mengizinkan impor data yang gagal validasi.
                    </div>
                </div>

                {{-- C. PREVIEW DATASET (5 BARIS PERTAMA) --}}
                <div class="review-box">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; flex-wrap:wrap; gap:8px;">
                        <h4 style="margin:0;">Preview Data (5 Baris Pertama Hasil Transformasi)</h4>
                        <button type="button" class="btn-secondary-etl" id="toggleAllColsBtn" style="padding: 6px 13px; font-size: 12.5px;">
                            Tampilkan Semua 21 Kolom
                        </button>
                    </div>
                    <div class="mini-table-wrap">
                        <table class="etl-data-table" id="previewDatasetTable">
                            <thead id="previewTableHead"></thead>
                            <tbody id="previewTableBody"></tbody>
                        </table>
                    </div>
                </div>

                {{-- D. TOMBOL TINDAKAN --}}
                <div class="stage-footer-actions">
                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        <button type="button" class="btn-secondary-etl" onclick="backToTransform()">← Kembali</button>
                        <button type="button" class="btn-secondary-etl" onclick="resetToUpload()">Upload File Baru</button>
                    </div>
                    <button type="button" class="btn-primary-etl" id="confirmLoadBtn">
                        Import Data ke Database
                    </button>
                </div>
            </div>

            {{-- ======================================================
                 PANEL 5: LOAD BERHASIL
            ====================================================== --}}
            <div class="etl-stage-panel" id="panelStage5">
                <div class="load-success-hero">
                    <div class="load-success-badge">✓</div>
                    <h2 style="font-size: 24px; font-weight: 800; color: #172B4D; margin-bottom: 6px;">Import Data Berhasil!</h2>
                    <p style="font-size: 14px; color: #475569; margin: 0;">
                        Database Superstore EIS telah diperbarui dengan data transaksi terbaru dan siap digunakan pada dashboard eksekutif.
                    </p>

                    <div class="load-success-metrics">
                        <div class="load-metric-box">
                            <strong id="loadSavedCount">0</strong>
                            <span>Data Berhasil Disimpan (<span id="loadMethodBadge">Replace</span>)</span>
                        </div>
                        <div class="load-metric-box">
                            <strong id="loadDurationText">0 detik</strong>
                            <span>Durasi Proses Load</span>
                        </div>
                        <div class="load-metric-box">
                            <strong id="loadDateText">-</strong>
                            <span>Tanggal &amp; Waktu Import</span>
                        </div>
                    </div>
                </div>

                <div class="stage-footer-actions">
                    <button type="button" class="btn-secondary-etl" onclick="resetToUpload(true)">
                        ← Kembali ke Menu ETL
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn-primary-etl">
                        Lihat Dashboard →
                    </a>
                </div>
            </div>

            {{-- ======================================================
                 PANEL 6: KONDISI KEGAGALAN (EXTRACT / TRANSFORM / LOAD)
            ====================================================== --}}
            <div class="etl-stage-panel" id="panelStageFailed">
                <div class="status-banner danger">
                    <div class="status-banner-icon">✕</div>
                    <div>
                        <strong style="display: block; font-size: 16px;" id="failedStageTitle">Proses ETL Gagal!</strong>
                        <span style="font-size: 13.5px;" id="failedStageMessage">Terjadi kesalahan yang menghentikan pipeline ETL.</span>
                    </div>
                </div>

                <div class="error-details-card">
                    <h4>Rincian Penyebab Kegagalan</h4>
                    <ul class="error-details-list" id="failedDetailsList"></ul>
                </div>

                <div class="stage-footer-actions">
                    <button type="button" class="btn-secondary-etl" onclick="resetToUpload()">← Kembali</button>
                    <button type="button" class="btn-primary-etl" onclick="resetToUpload()">↻ Upload File Lagi / Coba Ulang</button>
                </div>
            </div>

        </div>
    </section>

    {{-- ======================================================
         IMPORT HISTORY TABLE
    ====================================================== --}}
    <section class="history-card">
        <div class="history-header">
            <div>
                <h2>Import History</h2>
                <p>Riwayat proses ekstraksi, validasi, dan pemuatan dataset Superstore.</p>
            </div>

            <form method="GET" action="{{ route('etl') }}" class="history-filters">
                <input type="text" name="history_search" value="{{ $historySearch }}" placeholder="Cari nama file / metode...">
                <select name="history_status">
                    <option value="">Semua Status</option>
                    <option value="success" {{ $historyStatus === 'success' ? 'selected' : '' }}>Success</option>
                    <option value="failed" {{ $historyStatus === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
                <button type="submit" class="btn-primary-etl" style="height: 40px; padding: 0 16px; font-size: 13px;">Filter</button>
                @if ($historySearch !== '' || $historyStatus !== '')
                    <a href="{{ route('etl') }}" class="btn-secondary-etl" style="height: 40px; padding: 0 14px; font-size: 13px;">Reset</a>
                @endif
            </form>
        </div>

        <div class="mini-table-wrap">
            <table class="etl-data-table">
                <thead>
                    <tr>
                        <th>Tanggal &amp; Waktu</th>
                        <th>Nama File</th>
                        <th>Metode</th>
                        <th>Jumlah Data</th>
                        <th>Status</th>
                        <th>Tahap Kegagalan / Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($histories as $item)
                        <tr>
                            <td>{{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }}</td>
                            <td style="font-weight: 700;">{{ $item->file_name }}</td>
                            <td>
                                <span class="badge-pill {{ $item->import_method === 'replace' ? 'amber' : 'blue' }}">
                                    {{ strtoupper($item->import_method) }}
                                </span>
                            </td>
                            <td>
                                @if ($item->status === 'success')
                                    {{ number_format($item->loaded_rows, 0, ',', '.') }} baris
                                @else
                                    {{ number_format($item->total_rows, 0, ',', '.') }} diperiksa
                                @endif
                            </td>
                            <td>
                                @if ($item->status === 'success')
                                    <span class="badge-pill green">Success</span>
                                @else
                                    <span class="badge-pill red">Failed</span>
                                @endif
                            </td>
                            <td style="white-space: normal; max-width: 360px;">
                                @if ($item->status === 'success')
                                    <span style="color: #64748B;">Selesai dalam {{ number_format($item->duration_seconds, 2) }} dtk</span>
                                @else
                                    <strong style="color: #D64545; text-transform: capitalize;">[{{ $item->failed_stage ?? 'Error' }}]</strong>
                                    <span style="color: #475569;">{{ Str::limit($item->error_message, 110) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 28px; color: #64748B;">
                                Belum ada riwayat proses import ETL yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($histories->hasPages())
            <div style="margin-top: 16px;">
                {{ $histories->links() }}
            </div>
        @endif
    </section>

</div>
@endsection

@push('scripts')
<script>
(function () {
    const CSRF_TOKEN = '{{ csrf_token() }}';
    const PROCESS_URL = '{{ route("etl.process") }}';
    const LOAD_URL = '{{ route("etl.load") }}';
    const RESET_URL = '{{ route("etl.reset") }}';

    const ALL_COLUMNS = [
        'Row ID', 'Order ID', 'Order Date', 'Ship Date', 'Ship Mode',
        'Customer ID', 'Customer Name', 'Segment', 'Country', 'City',
        'State', 'Postal Code', 'Region', 'Product ID', 'Category',
        'Sub-Category', 'Product Name', 'Sales', 'Quantity', 'Discount', 'Profit'
    ];
    const COMPACT_COLUMNS = ['Row ID', 'Order ID', 'Order Date', 'Customer Name', 'Region', 'Category', 'Sales', 'Discount', 'Profit'];

    let selectedFile = null;
    let currentStagingToken = null;
    let lastProcessPayload = null;
    let showAllPreviewCols = false;

    const dropzoneArea = document.getElementById('dropzoneArea');
    const csvFileInput = document.getElementById('csvFileInput');
    const chooseFileBtn = document.getElementById('chooseFileBtn');
    const removeFileBtn = document.getElementById('removeFileBtn');
    const selectedFileCard = document.getElementById('selectedFileCard');
    const selectedFileName = document.getElementById('selectedFileName');
    const selectedFileMeta = document.getElementById('selectedFileMeta');
    const uploadValidationError = document.getElementById('uploadValidationError');
    const uploadValidationErrorText = document.getElementById('uploadValidationErrorText');
    const methodRadios = document.querySelectorAll('input[name="import_method"]');
    const cardMethodReplace = document.getElementById('cardMethodReplace');
    const cardMethodAppend = document.getElementById('cardMethodAppend');
    const replaceConfirmBox = document.getElementById('replaceConfirmBox');
    const replaceConfirmCheckbox = document.getElementById('replaceConfirmCheckbox');
    const startEtlBtn = document.getElementById('startEtlBtn');
    const uploadHelperHint = document.getElementById('uploadHelperHint');

    function formatBytes(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(2) + ' MB';
    }

    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    function getSelectedMethod() {
        const checked = document.querySelector('input[name="import_method"]:checked');
        return checked ? checked.value : 'replace';
    }

    function updateStartButtonState() {
        const method = getSelectedMethod();
        const isReplace = method === 'replace';
        replaceConfirmBox.style.display = isReplace ? 'flex' : 'none';
        cardMethodReplace.classList.toggle('selected', isReplace);
        cardMethodAppend.classList.toggle('selected', !isReplace);

        const confirmOk = !isReplace || replaceConfirmCheckbox.checked;
        const ready = Boolean(selectedFile) && confirmOk;
        startEtlBtn.disabled = !ready;

        if (!selectedFile) {
            uploadHelperHint.textContent = 'Unggah file CSV transaksi Superstore terlebih dahulu.';
        } else if (isReplace && !replaceConfirmCheckbox.checked) {
            uploadHelperHint.textContent = 'Centang kotak konfirmasi Replace untuk melanjutkan.';
        } else {
            uploadHelperHint.textContent = 'Siap menjalankan proses Extract & Transform otomatis.';
        }
    }

    function handleFileSelection(file) {
        uploadValidationError.style.display = 'none';
        if (!file) return;

        const nameLower = file.name.toLowerCase();
        if (!nameLower.endsWith('.csv') && !nameLower.endsWith('.txt')) {
            uploadValidationErrorText.textContent = 'Format file tidak didukung. Harap pilih file berekstensi .csv.';
            uploadValidationError.style.display = 'flex';
            selectedFile = null;
            selectedFileCard.style.display = 'none';
            updateStartButtonState();
            return;
        }

        if (file.size > 20 * 1024 * 1024) {
            uploadValidationErrorText.textContent = 'Ukuran file melebihi batas maksimal 20 MB.';
            uploadValidationError.style.display = 'flex';
            selectedFile = null;
            selectedFileCard.style.display = 'none';
            updateStartButtonState();
            return;
        }

        selectedFile = file;
        selectedFileName.textContent = file.name;
        selectedFileMeta.textContent = `Ukuran: ${formatBytes(file.size)} • Format: CSV • Siap diekstrak`;
        selectedFileCard.style.display = 'flex';
        updateStartButtonState();
    }

    chooseFileBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        csvFileInput.click();
    });

    dropzoneArea.addEventListener('click', () => csvFileInput.click());

    dropzoneArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzoneArea.classList.add('dragover');
    });

    dropzoneArea.addEventListener('dragleave', () => {
        dropzoneArea.classList.remove('dragover');
    });

    dropzoneArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzoneArea.classList.remove('dragover');
        if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            handleFileSelection(e.dataTransfer.files[0]);
        }
    });

    csvFileInput.addEventListener('change', (e) => {
        if (e.target.files && e.target.files.length > 0) {
            handleFileSelection(e.target.files[0]);
        }
    });

    removeFileBtn.addEventListener('click', () => {
        selectedFile = null;
        csvFileInput.value = '';
        selectedFileCard.style.display = 'none';
        updateStartButtonState();
    });

    methodRadios.forEach(r => r.addEventListener('change', updateStartButtonState));
    replaceConfirmCheckbox.addEventListener('change', updateStartButtonState);

    function setStepperStates(states) {
        const statusLabels = {
            pending: 'Pending',
            processing: 'Proses',
            completed: 'Selesai',
            failed: 'Gagal'
        };

        for (let i = 1; i <= 5; i++) {
            const stepEl = document.querySelector(`.etl-step[data-step="${i}"]`);
            const circleEl = document.getElementById(`stepCircle${i}`);
            const tagEl = document.getElementById(`stepTag${i}`);
            const st = states[i - 1] || 'pending';

            stepEl.classList.remove('pending', 'processing', 'completed', 'failed');
            stepEl.classList.add(st);
            tagEl.textContent = statusLabels[st] || 'Pending';

            if (st === 'completed') {
                circleEl.textContent = '✓';
            } else if (st === 'failed') {
                circleEl.textContent = '✕';
            } else {
                circleEl.textContent = String(i);
            }
        }
    }

    function showPanel(panelId) {
        document.querySelectorAll('.etl-stage-panel').forEach(p => p.classList.remove('active'));
        document.getElementById(panelId).classList.add('active');
    }

    startEtlBtn.addEventListener('click', async () => {
        if (!selectedFile) return;

        const method = getSelectedMethod();
        const nowStr = new Date().toLocaleString('id-ID', {
            day: '2-digit', month: 'short', year: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        });

        setStepperStates(['completed', 'processing', 'pending', 'pending', 'pending']);
        showPanel('panelStage2');

        document.getElementById('extractFileName').textContent = selectedFile.name;
        document.getElementById('extractMethodLabel').textContent = method === 'replace' ? 'Replace (Ganti Semua Data)' : 'Append (Tambah Data Baru)';
        document.getElementById('extractTotalRows').textContent = 'Membaca baris CSV...';
        document.getElementById('extractTotalCols').textContent = 'Memeriksa struktur 21 kolom...';
        document.getElementById('extractStartTime').textContent = nowStr;
        document.getElementById('extractBadge').textContent = 'Processing';
        document.getElementById('extractBadge').className = 'badge-pill blue';
        document.getElementById('extractStatusHeading').textContent = 'Proses Extract Berjalan...';
        document.getElementById('extractStatusDesc').textContent = 'Sistem sedang membaca file CSV dan memeriksa kelengkapan struktur kolom.';

        const bar = document.getElementById('extractProgressBar');
        const pct = document.getElementById('extractProgressPct');
        bar.style.width = '25%';
        pct.textContent = '25%';

        const timer1 = setTimeout(() => { bar.style.width = '60%'; pct.textContent = '60%'; }, 250);
        const timer2 = setTimeout(() => {
            setStepperStates(['completed', 'completed', 'processing', 'pending', 'pending']);
            document.getElementById('extractStatusHeading').textContent = 'Proses Transform & Validasi Berjalan...';
            document.getElementById('extractStatusDesc').textContent = 'Membersihkan data, memeriksa nilai kosong, duplikasi, dan menyeragamkan tipe data.';
            bar.style.width = '85%';
            pct.textContent = '85%';
        }, 650);

        const formData = new FormData();
        formData.append('csv', selectedFile);
        formData.append('method', method);

        try {
            const response = await fetch(PROCESS_URL, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json'
                },
                body: formData
            });

            clearTimeout(timer1);
            clearTimeout(timer2);
            bar.style.width = '100%';
            pct.textContent = '100%';

            const data = await response.json();

            if (!response.ok || !data.success) {
                const failedStage = data.stage || 'extract';
                if (failedStage === 'extract') {
                    setStepperStates(['completed', 'failed', 'pending', 'pending', 'pending']);
                } else {
                    setStepperStates(['completed', 'completed', 'failed', 'pending', 'pending']);
                }
                renderFailedState(
                    data.title || 'Extract Gagal!',
                    data.message || 'Gagal membaca atau memvalidasi file CSV.',
                    data.details || (data.errors ? Object.values(data.errors).flat() : ['Periksa kembali format file CSV Anda.'])
                );
                return;
            }

            lastProcessPayload = data;
            currentStagingToken = data.staging_token;

            renderTransformAndReview(data);

            setStepperStates(['completed', 'completed', 'completed', 'processing', 'pending']);
            showPanel('panelStage3');

            setTimeout(() => {
                goToReview();
            }, 900);

        } catch (err) {
            clearTimeout(timer1);
            clearTimeout(timer2);
            setStepperStates(['completed', 'failed', 'pending', 'pending', 'pending']);
            renderFailedState(
                'Extract Gagal!',
                'Terjadi gangguan koneksi atau server saat memproses file CSV.',
                [err.message || 'Pastikan server Laravel berjalan dan ukuran file tidak melebihi batas PHP upload_max_filesize.']
            );
        }
    });

    function renderTransformAndReview(data) {
        const t = data.transform;
        const total = t.total_checked || 0;
        const valid = t.valid_count || 0;
        const invalid = t.invalid_count || 0;
        const validPct = total > 0 ? ((valid / total) * 100).toFixed(2).replace('.', ',') : '0';
        const invalidPct = total > 0 ? ((invalid / total) * 100).toFixed(2).replace('.', ',') : '0';

        const banner = document.getElementById('transformBanner');
        const bannerIcon = document.getElementById('transformBannerIcon');
        const bannerTitle = document.getElementById('transformBannerTitle');
        const bannerSub = document.getElementById('transformBannerSub');

        if (invalid === 0) {
            banner.className = 'status-banner success';
            bannerIcon.textContent = '✓';
            bannerTitle.textContent = 'Transform & Validasi Berhasil!';
            bannerSub.textContent = 'Seluruh data telah dibersihkan, diseragamkan formatnya, dan lolos validasi struktur maupun tipe data.';
        } else {
            banner.className = 'status-banner danger';
            bannerIcon.textContent = '!';
            bannerTitle.textContent = 'Transform & Validasi Menemukan Data Bermasalah';
            bannerSub.textContent = `Ditemukan ${formatNumber(invalid)} baris tidak valid. Silakan tinjau rincian kesalahan pada tahap Review.`;
        }

        document.getElementById('valTotalChecked').textContent = formatNumber(total);
        document.getElementById('valValidCount').textContent = formatNumber(valid);
        document.getElementById('valValidPct').textContent = `Data Valid (${validPct}%)`;
        document.getElementById('valInvalidCount').textContent = formatNumber(invalid);
        document.getElementById('valInvalidPct').textContent = `Data Bermasalah (${invalidPct}%)`;

        document.getElementById('chkStructure').textContent = t.structure_status;
        document.getElementById('chkMissing').textContent = formatNumber(t.missing_values);
        document.getElementById('chkMissing').className = 'badge-pill ' + (t.missing_values === 0 ? 'green' : 'red');

        document.getElementById('chkDataType').textContent = t.type_status;
        document.getElementById('chkDataType').className = 'badge-pill ' + (t.type_errors === 0 ? 'green' : 'red');

        document.getElementById('chkDateFormat').textContent = t.date_format_status;
        document.getElementById('chkDuplicates').textContent = formatNumber(t.duplicates);
        document.getElementById('chkDuplicates').className = 'badge-pill ' + (t.duplicates === 0 ? 'green' : 'red');

        document.getElementById('chkConsistency').textContent = t.consistency_status;
        document.getElementById('chkConsistency').className = 'badge-pill ' + (invalid === 0 ? 'green' : 'red');

        const warnings = data.warnings || [];
        const tWarnBox = document.getElementById('transformWarningBox');
        const rWarnBox = document.getElementById('reviewWarningBox');
        if (warnings.length > 0) {
            const htmlWarn = warnings.map(w => `<div>• ${escapeHtml(w)}</div>`).join('');
            document.getElementById('transformWarningList').innerHTML = htmlWarn;
            document.getElementById('reviewWarningContent').innerHTML = htmlWarn;
            tWarnBox.style.display = 'flex';
            rWarnBox.style.display = 'flex';
        } else {
            tWarnBox.style.display = 'none';
            rWarnBox.style.display = 'none';
        }

        document.getElementById('revTotalData').textContent = formatNumber(total);
        document.getElementById('revValidData').textContent = formatNumber(valid);
        document.getElementById('revInvalidData').textContent = formatNumber(invalid);
        document.getElementById('revMethodText').textContent = data.meta.method;

        const problems = data.problem_rows || [];
        document.getElementById('problemCountLabel').textContent = `${problems.length} temuan`;
        const problemBody = document.getElementById('problemTableBody');
        if (problems.length === 0) {
            problemBody.innerHTML = `<tr><td colspan="5" style="text-align:center; color:#16834A; font-weight:600; padding:20px;">Tidak ditemukan kesalahan data. Seluruh baris siap dimuat.</td></tr>`;
        } else {
            problemBody.innerHTML = problems.map(p => `
                <tr>
                    <td>Baris ${p.row_number}</td>
                    <td><strong>${escapeHtml(p.order_id)}</strong></td>
                    <td><span class="badge-pill red">${escapeHtml(p.column)}</span></td>
                    <td><code>${escapeHtml(p.value)}</code></td>
                    <td style="white-space: normal;">${escapeHtml(p.reason)}</td>
                </tr>
            `).join('');
        }

        const fatalBox = document.getElementById('reviewFatalBlockBox');
        const confirmLoadBtn = document.getElementById('confirmLoadBtn');
        if (!data.can_load) {
            fatalBox.style.display = 'flex';
            confirmLoadBtn.disabled = true;
        } else {
            fatalBox.style.display = 'none';
            confirmLoadBtn.disabled = false;
        }

        renderPreviewTable();
    }

    function renderPreviewTable() {
        const rows = (lastProcessPayload && lastProcessPayload.preview_rows) || [];
        const cols = showAllPreviewCols ? ALL_COLUMNS : COMPACT_COLUMNS;
        const thead = document.getElementById('previewTableHead');
        const tbody = document.getElementById('previewTableBody');

        thead.innerHTML = '<tr>' + cols.map(c => `<th>${escapeHtml(c)}</th>`).join('') + '</tr>';

        if (rows.length === 0) {
            tbody.innerHTML = `<tr><td colspan="${cols.length}" style="text-align:center; padding:18px; color:#64748B;">Tidak ada baris valid untuk dipratinjau.</td></tr>`;
            return;
        }

        tbody.innerHTML = rows.map(row => {
            return '<tr>' + cols.map(c => {
                let val = row[c] !== undefined && row[c] !== null ? String(row[c]) : '-';
                if (c === 'Profit' && parseFloat(val) < 0) {
                    return `<td style="color:#D64545; font-weight:700;">${escapeHtml(val)}</td>`;
                }
                return `<td>${escapeHtml(val)}</td>`;
            }).join('') + '</tr>';
        }).join('');
    }

    document.getElementById('toggleAllColsBtn').addEventListener('click', function () {
        showAllPreviewCols = !showAllPreviewCols;
        this.textContent = showAllPreviewCols ? 'Tampilkan Kolom Ringkas' : 'Tampilkan Semua 21 Kolom';
        renderPreviewTable();
    });

    window.goToReview = function () {
        if (!lastProcessPayload) return;
        const hasError = !lastProcessPayload.can_load;
        setStepperStates(['completed', 'completed', hasError ? 'failed' : 'completed', 'processing', 'pending']);
        showPanel('panelStage4');
    };

    window.backToTransform = function () {
        if (!lastProcessPayload) return;
        const hasError = !lastProcessPayload.can_load;
        setStepperStates(['completed', 'completed', hasError ? 'failed' : 'processing', 'pending', 'pending']);
        showPanel('panelStage3');
    };

    document.getElementById('confirmLoadBtn').addEventListener('click', async function () {
        if (!currentStagingToken) return;

        const btn = this;
        const origText = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Menyimpan ke Database...';
        setStepperStates(['completed', 'completed', 'completed', 'completed', 'processing']);

        try {
            const response = await fetch(LOAD_URL, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ staging_token: currentStagingToken })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                setStepperStates(['completed', 'completed', 'completed', 'completed', 'failed']);
                renderFailedState(
                    data.title || 'Load Gagal!',
                    data.message || 'Gagal menyimpan data ke database.',
                    data.details || ['Transaksi database dibatalkan (Rollback). Data lama tetap aman.']
                );
                return;
            }

            currentStagingToken = null;
            const res = data.result;

            setStepperStates(['completed', 'completed', 'completed', 'completed', 'completed']);
            document.getElementById('loadSavedCount').textContent = formatNumber(res.loaded) + ' baris';
            document.getElementById('loadMethodBadge').textContent = res.method.toUpperCase();
            document.getElementById('loadDurationText').textContent = res.duration + ' detik';
            document.getElementById('loadDateText').textContent = res.imported_at;

            if (res.summary) {
                document.getElementById('statTotalRows').textContent = formatNumber(res.summary.total_rows);
                document.getElementById('statTotalOrders').textContent = formatNumber(res.summary.total_orders);
                document.getElementById('statTotalSales').textContent = '$' + Number(res.summary.total_sales).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById('statTotalProfit').textContent = '$' + Number(res.summary.total_profit).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            showPanel('panelStage5');
        } catch (err) {
            setStepperStates(['completed', 'completed', 'completed', 'completed', 'failed']);
            renderFailedState(
                'Load Gagal!',
                'Terjadi kesalahan saat menyimpan data ke database.',
                [err.message || 'Koneksi terputus saat proses penyimpanan.']
            );
        } finally {
            btn.disabled = false;
            btn.textContent = origText;
        }
    });

    function renderFailedState(title, message, details) {
        document.getElementById('failedStageTitle').textContent = title;
        document.getElementById('failedStageMessage').textContent = message;
        const list = document.getElementById('failedDetailsList');
        list.innerHTML = (details || []).map(d => `<li>${escapeHtml(String(d))}</li>`).join('');
        showPanel('panelStageFailed');
    }

    window.resetToUpload = async function (reloadPage = false) {
        if (currentStagingToken) {
            try {
                await fetch(RESET_URL, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ staging_token: currentStagingToken })
                });
            } catch (e) {}
            currentStagingToken = null;
        }

        if (reloadPage) {
            window.location.href = '{{ route("etl") }}';
            return;
        }

        lastProcessPayload = null;
        selectedFile = null;
        csvFileInput.value = '';
        selectedFileCard.style.display = 'none';
        replaceConfirmCheckbox.checked = false;
        uploadValidationError.style.display = 'none';
        updateStartButtonState();

        setStepperStates(['processing', 'pending', 'pending', 'pending', 'pending']);
        showPanel('panelStage1');
    };

    function escapeHtml(str) {
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    updateStartButtonState();
})();
</script>
@endpush
