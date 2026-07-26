<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Admin') ?> — Anything.lk Admin</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon"       href="<?= Helper::asset('img/favicon.ico') ?>">
    <link rel="icon" type="image/svg+xml"       href="<?= Helper::asset('img/favicon.svg') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= Helper::asset('img/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= Helper::asset('img/favicon-16x16.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180"    href="<?= Helper::asset('img/apple-touch-icon.png') ?>">
    <link rel="manifest"                            href="<?= Helper::asset('img/site.webmanifest') ?>">
    <meta name="theme-color" content="#E63946">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
    /* ═══════════════════════════════════════════
       RESET & BASE
    ═══════════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --sidebar-w:   260px;
        --bg:          #080c14;
        --bg2:         #0d1321;
        --surface:     rgba(255,255,255,0.035);
        --surface2:    rgba(255,255,255,0.065);
        --border:      rgba(255,255,255,0.07);
        --border2:     rgba(255,255,255,0.12);
        --cyan:        #00d4ff;
        --cyan-dim:    rgba(0,212,255,0.15);
        --purple:      #7c3aed;
        --purple-dim:  rgba(124,58,237,0.15);
        --green:       #10b981;
        --green-dim:   rgba(16,185,129,0.15);
        --amber:       #f59e0b;
        --amber-dim:   rgba(245,158,11,0.15);
        --red:         #ef4444;
        --red-dim:     rgba(239,68,68,0.15);
        --blue:        #3b82f6;
        --blue-dim:    rgba(59,130,246,0.15);
        --text:        #e2e8f0;
        --text-muted:  #64748b;
        --text-dim:    #94a3b8;
        --glow-cyan:   0 0 20px rgba(0,212,255,0.3);
        --glow-purple: 0 0 20px rgba(124,58,237,0.3);
        --radius:      12px;
        --radius-sm:   8px;
        --transition:  all 0.2s cubic-bezier(0.4,0,0.2,1);
    }

    html { scroll-behavior: smooth; }

    body {
        background: var(--bg);
        font-family: 'Inter', sans-serif;
        font-size: 13.5px;
        color: var(--text);
        min-height: 100vh;
        overflow-x: hidden;
    }

    body::before {
        content: '';
        position: fixed;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background:
            radial-gradient(ellipse 600px 400px at 20% 20%, rgba(0,212,255,0.04) 0%, transparent 70%),
            radial-gradient(ellipse 500px 500px at 80% 80%, rgba(124,58,237,0.05) 0%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }

    a { color: var(--cyan); text-decoration: none; transition: var(--transition); }
    a:hover { color: #fff; }

    /* ═══════════════════════════════════════════
       SCROLLBAR
    ═══════════════════════════════════════════ */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: var(--bg2); }
    ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(0,212,255,0.4); }

    /* ═══════════════════════════════════════════
       SIDEBAR
    ═══════════════════════════════════════════ */
    #adminSidebar {
        position: fixed; top: 0; left: 0; bottom: 0;
        width: var(--sidebar-w);
        background: var(--bg2);
        border-right: 1px solid var(--border);
        overflow-y: auto;
        z-index: 200;
        transition: transform .3s cubic-bezier(0.4,0,0.2,1);
    }

    #adminSidebar::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--cyan), transparent);
        opacity: 0.6;
    }

    .sidebar-brand {
        padding: 22px 20px 18px;
        border-bottom: 1px solid var(--border);
        position: relative;
    }

    .sidebar-brand .brand-name {
        font-size: 20px;
        font-weight: 800;
        letter-spacing: -0.5px;
        background: linear-gradient(135deg, #fff 0%, var(--cyan) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: block;
    }

    .sidebar-brand .brand-name span {
        background: linear-gradient(135deg, var(--cyan), var(--purple));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .sidebar-brand small {
        font-size: 10px;
        font-weight: 500;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .15em;
        display: block;
        margin-top: 4px;
    }

    .sidebar-nav { list-style: none; padding: 12px 0 20px; }

    .sidebar-nav .nav-section {
        padding: 14px 20px 5px;
        font-size: 9.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .15em;
        color: var(--text-muted);
    }

    .sidebar-nav li a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 20px;
        color: var(--text-dim);
        font-size: 13px;
        font-weight: 500;
        border-left: 2px solid transparent;
        transition: var(--transition);
        position: relative;
    }

    .sidebar-nav li a i {
        width: 18px;
        text-align: center;
        font-size: 13px;
        transition: var(--transition);
    }

    .sidebar-nav li a:hover {
        color: #fff;
        background: var(--surface2);
        border-left-color: rgba(0,212,255,0.4);
    }

    .sidebar-nav li a:hover i { color: var(--cyan); }

    .sidebar-nav li a.active {
        color: #fff;
        background: linear-gradient(90deg, rgba(0,212,255,0.1) 0%, transparent 100%);
        border-left-color: var(--cyan);
    }

    .sidebar-nav li a.active i { color: var(--cyan); }

    .sidebar-nav li a.active::after {
        content: '';
        position: absolute;
        right: 0; top: 50%;
        transform: translateY(-50%);
        width: 3px; height: 60%;
        background: var(--cyan);
        border-radius: 3px 0 0 3px;
        box-shadow: var(--glow-cyan);
    }

    .sidebar-badge {
        margin-left: auto;
        background: linear-gradient(135deg, var(--cyan), var(--purple));
        color: #fff;
        font-size: 9.5px;
        padding: 2px 7px;
        border-radius: 20px;
        font-weight: 700;
        letter-spacing: .02em;
    }

    /* ═══════════════════════════════════════════
       MAIN AREA
    ═══════════════════════════════════════════ */
    #adminMain {
        margin-left: var(--sidebar-w);
        min-height: 100vh;
        position: relative;
        z-index: 1;
    }

    /* ═══════════════════════════════════════════
       TOPBAR
    ═══════════════════════════════════════════ */
    #adminTopbar {
        background: rgba(13,19,33,0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border);
        padding: 0 28px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    #adminTopbar::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(0,212,255,0.2), transparent);
    }

    #adminTopbar h6 {
        font-weight: 700;
        font-size: 15px;
        color: #fff;
        letter-spacing: -0.2px;
    }

    .topbar-left, .topbar-right {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .menu-toggle {
        display: none;
        background: var(--surface2);
        border: 1px solid var(--border2);
        color: var(--text);
        width: 36px; height: 36px;
        border-radius: var(--radius-sm);
        cursor: pointer;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        transition: var(--transition);
    }

    .menu-toggle:hover { background: var(--surface); color: var(--cyan); border-color: var(--cyan); }

    .topbar-date {
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 500;
    }

    .topbar-alert {
        display: flex;
        align-items: center;
        gap: 6px;
        background: var(--amber-dim);
        border: 1px solid rgba(245,158,11,0.3);
        color: var(--amber);
        font-size: 12px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        transition: var(--transition);
    }

    .topbar-alert:hover { background: rgba(245,158,11,0.25); color: var(--amber); }

    .topbar-avatar {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--cyan), var(--purple));
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 14px;
        color: #fff;
        box-shadow: 0 0 14px rgba(0,212,255,0.25);
        flex-shrink: 0;
    }

    .topbar-username {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
    }

    /* ═══════════════════════════════════════════
       CONTENT
    ═══════════════════════════════════════════ */
    .content-area { padding: 28px; }

    /* ═══════════════════════════════════════════
       STAT CARDS
    ═══════════════════════════════════════════ */
    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    }

    .stat-card:hover {
        border-color: var(--border2);
        background: var(--surface2);
        transform: translateY(-2px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }

    .stat-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    /* ═══════════════════════════════════════════
       PAGE HEADER
    ═══════════════════════════════════════════ */
    .page-header {
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .page-header h4 {
        font-weight: 700;
        font-size: 18px;
        color: #fff;
        letter-spacing: -0.3px;
    }

    /* ═══════════════════════════════════════════
       ADMIN TABLE
    ═══════════════════════════════════════════ */
    .admin-table {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .admin-table .table {
        width: 100%;
        border-collapse: collapse;
        color: var(--text);
    }

    .admin-table .table thead th {
        background: rgba(0,212,255,0.06);
        color: var(--cyan);
        border: none;
        border-bottom: 1px solid var(--border2);
        padding: 11px 14px;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        white-space: nowrap;
    }

    .admin-table .table tbody td {
        padding: 11px 14px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
        font-size: 13px;
    }

    .admin-table .table tbody tr:last-child td { border-bottom: none; }

    .admin-table .table tbody tr:hover td {
        background: var(--surface2);
    }

    /* ═══════════════════════════════════════════
       FILTER BAR (shared pagination toolbar)
    ═══════════════════════════════════════════ */
    .admin-filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 10px 14px;
    }
    .afb-search { flex: 1; min-width: 180px; }
    .afb-select { min-width: 130px; }
    .afb-per-page { min-width: 100px; }
    .afb-input {
        background: var(--bg) !important;
        border-color: var(--border) !important;
        color: var(--text) !important;
        font-size: 13px !important;
    }
    .afb-input:focus {
        border-color: var(--cyan) !important;
        box-shadow: 0 0 0 2px rgba(6,182,212,.15) !important;
    }
    .admin-pag-nav .pagination .page-link {
        background: var(--surface);
        border-color: var(--border);
        color: var(--text-muted);
        font-size: 12px;
    }
    .admin-pag-nav .pagination .page-item.active .page-link {
        background: var(--cyan);
        border-color: var(--cyan);
        color: #fff;
    }
    .admin-pag-nav .pagination .page-item.disabled .page-link {
        background: var(--surface2);
        color: var(--text-muted);
    }

    /* ═══════════════════════════════════════════
       BUTTONS
    ═══════════════════════════════════════════ */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        border-radius: var(--radius-sm);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid transparent;
        transition: var(--transition);
        white-space: nowrap;
        text-decoration: none;
        line-height: 1.4;
    }

    .btn:hover { transform: translateY(-1px); }
    .btn:active { transform: translateY(0); }

    .btn-sm { padding: 5px 12px; font-size: 12px; }
    .btn-lg { padding: 10px 22px; font-size: 14px; }

    .btn-primary {
        background: linear-gradient(135deg, var(--cyan) 0%, #0099cc 100%);
        color: #000;
        border-color: var(--cyan);
        box-shadow: 0 0 14px rgba(0,212,255,0.2);
    }
    .btn-primary:hover { box-shadow: 0 0 22px rgba(0,212,255,0.4); color: #000; }

    .btn-secondary {
        background: var(--surface2);
        color: var(--text);
        border-color: var(--border2);
    }
    .btn-secondary:hover { background: rgba(255,255,255,0.12); color: #fff; }

    .btn-success {
        background: linear-gradient(135deg, var(--green), #059669);
        color: #fff;
        border-color: var(--green);
        box-shadow: 0 0 14px rgba(16,185,129,0.2);
    }
    .btn-success:hover { box-shadow: 0 0 22px rgba(16,185,129,0.4); color: #fff; }

    .btn-warning {
        background: linear-gradient(135deg, var(--amber), #d97706);
        color: #000;
        border-color: var(--amber);
    }
    .btn-warning:hover { color: #000; box-shadow: 0 0 18px rgba(245,158,11,0.3); }

    .btn-danger {
        background: linear-gradient(135deg, var(--red), #dc2626);
        color: #fff;
        border-color: var(--red);
        box-shadow: 0 0 14px rgba(239,68,68,0.2);
    }
    .btn-danger:hover { box-shadow: 0 0 22px rgba(239,68,68,0.35); color: #fff; }

    .btn-info {
        background: linear-gradient(135deg, var(--blue), #2563eb);
        color: #fff;
        border-color: var(--blue);
    }
    .btn-info:hover { color: #fff; }

    .btn-light {
        background: var(--surface2);
        color: var(--text);
        border-color: var(--border2);
    }
    .btn-light:hover { color: #fff; background: rgba(255,255,255,0.12); }

    .btn-outline-primary {
        background: transparent;
        color: var(--cyan);
        border-color: rgba(0,212,255,0.4);
    }
    .btn-outline-primary:hover { background: var(--cyan-dim); color: var(--cyan); }

    .btn-outline-secondary {
        background: transparent;
        color: var(--text-dim);
        border-color: var(--border2);
    }
    .btn-outline-secondary:hover { background: var(--surface2); color: #fff; }

    .btn-outline-success {
        background: transparent;
        color: var(--green);
        border-color: rgba(16,185,129,0.4);
    }
    .btn-outline-success:hover { background: var(--green-dim); color: var(--green); }

    .btn-outline-danger {
        background: transparent;
        color: var(--red);
        border-color: rgba(239,68,68,0.4);
    }
    .btn-outline-danger:hover { background: var(--red-dim); color: var(--red); }

    .btn-outline-warning {
        background: transparent;
        color: var(--amber);
        border-color: rgba(245,158,11,0.4);
    }
    .btn-outline-warning:hover { background: var(--amber-dim); color: var(--amber); }

    .btn-outline-info {
        background: transparent;
        color: var(--blue);
        border-color: rgba(59,130,246,0.4);
    }
    .btn-outline-info:hover { background: var(--blue-dim); color: var(--blue); }

    /* ═══════════════════════════════════════════
       BADGES
    ═══════════════════════════════════════════ */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .02em;
    }

    .bg-primary   { background: var(--blue-dim);   color: var(--blue);   border: 1px solid rgba(59,130,246,0.3); }
    .bg-success   { background: var(--green-dim);  color: var(--green);  border: 1px solid rgba(16,185,129,0.3); }
    .bg-warning   { background: var(--amber-dim);  color: var(--amber);  border: 1px solid rgba(245,158,11,0.3); }
    .bg-danger    { background: var(--red-dim);    color: var(--red);    border: 1px solid rgba(239,68,68,0.3); }
    .bg-info      { background: var(--cyan-dim);   color: var(--cyan);   border: 1px solid rgba(0,212,255,0.3); }
    .bg-secondary { background: var(--surface2);   color: var(--text-dim); border: 1px solid var(--border2); }
    .text-dark    { color: #000 !important; }
    .bg-purple    { background: var(--purple-dim); color: #a78bfa; border: 1px solid rgba(124,58,237,0.3); }

    /* ═══════════════════════════════════════════
       FORMS
    ═══════════════════════════════════════════ */
    .form-control, .form-select, select, textarea, input[type="text"],
    input[type="email"], input[type="number"], input[type="password"],
    input[type="date"], input[type="search"], input[type="url"] {
        width: 100%;
        background: rgba(255,255,255,0.04);
        border: 1px solid var(--border2);
        border-radius: var(--radius-sm);
        color: var(--text);
        padding: 8px 12px;
        font-size: 13px;
        font-family: 'Inter', sans-serif;
        transition: var(--transition);
        outline: none;
        appearance: none;
    }

    .form-control:focus, .form-select:focus, select:focus, textarea:focus,
    input[type="text"]:focus, input[type="email"]:focus, input[type="number"]:focus,
    input[type="password"]:focus, input[type="date"]:focus, input[type="search"]:focus,
    input[type="url"]:focus {
        border-color: var(--cyan);
        box-shadow: 0 0 0 3px rgba(0,212,255,0.1);
        background: rgba(0,212,255,0.04);
    }

    .form-control::placeholder, input::placeholder, textarea::placeholder { color: var(--text-muted); }

    .form-label, label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-dim);
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: 6px;
    }

    .form-select, select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%2364748b' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 36px;
    }

    select option { background: #1a2035; color: var(--text); }

    .form-text { font-size: 11.5px; color: var(--text-muted); margin-top: 4px; }
    .form-check { display: flex; align-items: center; gap: 8px; }
    .form-check-input { width: 16px; height: 16px; accent-color: var(--cyan); cursor: pointer; }
    .form-check-label { font-size: 13px; font-weight: 400; text-transform: none; letter-spacing: 0; color: var(--text); margin: 0; }

    /* ═══════════════════════════════════════════
       CARDS (generic)
    ═══════════════════════════════════════════ */
    .card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        position: relative;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
    }

    .card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        font-weight: 700;
        font-size: 14px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        background: rgba(255,255,255,0.02);
    }

    .card-body { padding: 20px; }
    .card-footer {
        padding: 14px 20px;
        border-top: 1px solid var(--border);
        background: rgba(255,255,255,0.02);
    }

    /* ═══════════════════════════════════════════
       ALERTS
    ═══════════════════════════════════════════ */
    .alert {
        padding: 12px 16px;
        border-radius: var(--radius-sm);
        border: 1px solid;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 16px;
    }

    .alert-success { background: var(--green-dim); border-color: rgba(16,185,129,0.3); color: #6ee7b7; }
    .alert-danger  { background: var(--red-dim);   border-color: rgba(239,68,68,0.3);  color: #fca5a5; }
    .alert-warning { background: var(--amber-dim); border-color: rgba(245,158,11,0.3); color: #fcd34d; }
    .alert-info    { background: var(--cyan-dim);  border-color: rgba(0,212,255,0.3);  color: #67e8f9; }

    /* ═══════════════════════════════════════════
       MODAL (custom lightweight)
    ═══════════════════════════════════════════ */
    .modal-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(4px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.show { display: flex; }

    .modal-box {
        background: #111827;
        border: 1px solid var(--border2);
        border-radius: 16px;
        width: 90%;
        max-width: 560px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 60px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,255,255,0.05);
        position: relative;
        animation: modalIn .25s cubic-bezier(0.4,0,0.2,1);
    }

    .modal-box-lg { max-width: 800px; }

    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    .modal-header {
        padding: 18px 22px 14px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-title {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
    }

    .modal-close {
        background: var(--surface2);
        border: 1px solid var(--border2);
        color: var(--text-muted);
        width: 30px; height: 30px;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: var(--transition);
    }

    .modal-close:hover { background: var(--red-dim); border-color: rgba(239,68,68,0.4); color: var(--red); }

    .modal-body    { padding: 20px 22px; }
    .modal-footer  { padding: 14px 22px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; }

    /* Bootstrap 5 nav-tabs */
    .nav { display: flex; flex-wrap: wrap; padding-left: 0; margin-bottom: 0; list-style: none; }
    .nav-tabs { border-bottom: 1px solid var(--border2); gap: 0; }
    .nav-tabs .nav-item { margin-bottom: -1px; }
    .nav-tabs .nav-link {
        display: block; padding: 8px 16px; font-size: 13px; font-weight: 500;
        color: var(--text-muted); background: none; border: 1px solid transparent;
        border-radius: 6px 6px 0 0; cursor: pointer; transition: var(--transition);
        text-decoration: none;
    }
    .nav-tabs .nav-link:hover { color: var(--text); border-color: var(--border) var(--border) transparent; }
    .nav-tabs .nav-link.active { color: var(--cyan); background: rgba(0,212,255,0.06); border-color: var(--border2) var(--border2) var(--bg); }
    .tab-content > .tab-pane { display: none; }
    .tab-content > .active { display: block; }

    /* Bootstrap 5 btn-close */
    .btn-close {
        background: transparent url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z' fill='%23adb5bd'/%3E%3C/svg%3E") center/1em no-repeat;
        border: 0; padding: .25em; cursor: pointer; opacity: .7; width: 1.5em; height: 1.5em; border-radius: .25em; flex-shrink: 0;
    }
    .btn-close:hover { opacity: 1; background-color: rgba(255,255,255,0.1); }

    /* ── Bootstrap 5 modal — standalone reimplementation ───────────
       Root cause of the old scroll bug: display:flex + justify-content:center
       on the fixed overlay clips overflowing content at the top — you cannot
       scroll up to reach it. Bootstrap 5 itself uses display:block on the
       overlay and lets the outer overflow-y:auto do the scrolling for very
       tall dialogs. The centered effect comes from a flex wrapper *inside*
       the dialog, not on the fixed backdrop.                              */

    /* 1 ── Fixed overlay — block, not flex */
    .modal {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: 1055;
        overflow-x: hidden;
        overflow-y: auto;
        outline: 0;
    }
    .modal.show, .modal.d-block {
        display: block !important;
        background: rgba(0,0,0,0.72);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }
    /* Bootstrap creates a .modal-backdrop element; hide it — our bg is on .modal */
    .modal-backdrop { display: none !important; }

    /* 2 ── Dialog positioner */
    .modal-dialog {
        position: relative;
        width: auto;
        margin: 1.75rem auto;
        max-width: 560px;
        pointer-events: none;
    }
    .modal-lg, .modal-dialog-lg { max-width: 800px; }
    .modal-xl { max-width: 1140px; }

    /* 3 ── Vertical centering variant
       min-height fills the remaining viewport so flex align-items:center
       works correctly. This is exactly Bootstrap 5's technique.          */
    .modal-dialog-centered {
        display: flex;
        align-items: center;
        min-height: calc(100% - 3.5rem);
    }
    .modal-dialog-centered > .modal-content { width: 100%; }

    /* 4 ── Content box */
    .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 100%;
        pointer-events: all;
        background: #111827;
        border: 1px solid var(--border2);
        border-radius: 16px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.6);
        overflow: hidden;
    }

    /* 4a ── CRITICAL: Bootstrap modals often wrap modal-body + modal-footer
       inside a <form> that sits as a direct child of modal-content.
       Because modal-content is display:flex, its DIRECT children participate
       in flex layout — but <form> has no flex properties, so the entire
       carefully-crafted scroll chain (flex→body grows→footer pinned) breaks.
       Fix: make form a flex column child so it passes flex context inward.
       modal-body and modal-footer then become flex children of the form and
       the overflow-y:auto / flex-shrink:0 rules below take effect.          */
    .modal-content > form {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-height: 0;
    }

    /* 5 ── Scrollable-body variant
       height:100% resolves against the fixed overlay (100vh equivalent)
       because .modal is position:fixed with top:0;left:0;right:0;bottom:0.
       .modal-dialog-scrollable gets an explicit height so its child
       .modal-content can use max-height:100% and the percentage resolves
       against a known ancestor height — letting .modal-body own the scroll. */
    .modal-dialog-scrollable { height: calc(100% - 3.5rem); }
    .modal-dialog-scrollable.modal-dialog-centered { height: calc(100% - 3.5rem); }
    .modal-dialog-scrollable .modal-content {
        max-height: 100%;
        overflow: hidden;
    }
    /* header and footer never shrink — they stay fully visible */
    .modal-dialog-scrollable .modal-header,
    .modal-dialog-scrollable .modal-footer { flex-shrink: 0; }
    /* body fills remaining space and becomes the sole scroll target */
    .modal-dialog-scrollable .modal-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-x: hidden;  /* stops negative row-margins from creating h-scroll */
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
    }

    /* 6 ── Mobile: tighter margins, full usable height */
    @media (max-width: 575.98px) {
        .modal-dialog {
            margin: .5rem auto;
            max-width: calc(100% - 1rem);
        }
        .modal-dialog-centered    { min-height: calc(100% - 1rem); }
        .modal-dialog-scrollable,
        .modal-dialog-scrollable.modal-dialog-centered { height: calc(100% - 1rem); }
        .modal-header { padding: 14px 16px 12px; }
        .modal-body   { padding: 16px;           }
        .modal-footer { padding: 10px 16px;      }
    }

    /* 7 ── Small phones: ensure footer buttons stack legibly */
    @media (max-width: 400px) {
        .modal-footer { flex-wrap: wrap; gap: .4rem; }
        .modal-footer .btn { flex: 1 1 auto; min-width: 80px; }
    }

    /* ═══════════════════════════════════════════
       DATATABLES OVERRIDE
    ═══════════════════════════════════════════ */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        background: rgba(255,255,255,0.04);
        border: 1px solid var(--border2);
        border-radius: var(--radius-sm);
        color: var(--text);
        padding: 6px 10px;
        font-size: 13px;
    }

    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label,
    .dataTables_wrapper .dataTables_info {
        color: var(--text-muted);
        font-size: 12.5px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        background: var(--surface2);
        border: 1px solid var(--border) !important;
        color: var(--text-dim) !important;
        border-radius: var(--radius-sm) !important;
        padding: 4px 10px !important;
        margin: 0 2px;
        font-size: 12.5px;
        cursor: pointer;
        transition: var(--transition);
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: var(--cyan-dim) !important;
        border-color: rgba(0,212,255,0.3) !important;
        color: var(--cyan) !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: linear-gradient(135deg, var(--cyan), #0099cc) !important;
        border-color: var(--cyan) !important;
        color: #000 !important;
        font-weight: 700;
    }

    /* ═══════════════════════════════════════════
       SWEETALERT OVERRIDE
    ═══════════════════════════════════════════ */
    .swal2-popup {
        background: #111827 !important;
        border: 1px solid var(--border2) !important;
        border-radius: 16px !important;
        color: var(--text) !important;
    }

    .swal2-title { color: #fff !important; }
    .swal2-html-container { color: var(--text-dim) !important; }

    .swal2-confirm {
        background: linear-gradient(135deg, var(--cyan), #0099cc) !important;
        color: #000 !important;
        font-weight: 700 !important;
    }

    .swal2-cancel {
        background: var(--surface2) !important;
        border: 1px solid var(--border2) !important;
        color: var(--text) !important;
    }

    /* ═══════════════════════════════════════════
       BOOTSTRAP GRID COMPATIBILITY
    ═══════════════════════════════════════════ */
    .container-fluid { width: 100%; padding: 0 16px; }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -8px;
    }

    .row > * { padding: 0 8px; }

    .g-1 > * { padding: 4px; }
    .g-2 > * { padding: 6px; }
    .g-3 > * { padding: 8px; }
    .g-4 > * { padding: 12px; }
    .mb-1 { margin-bottom: 4px !important; }
    .mb-2 { margin-bottom: 8px !important; }
    .mb-3 { margin-bottom: 16px !important; }
    .mb-4 { margin-bottom: 24px !important; }
    .mb-5 { margin-bottom: 40px !important; }
    .mt-1 { margin-top: 4px !important; }
    .mt-2 { margin-top: 8px !important; }
    .mt-3 { margin-top: 16px !important; }
    .mt-4 { margin-top: 24px !important; }
    .mt-5 { margin-top: 40px !important; }
    .me-1 { margin-right: 4px !important; }
    .me-2 { margin-right: 8px !important; }
    .me-3 { margin-right: 16px !important; }
    .ms-auto { margin-left: auto !important; }
    .ms-1 { margin-left: 4px !important; }
    .ms-2 { margin-left: 8px !important; }
    .p-0 { padding: 0 !important; }
    .p-2 { padding: 8px !important; }
    .p-3 { padding: 16px !important; }
    .p-4 { padding: 24px !important; }
    .px-3 { padding-left: 16px !important; padding-right: 16px !important; }
    .py-0 { padding-top: 0 !important; padding-bottom: 0 !important; }
    .py-1 { padding-top: 4px !important; padding-bottom: 4px !important; }
    .py-2 { padding-top: 8px !important; padding-bottom: 8px !important; }

    [class*="col-"] { width: 100%; min-height: 1px; box-sizing: border-box; }

    .col-1  { flex: 0 0 8.333%;  max-width: 8.333%; }
    .col-2  { flex: 0 0 16.666%; max-width: 16.666%; }
    .col-3  { flex: 0 0 25%;     max-width: 25%; }
    .col-4  { flex: 0 0 33.333%; max-width: 33.333%; }
    .col-5  { flex: 0 0 41.666%; max-width: 41.666%; }
    .col-6  { flex: 0 0 50%;     max-width: 50%; }
    .col-7  { flex: 0 0 58.333%; max-width: 58.333%; }
    .col-8  { flex: 0 0 66.666%; max-width: 66.666%; }
    .col-9  { flex: 0 0 75%;     max-width: 75%; }
    .col-10 { flex: 0 0 83.333%; max-width: 83.333%; }
    .col-11 { flex: 0 0 91.666%; max-width: 91.666%; }
    .col-12 { flex: 0 0 100%;    max-width: 100%; }

    @media (min-width: 576px) {
        .col-sm-1  { flex: 0 0 8.333%;  max-width: 8.333%; }
        .col-sm-2  { flex: 0 0 16.666%; max-width: 16.666%; }
        .col-sm-3  { flex: 0 0 25%;     max-width: 25%; }
        .col-sm-4  { flex: 0 0 33.333%; max-width: 33.333%; }
        .col-sm-5  { flex: 0 0 41.666%; max-width: 41.666%; }
        .col-sm-6  { flex: 0 0 50%;     max-width: 50%; }
        .col-sm-7  { flex: 0 0 58.333%; max-width: 58.333%; }
        .col-sm-8  { flex: 0 0 66.666%; max-width: 66.666%; }
        .col-sm-9  { flex: 0 0 75%;     max-width: 75%; }
        .col-sm-10 { flex: 0 0 83.333%; max-width: 83.333%; }
        .col-sm-11 { flex: 0 0 91.666%; max-width: 91.666%; }
        .col-sm-12 { flex: 0 0 100%;    max-width: 100%; }
    }

    @media (min-width: 768px) {
        .col-md-1  { flex: 0 0 8.333%;  max-width: 8.333%; }
        .col-md-2  { flex: 0 0 16.666%; max-width: 16.666%; }
        .col-md-3  { flex: 0 0 25%;     max-width: 25%; }
        .col-md-4  { flex: 0 0 33.333%; max-width: 33.333%; }
        .col-md-5  { flex: 0 0 41.666%; max-width: 41.666%; }
        .col-md-6  { flex: 0 0 50%;     max-width: 50%; }
        .col-md-7  { flex: 0 0 58.333%; max-width: 58.333%; }
        .col-md-8  { flex: 0 0 66.666%; max-width: 66.666%; }
        .col-md-9  { flex: 0 0 75%;     max-width: 75%; }
        .col-md-10 { flex: 0 0 83.333%; max-width: 83.333%; }
        .col-md-11 { flex: 0 0 91.666%; max-width: 91.666%; }
        .col-md-12 { flex: 0 0 100%;    max-width: 100%; }
        .d-md-none { display: none !important; }
        .d-md-block { display: block !important; }
    }

    @media (min-width: 992px) {
        .col-lg-1  { flex: 0 0 8.333%;  max-width: 8.333%; }
        .col-lg-2  { flex: 0 0 16.666%; max-width: 16.666%; }
        .col-lg-3  { flex: 0 0 25%;     max-width: 25%; }
        .col-lg-4  { flex: 0 0 33.333%; max-width: 33.333%; }
        .col-lg-5  { flex: 0 0 41.666%; max-width: 41.666%; }
        .col-lg-6  { flex: 0 0 50%;     max-width: 50%; }
        .col-lg-7  { flex: 0 0 58.333%; max-width: 58.333%; }
        .col-lg-8  { flex: 0 0 66.666%; max-width: 66.666%; }
        .col-lg-9  { flex: 0 0 75%;     max-width: 75%; }
        .col-lg-10 { flex: 0 0 83.333%; max-width: 83.333%; }
        .col-lg-11 { flex: 0 0 91.666%; max-width: 91.666%; }
        .col-lg-12 { flex: 0 0 100%;    max-width: 100%; }
    }

    @media (min-width: 1200px) {
        .col-xl-1  { flex: 0 0 8.333%;  max-width: 8.333%; }
        .col-xl-2  { flex: 0 0 16.666%; max-width: 16.666%; }
        .col-xl-3  { flex: 0 0 25%;     max-width: 25%; }
        .col-xl-4  { flex: 0 0 33.333%; max-width: 33.333%; }
        .col-xl-5  { flex: 0 0 41.666%; max-width: 41.666%; }
        .col-xl-6  { flex: 0 0 50%;     max-width: 50%; }
        .col-xl-7  { flex: 0 0 58.333%; max-width: 58.333%; }
        .col-xl-8  { flex: 0 0 66.666%; max-width: 66.666%; }
        .col-xl-9  { flex: 0 0 75%;     max-width: 75%; }
        .col-xl-10 { flex: 0 0 83.333%; max-width: 83.333%; }
        .col-xl-11 { flex: 0 0 91.666%; max-width: 91.666%; }
        .col-xl-12 { flex: 0 0 100%;    max-width: 100%; }
    }

    /* ═══════════════════════════════════════════
       UTILITIES
    ═══════════════════════════════════════════ */
    .d-flex   { display: flex !important; }
    .d-block  { display: block !important; }
    .d-inline { display: inline !important; }
    .d-none   { display: none !important; }
    .d-grid   { display: grid !important; }

    .flex-grow-1   { flex-grow: 1; }
    .flex-wrap     { flex-wrap: wrap; }
    .align-items-center  { align-items: center; }
    .align-items-start   { align-items: flex-start; }
    .align-items-end     { align-items: flex-end; }
    .justify-content-between { justify-content: space-between; }
    .justify-content-center  { justify-content: center; }
    .justify-content-end     { justify-content: flex-end; }

    .gap-1 { gap: 4px; }
    .gap-2 { gap: 8px; }
    .gap-3 { gap: 14px; }
    .gap-4 { gap: 20px; }

    .text-center  { text-align: center !important; }
    .text-end     { text-align: right !important; }
    .text-start   { text-align: left !important; }
    .text-muted   { color: var(--text-muted) !important; }
    .text-white   { color: #fff !important; }
    .text-success { color: var(--green) !important; }
    .text-danger  { color: var(--red) !important; }
    .text-warning { color: var(--amber) !important; }
    .text-info    { color: var(--cyan) !important; }
    .text-primary { color: var(--blue) !important; }
    .text-truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

    .fw-bold     { font-weight: 700 !important; }
    .fw-semibold { font-weight: 600 !important; }
    .fw-normal   { font-weight: 400 !important; }
    .small       { font-size: 12px !important; }
    .fs-5        { font-size: 1.1rem !important; }
    .fs-4        { font-size: 1.3rem !important; }
    .fs-3        { font-size: 1.6rem !important; }

    .w-100 { width: 100% !important; }
    .h-100 { height: 100% !important; }
    .overflow-hidden { overflow: hidden !important; }
    .rounded   { border-radius: var(--radius-sm) !important; }
    .rounded-circle { border-radius: 50% !important; }

    .border-bottom { border-bottom: 1px solid var(--border) !important; }
    .border-top    { border-top: 1px solid var(--border) !important; }

    .table { width: 100%; border-collapse: collapse; color: var(--text); }
    .table-sm td, .table-sm th { padding: 7px 10px; }
    .align-middle { vertical-align: middle !important; }

    .text-decoration-none { text-decoration: none !important; }

    /* Input group */
    .input-group {
        display: flex;
        align-items: stretch;
    }
    .input-group .form-control { border-radius: var(--radius-sm) 0 0 var(--radius-sm); flex: 1; }
    .input-group .btn { border-radius: 0 var(--radius-sm) var(--radius-sm) 0; }
    .input-group-text {
        background: var(--surface2);
        border: 1px solid var(--border2);
        border-right: none;
        border-radius: var(--radius-sm) 0 0 var(--radius-sm);
        color: var(--text-muted);
        padding: 8px 12px;
        font-size: 13px;
    }

    /* Spinner */
    .spinner-border {
        display: inline-block;
        width: 1.5rem; height: 1.5rem;
        border: 2px solid rgba(255,255,255,0.2);
        border-top-color: var(--cyan);
        border-radius: 50%;
        animation: spin .6s linear infinite;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    /* ═══════════════════════════════════════════
       RESPONSIVE
    ═══════════════════════════════════════════ */
    @media (max-width: 768px) {
        #adminSidebar { transform: translateX(-100%); }
        #adminSidebar.show { transform: translateX(0); }
        #adminMain { margin-left: 0; }
        .menu-toggle { display: flex; }
        .topbar-date, .topbar-username { display: none; }
        .content-area { padding: 16px; }
    }

    /* Sidebar overlay backdrop (mobile) */
    #sidebarOverlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.55);
        backdrop-filter: blur(2px);
        z-index: 199;
    }
    #sidebarOverlay.show { display: block; }
    </style>
</head>
<body>

<!-- Sidebar backdrop (mobile) -->
<div id="sidebarOverlay" onclick="toggleSidebar(false)"></div>

<!-- ═══ SIDEBAR ═══ -->
<div id="adminSidebar">
    <div class="sidebar-brand">
        <?php
        $_sbLogo = Helper::setting('site_logo', '');
        $_sbName = Helper::setting('site_name', 'Anything.lk');
        if ($_sbLogo): ?>
          <a href="<?= Helper::url('') ?>" style="display:inline-block;line-height:1;">
            <img src="<?= Helper::url('uploads/logo/'.htmlspecialchars($_sbLogo)) ?>"
                 alt="<?= htmlspecialchars($_sbName) ?>"
                 style="max-height:38px;max-width:160px;object-fit:contain;filter:brightness(0) invert(1);opacity:.9;">
          </a>
        <?php else: ?>
          <span class="brand-name"><?php
            $__sp = explode('.', $_sbName, 2);
            echo htmlspecialchars($__sp[0]);
            if (isset($__sp[1])) echo '<span>.'.htmlspecialchars($__sp[1]).'</span>';
          ?></span>
        <?php endif; ?>
        <small>Admin Panel</small>
    </div>
    <ul class="sidebar-nav">
        <?php
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $sidebarLink = function($url, $icon, $label, $badge='') use ($uri) {
            $active = (strpos($uri, '/' . ltrim($url,'admin/')) !== false || (rtrim($uri,'/') === '/admin' && $url==='admin/dashboard')) ? 'active' : '';
            $badgeHtml = $badge ? "<span class='sidebar-badge'>{$badge}</span>" : '';
            return "<li><a href='" . Helper::url($url) . "' class='{$active}'><i class='fa {$icon}'></i> {$label} {$badgeHtml}</a></li>";
        };
        echo '<li class="nav-section">Main</li>';
        echo $sidebarLink('admin/dashboard',       'fa-tachometer',        'Dashboard');
        echo '<li class="nav-section">Catalogue</li>';
        echo $sidebarLink('admin/products',         'fa-cube',              'Products');
        echo $sidebarLink('admin/categories',       'fa-th-large',          'Categories');
        echo $sidebarLink('admin/brands',           'fa-certificate',       'Brands');
        echo $sidebarLink('admin/vendors',          'fa-building',          'Vendors');
        echo '<li class="nav-section">Inventory</li>';
        echo $sidebarLink('admin/stock',            'fa-cubes',             'Stock',           $lowStockCount  > 0 ? $lowStockCount  : '');
        echo $sidebarLink('admin/purchase-orders',  'fa-file-text',         'Purchase Orders');
        echo '<li class="nav-section">Sales</li>';
        echo $sidebarLink('admin/orders',           'fa-shopping-bag',      'Orders',          $pendingOrders  > 0 ? $pendingOrders  : '');
        echo $sidebarLink('admin/coupons',          'fa-tag',               'Coupons');
        echo $sidebarLink('admin/banners',          'fa-image',             'Banners');
        echo $sidebarLink('admin/home-sections', 'fa-indent', 'Homepage Sections');
        echo '<li class="nav-section">Commerce</li>';
        echo $sidebarLink('admin/payments',         'fa-credit-card',       'Payment Methods');
        echo $sidebarLink('admin/payment-gateway',  'fa-key',               'Gateway Settings');
        echo $sidebarLink('admin/shipping',         'fa-truck',             'Shipping Methods');
        echo '<li class="nav-section">Community</li>';
        echo $sidebarLink('admin/users',            'fa-users',             'Customers');
        echo $sidebarLink('admin/reviews',          'fa-star-o',            'Reviews',         $pendingReviews > 0 ? $pendingReviews : '');
        echo '<li class="nav-section">Analytics</li>';
        echo $sidebarLink('admin/reports/sales',    'fa-bar-chart',         'Sales Report');
        echo $sidebarLink('admin/reports/products', 'fa-line-chart',        'Product Report');
        echo '<li class="nav-section">System</li>';
        echo $sidebarLink('admin/chat',              'fa-comments',          'Live Chat');
        echo $sidebarLink('admin/messages',         'fa-envelope',          'Messages',        $unreadMessages > 0 ? $unreadMessages : '');
        echo $sidebarLink('admin/newsletter',       'fa-paper-plane',       'Newsletter');
        echo $sidebarLink('admin/contact-settings', 'fa-phone',             'Contact & Social');
        echo $sidebarLink('admin/settings',         'fa-cog',               'Settings');
        ?>
        <li><a href="<?= Helper::url('') ?>" target="_blank"><i class="fa fa-external-link"></i> View Site</a></li>
        <li><a href="<?= Helper::url('logout') ?>"><i class="fa fa-sign-out"></i> Logout</a></li>
    </ul>
</div>

<!-- ═══ MAIN ═══ -->
<div id="adminMain">
    <div id="adminTopbar">
        <div class="topbar-left">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <i class="fa fa-bars"></i>
            </button>
            <h6><?= e($pageTitle ?? 'Dashboard') ?></h6>
        </div>
        <div class="topbar-right">
            <?php if ($lowStockCount > 0): ?>
            <a href="<?= Helper::url('admin/stock/low-stock') ?>" class="topbar-alert">
                <i class="fa fa-exclamation-triangle"></i><?= $lowStockCount ?> Low Stock
            </a>
            <?php endif; ?>
            <span class="topbar-date"><?= date('D, M j Y') ?></span>
            <div class="topbar-avatar">
                <?= strtoupper(substr($adminUser['name'] ?? 'A', 0, 1)) ?>
            </div>
            <span class="topbar-username"><?= e($adminUser['name'] ?? '') ?></span>
        </div>
    </div>

    <div class="content-area">
        <?= $content ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const SITE_URL   = '<?= Helper::url('') ?>';
const CSRF_TOKEN = '<?= $csrfToken ?>';

// Override SweetAlert2 theme
const SwalDark = Swal.mixin({
    background: '#111827',
    color: '#e2e8f0',
    confirmButtonColor: '#00d4ff',
    cancelButtonColor: '#1f2937',
    customClass: { popup: 'swal2-popup' }
});

function ajaxPost(url, data, onSuccess, onError) {
    data._csrf = CSRF_TOKEN;
    $.ajax({
        url: SITE_URL + '/' + url,
        type: 'POST', data: data, dataType: 'json',
        success: onSuccess,
        error: onError || function() { SwalDark.fire('Error', 'Something went wrong.', 'error'); }
    });
}

$(document).ready(function() {
    $('.admin-datatable').each(function() {
        if (!$.fn.DataTable.isDataTable(this)) {
            $(this).DataTable({
                pageLength: 25,
                responsive: true,
                language: { search: '', searchPlaceholder: 'Search...', paginate: { next: '›', previous: '‹' } }
            });
        }
    });
});

function toggleSidebar(force) {
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const isOpen  = sidebar.classList.contains('show');
    const open    = force === undefined ? !isOpen : force;
    sidebar.classList.toggle('show', open);
    overlay.classList.toggle('show', open);
}

$(document).on('click', function(e) {
    if ($(window).width() < 768 && !$(e.target).closest('#adminSidebar, .menu-toggle').length) {
        toggleSidebar(false);
    }
});
</script>

<?= $extraScript ?? '' ?>
</body>
</html>
