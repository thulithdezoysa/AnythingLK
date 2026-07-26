<!DOCTYPE html>
<html lang="<?= e($currentLang) ?>" data-theme="light" id="htmlRoot">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title><?= isset($pageTitle) ? e($pageTitle).' — ' : '' ?><?= e(setting('site_name','Anything.lk')) ?></title>
  <meta name="description" content="<?= isset($metaDesc) ? e($metaDesc) : 'Anything.lk — Sri Lanka\'s premier online marketplace' ?>">

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon"       href="<?= Helper::asset('img/favicon.ico') ?>">
  <link rel="icon" type="image/svg+xml"       href="<?= Helper::asset('img/favicon.svg') ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= Helper::asset('img/favicon-32x32.png') ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= Helper::asset('img/favicon-16x16.png') ?>">
  <link rel="apple-touch-icon" sizes="180x180"    href="<?= Helper::asset('img/apple-touch-icon.png') ?>">
  <link rel="manifest"                            href="<?= Helper::asset('img/site.webmanifest') ?>">
  <meta name="theme-color" content="#E63946">
  <meta name="msapplication-TileColor" content="#E63946">

  <!-- Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Google Fonts — Inter + Outfit -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">
  <!-- AOS Animation Library -->
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

  <style>
  /* ═══════════════════════════════════════════════
     ANYTHING.LK — Premium Design System v3
     Bootstrap 5 enhanced — Sri Lankan e-commerce
  ═══════════════════════════════════════════════ */

  /* ── Color Palette ── */
  :root {
    --brand:        #E63946;        /* vibrant red-coral */
    --brand-dark:   #c1121f;
    --brand-light:  #fff1f2;
    --navy:         #0d1b2a;
    --gold:         #f4a261;
    --teal:         #2ec4b6;
    --text:         #1a1a2e;
    --text-muted:   #6c757d;
    --border:       #e9ecef;
    --bg:           #ffffff;
    --bg-soft:      #f8f9fc;
    --radius:       12px;
    --radius-lg:    18px;
    --shadow:       0 2px 20px rgba(0,0,0,.08);
    --shadow-lg:    0 8px 40px rgba(0,0,0,.12);
    --header-h:     70px;
    --topbar-h:     36px;
    --transition:   all .25s cubic-bezier(.4,0,.2,1);

    /* gradient shortcuts */
    --grad-brand:   linear-gradient(135deg, #E63946 0%, #c1121f 100%);
    --grad-logo:    linear-gradient(90deg, #E63946, #f4a261);
    --grad-accent:  linear-gradient(135deg, #f4a261, #E63946);
    --grad-navy:    linear-gradient(135deg, #0d1b2a 0%, #1a2f47 100%);

    /* aliases kept for any inline styles throughout views */
    --primary:      #E63946;
    --primary-dark: #c1121f;
    --primary-light: #fff1f2;
    --accent:       #f4a261;
    --success:      #2ec4b6;
    --danger:       #E63946;
    --cyan:         #2ec4b6;
    --bg-card:      #ffffff;
    --bg-light:     #f8f9fc;
    --text-dark:    #1a1a2e;
    --shadow-sm:    0 1px 6px rgba(0,0,0,.06);
    --shadow-md:    0 4px 24px rgba(0,0,0,.10);
    --glow-primary: 0 0 20px rgba(230,57,70,.25);
  }

  /* ── Dark Mode overrides ── */
  [data-theme="dark"] {
    --bg:           #0d1117;
    --bg-soft:      #161b22;
    --bg-card:      #1c2128;
    --bg-light:     #161b22;
    --text:         #e6edf3;
    --text-dark:    #e6edf3;
    --text-muted:   #8b949e;
    --border:       rgba(255,255,255,.1);
    --shadow:       0 2px 20px rgba(0,0,0,.4);
    --shadow-lg:    0 8px 40px rgba(0,0,0,.6);
    --shadow-sm:    0 1px 6px rgba(0,0,0,.4);
    --shadow-md:    0 4px 24px rgba(0,0,0,.5);
    --glow-primary: 0 0 24px rgba(230,57,70,.4);
  }

  /* ── Custom Scrollbar ── */
  ::-webkit-scrollbar { width: 5px; height: 5px; }
  ::-webkit-scrollbar-track { background: transparent; }
  ::-webkit-scrollbar-thumb { background: var(--grad-brand); border-radius: 99px; }
  ::-webkit-scrollbar-thumb:hover { background: var(--brand-dark); }

  /* ── Base ── */
  html { scroll-behavior: smooth; }
  body {
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    line-height: 1.6;
    color: var(--text);
    background: var(--bg);
    transition: background .35s, color .35s;
    padding-top: var(--header-h);
  }
  h1, h2, h3, h4, h5, h6,
  .h1, .h2, .h3, .h4, .h5, .h6 {
    font-family: 'Outfit', sans-serif;
  }
  a { color: inherit; text-decoration: none; transition: var(--transition); }
  img { max-width: 100%; }

  /* ── Bootstrap overrides — keep brand red as primary ── */
  .btn-primary {
    background: var(--grad-brand) !important;
    border-color: var(--brand) !important;
    color: #fff !important;
    box-shadow: 0 4px 14px rgba(230,57,70,.3) !important;
    font-family: 'Inter', sans-serif;
  }
  .btn-primary:hover, .btn-primary:focus {
    background: linear-gradient(135deg, #c1121f, #a00f18) !important;
    border-color: #a00f18 !important;
    box-shadow: 0 6px 22px rgba(230,57,70,.5) !important;
    transform: translateY(-1px);
  }
  .btn-outline-primary {
    border-color: var(--brand) !important;
    color: var(--brand) !important;
  }
  .btn-outline-primary:hover {
    background: var(--brand) !important;
    border-color: var(--brand) !important;
    color: #fff !important;
  }
  .btn-danger {
    background: var(--grad-brand) !important;
    border-color: var(--brand) !important;
  }
  .badge.bg-primary { background-color: var(--brand) !important; }
  .text-primary { color: var(--brand) !important; }
  .bg-primary { background-color: var(--brand) !important; }
  .border-primary { border-color: var(--brand) !important; }
  a.text-primary:hover { color: var(--brand-dark) !important; }
  .form-control:focus, .form-select:focus {
    border-color: var(--brand) !important;
    box-shadow: 0 0 0 3px rgba(230,57,70,.15) !important;
  }
  .form-check-input:checked {
    background-color: var(--brand) !important;
    border-color: var(--brand) !important;
  }
  .nav-link.active, .nav-pills .nav-link.active { background-color: var(--brand) !important; }
  .page-link { color: var(--brand) !important; }
  .page-item.active .page-link {
    background-color: var(--brand) !important;
    border-color: var(--brand) !important;
    color: #fff !important;
  }
  .progress-bar { background-color: var(--brand) !important; }
  .spinner-border { color: var(--brand) !important; }

  /* Bootstrap cards — premium */
  .card {
    border-radius: var(--radius-lg) !important;
    border: 1px solid var(--border) !important;
    box-shadow: var(--shadow) !important;
    background: var(--bg-card) !important;
  }
  .card-header { background: var(--bg-soft) !important; }

  /* Bootstrap form controls */
  .form-control, .form-select {
    border-radius: 10px !important;
    border-color: var(--border) !important;
    background: var(--bg-card) !important;
    color: var(--text) !important;
    font-family: 'Inter', sans-serif;
    transition: var(--transition);
  }
  .form-control-sm, .form-select-sm { border-radius: 8px !important; }
  .input-group-text {
    background: var(--bg-soft) !important;
    border-color: var(--border) !important;
    color: var(--text-muted) !important;
  }

  /* Bootstrap dropdown */
  .dropdown-menu {
    border-radius: var(--radius) !important;
    border: 1px solid var(--border) !important;
    box-shadow: var(--shadow-lg) !important;
    font-size: 13px;
    background: var(--bg-card) !important;
    color: var(--text) !important;
    padding: 6px 0 !important;
  }
  .dropdown-item {
    color: var(--text) !important;
    padding: 8px 16px !important;
    transition: var(--transition);
    border-radius: 0;
  }
  .dropdown-item:hover, .dropdown-item:focus {
    background: var(--brand-light) !important;
    color: var(--brand) !important;
  }
  .dropdown-item.active {
    background: var(--brand) !important;
    color: #fff !important;
  }
  .dropdown-divider { border-color: var(--border) !important; }

  /* Bootstrap alerts */
  .alert-primary {
    background: var(--brand-light) !important;
    border-color: rgba(230,57,70,.3) !important;
    color: var(--brand-dark) !important;
  }

  /* Bootstrap modal */
  .modal-content {
    border-radius: var(--radius-lg) !important;
    border: 1px solid var(--border) !important;
    background: var(--bg-card) !important;
  }
  .modal-header { border-bottom-color: var(--border) !important; }
  .modal-footer { border-top-color: var(--border) !important; }

  /* Bootstrap table */
  .table { color: var(--text) !important; }
  .table td, .table th { border-color: var(--border) !important; }
  .table-striped > tbody > tr:nth-of-type(odd) > * {
    background-color: var(--bg-soft) !important;
    color: var(--text) !important;
  }

  /* ── Mobile Nav Drawer ─────────────────────────────── */
  #mobileMenu {
    width: min(340px, 93vw);
    max-width: none;
    background: var(--bg-card);
    border-right: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    padding: 0;
  }

  .mnav-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 16px 13px;
    border-bottom: 1px solid var(--border);
    flex-shrink: 0;
  }

  .mnav-close {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: var(--bg-soft);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    font-size: 17px;
    cursor: pointer;
    line-height: 1;
    transition: background .2s, color .2s, border-color .2s;
    flex-shrink: 0;
  }

  .mnav-close:hover { background: var(--brand); color: #fff; border-color: var(--brand); }

  /* Search */
  .mnav-search {
    padding: 11px 14px;
    border-bottom: 1px solid var(--border);
    flex-shrink: 0;
    position: relative;
  }

  .mnav-search-input {
    width: 100%;
    background: var(--bg-soft);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 9px 36px 9px 14px;
    font-size: .84rem;
    color: var(--text);
    outline: none;
    transition: border-color .2s, box-shadow .2s;
  }

  .mnav-search-input::placeholder { color: var(--text-muted); }
  .mnav-search-input:focus { border-color: var(--brand); box-shadow: 0 0 0 3px rgba(230,57,70,.1); }

  .mnav-search-icon {
    position: absolute;
    right: 27px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 13px;
    pointer-events: none;
  }

  /* Tabs */
  .mnav-tabs {
    display: flex;
    flex-shrink: 0;
    border-bottom: 1px solid var(--border);
  }

  .mnav-tab {
    flex: 1;
    padding: 11px 8px;
    border: none;
    background: none;
    color: var(--text-muted);
    font-size: .8rem;
    font-weight: 700;
    letter-spacing: .02em;
    border-bottom: 2px solid transparent;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: color .2s, border-color .2s;
  }

  .mnav-tab.active { color: var(--brand); border-bottom-color: var(--brand); }

  /* Scrollable body — fills remaining height */
  .mnav-body {
    flex: 1;
    position: relative;
    overflow: hidden;
    min-height: 0;
  }

  .mnav-panel {
    position: absolute;
    inset: 0;
    overflow-y: auto;
    overflow-x: hidden;
    transform: translateX(100%);
    transition: transform .28s cubic-bezier(.4,0,.2,1);
    will-change: transform;
  }

  .mnav-panel.active { transform: translateX(0); }

  /* ── Category drill-down viewport ── */
  .mcat-viewport {
    position: relative;
    overflow: hidden;
  }

  .mcat-level {
    /* Stacked panels — only active is visible in flow */
    display: none;
    width: 100%;
    background: var(--bg-card);
    transform: translateX(100%);
    transition: transform .28s cubic-bezier(.4,0,.2,1);
    will-change: transform;
  }

  /* Active and behind levels are in-flow (display:block) */
  .mcat-level.is-active,
  .mcat-level.is-behind {
    display: block;
    position: absolute;
    inset: 0;
    overflow-y: auto;
  }

  /* The is-active level is position:relative to size the viewport */
  .mcat-level.is-active  { position: relative; transform: translateX(0); }
  .mcat-level.is-behind  { transform: translateX(-28%); }

  /* Back bar */
  .mcat-back-bar {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 11px 14px 10px;
    border-bottom: 1px solid var(--border);
    background: var(--bg-soft);
    position: sticky;
    top: 0;
    z-index: 2;
  }

  .mcat-back-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    background: none;
    border: none;
    color: var(--brand);
    font-size: .8rem;
    font-weight: 700;
    cursor: pointer;
    padding: 4px 0;
    flex-shrink: 0;
  }

  .mcat-back-title {
    flex: 1;
    font-weight: 700;
    font-size: .88rem;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .mcat-see-all {
    font-size: .74rem;
    color: var(--brand);
    font-weight: 600;
    text-decoration: none;
    flex-shrink: 0;
    white-space: nowrap;
  }

  /* Category items */
  .mcat-item {
    display: flex;
    align-items: center;
    border-bottom: 1px solid var(--border);
  }

  .mcat-item.hidden { display: none; }

  .mcat-link {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    color: var(--text);
    text-decoration: none;
    font-size: .875rem;
    font-weight: 500;
    min-height: 52px;
    transition: color .15s;
  }

  .mcat-link:hover, .mcat-link:focus { color: var(--brand); }

  .mcat-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(230,57,70,.08);
    color: var(--brand);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
    transition: background .2s;
  }

  [data-theme="dark"] .mcat-icon { background: rgba(230,57,70,.14); }
  .mcat-item:hover .mcat-icon { background: rgba(230,57,70,.16); }

  .mcat-name { flex: 1; line-height: 1.3; }

  .mcat-drill {
    min-width: 46px;
    height: 52px;
    background: none;
    border: none;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 11px;
    flex-shrink: 0;
    transition: color .15s;
  }

  .mcat-drill:hover { color: var(--brand); }

  /* ── Menu tab items ── */
  .mnav-menu-item {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 13px 16px;
    color: var(--text);
    text-decoration: none;
    font-size: .9rem;
    font-weight: 500;
    border-bottom: 1px solid var(--border);
    min-height: 52px;
    transition: color .15s, background .15s;
  }

  .mnav-menu-item:hover, .mnav-menu-item:focus { background: var(--bg-soft); color: var(--brand); }
  .mnav-menu-item.danger { color: #ef4444; }
  .mnav-menu-item.danger:hover { color: #dc2626; }
  .mnav-menu-item.primary { color: var(--brand); font-weight: 700; }

  .mnav-menu-icon {
    width: 38px;
    height: 38px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
  }

  .mnav-section-label {
    padding: 10px 16px 6px;
    font-size: .7rem;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--text-muted);
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border);
  }

  /* User chip in menu tab */
  .mnav-user-chip {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
    background: var(--bg-soft);
  }

  .mnav-user-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 2px solid var(--brand);
  }

  .mnav-user-placeholder {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: var(--grad-brand);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
  }

  .mnav-user-name { font-weight: 700; font-size: .9rem; color: var(--text); line-height: 1.3; }
  .mnav-user-email { font-size: .74rem; color: var(--text-muted); }

  /* Footer */
  .mnav-footer {
    padding: 13px 14px;
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
    background: var(--bg-soft);
  }

  .mnav-wa-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 14px;
    background: #25d366;
    color: #fff;
    border-radius: 11px;
    font-size: .82rem;
    font-weight: 700;
    text-decoration: none;
    transition: background .2s, transform .15s;
  }

  .mnav-wa-btn:hover { background: #1ebe5d; color: #fff; transform: translateY(-1px); }

  /* Bootstrap carousel controls */
  .carousel-control-prev-icon, .carousel-control-next-icon { filter: none; }
  .carousel-control-prev, .carousel-control-next {
    background: rgba(230,57,70,.7) !important;
    border-radius: 50% !important;
    width: 40px !important; height: 40px !important;
    top: 50%; transform: translateY(-50%);
    opacity: 1 !important;
    transition: var(--transition);
  }
  .carousel-control-prev { left: 10px !important; }
  .carousel-control-next { right: 10px !important; }
  .carousel-control-prev:hover, .carousel-control-next:hover {
    background: var(--brand) !important;
    box-shadow: var(--glow-primary) !important;
  }
  .carousel-indicators [data-bs-target] {
    border-radius: 50% !important;
    width: 8px !important; height: 8px !important;
    background-color: rgba(255,255,255,.6) !important;
  }
  .carousel-indicators .active { background-color: #fff !important; width: 24px !important; border-radius: 4px !important; }

  /* Bootstrap btn base enhancements */
  .btn {
    font-family: 'Inter', sans-serif;
    font-weight: 600;
    border-radius: var(--radius) !important;
    transition: var(--transition) !important;
  }
  .btn:focus { box-shadow: 0 0 0 3px rgba(230,57,70,.2) !important; }
  .btn-sm { border-radius: 8px !important; }
  .btn-lg { border-radius: var(--radius-lg) !important; }

  /* Bootstrap pill search form */
  .input-group .form-control:first-child { border-radius: 50px 0 0 50px !important; }
  .input-group .btn:last-child          { border-radius: 0 50px 50px 0 !important; }

  /* ── Top Bar ── */
  #topBar {
    position: fixed; top: 0; left: 0; right: 0; z-index: 1001;
    background: var(--navy);
    height: var(--topbar-h);
    border-bottom: 1px solid rgba(255,255,255,.07);
    font-size: 12px; color: rgba(255,255,255,.65);
    transition: transform .35s cubic-bezier(.4,0,.2,1);
  }
  #topBar.topbar-hidden { transform: translateY(-100%); }
  #catNavBar  { transition: top .35s cubic-bezier(.4,0,.2,1); }
  .topbar-contact { display: flex; align-items: center; gap: 18px; }
  .topbar-contact a { color: rgba(255,255,255,.65); transition: var(--transition); display: flex; align-items: center; gap: 5px; }
  .topbar-contact a:hover { color: #fff; }
  .topbar-social { display: flex; align-items: center; gap: 5px; }
  .topbar-social-label { font-size: 11px; color: rgba(255,255,255,.35); margin-right: 4px; }
  .topbar-social a {
    width: 24px; height: 24px; border-radius: 5px;
    background: rgba(255,255,255,.08);
    display: inline-flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,.5); font-size: 11px;
    transition: var(--transition);
  }
  .topbar-social a:hover { background: var(--brand); color: #fff; }
  [data-theme="dark"] #topBar { background: #0a0f16; }

  /* ── Header ── */
  #siteHeader {
    position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
    background: #ffffff;
    border-bottom: 1px solid var(--border);
    height: var(--header-h);
    display: flex; align-items: center;
    transition: top .35s cubic-bezier(.4,0,.2,1), background .35s, box-shadow .35s;
  }
  #siteHeader.scrolled {
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    border-bottom-color: rgba(230,57,70,.15);
    box-shadow: 0 4px 28px rgba(0,0,0,.09);
  }
  [data-theme="dark"] #siteHeader { background: var(--navy); border-bottom-color: rgba(255,255,255,.08); }
  [data-theme="dark"] #siteHeader.scrolled { background: rgba(13,27,42,0.92); }
  .header-inner { display: flex; align-items: center; gap: 12px; width: 100%; }

  /* Logo */
  .site-logo {
    font-family: 'Outfit', sans-serif;
    font-size: 24px; font-weight: 800;
    background: var(--grad-logo);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
    white-space: nowrap; letter-spacing: -0.5px;
    line-height: 1;
  }
  .site-logo span {
    background: linear-gradient(90deg, #f4a261, #E63946);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  /* Search */
  .header-search { position: relative; flex: 1; max-width: 520px; }
  .header-search input {
    width: 100%; padding: 10px 48px 10px 20px;
    border: 1.5px solid var(--border); border-radius: 50px;
    background: var(--bg-soft); color: var(--text);
    font-size: 13.5px; transition: var(--transition);
    font-family: 'Inter', sans-serif;
  }
  .header-search input:focus {
    border-color: var(--brand);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(230,57,70,.12);
    outline: none;
  }
  .header-search .search-btn {
    position: absolute; right: 0; top: 0; bottom: 0;
    width: 44px; border: none;
    background: var(--grad-brand);
    color: #fff; border-radius: 0 50px 50px 0; cursor: pointer;
    transition: var(--transition); font-size: 14px;
  }
  .header-search .search-btn:hover { background: var(--brand-dark); }
  .search-dropdown {
    position: absolute; top: calc(100% + 8px); left: 0; right: 0;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-lg);
    z-index: 500; display: none; overflow: hidden;
    max-height: 380px; overflow-y: auto;
  }
  .search-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 14px; border-bottom: 1px solid var(--border);
    transition: var(--transition);
  }
  .search-item:hover { background: var(--brand-light); }
  .search-item img { width: 40px; height: 40px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }

  /* Header actions */
  .header-actions { display: flex; align-items: center; gap: 2px; margin-left: auto; }
  /* Base square icon btn (hamburger / etc.) */
  .h-btn {
    position: relative; display: flex; align-items: center; justify-content: center;
    width: 34px; height: 34px; border-radius: 9px; border: none;
    background: transparent; color: var(--text); cursor: pointer;
    font-size: 16px; transition: var(--transition);
  }
  .h-btn:hover { background: var(--brand-light); color: var(--brand); }
  /* Compact icon-only action buttons */
  .h-icon-btn {
    position: relative; display: flex; align-items: center; justify-content: center;
    width: 34px; height: 34px; border-radius: 9px; border: none;
    background: transparent; color: var(--text); cursor: pointer;
    font-size: 16px; transition: var(--transition); flex-shrink: 0;
  }
  .h-icon-btn:hover { background: var(--brand-light); color: var(--brand); }
  .h-icon-btn.dropdown-toggle::after { display: none; }
  /* Always hide text labels — icon only */
  .h-icon-label { display: none !important; }
  /* Badge — tight, pill-shaped */
  .h-badge {
    position: absolute; top: 3px; right: 3px;
    background: var(--brand); color: #fff;
    font-size: 7.5px; font-weight: 800; line-height: 1;
    min-width: 13px; height: 13px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    padding: 0 3px; border: 1.5px solid var(--bg);
    letter-spacing: 0;
  }
  /* Sign-in pill button */
  .btn-signin {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 14px; border-radius: 20px;
    background: var(--brand); color: #fff !important; border: none;
    font-weight: 600; font-size: 12.5px; cursor: pointer; white-space: nowrap;
    transition: var(--transition); font-family: 'Inter', sans-serif;
    letter-spacing: .01em;
  }
  .btn-signin:hover { filter: brightness(1.12); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(230,57,70,.3); }
  /* ── Cat sub-dropdowns ── */
  .cat-sub-wrap { position: relative; }
  .cat-subdrop {
    position: absolute; top: 100%; left: 0; min-width: 200px;
    background: var(--bg-card); border: 1px solid var(--border);
    border-top: 2px solid var(--brand);
    border-radius: 0 0 var(--radius) var(--radius);
    box-shadow: 0 8px 24px rgba(0,0,0,.14);
    list-style: none; padding: 6px 0; z-index: 999;
    opacity: 0; pointer-events: none;
    transform: translateY(4px);
    transition: opacity .2s, transform .2s;
  }
  .cat-sub-wrap:hover .cat-subdrop { opacity: 1; pointer-events: auto; transform: translateY(0); }
  .cat-subdrop a { display: block; padding: 8px 16px; color: var(--text); font-size: 13px; white-space: nowrap; transition: var(--transition); }
  .cat-subdrop a:hover { background: var(--bg-soft); color: var(--brand); padding-left: 22px; }
  /* ── Cat caret ── */
  .cat-caret { font-size: 9px; opacity: .6; margin-left: 3px; transition: transform .2s; flex-shrink: 0; }
  .cat-sub-wrap:hover .cat-caret, #catMegaToggle.active .cat-caret { transform: rotate(180deg); }
  /* ── More overflow dropdown ── */
  .cat-more-wrap { position: relative; flex-shrink: 0; }
  .cat-more-drop {
    position: absolute; top: 100%; right: 0; min-width: 200px;
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 0 0 var(--radius) var(--radius);
    box-shadow: 0 8px 24px rgba(0,0,0,.14);
    list-style: none; padding: 6px 0; z-index: 999; display: none;
  }
  .cat-more-wrap.open .cat-more-drop { display: block; }
  .cat-more-wrap.open .cat-caret { transform: rotate(180deg); }
  .cat-more-drop a { display: block; padding: 9px 16px; color: var(--text); font-size: 13px; white-space: nowrap; transition: var(--transition); }
  .cat-more-drop a:hover { background: var(--bg-soft); color: var(--brand); }

  /* ── Category Nav Bar ── */
  #catNavBar {
    position: fixed; left: 0; right: 0; z-index: 998;
    top: var(--header-h);   /* mobile default (no topbar) */
    background: var(--navy);
    border-bottom: 1px solid rgba(255,255,255,.06);
    transition: top .35s cubic-bezier(.4,0,.2,1);
  }
  .cat-nav-inner { display: flex; align-items: center; overflow-x: auto; }
  .cat-nav-inner::-webkit-scrollbar { display: none; }
  .cat-link {
    display: flex; align-items: center; gap: 6px;
    padding: 10px 16px; color: rgba(255,255,255,.75);
    font-size: 13px; font-weight: 500; white-space: nowrap;
    transition: var(--transition);
    border-bottom: 2px solid transparent;
    position: relative;
  }
  .cat-link:hover, .cat-link.active {
    color: #fff;
    border-bottom-color: var(--brand);
  }
  .cat-link i { font-size: 12px; opacity: .8; }

  /* ── Mega menu — container-width dropdown ── */
  .cat-mega-btn { position: static; }
  #catNavBar .container { position: relative; }
  .cat-mega-menu {
    position: absolute; top: 100%; left: 0; right: 0;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-top: 2px solid var(--brand);
    border-radius: 0 0 var(--radius-lg) var(--radius-lg);
    box-shadow: 0 16px 48px rgba(0,0,0,.14);
    z-index: 997; display: none;
    animation: megaIn .18s cubic-bezier(.4,0,.2,1);
  }
  @keyframes megaIn { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }
  .cat-mega-menu.open { display: block; }
  .mega-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    max-height: calc(50vh - var(--header-h) - 41px);
    overflow-y: auto; padding: 20px 0;
  }
  .mega-col {
    padding: 14px 18px;
    border-right: 1px solid var(--border);
  }
  .mega-col:last-child { border-right: none; }
  .mega-col-title {
    display: flex; align-items: center; gap: 7px;
    font-weight: 700; font-size: 13px; color: var(--text);
    padding-bottom: 9px; margin-bottom: 8px;
    border-bottom: 2px solid var(--border);
    text-decoration: none; transition: var(--transition); white-space: nowrap;
  }
  .mega-col-title:hover { color: var(--brand); border-bottom-color: var(--brand); }
  .mega-col-title i { color: var(--brand); font-size: 12px; width: 15px; text-align: center; flex-shrink: 0; }
  .mega-col a.mega-sub-link {
    display: block; padding: 4px 0; font-size: 12.5px;
    color: var(--text-muted); transition: var(--transition);
  }
  .mega-col a.mega-sub-link:hover { color: var(--brand); padding-left: 5px; }
  /* Grandchild links — visually indented and de-emphasised */
  .mega-col a.mega-sub-link-child {
    padding: 3px 0 3px 10px;
    font-size: 11.5px;
    color: var(--text-muted);
    opacity: .75;
    border-left: 1px solid var(--border);
    margin-left: 4px;
    display: block;
    transition: var(--transition);
  }
  .mega-col a.mega-sub-link-child:hover { color: var(--brand); opacity: 1; padding-left: 14px; }
  #catMegaToggle .fa-chevron-down { transition: transform .25s; }
  #catMegaToggle.active .fa-chevron-down { transform: rotate(180deg); }
  [data-theme="dark"] .cat-mega-menu { background: var(--bg-card); }

  /* ── Slide Panels ── */
  .slide-panel-overlay {
    position: fixed; inset: 0; background: rgba(0,0,0,.5);
    backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
    z-index: 1100; opacity: 0; pointer-events: none;
    transition: opacity .3s;
  }
  .slide-panel-overlay.open { opacity: 1; pointer-events: all; }
  .slide-panel {
    position: fixed; top: 0; right: -420px; bottom: 0;
    width: 380px; max-width: 100vw;
    background: var(--bg-card);
    border-left: 1px solid var(--border);
    z-index: 1200;
    display: flex; flex-direction: column;
    box-shadow: -4px 0 40px rgba(0,0,0,.15);
    transition: right .35s cubic-bezier(.4,0,.2,1);
  }
  .slide-panel.open { right: 0; }
  .slide-panel-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 20px;
    font-weight: 700; font-size: 16px;
    background: var(--grad-brand);
    color: #fff;
    font-family: 'Outfit', sans-serif;
  }
  .slide-panel-close {
    width: 32px; height: 32px; border: none; border-radius: 50%;
    background: rgba(255,255,255,.2); color: #fff; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; transition: var(--transition);
  }
  .slide-panel-close:hover { background: rgba(0,0,0,.25); }
  .slide-panel-body { flex: 1; overflow-y: auto; padding: 16px; }
  .slide-panel-footer {
    padding: 16px; border-top: 1px solid var(--border);
    background: var(--bg-card);
  }
  .panel-item {
    display: flex; gap: 12px; padding: 12px 0;
    border-bottom: 1px solid var(--border);
  }
  .panel-item img { width: 64px; height: 64px; object-fit: cover; border-radius: 10px; flex-shrink: 0; }
  .panel-item-info { flex: 1; min-width: 0; }
  .panel-item-name { font-weight: 600; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .panel-item-price {
    background: var(--grad-logo);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 700; font-size: 13px; margin-top: 2px;
  }
  .panel-item-remove {
    width: 28px; height: 28px; border: none;
    background: var(--bg-soft); border-radius: 50%; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; color: var(--text-muted);
    transition: var(--transition); flex-shrink: 0; align-self: center;
  }
  .panel-item-remove:hover { background: var(--brand); color: #fff; }
  .panel-qty { display: flex; align-items: center; gap: 6px; margin-top: 6px; }
  .panel-qty button {
    width: 24px; height: 24px;
    border: 1px solid var(--border);
    background: var(--bg-soft); color: var(--text);
    border-radius: 6px; cursor: pointer; font-size: 14px;
    display: flex; align-items: center; justify-content: center;
    transition: var(--transition);
  }
  .panel-qty button:hover { background: var(--brand); color: #fff; border-color: var(--brand); }
  .panel-qty span { min-width: 24px; text-align: center; font-weight: 600; font-size: 13px; }

  /* ══════════════════════════════════════════════════════
     PRODUCT CARDS
  ══════════════════════════════════════════════════════ */

  .product-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: box-shadow .28s ease, transform .28s ease;
  }

  @media (hover: hover) {
    .product-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(0,0,0,.08), 0 20px 40px rgba(0,0,0,.07);
    }
  }

  .product-card.out-of-stock { opacity: .68; }

  /* ─── Image wrap ──────────────────────────────────── */
  .product-card .img-wrap {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1 / 1;
    background: var(--bg-soft);
    flex-shrink: 0;
  }

  .product-card .img-wrap > a { display: block; width: 100%; height: 100%; }

  .product-card .img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  /* ─── Badges — top-left stack ─────────────────────── */
  .pc-badge-stack {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 4;
    display: flex;
    flex-direction: column;
    gap: 4px;
    pointer-events: none;
  }

  .pc-label-badge,
  .pc-discount-badge {
    display: inline-block;
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    padding: 3px 9px;
    border-radius: 4px;
    line-height: 1.6;
    white-space: nowrap;
  }

  .pc-label-new  { background: #0ea5e9; color: #fff; }
  .pc-label-best { background: #f59e0b; color: #fff; }
  .pc-discount-badge { background: var(--brand); color: #fff; }

  /* ─── Wishlist — top-right, always visible ─────────── */
  .pc-wishlist {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 5;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: rgba(255,255,255,.75);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.6);
    box-shadow: 0 2px 8px rgba(0,0,0,.12);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #475569;
    font-size: 13px;
    transition: background .2s ease, color .2s ease, transform .2s ease, box-shadow .2s ease;
  }

  [data-theme="dark"] .pc-wishlist {
    background: rgba(15,23,42,.75);
    border-color: rgba(255,255,255,.12);
    color: #94a3b8;
  }

  .pc-wishlist:hover,
  .pc-wishlist.active {
    background: var(--brand);
    border-color: var(--brand);
    color: #fff;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(230,57,70,.4);
  }

  .pc-wishlist.active i { font-weight: 900; /* fas solid heart when active */ }

  /* ─── Hover overlay — gradient scrim with actions ─── */
  .pc-overlay {
    position: absolute;
    inset: 0;
    z-index: 4;
    background: linear-gradient(
      to top,
      rgba(0,0,0,.72) 0%,
      rgba(0,0,0,.28) 45%,
      transparent 75%
    );
    display: flex;
    align-items: flex-end;
    padding: 10px;
    gap: 7px;
    opacity: 0;
    transition: opacity .28s ease;
    pointer-events: none;
  }

  @media (hover: hover) {
    .product-card:hover .pc-overlay {
      opacity: 1;
      pointer-events: auto;
    }
  }

  @media (hover: none) { .pc-overlay { display: none; } }

  /* Add to cart inside overlay */
  .pc-cta-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 9px 12px;
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,.25);
    background: rgba(255,255,255,.14);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    color: #fff;
    font-size: .76rem;
    font-weight: 700;
    letter-spacing: .04em;
    cursor: pointer;
    white-space: nowrap;
    transition: background .2s ease, border-color .2s ease;
  }

  .pc-cta-btn:not(:disabled):hover {
    background: var(--brand);
    border-color: var(--brand);
  }

  .pc-cta-btn:disabled {
    color: rgba(255,255,255,.45);
    cursor: not-allowed;
    border-color: rgba(255,255,255,.12);
    background: rgba(255,255,255,.07);
  }

  /* Quick-view icon button inside overlay */
  .pc-qv-btn {
    flex-shrink: 0;
    width: 38px;
    height: 38px;
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,.25);
    background: rgba(255,255,255,.14);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    color: #fff;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease;
  }

  .pc-qv-btn:hover {
    background: rgba(255,255,255,.28);
    border-color: rgba(255,255,255,.45);
  }

  /* Quick-view button — always visible on touch/mobile */
  .pc-qv-mobile {
    display: none;
    position: absolute;
    bottom: 8px;
    right: 8px;
    z-index: 5;
    width: 34px;
    height: 34px;
    border-radius: 8px;
    border: none;
    background: rgba(0,0,0,.52);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    color: #fff;
    font-size: 13px;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background .18s;
  }
  .pc-qv-mobile:active { background: rgba(0,0,0,.75); }

  @media (hover: none) {
    .pc-qv-mobile { display: flex; }
  }

  /* ─── Card body ────────────────────────────────────── */
  .product-card .card-body {
    padding: 12px 13px 14px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  /* Product name */
  .product-name {
    font-size: .84rem;
    font-weight: 600;
    color: var(--text);
    line-height: 1.45;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-decoration: none;
    transition: color .18s;
    min-height: calc(1.45em * 2);
  }

  .product-name:hover { color: var(--brand); }

  /* Price */
  .pc-price-block {
    display: flex;
    align-items: baseline;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 8px;
  }

  .product-price {
    font-size: 1rem;
    font-weight: 800;
    color: var(--brand);
    letter-spacing: -.02em;
    line-height: 1;
  }

  .price-old {
    font-size: .73rem;
    color: var(--text-muted);
    text-decoration: line-through;
    font-weight: 400;
    line-height: 1;
  }

  /* Stars */
  .star-row {
    display: flex;
    align-items: center;
    gap: 2px;
    margin-top: 5px;
    line-height: 1;
  }

  .star-row i { font-size: 10.5px; }
  .rating-count { font-size: 10.5px; color: var(--text-muted); margin-left: 2px; }

  /* Stock status — simple inline indicator */
  .pc-stock {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 10.5px;
    font-weight: 600;
    margin-top: 5px;
    line-height: 1;
  }

  .pc-stock::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
  }

  .pc-stock-in  { color: #10b981; }
  .pc-stock-in::before  { background: #10b981; box-shadow: 0 0 0 2px rgba(16,185,129,.2); }
  .pc-stock-out { color: #ef4444; }
  .pc-stock-out::before { background: #ef4444; box-shadow: 0 0 0 2px rgba(239,68,68,.2); }
  .pc-stock-low { color: #f59e0b; }
  .pc-stock-low::before { background: #f59e0b; box-shadow: 0 0 0 2px rgba(245,158,11,.2); }

  /* Mobile CTA row */
  .pc-cart-row { margin-top: auto; padding-top: 10px; }

  @media (hover: hover) { .pc-cart-row { display: none; } }

  .btn-add-cart {
    width: 100%;
    border-radius: 8px;
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .025em;
    padding: 9px 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: transform .15s ease;
  }

  .btn-add-cart:not(:disabled):active { transform: scale(.97); }
  .btn-add-cart:disabled { cursor: not-allowed; opacity: .55; }

  /* ─── Responsive ───────────────────────────────────── */

  /* Tablet */
  @media (min-width: 576px) and (max-width: 991.98px) {
    .product-name  { font-size: .81rem; }
    .product-price { font-size: .97rem; }
  }

  /* Small mobile (360–575px) */
  @media (max-width: 575.98px) {
    .product-card { border-radius: 10px; }
    .product-card .card-body { padding: 8px 9px 10px; }
    .product-name  { font-size: .77rem; min-height: calc(1.4em * 2); }
    .product-price { font-size: .9rem; }
    .price-old     { font-size: .69rem; }
    .pc-price-block { margin-top: 5px; gap: 4px; }
    .star-row { margin-top: 4px; }
    .star-row i { font-size: 9.5px; }
    .rating-count { font-size: 9.5px; }
    .pc-stock { font-size: 9.5px; margin-top: 4px; }
    .pc-cart-row { padding-top: 7px; }
    .btn-add-cart { padding: 7px 8px; font-size: .72rem; gap: 4px; border-radius: 7px; }
    /* Shrink wishlist button so it doesn't eat card width */
    .pc-wishlist { width: 28px; height: 28px; font-size: 11px; top: 7px; right: 7px; }
    /* Shrink badges */
    .pc-badge-stack { top: 7px; left: 7px; gap: 3px; }
    .pc-label-badge,
    .pc-discount-badge { font-size: 8.5px; padding: 2px 6px; }
    /* Mobile quick-view button */
    .pc-qv-mobile { width: 28px; height: 28px; font-size: 11px; bottom: 7px; right: 7px; }
  }

  /* Very small phones (≤359px) — single column */
  @media (max-width: 359px) {
    .row-cols-2 > .col { flex: 0 0 100%; max-width: 100%; }
    /* Override listing-page 2-col grid too */
    #productsWrap[data-view="grid"] .products-row > .col { flex: 0 0 100%; max-width: 100%; }
    #productsWrap[data-view="compact"] .products-row > .col { flex: 0 0 50%; max-width: 50%; }
    .product-card .card-body { padding: 10px 11px 12px; }
    .product-name  { font-size: .82rem; }
    .product-price { font-size: .95rem; }
  }

  /* ── List-view zones hidden globally (shown only inside #productsWrap[data-view=list]) ── */
  .product-card .lv-info,
  .product-card .lv-actions { display: none !important; }

  /* ── Quick View Modal ── */
  #quickViewModal .modal-content { border-radius: var(--radius-lg) !important; overflow: hidden; }
  #quickViewModal .modal-body { padding: 0; max-height: calc(90vh - 56px); overflow-y: auto; }
  .qv-img-wrap {
    position: relative; background: var(--bg-soft);
    display: flex; align-items: center; justify-content: center;
    height: min(300px, 40vw); overflow: hidden; border-radius: var(--radius) var(--radius) 0 0;
  }
  .qv-img-wrap img { width: 100%; height: 100%; object-fit: contain; transition: transform .4s ease; }
  .qv-img-wrap:hover img { transform: scale(1.06); }
  .qv-thumbs { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 8px; }
  .qv-thumb {
    width: 52px; height: 52px; border-radius: 8px; overflow: hidden;
    border: 2px solid var(--border); cursor: pointer; flex-shrink: 0;
    transition: var(--transition);
  }
  .qv-thumb:hover, .qv-thumb.active { border-color: var(--brand); }
  .qv-thumb img { width: 100%; height: 100%; object-fit: cover; }
  .qv-info { padding: 18px 22px; }
  .qv-name { font-family: 'Outfit', sans-serif; font-size: 1.05rem; font-weight: 700; line-height: 1.3; margin-bottom: 6px; }
  .qv-meta { font-size: 11.5px; color: var(--text-muted); margin-bottom: 8px; }
  .qv-price { font-size: 1.35rem; font-weight: 800; color: var(--brand); }
  .qv-price-old { font-size: .9rem; color: var(--text-muted); text-decoration: line-through; margin-left: 8px; }
  .qv-badge-disc { background: var(--grad-brand); color: #fff; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px; margin-left: 8px; }
  .qv-short-desc { font-size: 13px; color: var(--text-muted); line-height: 1.6; margin: 12px 0; border-top: 1px solid var(--border); padding-top: 12px; }
  .qv-var-label { font-size: 12px; font-weight: 700; margin-bottom: 6px; color: var(--text); }
  .qv-var-btn {
    padding: 5px 14px; border: 1.5px solid var(--border); border-radius: 8px;
    background: var(--bg-soft); font-size: 12.5px; cursor: pointer;
    transition: var(--transition); color: var(--text);
  }
  .qv-var-btn:hover, .qv-var-btn.selected { background: var(--brand); color: #fff; border-color: var(--brand); }
  .qv-actions { display: flex; gap: 8px; margin-top: 16px; align-items: center; }
  .qv-qty { display: flex; align-items: center; border: 1.5px solid var(--border); border-radius: 10px; overflow: hidden; }
  .qv-qty button { width: 34px; height: 40px; border: none; background: var(--bg-soft); font-size: 16px; cursor: pointer; transition: var(--transition); color: var(--text); }
  .qv-qty button:hover { background: var(--brand); color: #fff; }
  .qv-qty input { width: 42px; height: 40px; border: none; border-left: 1.5px solid var(--border); border-right: 1.5px solid var(--border); text-align: center; font-weight: 700; font-size: 14px; background: var(--bg-card); color: var(--text); }
  .qv-stock-badge { font-size: 11.5px; font-weight: 600; padding: 4px 10px; border-radius: 6px; }
  .qv-footer { padding: 14px 28px; border-top: 1px solid var(--border); display: flex; gap: 10px; background: var(--bg-soft); }
  #qvSpinner { display: flex; align-items: center; justify-content: center; min-height: 160px; }
  .qv-footer { padding: 10px 22px; border-top: 1px solid var(--border); display: flex; gap: 10px; background: var(--bg-soft); }

  /* ── Stat Card ── */
  .stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 20px 24px;
    transition: var(--transition);
  }
  .stat-card:hover {
    box-shadow: var(--shadow-lg);
    border-color: rgba(230,57,70,.2);
    transform: translateY(-3px);
  }

  /* ── Category Cards ── */
  .cat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 20px 16px; text-align: center;
    transition: var(--transition); cursor: pointer;
  }
  .cat-card:hover {
    border-color: rgba(230,57,70,.25);
    box-shadow: var(--shadow-lg);
    transform: translateY(-4px);
  }
  .cat-icon {
    width: 56px; height: 56px; border-radius: 50%;
    background: var(--grad-brand);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: #fff; margin: 0 auto 10px;
    transition: var(--transition);
  }
  .cat-card:hover .cat-icon {
    background: var(--grad-accent);
    box-shadow: 0 6px 20px rgba(230,57,70,.3);
  }
  .cat-name { font-weight: 600; font-size: 13px; color: var(--text); font-family: 'Outfit', sans-serif; }
  .cat-count { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

  /* ── Section Headings ── */
  .section-title { font-size: 22px; font-weight: 800; color: var(--text); margin-bottom: 6px; font-family: 'Outfit', sans-serif; }
  .section-sub   { color: var(--text-muted); font-size: 14px; margin-bottom: 24px; }
  .section-heading {
    font-family: 'Outfit', sans-serif;
    font-size: 22px; font-weight: 800; color: var(--text);
    position: relative; display: inline-block; margin-bottom: 8px;
  }
  .section-heading::after {
    content: ''; position: absolute; bottom: -5px; left: 0;
    width: 50px; height: 3px; border-radius: 2px;
    background: var(--grad-brand);
  }

  /* ── Buttons ── */
  .btn-brand {
    background: var(--grad-brand); color: #fff; border: none;
    border-radius: var(--radius); padding: 10px 22px;
    font-weight: 600; cursor: pointer;
    transition: var(--transition);
    box-shadow: 0 4px 14px rgba(230,57,70,.3);
    display: inline-flex; align-items: center; gap: 6px;
    font-family: 'Inter', sans-serif; font-size: 14px;
  }
  .btn-brand:hover {
    background: linear-gradient(135deg, #c1121f, #a00f18);
    box-shadow: 0 6px 24px rgba(230,57,70,.5);
    transform: translateY(-2px); color: #fff;
  }
  .btn-outline-brand {
    background: transparent;
    border: 1.5px solid var(--brand);
    color: var(--brand); border-radius: var(--radius);
    padding: 8px 20px; font-weight: 600; cursor: pointer;
    transition: var(--transition);
    display: inline-flex; align-items: center; gap: 6px;
    font-family: 'Inter', sans-serif; font-size: 14px;
  }
  .btn-outline-brand:hover {
    background: var(--brand); color: #fff;
    box-shadow: 0 4px 14px rgba(230,57,70,.3);
    border-color: var(--brand);
  }
  .btn-accent {
    background: linear-gradient(135deg, #f4a261, #e76f51);
    color: #fff; border: none; border-radius: var(--radius);
    padding: 10px 22px; font-weight: 700; cursor: pointer;
    font-family: 'Inter', sans-serif; font-size: 14px;
    box-shadow: 0 4px 14px rgba(244,162,97,.35);
    transition: var(--transition);
    display: inline-flex; align-items: center; gap: 6px;
  }
  .btn-accent:hover {
    box-shadow: 0 6px 24px rgba(244,162,97,.55);
    transform: translateY(-2px); color: #fff;
  }
  .btn-outline-light {
    background: transparent;
    border: 1.5px solid rgba(255,255,255,.65);
    color: #fff; border-radius: var(--radius);
    padding: 10px 22px; font-weight: 600; cursor: pointer;
    font-family: 'Inter', sans-serif; font-size: 14px;
    transition: var(--transition);
    display: inline-flex; align-items: center; gap: 6px;
  }
  .btn-outline-light:hover {
    background: rgba(255,255,255,.18);
    border-color: #fff; color: #fff;
  }
  .btn-lg { padding: 12px 28px; font-size: 15px; }
  .btn-sm { padding: 6px 14px; font-size: 13px; }

  /* ── Dark Mode Toggle ── */
  .dark-toggle { position: relative; width: 40px; height: 22px; }
  .dark-toggle input { opacity: 0; width: 0; height: 0; }
  .dark-slider {
    position: absolute; inset: 0;
    background: linear-gradient(135deg,#e2e8f0,#cbd5e1);
    border-radius: 22px; cursor: pointer; transition: .35s;
    display: flex; align-items: center; padding: 0 3px;
  }
  .dark-slider::after {
    content: '☀️'; font-size: 11px;
    width: 17px; height: 17px;
    background: #fff; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    transition: .35s; transform: translateX(0);
    box-shadow: 0 1px 4px rgba(0,0,0,.2);
  }
  input:checked + .dark-slider { background: linear-gradient(135deg, var(--navy), #1a2f47); }
  input:checked + .dark-slider::after { content: '🌙'; transform: translateX(18px); }

  /* ── Language Dropdown ── */
  .lang-dropdown .dropdown-toggle { color: var(--text); font-size: 13px; font-weight: 500; }
  .lang-dropdown .dropdown-menu { min-width: 130px; }

  /* ── Floating Action Stack ── */
  #fabStack {
    position: fixed; bottom: 28px; right: 22px; z-index: 990;
    display: flex; flex-direction: column; align-items: center; gap: 10px;
  }
  .fab-btn {
    width: 46px; height: 46px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; cursor: pointer; border: 1px solid var(--border);
    background: var(--bg-card);
    backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 4px 18px rgba(0,0,0,.12), 0 1px 4px rgba(0,0,0,.06);
    color: var(--text); text-decoration: none; position: relative;
    transition: transform .22s cubic-bezier(.34,1.5,.64,1), box-shadow .2s, opacity .3s, background .18s;
    opacity: 0; transform: translateX(30px);
  }
  .fab-btn.fab-in { opacity: 1; transform: translateX(0); }
  #fabStack .fab-btn:nth-child(1) { transition-delay: .05s; }
  #fabStack .fab-btn:nth-child(2) { transition-delay: .13s; }
  #fabStack .fab-btn:nth-child(3) { transition-delay: .21s; }
  .fab-btn:hover { transform: scale(1.08); box-shadow: 0 8px 28px rgba(0,0,0,.18); }
  /* Scroll-to-top: invisible until scrolled (visibility keeps layout space) */
  .fab-top { visibility: hidden; pointer-events: none; }
  .fab-top.fab-show { visibility: visible; pointer-events: auto; }
  /* WhatsApp */
  .fab-wa { background: #25d366 !important; border-color: #25d366; color: #fff !important; }
  .fab-wa:hover { background: #1db954 !important; box-shadow: 0 8px 28px rgba(37,211,102,.4); }
  /* Chat */
  .fab-chat { background: var(--grad-brand) !important; border-color: transparent; color: #fff !important; box-shadow: 0 4px 18px rgba(230,57,70,.35); }
  .fab-chat:hover { box-shadow: 0 8px 28px rgba(230,57,70,.5); }
  /* Mobile — above bottom nav */
  @media (max-width: 767px) {
    #fabStack { bottom: 90px; right: 14px; gap: 8px; }
    .fab-btn  { width: 42px; height: 42px; font-size: 16px; }
  }
  @media (max-width: 480px) {
    #fabStack { bottom: 84px; right: 12px; }
  }

  /* ── Swiper / Product Carousel Buttons ── */
  .product-carousel-wrap { position: relative; padding: 0 20px; }
  .swiper-btn {
    position: absolute; top: 50%; transform: translateY(-50%); z-index: 5;
    width: 38px; height: 38px; border-radius: 50%;
    border: 2px solid var(--border); background: #fff; color: var(--text);
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    box-shadow: var(--shadow); transition: var(--transition);
  }
  .swiper-btn:hover { background: var(--brand); color: #fff; border-color: var(--brand); }
  .swiper-btn.prev { left: 0; }
  .swiper-btn.next { right: 0; }
  .product-carousel::-webkit-scrollbar { display: none; }
  @media (max-width: 768px) {
    .swiper-btn.prev { left: 0; }
    .swiper-btn.next { right: 0; }
  }

  /* ── Toast Notifications ── */
  #toastContainer {
    position: fixed; top: 80px; right: 16px; z-index: 9999;
    display: flex; flex-direction: column; gap: 8px;
    max-width: 320px;
  }
  .toast-msg {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px 16px; border-radius: var(--radius);
    background: var(--bg-card);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-lg);
    animation: toastIn .3s cubic-bezier(.4,0,.2,1);
    cursor: pointer; position: relative; overflow: hidden;
  }
  .toast-msg.removing { animation: toastOut .3s ease forwards; }
  @keyframes toastIn  { from { opacity:0; transform:translateX(110%); } to { opacity:1; transform:translateX(0); } }
  @keyframes toastOut { from { opacity:1; transform:translateX(0); } to { opacity:0; transform:translateX(110%); } }
  .toast-icon { font-size: 20px; flex-shrink: 0; margin-top: 1px; }
  .toast-body .toast-title { font-weight: 700; font-size: 13px; }
  .toast-body .toast-text  { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
  .toast-progress {
    height: 3px; background: var(--grad-brand);
    border-radius: 0 0 4px 4px;
    animation: progress 3s linear forwards;
    position: absolute; bottom: 0; left: 0; right: 0;
  }
  @keyframes progress { from { width: 100%; } to { width: 0%; } }
  .toast-msg.success .toast-icon { color: var(--teal); }
  .toast-msg.error   .toast-icon { color: var(--brand); }
  .toast-msg.info    .toast-icon { color: var(--brand); }
  .toast-msg.warning .toast-icon { color: var(--gold); }

  /* ── Footer ── */
  .site-footer {
    background: var(--navy);
    border-top: 3px solid var(--brand);
    margin-top: 60px;
    color: #94a3b8;
  }
  .footer-brand {
    font-family: 'Outfit', sans-serif;
    font-size: 24px; font-weight: 800;
    background: var(--grad-logo);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  .footer-brand span {
    background: linear-gradient(90deg, #f4a261, #E63946);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  /* Footer grid */
  .ft-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr 1fr 1.5fr;
    gap: 2rem 2.5rem;
    padding: 3rem 0 2rem;
  }
  @media(max-width:1199px) { .ft-grid { grid-template-columns: 1.2fr 1fr 1fr 1fr; gap: 1.5rem 2rem; } .ft-newsletter-col { grid-column: span 4; } }
  @media(max-width:767px)  { .ft-grid { grid-template-columns: 1fr 1fr; gap: 0; padding-top: 1.5rem; } .ft-brand-col { grid-column: span 2; border-bottom: 1px solid rgba(255,255,255,.07); padding-bottom: 1.25rem; margin-bottom: .5rem; } .ft-newsletter-col { grid-column: span 2; } }
  @media(max-width:479px)  { .ft-grid { grid-template-columns: 1fr; } .ft-brand-col,.ft-newsletter-col { grid-column: span 1; } }

  /* Accordion groups (mobile collapsible) */
  .ft-acc { border: none; }
  .ft-acc summary {
    font-size: 11px; font-weight: 800; text-transform: uppercase;
    letter-spacing: .1em; color: rgba(255,255,255,.45);
    padding: 13px 0; cursor: pointer; display: flex;
    justify-content: space-between; align-items: center;
    list-style: none; user-select: none;
    border-bottom: 1px solid rgba(255,255,255,.06);
  }
  .ft-acc summary::-webkit-details-marker { display: none; }
  .ft-acc summary::after { content: '+'; font-size: 17px; font-weight: 300; color: rgba(255,255,255,.3); transition: transform .2s; }
  .ft-acc[open] summary::after { content: '−'; }
  .ft-acc-body { padding: 8px 0 12px; }
  @media(min-width:768px) {
    .ft-acc summary { pointer-events: none; padding-bottom: 10px; border-bottom: none; }
    .ft-acc summary::after { display: none; }
    .ft-acc { open: true; }
    .ft-acc-body { display: block !important; padding: 0; }
  }

  /* Links */
  .ft-link {
    display: flex; align-items: center; gap: 7px;
    color: rgba(255,255,255,.5); font-size: 13px;
    padding: 4px 0; text-decoration: none; transition: var(--transition);
  }
  .ft-link:hover { color: #fff; padding-left: 4px; }
  .ft-link i { font-size: 10px; color: var(--brand); opacity: .7; flex-shrink: 0; }

  /* Contact items */
  .ft-contact-item {
    display: flex; align-items: center; gap: 9px;
    padding: 5px 0; font-size: 13px; color: rgba(255,255,255,.55);
    text-decoration: none;
  }
  .ft-contact-item:hover { color: #fff; }
  .ft-contact-icon {
    width: 28px; height: 28px; border-radius: 8px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 12px;
  }

  /* Social */
  .social-btn {
    width: 34px; height: 34px; border-radius: 50%;
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12);
    display: inline-flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,.5); transition: var(--transition);
    text-decoration: none;
  }
  .social-btn:hover { background: var(--brand); color: #fff; border-color: var(--brand); box-shadow: 0 4px 14px rgba(230,57,70,.35); }

  /* Newsletter */
  .ft-nl-form { display: flex; gap: .5rem; margin-top: .5rem; }
  .ft-nl-input {
    flex: 1; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.12);
    border-radius: 8px; color: #e2e8f0; font-size: 13px; padding: 9px 13px; outline: none;
    transition: var(--transition);
  }
  .ft-nl-input:focus { border-color: var(--brand); background: rgba(255,255,255,.09); }
  .ft-nl-input::placeholder { color: rgba(255,255,255,.3); }
  .ft-nl-btn {
    background: var(--grad-brand); color: #fff; border: none; border-radius: 8px;
    padding: 9px 16px; font-size: 13px; font-weight: 700; cursor: pointer; white-space: nowrap;
    transition: var(--transition);
  }
  .ft-nl-btn:hover { box-shadow: 0 4px 14px rgba(230,57,70,.4); transform: translateY(-1px); }

  /* Payment chips */
  .ft-pm-grid { display: flex; flex-wrap: wrap; gap: 6px; margin-top: .75rem; }
  .ft-pm-chip {
    display: flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
    border-radius: 8px; padding: 5px 10px; font-size: 11px;
    color: rgba(255,255,255,.7); transition: var(--transition);
  }
  .ft-pm-chip:hover { background: rgba(255,255,255,.1); border-color: rgba(255,255,255,.2); }
  .ft-pm-chip i { font-size: 13px; }

  /* Bottom bar */
  .ft-bottom {
    border-top: 1px solid rgba(255,255,255,.07);
    padding: 15px 0; font-size: 12px; color: rgba(255,255,255,.3);
  }
  .ft-divider { border: none; border-top: 1px solid rgba(255,255,255,.07); margin: 0; }
  .ft-bottom-inner { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: .5rem; }
  .ft-made { font-size: 11px; }
  .ft-logo img { max-height: 42px; max-width: 150px; object-fit: contain; filter: brightness(0) invert(1); opacity: .88; }
  .ft-brand-name { font-size: 1.35rem; font-weight: 800; letter-spacing: -.02em; color: #fff; margin-bottom: .5rem; }
  .ft-brand-name span { background: linear-gradient(90deg,#f4a261,var(--brand,#E63946)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
  .ft-tagline { font-size: 13px; color: rgba(255,255,255,.38); line-height: 1.6; margin: .5rem 0 0; }
  .ft-contact-list { margin-top: 1rem; display: flex; flex-direction: column; gap: 2px; }
  .ft-contact-item a { color: rgba(255,255,255,.55); text-decoration: none; font-size: 13px; transition: color .18s; }
  .ft-contact-item a:hover { color: #fff; }
  .ft-contact-icon { background: rgba(255,255,255,.07); color: rgba(255,255,255,.45); }
  .ft-social { display: flex; gap: .5rem; margin-top: 1rem; flex-wrap: wrap; }
  .ft-soc-btn { width: 34px; height: 34px; border-radius: 50%; background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); display: inline-flex; align-items: center; justify-content: center; color: rgba(255,255,255,.5); transition: var(--transition,all .2s); text-decoration: none; font-size: 13px; }
  .ft-soc-btn:hover { background: var(--sc,var(--brand,#E63946)); color: #fff; border-color: transparent; box-shadow: 0 4px 14px rgba(0,0,0,.3); }
  .ft-acc-head { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.45); padding: 13px 0 10px; display: block; }
  .ft-link-list { display: flex; flex-direction: column; padding-bottom: 10px; }
  .ft-pm-wrap { margin-top: 1.25rem; }
  .ft-pm-label { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.3); margin-bottom: .5rem; }
  .ft-pm-chips { display: flex; flex-wrap: wrap; gap: 6px; }

  /* ── Hero Section ── */
  .hero-section {
    background: linear-gradient(135deg, var(--navy) 0%, #1a2f47 45%, var(--brand-dark) 100%);
    position: relative; overflow: hidden;
  }
  .hero-section::before {
    content: '';
    position: absolute; inset: 0;
    background:
    radial-gradient(ellipse 60% 80% at 20% 50%, rgba(244,162,97,.12) 0%, transparent 70%),
    radial-gradient(ellipse 40% 60% at 80% 20%, rgba(230,57,70,.2) 0%, transparent 60%),
    radial-gradient(ellipse 50% 70% at 60% 80%, rgba(46,196,182,.08) 0%, transparent 60%);
    pointer-events: none;
  }

  /* ── Features Strip (navy background inline styles) ── */
  /* Ensure text/icons inside navy bg look correct */
  [style*="background:var(--navy)"] .text-muted,
  [style*="background:#0d1b2a"] .text-muted { color: rgba(255,255,255,.5) !important; }
  [style*="background:var(--navy)"] h2,
  [style*="background:var(--navy)"] h3,
  [style*="background:#0d1b2a"] h2,
  [style*="background:#0d1b2a"] h3 { color: #fff !important; }

  /* ── Breadcrumb ── */
  .page-breadcrumb {
    background: var(--bg-soft); padding: 10px 0;
    border-bottom: 1px solid var(--border); font-size: 13px;
  }
  .breadcrumb-item a { color: var(--brand); }
  .breadcrumb-item+.breadcrumb-item::before { color: var(--text-muted); }

  /* ── Card base alias ── */
  .card-base {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
  }

  /* ── Shadow helpers ── */
  .shadow-sm { box-shadow: var(--shadow-sm) !important; }
  .shadow    { box-shadow: var(--shadow) !important; }
  .shadow-lg { box-shadow: var(--shadow-lg) !important; }

  /* ── Utility extras ── */
  .text-brand  { color: var(--brand) !important; }
  .text-gold   { color: var(--gold) !important; }
  .text-teal   { color: var(--teal) !important; }
  .text-navy   { color: var(--navy) !important; }
  .bg-navy     { background: var(--navy) !important; }
  .bg-brand    { background: var(--brand) !important; }
  .bg-soft     { background: var(--bg-soft) !important; }
  .rounded-pill { border-radius: 9999px !important; }
  .object-fit-cover { object-fit: cover; }
  .img-fluid { max-width: 100%; height: auto; }

  /* ── Desktop body padding for catNavBar + topbar ── */
  @media (min-width: 768px) {
    body { padding-top: calc(var(--topbar-h) + var(--header-h) + 41px); }
    #siteHeader { top: var(--topbar-h); border-bottom: none; }
    #catNavBar  { top: calc(var(--topbar-h) + var(--header-h)); }
    #toastContainer { top: calc(var(--topbar-h) + var(--header-h) + 10px); }
  }

  /* ── Mobile ── */
  @media (max-width: 767px) {
    body { padding-top: calc(var(--header-h) + 44px); }
    .header-search { display: none; }
    .site-logo { font-size: 20px; }
    .fab-btn { width: 42px; height: 42px; font-size: 16px; }
    .slide-panel { width: 100%; max-width: 100vw; }
    .h-icon-btn { width: 32px; height: 32px; font-size: 15px; }
  }
</style>

<?= $extraHead ?? '' ?>
</head>
<body>

<!-- ═══ TOAST CONTAINER ═══ -->
<div id="toastContainer"></div>

<!-- ═══ SLIDE PANEL OVERLAYS ═══ -->
<div class="slide-panel-overlay" id="panelOverlay"></div>

<!-- ═══ CART PANEL ═══ -->
<div class="slide-panel" id="cartPanel">
  <div class="slide-panel-header">
    <div><i class="fas fa-shopping-cart me-2"></i>Shopping Cart <span id="cartPanelCount" class="badge rounded-pill ms-2" style="background:rgba(255,255,255,.2);font-size:11px;"></span></div>
    <button class="slide-panel-close" onclick="closePanel('cartPanel')"><i class="fas fa-times"></i></button>
  </div>
  <div class="slide-panel-body" id="cartPanelBody">
    <div class="text-center py-5 text-muted" id="cartPanelEmpty" style="display:none;">
      <i class="fas fa-shopping-cart fa-3x mb-3 d-block opacity-25"></i>
      <p>Your cart is empty</p>
      <a href="<?= url('products') ?>" class="btn-brand d-inline-block mt-2 text-white" onclick="closePanel('cartPanel')">Browse Products</a>
    </div>
    <div id="cartPanelItems"></div>
  </div>
  <div class="slide-panel-footer">
    <div class="d-flex justify-content-between fw-bold mb-3" style="font-size:16px;">
      <span>Subtotal</span>
      <span id="cartPanelTotal" style="background:var(--grad-logo);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">LKR 0.00</span>
    </div>
    <a href="<?= url('cart') ?>" class="btn-brand w-100 d-block text-center text-white mb-2 py-2 rounded-3">
      <i class="fas fa-shopping-bag me-2"></i>View Full Cart
    </a>
    <a href="<?= url('checkout') ?>" class="btn-outline-brand w-100 d-block text-center py-2 rounded-3">
      <i class="fas fa-lock me-2"></i>Checkout
    </a>
  </div>
</div>

<!-- ═══ WISHLIST PANEL ═══ -->
<div class="slide-panel" id="wishlistPanel">
  <div class="slide-panel-header">
    <div><i class="fas fa-heart me-2" style="color:rgba(255,255,255,.8);"></i>My Wishlist <span id="wishPanelCount" class="badge rounded-pill ms-2" style="background:rgba(255,255,255,.2);font-size:11px;"></span></div>
    <button class="slide-panel-close" onclick="closePanel('wishlistPanel')"><i class="fas fa-times"></i></button>
  </div>
  <div class="slide-panel-body" id="wishPanelBody">
    <div class="text-center py-5 text-muted" id="wishPanelEmpty" style="display:none;">
      <i class="fas fa-heart fa-3x mb-3 d-block opacity-25"></i>
      <p>Your wishlist is empty</p>
    </div>
    <div id="wishPanelItems"></div>
  </div>
  <div class="slide-panel-footer">
    <a href="<?= url('account/wishlist') ?>" class="btn-brand w-100 d-block text-center text-white py-2 rounded-3">
      <i class="fas fa-heart me-2"></i>View Full Wishlist
    </a>
  </div>
</div>

<!-- ═══ TOP BAR ═══ -->
<?php
$sPhone = setting('support_phone', '+94766961154');
$sEmail = setting('support_email', '');
$waNum  = preg_replace('/\D/', '', setting('whatsapp_number', '94770000000'));
?>
<div id="topBar" class="d-none d-md-flex align-items-center">
  <div class="container d-flex align-items-center justify-content-between">
    <div class="topbar-contact">
      <?php if ($sPhone): ?>
        <a href="tel:<?= e($sPhone) ?>"><i class="fas fa-phone-alt" style="font-size:10px;"></i><?= e($sPhone) ?></a>
      <?php endif; ?>
      <a href="https://wa.me/<?= e($waNum) ?>?text=<?= urlencode('Hi! I need help with Anything.lk') ?>"
       target="_blank"
       rel="noopener">
       <i class="fab fa-whatsapp" style="color:#25d366;font-size:12px;"></i>
       WhatsApp
     </a>
     <?php if ($sEmail): ?>
      <a href="mailto:<?= e($sEmail) ?>"><i class="fas fa-envelope" style="font-size:10px;"></i><?= e($sEmail) ?></a>
    <?php endif; ?>
  </div>
  <div class="topbar-social">
    <span class="topbar-social-label">Follow:</span>
    <?php
    $socialLinks = [
      'social_facebook'  => ['fab fa-facebook-f', 'Facebook'],
      'social_instagram' => ['fab fa-instagram',  'Instagram'],
      'social_tiktok'    => ['fab fa-tiktok',     'TikTok'],
      'social_youtube'   => ['fab fa-youtube',    'YouTube'],
      'social_twitter'   => ['fab fa-twitter',    'Twitter'],
      'social_skype'     => ['fab fa-skype',      'Skype'],
    ];
    foreach ($socialLinks as $key => [$icon, $label]):
      $href = setting($key, '');
      if (!$href) continue;
      ?>
      <a href="<?= e($href) ?>" target="_blank" rel="noopener" title="<?= $label ?>" aria-label="<?= $label ?>">
        <i class="<?= $icon ?>"></i>
      </a>
    <?php endforeach; ?>
  </div>
</div>
</div>

<!-- ═══ HEADER ═══ -->
<header id="siteHeader">
  <div class="container">
    <div class="header-inner">
      <!-- Logo -->
      <?php $_siteLogo = setting('site_logo'); $_siteName = setting('site_name','Anything.lk'); ?>
      <a href="<?= url('') ?>" class="site-logo">
        <?php if ($_siteLogo): ?>
          <img src="<?= url('uploads/logo/'.e($_siteLogo)) ?>" alt="<?= e($_siteName) ?>" style="max-height:40px;max-width:160px;object-fit:contain;">
        <?php else: ?>
          <?php $parts = explode('.', $_siteName, 2); echo e($parts[0]); if (isset($parts[1])): ?><span>.<?= e($parts[1]) ?></span><?php endif; ?>
        <?php endif; ?>
      </a>

      <!-- Search (desktop) -->
      <div class="header-search d-none d-md-block ms-3">
        <form action="<?= url('search') ?>" method="GET">
          <input type="text" name="q" id="mainSearch" placeholder="Search products, brands, categories..."
          value="<?= e($_GET['q'] ?? '') ?>" autocomplete="off">
          <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
        </form>
        <div class="search-dropdown" id="searchDropdown"></div>
      </div>

      <!-- Actions -->
      <div class="header-actions d-flex align-items-center gap-2">

        <!-- Dark mode -->
        <div class="d-none d-md-flex align-items-center">
          <label class="dark-toggle" title="Toggle dark mode">
            <input type="checkbox" id="darkToggle">
            <span class="dark-slider"></span>
          </label>
        </div>

        <!-- Wishlist -->
        <button class="btn btn-light btn-sm position-relative"
        onclick="openPanel('wishlistPanel')"
        title="Wishlist">
        <i class="fa-solid fa-heart"></i>
        <span class="d-none d-lg-inline ms-1">Wishlist</span>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
        id="wishCount" style="display:none;">0</span>
      </button>

      <!-- Cart -->
      <button class="btn btn-light btn-sm position-relative"
      onclick="openPanel('cartPanel')"
      title="Cart">
      <i class="fa-solid fa-cart-arrow-down"></i>
      <span class="d-none d-lg-inline ms-1">Cart</span>
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
      id="cartCount"><?= $cartCount > 0 ? $cartCount : '' ?></span>
    </button>

    <!-- Account -->
    <?php if (Auth::check()): ?>
      <?php $u = Auth::user(); $firstName = explode(' ', trim($u['name']))[0]; ?>

      <div class="dropdown">
        <button class="btn btn-light btn-sm dropdown-toggle"
        data-bs-toggle="dropdown">
        <?php if (!empty($u['avatar'])): ?>
          <img src="<?= url('uploads/avatars/'.e($u['avatar'])) ?>"
          style="width:20px;height:20px;border-radius:50%;object-fit:cover;margin-right:4px;" alt="">
        <?php else: ?>
          <i class="fa-solid fa-circle-user"></i>
        <?php endif; ?>
        <span class="d-none d-lg-inline ms-1"><?= e($firstName) ?></span>
      </button>

      <ul class="dropdown-menu dropdown-menu-end shadow-sm">
        <li class="px-3 py-2 border-bottom">
          <div style="font-weight:700;font-size:13px;"><?= e($u['name']) ?></div>
          <div style="font-size:11px;color:var(--text-muted);"><?= e($u['email'] ?? '') ?></div>
        </li>

        <?php if (Auth::isSupervisor()): ?>
          <li>
            <a class="dropdown-item" href="<?= url('admin/dashboard') ?>">
              <i class="fas fa-tachometer-alt me-2"></i>Admin Panel
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
        <?php endif; ?>

        <li>
          <a class="dropdown-item" href="<?= url('account') ?>">
            <i class="fas fa-user me-2"></i>My Account
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="<?= url('account/orders') ?>">
            <i class="fas fa-box me-2"></i>My Orders
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="<?= url('account/wishlist') ?>">
            <i class="fas fa-heart me-2"></i>Wishlist
          </a>
        </li>

        <li><hr class="dropdown-divider"></li>

        <li>
          <a class="dropdown-item text-danger" href="<?= url('logout') ?>">
            <i class="fas fa-sign-out-alt me-2"></i>Sign Out
          </a>
        </li>
      </ul>
    </div>

  <?php else: ?>
    <a href="<?= url('login') ?>" class="btn btn-primary btn-sm">
      <i class="fa-solid fa-circle-user me-1"></i>
      <span class="d-none d-lg-inline">Sign In</span>
    </a>
  <?php endif; ?>

  <!-- Mobile -->
  <button class="btn btn-light btn-sm d-md-none"
  data-bs-toggle="offcanvas"
  data-bs-target="#mobileMenu">
  <i class="fas fa-bars"></i>
</button>

</div>
</div>
</div>
</header>

<!-- Mobile search -->
<div class="d-md-none" style="position:fixed;top:var(--header-h);left:0;right:0;z-index:999;background:var(--bg);border-bottom:1px solid var(--border);padding:8px 12px;">
  <div style="position:relative;">
    <form action="<?= url('search') ?>" method="GET" class="d-flex gap-2">
      <input type="text" name="q" id="mobileSearch" autocomplete="off"
             class="form-control form-control-sm" placeholder="Search products, brands, categories...">
      <button class="btn btn-sm btn-brand" type="submit"><i class="fas fa-search"></i></button>
    </form>
    <div id="mobileSearchDropdown" class="search-dropdown" style="top:calc(100% + 4px);max-height:320px;"></div>
  </div>
</div>

<!-- Category nav -->
<?php $allNavCats = $navCategories ?? []; $rootCats = $navRootCats ?? []; ?>
<nav class="d-none d-md-block" id="catNavBar">
  <div class="container">
    <div class="cat-nav-inner">
      <!-- All Categories mega menu trigger -->
      <div class="cat-mega-btn">
        <a href="#" class="cat-link" id="catMegaToggle" style="gap:8px;" aria-expanded="false" aria-controls="catMegaMenu">
          <i class="fas fa-th-large"></i> All Categories <i class="fas fa-chevron-down ms-1" style="font-size:10px;"></i>
        </a>
      </div>
      <!-- Top category links -->
      <a href="<?= url('products') ?>" class="cat-link"><i class="fa-brands fa-shopify"></i> Products</a>
      <a href="<?= url('products?sort=newest') ?>" class="cat-link"><i class="fa-solid fa-meteor"></i> New Arrivals</a>
      <a href="<?= url('products?sort=popular') ?>" class="cat-link"><i class="fa-solid fa-ranking-star"></i> Best Sellers</a>
      <a href="<?= url('products?featured=1') ?>" class="cat-link"><i class="fa-solid fa-layer-group"></i> Featured</a>
      <?php foreach (array_slice($rootCats, 0, 4) as $rc): ?>
        <a href="<?= url('category/'.e($rc['slug'])) ?>" class="cat-link">
          <i class="fa <?= e($rc['icon'] ?: 'fa-tag') ?>"></i><?= e($rc['name']) ?>
        </a>
      <?php endforeach; ?>
      <?php if (count($rootCats) > 4): ?>
        <div class="dropdown cat-more-wrap" style="position:static;">
          <a href="#" class="cat-link" data-bs-toggle="dropdown" id="catMoreToggle" aria-expanded="false">
            <i class="fas fa-ellipsis-h"></i> More <i class="fas fa-chevron-down ms-1" style="font-size:10px;"></i>
          </a>
          <ul class="dropdown-menu cat-more-menu" style="background:var(--bg-card);margin-top:0;">
            <?php foreach (array_slice($rootCats, 4) as $rc): ?>
              <li>
                <a class="dropdown-item" href="<?= url('category/'.e($rc['slug'])) ?>">
                  <i class="fa <?= e($rc['icon'] ?: 'fa-tag') ?> me-2"></i><?= e($rc['name']) ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
    </div>

    <!-- Mega menu — inside container, positioned absolute to container width -->
    <div class="cat-mega-menu" id="catMegaMenu" role="region" aria-label="All Categories">
      <div class="mega-grid">
        <?php
        // Build children lookup in a single O(n) pass — no N+1, no per-root array_filter loop.
        $_megaKids = [];  // parentId → [child, ...]
        foreach ($allNavCats as $_mc) {
            $pid = (int)$_mc['parent_id'];
            if ($pid > 0) $_megaKids[$pid][] = $_mc;
        }

        foreach ($rootCats as $rc):
            $rcId     = (int)$rc['id'];
            $rcIcon   = e($rc['icon'] ?: 'fa-tag');
            $level1   = $_megaKids[$rcId] ?? [];
            if (empty($level1)) continue;   // skip root cats with no children
        ?>
          <div class="mega-col">
            <a href="<?= url('category/'.e($rc['slug'])) ?>" class="mega-col-title">
              <i class="fa <?= $rcIcon ?>"></i><?= e($rc['name']) ?>
            </a>
            <?php foreach (array_slice($level1, 0, 7) as $ch):
                $chId   = (int)$ch['id'];
                $level2 = $_megaKids[$chId] ?? [];
            ?>
              <a href="<?= url('category/'.e($ch['slug'])) ?>" class="mega-sub-link"><?= e($ch['name']) ?></a>
              <?php foreach (array_slice($level2, 0, 4) as $gch): ?>
                <a href="<?= url('category/'.e($gch['slug'])) ?>" class="mega-sub-link-child"><?= e($gch['name']) ?></a>
              <?php endforeach; ?>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</nav>


<!-- ═══ MOBILE DRAWER ═══ -->
<?php
$_mnUser   = Auth::check() ? Auth::user() : null;
$_mnWaNum  = preg_replace('/\D/', '', setting('whatsapp_number','94770000000'));
$_mnWaMsg  = urlencode('Hi! I need help with ' . setting('site_name','Anything.lk'));
?>
<div class="offcanvas offcanvas-start" id="mobileMenu" tabindex="-1" aria-label="Navigation">

  <!-- Header -->
  <div class="mnav-header">
    <?php if ($_siteLogo): ?>
      <img src="<?= url('uploads/logo/'.e($_siteLogo)) ?>" alt="<?= e($_siteName) ?>" class="site-logo" style="max-height:34px;max-width:130px;object-fit:contain;">
    <?php else: ?>
      <span class="site-logo" style="font-size:17px;"><?php $p2=explode('.',$_siteName,2);echo e($p2[0]);if(isset($p2[1])):?><span>.<?= e($p2[1]) ?></span><?php endif;?></span>
    <?php endif; ?>
    <button class="mnav-close" data-bs-dismiss="offcanvas" aria-label="Close menu">
      <i class="fa-solid fa-xmark"></i>
    </button>
  </div>

  <!-- Search -->
  <div class="mnav-search">
    <input type="text" class="mnav-search-input" id="mnavSearchInput"
           placeholder="Search categories &amp; pages…" autocomplete="off">
    <i class="fa-solid fa-magnifying-glass mnav-search-icon"></i>
  </div>

  <!-- Tabs -->
  <div class="mnav-tabs" role="tablist">
    <button class="mnav-tab active" data-tab="cats" role="tab" aria-selected="true">
      <i class="fa-solid fa-grid-2"></i> Categories
    </button>
    <button class="mnav-tab" data-tab="menu" role="tab" aria-selected="false">
      <i class="fa-solid fa-bars"></i> Menu
    </button>
  </div>

  <!-- Body -->
  <div class="mnav-body">

    <!-- ── Panel: Categories ── -->
    <div class="mnav-panel active" id="mnavPanel-cats" role="tabpanel">
      <div class="mcat-viewport" id="mcatViewport">

        <!-- Level 0: Root categories -->
        <div class="mcat-level is-active" id="mcat-root" data-level="0">
          <?php foreach ($rootCats as $rc):
            $rcIcon     = e($rc['icon'] ?? 'fa-tag');
            $rcChildren = array_filter($allNavCats, fn($c) => (int)$c['parent_id'] === (int)$rc['id']);
          ?>
          <div class="mcat-item" data-search-text="<?= strtolower(e($rc['name'])) ?>">
            <a class="mcat-link" href="<?= url('category/'.e($rc['slug'])) ?>">
              <span class="mcat-icon"><i class="fa-solid <?= $rcIcon ?>"></i></span>
              <span class="mcat-name"><?= e($rc['name']) ?></span>
            </a>
            <?php if (!empty($rcChildren)): ?>
            <button class="mcat-drill"
                    data-target="mcat-sub-<?= (int)$rc['id'] ?>"
                    data-title="<?= e($rc['name']) ?>"
                    data-href="<?= url('category/'.e($rc['slug'])) ?>"
                    aria-label="Expand <?= e($rc['name']) ?>">
              <i class="fa-solid fa-chevron-right"></i>
            </button>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Level 1: Sub-categories (one panel per root) -->
        <?php foreach ($rootCats as $rc):
          $rcChildren = array_filter($allNavCats, fn($c) => (int)$c['parent_id'] === (int)$rc['id']);
          if (empty($rcChildren)) continue;
        ?>
        <div class="mcat-level" id="mcat-sub-<?= (int)$rc['id'] ?>" data-level="1">

          <div class="mcat-back-bar">
            <button class="mcat-back-btn" data-back="mcat-root" aria-label="Back to categories">
              <i class="fa-solid fa-arrow-left"></i> Back
            </button>
            <span class="mcat-back-title"><?= e($rc['name']) ?></span>
            <a class="mcat-see-all" href="<?= url('category/'.e($rc['slug'])) ?>">See All</a>
          </div>

          <?php foreach ($rcChildren as $ch):
            $chIcon     = e($ch['icon'] ?? 'fa-tag');
            $chChildren = array_filter($allNavCats, fn($c) => (int)$c['parent_id'] === (int)$ch['id']);
          ?>
          <div class="mcat-item" data-search-text="<?= strtolower(e($ch['name'])) ?>">
            <a class="mcat-link" href="<?= url('category/'.e($ch['slug'])) ?>">
              <span class="mcat-icon"><i class="fa-solid <?= $chIcon ?>"></i></span>
              <span class="mcat-name"><?= e($ch['name']) ?></span>
            </a>
            </div>
          <?php endforeach; ?>
        </div>
        <?php endforeach; ?>

      </div><!-- /mcat-viewport -->
    </div><!-- /mnavPanel-cats -->

    <!-- ── Panel: Menu ── -->
    <div class="mnav-panel" id="mnavPanel-menu" role="tabpanel">

      <!-- User chip -->
      <?php if ($_mnUser): ?>
      <div class="mnav-user-chip">
        <?php if (!empty($_mnUser['avatar'])): ?>
          <img src="<?= url('uploads/avatars/'.e($_mnUser['avatar'])) ?>"
               class="mnav-user-avatar" alt="<?= e($_mnUser['name']) ?>">
        <?php else: ?>
          <div class="mnav-user-placeholder"><i class="fa-solid fa-user"></i></div>
        <?php endif; ?>
        <div>
          <div class="mnav-user-name"><?= e($_mnUser['name']) ?></div>
          <div class="mnav-user-email"><?= e($_mnUser['email'] ?? '') ?></div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Main links -->
      <div class="mnav-section-label">Navigate</div>

      <a class="mnav-menu-item" href="<?= url('') ?>">
        <span class="mnav-menu-icon" style="background:rgba(99,102,241,.1);color:#6366f1;">
          <i class="fa-solid fa-house"></i>
        </span>
        Home
      </a>

      <a class="mnav-menu-item" href="<?= url('products') ?>">
        <span class="mnav-menu-icon" style="background:rgba(230,57,70,.1);color:var(--brand);">
          <i class="fa-solid fa-bag-shopping"></i>
        </span>
        All Products
      </a>

      <a class="mnav-menu-item" href="#" onclick="openPanel('cartPanel');document.getElementById('mobileMenu')._offcanvas?.hide();return false;">
        <span class="mnav-menu-icon" style="background:rgba(245,158,11,.1);color:#f59e0b;">
          <i class="fa-solid fa-cart-arrow-down"></i>
        </span>
        Cart
        <?php if (($cartCount ?? 0) > 0): ?>
          <span class="badge rounded-pill ms-auto" style="background:var(--brand);color:#fff;"><?= (int)$cartCount ?></span>
        <?php endif; ?>
      </a>

      <a class="mnav-menu-item" href="#" onclick="openPanel('wishlistPanel');return false;">
        <span class="mnav-menu-icon" style="background:rgba(239,68,68,.1);color:#ef4444;">
          <i class="fa-solid fa-heart"></i>
        </span>
        Wishlist
      </a>

      <!-- Account section -->
      <div class="mnav-section-label">Account</div>

      <?php if ($_mnUser): ?>
        <a class="mnav-menu-item" href="<?= url('account') ?>">
          <span class="mnav-menu-icon" style="background:rgba(16,185,129,.1);color:#10b981;">
            <i class="fa-solid fa-circle-user"></i>
          </span>
          My Account
        </a>
        <a class="mnav-menu-item" href="<?= url('account/orders') ?>">
          <span class="mnav-menu-icon" style="background:rgba(99,102,241,.1);color:#6366f1;">
            <i class="fa-solid fa-box"></i>
          </span>
          My Orders
        </a>
        <a class="mnav-menu-item" href="<?= url('account/wishlist') ?>">
          <span class="mnav-menu-icon" style="background:rgba(239,68,68,.1);color:#ef4444;">
            <i class="fa-solid fa-bookmark"></i>
          </span>
          Wishlist
        </a>
        <?php if (Auth::isSupervisor()): ?>
        <a class="mnav-menu-item primary" href="<?= url('admin/dashboard') ?>">
          <span class="mnav-menu-icon" style="background:rgba(230,57,70,.12);color:var(--brand);">
            <i class="fa-solid fa-gear"></i>
          </span>
          Admin Panel
        </a>
        <?php endif; ?>
        <a class="mnav-menu-item danger" href="<?= url('logout') ?>">
          <span class="mnav-menu-icon" style="background:rgba(239,68,68,.1);color:#ef4444;">
            <i class="fa-solid fa-right-from-bracket"></i>
          </span>
          Logout
        </a>
      <?php else: ?>
        <a class="mnav-menu-item primary" href="<?= url('login') ?>">
          <span class="mnav-menu-icon" style="background:rgba(230,57,70,.12);color:var(--brand);">
            <i class="fa-solid fa-right-to-bracket"></i>
          </span>
          Login
        </a>
        <a class="mnav-menu-item" href="<?= url('register') ?>">
          <span class="mnav-menu-icon" style="background:rgba(99,102,241,.1);color:#6366f1;">
            <i class="fa-solid fa-user-plus"></i>
          </span>
          Register
        </a>
      <?php endif; ?>

      <!-- Info section -->
      <div class="mnav-section-label">Info</div>

      <a class="mnav-menu-item" href="<?= url('about') ?>">
        <span class="mnav-menu-icon" style="background:rgba(20,184,166,.1);color:#14b8a6;">
          <i class="fa-solid fa-circle-info"></i>
        </span>
        About Us
      </a>
      <a class="mnav-menu-item" href="<?= url('contact') ?>">
        <span class="mnav-menu-icon" style="background:rgba(245,158,11,.1);color:#f59e0b;">
          <i class="fa-solid fa-envelope"></i>
        </span>
        Contact
      </a>
      <a class="mnav-menu-item" href="<?= url('faq') ?>">
        <span class="mnav-menu-icon" style="background:rgba(99,102,241,.1);color:#6366f1;">
          <i class="fa-solid fa-circle-question"></i>
        </span>
        FAQ
      </a>
      <a class="mnav-menu-item" href="<?= url('order-tracking') ?>">
        <span class="mnav-menu-icon" style="background:rgba(16,185,129,.1);color:#10b981;">
          <i class="fa-solid fa-location-dot"></i>
        </span>
        Track Order
      </a>

    </div><!-- /mnavPanel-menu -->

  </div><!-- /mnav-body -->

  <!-- Footer: WhatsApp + Dark mode toggle -->
  <div class="mnav-footer">
    <a class="mnav-wa-btn"
       href="https://wa.me/<?= e($_mnWaNum) ?>?text=<?= $_mnWaMsg ?>"
       target="_blank" rel="noopener noreferrer">
      <i class="fa-brands fa-whatsapp fa-lg"></i> WhatsApp Us
    </a>
    <label class="dark-toggle" title="Toggle dark mode">
      <input type="checkbox" id="darkToggleMobile">
      <span class="dark-slider"></span>
    </label>
  </div>

</div><!-- /#mobileMenu -->

<!-- ═══ MAIN CONTENT ═══ -->
<main><?= $content ?></main>

<!-- ═══ MOBILE BOTTOM NAV ═══ -->
<?php
$_uri   = strtok($_SERVER['REQUEST_URI'], '?');
$_base  = rtrim(parse_url(Helper::baseUrl(), PHP_URL_PATH) ?? '', '/');
$_path  = ltrim(str_replace($_base, '', $_uri), '/');
function _bnActive(string $path, string $match): bool {
    if ($match === '') return $path === '';
    return str_starts_with($path, $match);
}
?>
<style>
#mobileBottomNav {
  display: none;
}
@media (max-width: 767.98px) {
  #mobileBottomNav {
    display: flex;
    position: fixed;
    bottom: 0; left: 0; right: 0;
    height: 62px;
    background: var(--bg-card, #fff);
    border-top: 1px solid var(--border, #e5e7eb);
    z-index: 1040;
    box-shadow: 0 -2px 16px rgba(0,0,0,.08);
  }
  #mobileBottomNav .bn-item {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 3px;
    text-decoration: none;
    color: var(--text-muted, #9ca3af);
    font-size: 10px;
    font-weight: 600;
    letter-spacing: .02em;
    border: none;
    background: none;
    cursor: pointer;
    padding: 0;
    transition: color .18s;
    position: relative;
  }
  #mobileBottomNav .bn-item i {
    font-size: 20px;
    line-height: 1;
  }
  #mobileBottomNav .bn-item.active {
    color: var(--brand, #E63946);
  }
  #mobileBottomNav .bn-item.active i {
    transform: translateY(-1px);
  }
  #mobileBottomNav .bn-badge {
    position: absolute;
    top: 4px;
    left: calc(50% + 5px);
    min-width: 16px;
    height: 16px;
    background: var(--brand, #E63946);
    color: #fff;
    font-size: 9px;
    font-weight: 700;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
    line-height: 1;
  }
  /* push page content above the nav */
  main { padding-bottom: 70px; }
  footer.site-footer { padding-bottom: 70px !important; }
}
</style>

<nav id="mobileBottomNav" aria-label="Bottom navigation">

  <?php
  $_bnHome     = _bnActive($_path, '');
  $_bnWishlist = _bnActive($_path, 'account/wishlist');
  $_bnCart     = _bnActive($_path, 'cart');
  // Exclude wishlist sub-path so account doesn't light up while on wishlist
  $_bnAccount  = _bnActive($_path, 'account') && !$_bnWishlist;
  $_bnMenu     = _bnActive($_path, 'products') || _bnActive($_path, 'category');
  ?>

  <!-- Home — fas only (far fa-house not in FA6 free) -->
  <a href="<?= url('') ?>" class="bn-item <?= $_bnHome ? 'active' : '' ?>">
    <i class="fas fa-house"></i>
    <span>Home</span>
  </a>

  <!-- Wishlist — far fa-heart is available in FA6 free -->
  <a href="<?= url(Auth::check() ? 'account/wishlist' : 'login') ?>"
     class="bn-item <?= $_bnWishlist ? 'active' : '' ?>">
    <i class="<?= $_bnWishlist ? 'fas' : 'far' ?> fa-heart"></i>
    <span>Wishlist</span>
  </a>

  <!-- Cart — fa-bag-shopping (FA6 name; fa-shopping-bag was FA5) -->
  <a href="<?= url('cart') ?>" class="bn-item <?= $_bnCart ? 'active' : '' ?>">
    <i class="fas fa-bag-shopping"></i>
    <?php if ($cartCount > 0): ?>
    <span class="bn-badge" id="bnCartBadge"><?= $cartCount ?></span>
    <?php else: ?>
    <span class="bn-badge" id="bnCartBadge" style="display:none;"></span>
    <?php endif; ?>
    <span>Cart</span>
  </a>

  <!-- Account — far fa-user is available in FA6 free -->
  <a href="<?= url(Auth::check() ? 'account' : 'login') ?>"
     class="bn-item <?= $_bnAccount ? 'active' : '' ?>">
    <i class="<?= $_bnAccount ? 'fas' : 'far' ?> fa-user"></i>
    <span><?= Auth::check() ? 'Account' : 'Sign In' ?></span>
  </a>

  <!-- Menu -->
  <button class="bn-item <?= $_bnMenu ? 'active' : '' ?>"
          data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-label="Menu">
    <i class="fas fa-bars"></i>
    <span>Menu</span>
  </button>

</nav>

<!-- ═══ FOOTER ═══ -->
<footer class="site-footer">
  <div class="container">
    <div class="ft-grid">

      <!-- Col 1: Brand + contact + social -->
      <div class="ft-brand-col">
        <?php $_fLogo=setting('site_logo'); $_fName=setting('site_name','Anything.lk'); ?>
        <?php if ($_fLogo): ?>
          <div class="ft-logo"><img src="<?= url('uploads/logo/'.e($_fLogo)) ?>" alt="<?= e($_fName) ?>"></div>
        <?php else: ?>
          <div class="ft-brand-name"><?php $fp=explode('.',$_fName,2);echo e($fp[0]);if(isset($fp[1])):?><span>.<?= e($fp[1]) ?></span><?php endif;?></div>
        <?php endif; ?>

        <?php $_ftPhone=setting('support_phone',''); $_ftWa=setting('whatsapp_number',''); $_ftEmail=setting('support_email',''); ?>
        <?php if ($_ftPhone || $_ftWa || $_ftEmail): ?>
        <div class="ft-contact-list">
          <?php if ($_ftPhone): ?><div class="ft-contact-item"><span class="ft-contact-icon"><i class="fa fa-phone"></i></span><a href="tel:<?= e($_ftPhone) ?>"><?= e($_ftPhone) ?></a></div><?php endif; ?>
          <?php if ($_ftWa): ?><div class="ft-contact-item"><span class="ft-contact-icon" style="background:rgba(37,211,102,.15);color:#25d366;"><i class="fab fa-whatsapp"></i></span><a href="https://wa.me/<?= e(preg_replace('/\D/','',$_ftWa)) ?>" target="_blank" rel="noopener"><?= e($_ftWa) ?></a></div><?php endif; ?>
          <?php if ($_ftEmail): ?><div class="ft-contact-item"><span class="ft-contact-icon"><i class="fa fa-envelope"></i></span><a href="mailto:<?= e($_ftEmail) ?>"><?= e($_ftEmail) ?></a></div><?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="ft-social">
          <?php
          $ftSocials=['social_facebook'=>['fab fa-facebook-f','#1877f2'],'social_instagram'=>['fab fa-instagram','#e1306c'],'social_twitter'=>['fab fa-twitter','#1da1f2'],'social_youtube'=>['fab fa-youtube','#ff0000'],'social_tiktok'=>['fab fa-tiktok','#fff']];
          $ftAnySocial=false;
          foreach($ftSocials as $fsk=>[$fsic,$fscol]):
            $fsh=setting($fsk,''); if(!$fsh) continue; $ftAnySocial=true; ?>
            <a href="<?= e($fsh) ?>" target="_blank" rel="noopener" class="ft-soc-btn" style="--sc:<?= $fscol ?>;"><i class="<?= $fsic ?>"></i></a>
          <?php endforeach;
          if(!$ftAnySocial): ?>
            <a href="#" class="ft-soc-btn" style="--sc:#1877f2;"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="ft-soc-btn" style="--sc:#e1306c;"><i class="fab fa-instagram"></i></a>
            <a href="#" class="ft-soc-btn" style="--sc:#ff0000;"><i class="fab fa-youtube"></i></a>
          <?php endif; ?>
        </div>
      </div>

      <!-- Col 2: Customer Service -->
      <details class="ft-acc" open>
        <summary class="ft-acc-head">Customer Service</summary>
        <nav class="ft-link-list">
          <a href="<?= url('about') ?>" class="ft-link">About Us</a>
          <a href="<?= url('faq') ?>" class="ft-link">FAQ</a>
          <a href="<?= url('shipping-policy') ?>" class="ft-link">Shipping Policy</a>
          <a href="<?= url('return-policy') ?>" class="ft-link">Returns &amp; Refunds</a>
          <a href="<?= url('contact') ?>" class="ft-link">Contact Us</a>
        </nav>
      </details>

      <!-- Col 3: My Account -->
      <details class="ft-acc" open>
        <summary class="ft-acc-head">My Account</summary>
        <nav class="ft-link-list">
          <?php if (Auth::check()): ?>
          <a href="<?= url('account') ?>" class="ft-link">My Account</a>
          <a href="<?= url('account/orders') ?>" class="ft-link">My Orders</a>
          <a href="<?= url('account/wishlist') ?>" class="ft-link">Wishlist</a>
          <?php else: ?>
          <a href="<?= url('login') ?>" class="ft-link">Sign In</a>
          <a href="<?= url('register') ?>" class="ft-link">Register</a>
          <?php endif; ?>
          <a href="<?= url('order-tracking') ?>" class="ft-link">Track Order</a>
          <a href="<?= url('cart') ?>" class="ft-link">Shopping Cart</a>
        </nav>
      </details>

      <!-- Col 4: Categories -->
      <details class="ft-acc" open>
        <summary class="ft-acc-head">Shop by Category</summary>
        <nav class="ft-link-list">
          <?php
          $ftCats = isset($navRootCats) ? array_slice($navRootCats, 0, 7) : array_slice($rootCats ?? [], 0, 7);
          foreach ($ftCats as $ftc): ?>
          <a href="<?= url('category/'.e($ftc['slug'])) ?>" class="ft-link"><?= e($ftc['name']) ?></a>
          <?php endforeach; ?>
          <a href="<?= url('products') ?>" class="ft-link" style="color:var(--brand,#E63946);">View All →</a>
        </nav>
      </details>

      <!-- Col 5: Newsletter + payment chips -->
      <div class="ft-newsletter-col">
        <div class="ft-acc-head" style="cursor:default;">Stay Updated</div>
        <p class="ft-tagline" style="margin-top:.5rem;">Exclusive deals, new arrivals &amp; offers straight to your inbox.</p>
        <form class="ft-nl-form" id="newsletterForm">
          <input type="email" class="ft-nl-input" placeholder="your@email.com" required>
          <button type="submit" class="ft-nl-btn">Subscribe</button>
        </form>

        <?php
        $_ftPm = $footerPaymentMethods ?? [];
        if (!empty($_ftPm)):
          $paymentMethods = $_ftPm;
          $paymentSize    = 'sm';
          $paymentLabel   = true;
        ?>
        <div class="ft-pm-wrap footer-pm-strip">
          <?php include dirname(__DIR__) . '/components/payment_logos.php'; ?>
        </div>
        <?php endif; ?>
      </div>

    </div><!-- /.ft-grid -->
  </div><!-- /.container -->

  <div class="ft-divider"></div>
  <div class="ft-bottom">
    <div class="container ft-bottom-inner">
      <span>© <?= date('Y') ?> <?= e(setting('site_name','Anything.lk')) ?>. All rights reserved.</span>
      <span class="ft-made">Made with <span style="color:var(--brand,#E63946);">♥</span> in Sri Lanka 🇱🇰</span>
    </div>
  </div>
</footer>

<!-- ═══ FLOATING ACTION STACK ═══ -->
<div id="fabStack">
  <button class="fab-btn fab-top" id="backToTop" title="Back to top" aria-label="Scroll to top">
    <i class="fas fa-chevron-up"></i>
  </button>
  <a href="https://wa.me/<?= e($waNum) ?>?text=<?= urlencode('Hi! I need help with Anything.lk') ?>"
     target="_blank" rel="noopener" class="fab-btn fab-wa" title="WhatsApp" aria-label="WhatsApp">
    <i class="fab fa-whatsapp"></i>
  </a>
  <button class="fab-btn fab-chat" id="chatToggle" aria-label="Open chat">
    <span id="chatBadge"></span>
    <i class="fa fa-comment ct-icon ct-open"></i>
    <i class="fa fa-times ct-icon ct-close"></i>
  </button>
</div>

<!-- ═══ QUICK VIEW MODAL ═══ -->
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:min(920px,94vw);width:100%;">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0 px-4 pt-3">
        <span class="fw-bold" style="font-family:'Outfit',sans-serif;font-size:13px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;">Quick View</span>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Spinner -->
        <div id="qvSpinner"><div class="spinner-border" style="width:2.5rem;height:2.5rem;"></div></div>
        <!-- Content (populated by JS) -->
        <div id="qvContent" style="display:none;">
          <div class="row g-0">
            <!-- Left: images -->
            <div class="col-md-5 p-3">
              <div class="qv-img-wrap">
                <img id="qvMainImg" src="" alt="">
              </div>
              <div class="qv-thumbs" id="qvThumbs"></div>
            </div>
            <!-- Right: info -->
            <div class="col-md-7">
              <div class="qv-info">
                <div id="qvBadges" class="d-flex flex-wrap gap-2 mb-2"></div>
                <div class="qv-name" id="qvName"></div>
                <div class="qv-meta" id="qvMeta"></div>
                <!-- Rating -->
                <div id="qvRating" class="d-flex align-items-center gap-2 mb-2"></div>
                <!-- Price -->
                <div class="mb-2">
                  <span class="qv-price" id="qvPrice"></span>
                  <span class="qv-price-old" id="qvPriceOld" style="display:none;"></span>
                  <span class="qv-badge-disc" id="qvDisc" style="display:none;"></span>
                </div>
                <!-- Stock -->
                <div id="qvStockWrap" class="mb-2"></div>
                <!-- Short desc -->
                <div class="qv-short-desc" id="qvDesc" style="display:none;"></div>
                <!-- Variations -->
                <div id="qvVarWrap" style="display:none;" class="mb-3">
                  <div class="qv-var-label">Select Option</div>
                  <div class="d-flex flex-wrap gap-2" id="qvVars"></div>
                </div>
                <!-- Qty + Add to Cart -->
                <div class="qv-actions">
                  <div class="qv-qty">
                    <button id="qvQtyMinus" type="button">−</button>
                    <input type="number" id="qvQtyInput" value="1" min="1" max="99">
                    <button id="qvQtyPlus" type="button">+</button>
                  </div>
                  <button class="btn btn-primary flex-grow-1" id="qvAddCart">
                    <i class="fa fa-shopping-cart me-2"></i>Add to Cart
                  </button>
                  <button class="btn btn-outline-secondary wishlist-btn" id="qvWishBtn" data-product-id="">
                    <i class="far fa-heart"></i>
                  </button>
                </div>
              </div>
              <div class="qv-footer">
                <a href="#" id="qvViewFull" class="btn btn-outline-primary btn-sm flex-grow-1">
                  <i class="fa fa-expand me-1"></i>View Full Details
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ═══ SCRIPTS ═══ -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
// ── Globals ──
  const SITE_URL   = '<?= url('') ?>';
  const CSRF_TOKEN = '<?= $csrfToken ?>';

// ── AJAX Helper ──
  function apPost(endpoint, data, cb, errCb) {
    data._csrf = CSRF_TOKEN;
    $.ajax({
      url: SITE_URL + '/' + endpoint,
      type: 'POST', data: data, dataType: 'json',
      success: cb,
      error: errCb || function(xhr) {
        let msg = 'Something went wrong.';
        try { msg = JSON.parse(xhr.responseText).message || msg; } catch(e) {}
        toast('error', 'Error', msg);
      }
    });
  }
// Keep backward compat
  function ajaxPost(u,d,s,e) { apPost(u,d,s,e); }

// ── Toast Notification System ──
  function toast(type, title, text, duration=3500) {
    const icons = { success:'✅', error:'❌', info:'ℹ️', warning:'⚠️', cart:'🛒' };
    const el = document.createElement('div');
    el.className = 'toast-msg ' + type;
    el.innerHTML = `<div class="toast-icon">${icons[type]||'ℹ️'}</div>
    <div class="toast-body flex-grow-1">
      <div class="toast-title">${title}</div>
    ${text ? `<div class="toast-text">${text}</div>` : ''}
    </div>
    <div style="position:absolute;bottom:0;left:0;right:0;height:3px;background:var(--grad-brand);animation:progress ${duration}ms linear forwards;border-radius:0 0 var(--radius) var(--radius);"></div>`;
    el.onclick = () => removeToast(el);
    document.getElementById('toastContainer').appendChild(el);
    setTimeout(() => removeToast(el), duration);
  }
  function removeToast(el) {
    el.classList.add('removing');
    setTimeout(() => el.remove(), 300);
  }

// ── Mobile Drawer ──
(function() {
  // Tab switching
  document.querySelectorAll('.mnav-tab').forEach(tab => {
    tab.addEventListener('click', function() {
      const target = this.dataset.tab;
      document.querySelectorAll('.mnav-tab').forEach(t => {
        t.classList.remove('active');
        t.setAttribute('aria-selected', 'false');
      });
      document.querySelectorAll('.mnav-panel').forEach(p => p.classList.remove('active'));
      this.classList.add('active');
      this.setAttribute('aria-selected', 'true');
      const panel = document.getElementById('mnavPanel-' + target);
      if (panel) panel.classList.add('active');
    });
  });

  // Category drill-down
  let levelStack = []; // [{levelEl, parentEl}]

  function drillInto(targetId, fromEl) {
    const target = document.getElementById(targetId);
    if (!target) return;
    fromEl.classList.remove('is-active');
    fromEl.classList.add('is-behind');
    // Scroll target to top
    target.scrollTop = 0;
    target.classList.add('is-active');
    levelStack.push(fromEl);
  }

  function drillBack(backToEl) {
    const current = document.querySelector('.mcat-level.is-active');
    if (!current) return;
    current.classList.remove('is-active');
    current.classList.remove('is-behind');
    backToEl.classList.remove('is-behind');
    backToEl.classList.add('is-active');
    levelStack.pop();
    // Hide current after transition
    const hidden = current;
    setTimeout(() => { if (!hidden.classList.contains('is-active') && !hidden.classList.contains('is-behind')) hidden.style.display = ''; }, 300);
  }

  document.addEventListener('click', function(e) {
    // Drill button
    const drill = e.target.closest('.mcat-drill');
    if (drill) {
      e.preventDefault();
      const from = drill.closest('.mcat-level');
      const targetId = drill.dataset.target;
      if (from && targetId) drillInto(targetId, from);
      return;
    }
    // Back button
    const back = e.target.closest('.mcat-back-btn');
    if (back) {
      const backToId = back.dataset.back;
      const backTo = backToId
        ? document.getElementById(backToId)
        : levelStack[levelStack.length - 1];
      if (backTo) drillBack(backTo);
      return;
    }
  });

  // Reset drawer state on close
  const menuEl = document.getElementById('mobileMenu');
  if (menuEl) {
    menuEl.addEventListener('hidden.bs.offcanvas', function() {
      // Reset to root level
      document.querySelectorAll('.mcat-level').forEach(l => {
        l.classList.remove('is-active', 'is-behind');
      });
      const root = document.getElementById('mcat-root');
      if (root) root.classList.add('is-active');
      levelStack = [];
      // Reset to cats tab
      document.querySelectorAll('.mnav-tab').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.mnav-panel').forEach(p => p.classList.remove('active'));
      const catsTab = document.querySelector('.mnav-tab[data-tab="cats"]');
      const catsPanel = document.getElementById('mnavPanel-cats');
      if (catsTab) { catsTab.classList.add('active'); catsTab.setAttribute('aria-selected','true'); }
      if (catsPanel) catsPanel.classList.add('active');
      // Clear search
      const si = document.getElementById('mnavSearchInput');
      if (si) { si.value = ''; filterDrawer(''); }
    });
  }

  // Search filtering
  function filterDrawer(q) {
    q = q.trim().toLowerCase();
    const items = document.querySelectorAll('#mnavPanel-cats .mcat-item[data-search-text]');
    if (!q) {
      items.forEach(el => el.classList.remove('hidden'));
      return;
    }
    items.forEach(el => {
      el.classList.toggle('hidden', !el.dataset.searchText.includes(q));
    });
  }

  const searchInput = document.getElementById('mnavSearchInput');
  if (searchInput) {
    searchInput.addEventListener('input', function() { filterDrawer(this.value); });
  }
})();

// ── Dark Mode ──
  function applyTheme(dark) {
    document.getElementById('htmlRoot').setAttribute('data-theme', dark ? 'dark' : 'light');
    document.querySelectorAll('#darkToggle, #darkToggleMobile').forEach(el => { if (el) el.checked = dark; });
    localStorage.setItem('theme', dark ? 'dark' : 'light');
  }
  const savedTheme = localStorage.getItem('theme') || 'light';
  applyTheme(savedTheme === 'dark');
  document.querySelectorAll('#darkToggle, #darkToggleMobile').forEach(el => {
    el?.addEventListener('change', function() { applyTheme(this.checked); });
  });

// ── Slide Panels ──
  function openPanel(id) {
    document.getElementById(id).classList.add('open');
    document.getElementById('panelOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
    if (id === 'cartPanel')     loadCartPanel();
    if (id === 'wishlistPanel') loadWishPanel();
  }
  function closePanel(id) {
    document.getElementById(id).classList.remove('open');
    document.getElementById('panelOverlay').classList.remove('open');
    document.body.style.overflow = '';
  }
  document.getElementById('panelOverlay').addEventListener('click', function() {
    ['cartPanel','wishlistPanel'].forEach(closePanel);
  });

  function syncBnCart(n) {
    var b = document.getElementById('bnCartBadge');
    if (!b) return;
    b.textContent = n > 0 ? n : '';
    b.style.display = n > 0 ? 'flex' : 'none';
  }
  function syncCartCount(n) {
    var el = document.getElementById('cartCount');
    if (el) el.textContent = n > 0 ? n : '';
    syncBnCart(n);
  }
  function syncWishCount(n) {
    var wc = document.getElementById('wishCount');
    if (!wc) return;
    wc.style.display = n > 0 ? '' : 'none';
    wc.textContent = n;
  }

  // ── Cart Panel ─────────────────────────────────────────
  var _cartLoading = false;
  function loadCartPanel(force) {
    if (_cartLoading && !force) return;
    _cartLoading = true;
    var body  = document.getElementById('cartPanelItems');
    var empty = document.getElementById('cartPanelEmpty');
    body.innerHTML = '<div style="padding:2rem;text-align:center;"><div class="spinner-border" style="width:1.5rem;height:1.5rem;"></div></div>';
    empty.style.display = 'none';
    $.getJSON(SITE_URL + '/cart/items', function(res) {
      _cartLoading = false;
      if (!res.items || !res.items.length) {
        body.innerHTML = '';
        empty.style.display = '';
        document.getElementById('cartPanelTotal').textContent = 'LKR 0.00';
        document.getElementById('cartPanelCount').textContent = '';
        syncCartCount(0);
        return;
      }
      empty.style.display = 'none';
      body.innerHTML = res.items.map(function(item) {
        var price = parseFloat(item.effective_price);
        var qty   = parseInt(item.quantity, 10);
        var img   = item.thumbnail
          ? SITE_URL + '/uploads/products/' + item.thumbnail
          : SITE_URL + '/assets/img/placeholder.webp';
        return '<div class="panel-item" data-cart-id="' + item.id + '">'
          + '<img src="' + img + '" alt="">'
          + '<div class="panel-item-info">'
          +   '<div class="panel-item-name">' + item.name + '</div>'
          + (item.variation_name ? '<div style="font-size:11px;color:var(--text-muted);">' + item.variation_name + '</div>' : '')
          +   '<div class="panel-item-price">LKR ' + price.toLocaleString('en-US',{minimumFractionDigits:2}) + '</div>'
          +   '<div class="panel-qty">'
          +     '<button class="cp-minus" data-cid="' + item.id + '" data-qty="' + (qty - 1) + '">−</button>'
          +     '<span class="cp-qty-val">' + qty + '</span>'
          +     '<button class="cp-plus" data-cid="' + item.id + '" data-qty="' + (qty + 1) + '">+</button>'
          +   '</div>'
          + '</div>'
          + '<button class="panel-item-remove cp-remove" data-cid="' + item.id + '"><i class="fas fa-times"></i></button>'
          + '</div>';
      }).join('');
      var subtotal = parseFloat(res.subtotal) || 0;
      document.getElementById('cartPanelTotal').textContent = 'LKR ' + subtotal.toLocaleString('en-US',{minimumFractionDigits:2});
      document.getElementById('cartPanelCount').textContent = res.count;
      syncCartCount(res.count);
    }).fail(function() {
      _cartLoading = false;
      body.innerHTML = '<p style="padding:1rem;color:var(--text-muted);text-align:center;">Failed to load cart.</p>';
    });
  }

  // Delegated: cart panel qty & remove
  $(document).on('click', '.cp-minus, .cp-plus', function() {
    var cid = $(this).data('cid');
    var qty = parseInt($(this).data('qty'));
    if (qty < 0) return;
    $(this).prop('disabled', true);
    apPost('cart/update', {cart_id: cid, quantity: qty}, function(res) {
      loadCartPanel(true);
    });
  });
  $(document).on('click', '.cp-remove', function() {
    var cid = $(this).data('cid');
    var $row = $(this).closest('.panel-item');
    $row.css('opacity','0.4');
    apPost('cart/remove', {cart_id: cid}, function(res) {
      loadCartPanel(true);
      syncCartCount(res.count);
      toast('info', 'Removed', 'Item removed from cart.');
    });
  });

  // ── Wishlist Panel ──────────────────────────────────────
  var _wishLoading = false;
  function loadWishPanel(force) {
    if (_wishLoading && !force) return;
    _wishLoading = true;
    var body  = document.getElementById('wishPanelItems');
    var empty = document.getElementById('wishPanelEmpty');
    body.innerHTML = '<div style="padding:2rem;text-align:center;"><div class="spinner-border" style="width:1.5rem;height:1.5rem;"></div></div>';
    empty.style.display = 'none';
    $.getJSON(SITE_URL + '/wishlist/items', function(res) {
      _wishLoading = false;
      if (!res.items || !res.items.length) {
        body.innerHTML = '';
        empty.style.display = '';
        document.getElementById('wishPanelCount').textContent = '';
        syncWishCount(0);
        return;
      }
      empty.style.display = 'none';
      body.innerHTML = res.items.map(function(item) {
        var pid   = item.product_id;
        var price = parseFloat(item.sale_price || item.price);
        var img   = item.thumbnail
          ? SITE_URL + '/uploads/products/' + item.thumbnail
          : SITE_URL + '/assets/img/placeholder.webp';
        return '<div class="panel-item" data-pid="' + pid + '">'
          + '<a href="' + SITE_URL + '/product/' + item.slug + '" onclick="closePanel(\'wishlistPanel\')">'
          +   '<img src="' + img + '" alt="">'
          + '</a>'
          + '<div class="panel-item-info">'
          +   '<a href="' + SITE_URL + '/product/' + item.slug + '" class="panel-item-name d-block" onclick="closePanel(\'wishlistPanel\')">' + item.name + '</a>'
          +   '<div class="panel-item-price">LKR ' + price.toLocaleString('en-US',{minimumFractionDigits:2}) + '</div>'
          +   '<button class="btn-brand mt-2 wp-move-cart" data-pid="' + pid + '" style="font-size:11px;padding:4px 10px;border-radius:6px;">'
          +     '<i class="fas fa-cart-plus me-1"></i>Move to Cart'
          +   '</button>'
          + '</div>'
          + '<button class="panel-item-remove wp-remove" data-pid="' + pid + '" title="Remove from wishlist"><i class="fas fa-times"></i></button>'
          + '</div>';
      }).join('');
      document.getElementById('wishPanelCount').textContent = res.count;
      syncWishCount(res.count);
    }).fail(function() {
      _wishLoading = false;
      body.innerHTML = '<p style="padding:1rem;color:var(--text-muted);text-align:center;">Failed to load wishlist.</p>';
    });
  }

  // Delegated: wishlist panel remove
  $(document).on('click', '.wp-remove', function() {
    var pid  = $(this).data('pid');
    var $row = $(this).closest('.panel-item');
    $row.css('opacity','0.4');
    apPost('wishlist/toggle', {product_id: pid}, function(res) {
      if (res.login) { window.location.href = SITE_URL + '/login'; return; }
      loadWishPanel(true);
      syncWishCount(res.wish_count);
      // Update wishlist hearts on page
      $('.wishlist-btn[data-product-id="' + pid + '"], .pc-wishlist[data-product-id="' + pid + '"]')
        .removeClass('wishlisted active')
        .find('i').removeClass('fas').addClass('far');
      toast('info', 'Removed', res.message);
    });
  });

  // Delegated: move to cart from wishlist panel
  $(document).on('click', '.wp-move-cart', function() {
    var btn = $(this);
    var pid = btn.data('pid');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" style="width:10px;height:10px;"></span>');
    apPost('cart/add', {product_id: pid, quantity: 1}, function(res) {
      if (res.success) {
        syncCartCount(res.count);
        toast('cart', 'Moved to Cart!', 'Item added to cart.');
        // Also remove from wishlist
        apPost('wishlist/toggle', {product_id: pid}, function(wr) {
          if (!wr.in_wishlist) {
            syncWishCount(wr.wish_count);
            loadWishPanel(true);
          }
        });
      } else {
        btn.prop('disabled', false).html('<i class="fas fa-cart-plus me-1"></i>Move to Cart');
        toast('warning', 'Oops', res.message);
      }
    }, function() {
      btn.prop('disabled', false).html('<i class="fas fa-cart-plus me-1"></i>Move to Cart');
      toast('error', 'Error', 'Failed to add item.');
    });
  });

// ── Add to Cart (product cards / detail page) ──
  $(document).on('click', '.btn-add-cart', function(e) {
    e.preventDefault();
    var btn = $(this);
    var pid = btn.data('product-id');
    var vid = btn.data('variation-id') || 0;
    var qty = parseInt(document.getElementById('qty-' + pid)?.value || 1);
    btn.prop('disabled', true);
    apPost('cart/add', {product_id: pid, variation_id: vid, quantity: qty}, function(res) {
      btn.prop('disabled', false);
      if (res.success) {
        syncCartCount(res.count);
        toast('cart', '🛒 Added to Cart!', res.message || 'Item added successfully.');
      } else {
        toast('warning', 'Oops', res.message);
      }
    }, function() { btn.prop('disabled', false); toast('error', 'Error', 'Failed to add item.'); });
  });

// ── Wishlist Toggle (hearts on product cards) ──
  $(document).on('click', '.wishlist-btn', function(e) {
    e.preventDefault();
    var btn = $(this);
    var pid = btn.data('product-id');
    if (!pid) return;
    apPost('wishlist/toggle', {product_id: pid}, function(res) {
      if (res.login) { window.location.href = SITE_URL + '/login'; return; }
      var inWish = res.in_wishlist;
      // Update ALL wishlist buttons for this product on the page
      $('[data-product-id="' + pid + '"].wishlist-btn').each(function() {
        $(this).toggleClass('wishlisted', inWish).toggleClass('active', inWish)
          .find('i').toggleClass('far', !inWish).toggleClass('fas', inWish);
      });
      syncWishCount(res.wish_count);
      toast(inWish ? 'success' : 'info', inWish ? '❤️ Wishlisted' : 'Removed', res.message);
      if (!inWish) $(document).trigger('wishlist:removed', [pid]);
      if (document.getElementById('wishlistPanel').classList.contains('open')) loadWishPanel(true);
    });
  });

// ── Init: load wishlist count on page load ──
  $.getJSON(SITE_URL + '/wishlist/items', function(res) {
    if (res.count > 0) syncWishCount(res.count);
  });

// ── Sticky header + topbar hide on scroll ──
  (function() {
    var lastY        = 0;
    var ticking      = false;
    var topbarHidden = false;
  var HIDE_AFTER   = 80;   // px scrolled before hide kicks in
  var SHOW_DELTA   = 6;    // px scrolled up to reveal topbar again

  var topbar = document.getElementById('topBar');
  var header = document.getElementById('siteHeader');
  var catNav = document.getElementById('catNavBar');

  function setTopbarVisible(visible) {
    if (!topbar) return;
    topbarHidden = !visible;
    topbar.classList.toggle('topbar-hidden', topbarHidden);
    if (header) header.style.top = topbarHidden ? '0' : '';
    if (catNav) catNav.style.top = topbarHidden ? 'var(--header-h)' : '';
  }

  function onScroll() {
    var y = window.scrollY;

    if (window.innerWidth >= 768) {
      var delta = y - lastY;
      if (!topbarHidden && y > HIDE_AFTER && delta > 0) {
        setTopbarVisible(false);
      } else if (topbarHidden && delta < -SHOW_DELTA) {
        setTopbarVisible(true);
      }
    } else if (topbarHidden) {
      // always show topbar on mobile (it's hidden via d-none anyway)
      setTopbarVisible(true);
    }

    lastY = y;
    if (header) header.classList.toggle('scrolled', y > 60);
    var btn = document.getElementById('backToTop');
    if (btn) btn.classList.toggle('fab-show', y > 400);
    ticking = false;
  }

  window.addEventListener('scroll', function() {
    if (!ticking) { requestAnimationFrame(onScroll); ticking = true; }
  }, { passive: true });

  // Stagger fab-in on load
  requestAnimationFrame(function() {
    document.querySelectorAll('#fabStack .fab-btn').forEach(function(el) {
      requestAnimationFrame(function() { el.classList.add('fab-in'); });
    });
  });

  // Back to top click
  document.getElementById('backToTop')?.addEventListener('click', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
})();

// ── Live Search ──
(function() {
  function hesc(s) {
    return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  // Wrap first occurrence of q in the text with a highlight span
  function hl(text, q) {
    if (!q || !text) return hesc(text);
    var idx = text.toLowerCase().indexOf(q.toLowerCase());
    if (idx === -1) return hesc(text);
    return hesc(text.slice(0, idx))
      + '<mark style="background:var(--brand-light);color:var(--brand);font-weight:700;border-radius:2px;padding:0 1px;">'
      + hesc(text.slice(idx, idx + q.length))
      + '</mark>'
      + hesc(text.slice(idx + q.length));
  }

  function tagBadge(label, color) {
    return '<span style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;'
      + 'padding:2px 6px;border-radius:4px;background:'+color+'22;color:'+color+';flex-shrink:0;">'
      + label + '</span>';
  }

  function buildDropdown(data, q, dd) {
    var html = '';

    // ── Products (shown first — primary search intent) ──
    (data.products || []).forEach(function(p) {
      var img   = p.thumbnail
        ? SITE_URL + '/uploads/products/' + p.thumbnail
        : SITE_URL + '/assets/img/placeholder.webp';
      var price = parseFloat(p.price || 0).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
      var meta  = p.brand_name || p.category_name || '';
      html += '<a href="' + SITE_URL + '/product/' + hesc(p.slug) + '" class="search-item">'
        + '<img src="' + hesc(img) + '" alt="" loading="lazy">'
        + '<div class="flex-grow-1" style="min-width:0;overflow:hidden;">'
        +   '<div style="font-weight:600;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + hl(p.name, q) + '</div>'
        +   (meta ? '<div style="font-size:11px;color:var(--text-muted);margin-top:1px;">' + hesc(meta) + '</div>' : '')
        + '</div>'
        + '<div style="text-align:right;flex-shrink:0;margin-left:6px;">'
        +   '<div style="font-weight:700;font-size:12px;color:var(--brand);white-space:nowrap;">LKR ' + price + '</div>'
        +   tagBadge('Product', '#10b981')
        + '</div></a>';
    });

    // ── Categories ──
    (data.cats || []).forEach(function(c) {
      html += '<a href="' + SITE_URL + '/category/' + hesc(c.slug) + '" class="search-item">'
        + '<div style="width:40px;height:40px;border-radius:8px;background:var(--brand-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;">'
        +   '<i class="fas fa-th-large" style="color:var(--brand);font-size:14px;"></i>'
        + '</div>'
        + '<div class="flex-grow-1"><div style="font-weight:600;font-size:13px;">' + hl(c.name, q) + '</div></div>'
        + tagBadge('Category', 'var(--brand)') + '</a>';
    });

    // ── Brands ──
    (data.brands || []).forEach(function(b) {
      html += '<a href="' + SITE_URL + '/brand/' + hesc(b.slug) + '" class="search-item">'
        + '<div style="width:40px;height:40px;border-radius:8px;background:var(--bg-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;">'
        +   '<i class="fas fa-trademark" style="color:var(--text-muted);font-size:14px;"></i>'
        + '</div>'
        + '<div class="flex-grow-1"><div style="font-weight:600;font-size:13px;">' + hl(b.name, q) + '</div></div>'
        + tagBadge('Brand', '#f59e0b') + '</a>';
    });

    if (!html) { dd.style.display = 'none'; return; }
    html += '<a href="' + SITE_URL + '/search?q=' + encodeURIComponent(q) + '" class="search-item"'
      + ' style="justify-content:center;font-weight:600;color:var(--brand);border-top:2px solid var(--border);">'
      + 'View all results for &ldquo;' + hesc(q) + '&rdquo; &rarr;</a>';
    dd.innerHTML = html;
    dd.style.display = 'block';
  }

  function attachSearch(inputId, dropdownId, wrapperSelector) {
    var inp = document.getElementById(inputId);
    var dd  = document.getElementById(dropdownId);
    if (!inp || !dd) return;
    var timer, xhr;

    inp.addEventListener('input', function() {
      clearTimeout(timer);
      var q = this.value.trim();
      if (q.length < 2) { dd.style.display = 'none'; return; }

      // Immediate loading state so the UI feels instant
      dd.innerHTML = '<div style="padding:14px 16px;text-align:center;color:var(--text-muted);font-size:13px;">'
        + '<i class="fas fa-spinner fa-spin" style="margin-right:6px;"></i>Searching&hellip;</div>';
      dd.style.display = 'block';

      timer = setTimeout(function() {
        if (xhr) xhr.abort();
        xhr = $.getJSON(SITE_URL + '/search?q=' + encodeURIComponent(q) + '&ajax=1', function(data) {
          buildDropdown(data, q, dd);
        }).fail(function(jqXHR) {
          if (jqXHR.statusText !== 'abort') dd.style.display = 'none';
        });
      }, 260);
    });

    // Hide on outside click
    document.addEventListener('click', function(e) {
      if (!e.target.closest(wrapperSelector)) dd.style.display = 'none';
    });

    // Keyboard: Escape closes; arrows navigate items
    inp.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') { dd.style.display = 'none'; inp.blur(); return; }
      if (dd.style.display === 'none') return;
      var items = Array.from(dd.querySelectorAll('a.search-item'));
      if (!items.length) return;
      if (e.key === 'ArrowDown') { e.preventDefault(); items[0].focus(); }
    });

    dd.addEventListener('keydown', function(e) {
      var items = Array.from(dd.querySelectorAll('a.search-item'));
      var idx   = items.indexOf(document.activeElement);
      if (e.key === 'ArrowDown') { e.preventDefault(); if (idx < items.length - 1) items[idx + 1].focus(); }
      else if (e.key === 'ArrowUp') { e.preventDefault(); if (idx > 0) items[idx - 1].focus(); else inp.focus(); }
      else if (e.key === 'Escape') { dd.style.display = 'none'; inp.focus(); }
    });
  }

  attachSearch('mainSearch',   'searchDropdown',       '.header-search');
  attachSearch('mobileSearch', 'mobileSearchDropdown', '#mobileSearch');
})();

// ── Newsletter ──
document.getElementById('newsletterForm')?.addEventListener('submit', function(e) {
  e.preventDefault();
  var form = this;
  var emailVal = form.querySelector('[type=email]').value.trim();
  apPost('newsletter/subscribe', { email: emailVal }, function(res) {
    if (res.success) {
      toast('success', 'Subscribed!', res.message);
      form.reset();
    } else {
      toast('warning', 'Oops', res.message);
    }
  });
});

// ── Mega menu toggle ──
(function() {
  const toggle = document.getElementById('catMegaToggle');
  const menu   = document.getElementById('catMegaMenu');
  if (!toggle || !menu) return;
  toggle.addEventListener('click', function(e) {
    e.preventDefault();
    const open = menu.classList.toggle('open');
    toggle.classList.toggle('active', open);
    toggle.setAttribute('aria-expanded', open);
  });
  document.addEventListener('click', function(e) {
    if (!e.target.closest('#catNavBar')) {
      menu.classList.remove('open');
      toggle.classList.remove('active');
      toggle.setAttribute('aria-expanded', 'false');
    }
  });
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && menu.classList.contains('open')) {
      menu.classList.remove('open');
      toggle.classList.remove('active');
      toggle.setAttribute('aria-expanded', 'false');
      toggle.focus();
    }
  });
})();

// ── Quick View ──
(function() {
  let qvProductId = 0, qvVariationId = 0;

  function starsHtml(avg) {
    let h = '';
    for (let i = 1; i <= 5; i++) {
      if (avg >= i)           h += '<i class="fa fa-star" style="color:#ffb83c;font-size:12px;"></i>';
      else if (avg >= i-.5)   h += '<i class="fa fa-star-half-o" style="color:#ffb83c;font-size:12px;"></i>';
      else                    h += '<i class="fa fa-star-o" style="color:#ddd;font-size:12px;"></i>';
    }
    return h;
  }

  $(document).on('click', '.btn-quick-view', function() {
    const slug = $(this).data('slug');
    qvVariationId = 0;
    $('#qvSpinner').show();
    $('#qvContent').hide();
    const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
    modal.show();

    $.getJSON(SITE_URL + '/product/' + slug + '/quick', function(p) {
      if (!p.success) { $('#qvSpinner').html('<p class="text-muted p-4">Product not found.</p>'); return; }
      qvProductId = p.id;

      // Main image
      const mainImg = p.thumbnail ? SITE_URL+'/uploads/products/'+p.thumbnail : SITE_URL+'/assets/img/placeholder.webp';
      $('#qvMainImg').attr('src', mainImg).attr('alt', p.name);

      // Thumbnails
      const allImgs = [];
      if (p.thumbnail) allImgs.push(p.thumbnail);
      (p.images||[]).forEach(img => { if (img.image && img.image !== p.thumbnail) allImgs.push(img.image); });
      const thumbsHtml = allImgs.map((img, i) =>
        `<div class="qv-thumb${i===0?' active':''}" onclick="qvSetImg(this,'${SITE_URL}/uploads/products/${img}')">
           <img src="${SITE_URL}/uploads/products/${img}" alt="">
      </div>`
      ).join('');
      $('#qvThumbs').html(thumbsHtml);

      // Name & meta
      $('#qvName').text(p.name);
      let meta = [];
      if (p.brand)    meta.push('Brand: <strong>'+p.brand+'</strong>');
      if (p.category) meta.push('Category: <strong>'+p.category+'</strong>');
      if (p.sku)      meta.push('SKU: '+p.sku);
      $('#qvMeta').html(meta.join(' &nbsp;|&nbsp; '));

      // Badges
      let badges = '';
      if (p.has_disc) badges += `<span style="background:var(--grad-brand);color:#fff;font-size:10px;font-weight:700;padding:3px 9px;border-radius:6px;">-${p.disc_pct}% OFF</span>`;
      $('#qvBadges').html(badges);

      // Rating
      if (p.rating_total > 0) {
        $('#qvRating').html(starsHtml(p.avg) + `<span class="text-muted small">(${p.rating_total} reviews)</span>`).show();
      } else {
        $('#qvRating').empty().hide();
      }

      // Price
      $('#qvPrice').text('LKR ' + p.eff_price.toLocaleString('en-US', {minimumFractionDigits:2}));
      if (p.has_disc) {
        $('#qvPriceOld').text('LKR ' + p.price.toLocaleString('en-US', {minimumFractionDigits:2})).show();
        $('#qvDisc').text('-'+p.disc_pct+'%').show();
      } else {
        $('#qvPriceOld').hide(); $('#qvDisc').hide();
      }

      // Stock
      const outOfStock = p.track_stock && p.stock_qty === 0;
      const lowStock   = p.track_stock && p.stock_qty > 0 && p.stock_qty <= 5;
      let stockHtml = '';
      if (outOfStock)      stockHtml = '<span class="qv-stock-badge" style="background:#fee2e2;color:#ef4444;">Out of Stock</span>';
      else if (lowStock)   stockHtml = `<span class="qv-stock-badge" style="background:#fef3c7;color:#d97706;">Only ${p.stock_qty} left!</span>`;
      else                 stockHtml = '<span class="qv-stock-badge" style="background:#d1fae5;color:#059669;">In Stock</span>';
      $('#qvStockWrap').html(stockHtml);

      // Short desc
      if (p.short_desc) { $('#qvDesc').html(p.short_desc).show(); } else { $('#qvDesc').hide(); }

      // Variations
      if (p.variations && p.variations.length) {
        const varsHtml = p.variations.map(v =>
      `<button class="qv-var-btn" data-vid="${v.id}" data-price="${v.price||p.price}" data-sale="${v.sale_price||p.sale_price||''}">${v.name}</button>`
      ).join('');
        $('#qvVars').html(varsHtml);
        $('#qvVarWrap').show();
      } else {
        $('#qvVarWrap').hide();
      }

      // Add to cart button
      $('#qvAddCart').data('product-id', p.id).prop('disabled', outOfStock);
      $('#qvWishBtn').attr('data-product-id', p.id);
      $('#qvViewFull').attr('href', SITE_URL+'/product/'+p.slug);
      $('#qvQtyInput').val(1).attr('max', p.track_stock && p.stock_qty > 0 ? p.stock_qty : 99);

      $('#qvSpinner').hide();
      $('#qvContent').show();
    }).fail(function() {
      $('#qvSpinner').html('<p class="text-danger p-4">Failed to load product.</p>');
    });
  });

  // Variation select
$(document).on('click', '.qv-var-btn', function() {
  $('.qv-var-btn').removeClass('selected');
  $(this).addClass('selected');
  qvVariationId = parseInt($(this).data('vid')) || 0;
  const sale  = parseFloat($(this).data('sale'));
  const price = parseFloat($(this).data('price'));
  const eff   = (sale && sale < price) ? sale : price;
  $('#qvPrice').text('LKR ' + eff.toLocaleString('en-US', {minimumFractionDigits:2}));
});

  // Qty
$('#qvQtyMinus').on('click', function() {
  const el = document.getElementById('qvQtyInput');
  if (parseInt(el.value) > 1) el.value = parseInt(el.value) - 1;
});
$('#qvQtyPlus').on('click', function() {
  const el = document.getElementById('qvQtyInput');
  const max = parseInt(el.max) || 99;
  if (parseInt(el.value) < max) el.value = parseInt(el.value) + 1;
});

  // Add to cart from quick view
$('#qvAddCart').on('click', function() {
  const qty = parseInt($('#qvQtyInput').val()) || 1;
  const btn = $(this).prop('disabled', true);
  apPost('cart/add', {product_id: qvProductId, variation_id: qvVariationId, quantity: qty}, function(res) {
    btn.prop('disabled', false);
    if (res.success) {
      const el = document.getElementById('cartCount');
      if (el) el.textContent = res.count > 0 ? res.count : '';
      syncBnCart(res.count);
      toast('cart', '🛒 Added!', res.message || 'Item added to cart.');
      bootstrap.Modal.getInstance(document.getElementById('quickViewModal'))?.hide();
    } else {
      toast('warning', 'Oops', res.message);
      btn.prop('disabled', false);
    }
  }, function() { btn.prop('disabled', false); toast('error','Error','Failed to add item.'); });
});
})();

window.qvSetImg = function(el, src) {
  document.getElementById('qvMainImg').src = src;
  document.querySelectorAll('.qv-thumb').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
};

// ── AOS Init ──
AOS.init({ duration: 600, once: true });
</script>

<?= $extraScript ?? '' ?>

<!-- ═══════════════════════════════════════════════════════════
     CHATBOT WIDGET
═══════════════════════════════════════════════════════════ -->
<style>
/* ── Chat toggle icon morph ── */
#chatToggle .ct-icon { transition: opacity .2s, transform .2s; position: absolute; }
#chatToggle .ct-open  { opacity: 1; transform: scale(1); }
#chatToggle .ct-close { opacity: 0; transform: scale(.5) rotate(90deg); }
#chatToggle.is-open .ct-open  { opacity: 0; transform: scale(.5) rotate(-90deg); }
#chatToggle.is-open .ct-close { opacity: 1; transform: scale(1) rotate(0deg); }

/* Unread badge */
#chatBadge {
  position: absolute; top: -4px; right: -4px;
  width: 20px; height: 20px; border-radius: 50%;
  background: #fff; color: var(--brand); font-size: 10px; font-weight: 800;
  display: none; align-items: center; justify-content: center;
  border: 2px solid var(--brand);
}
#chatBadge.show { display: flex; }

/* ── Chat window ── */
#chatWindow {
  position: fixed; bottom: 28px; right: 78px; z-index: 1079;
  width: min(380px, calc(100vw - 32px));
  height: min(560px, calc(100vh - 110px));
  border-radius: 18px;
  background: var(--bg-card);
  border: 1px solid var(--border);
  box-shadow: 0 24px 64px rgba(0,0,0,.18), 0 4px 16px rgba(0,0,0,.1);
  display: flex; flex-direction: column; overflow: hidden;
  transform: translateY(20px) scale(.95); opacity: 0; pointer-events: none;
  transition: transform .28s cubic-bezier(.34,1.3,.64,1), opacity .22s ease;
}
#chatWindow.is-open { transform: translateY(0) scale(1); opacity: 1; pointer-events: auto; }

/* Header */
#chatHeader {
  background: var(--grad-brand);
  padding: 14px 16px 12px;
  display: flex; align-items: center; gap: 10px;
  flex-shrink: 0;
}
.chat-avatar {
  width: 38px; height: 38px; border-radius: 50%;
  background: rgba(255,255,255,.22); border: 2px solid rgba(255,255,255,.4);
  display: flex; align-items: center; justify-content: center;
  font-size: 16px; color: #fff; flex-shrink: 0;
}
.chat-header-info { flex: 1; min-width: 0; }
.chat-header-info strong { display: block; color: #fff; font-size: .88rem; font-weight: 700; }
.chat-status { display: flex; align-items: center; gap: 5px; font-size: .71rem; color: rgba(255,255,255,.8); margin-top: 1px; }
.chat-status-dot { width: 6px; height: 6px; border-radius: 50%; background: #4ade80; flex-shrink: 0; }
.chat-close-btn {
  width: 28px; height: 28px; border-radius: 50%; background: rgba(255,255,255,.18);
  border: none; color: #fff; font-size: 13px; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background .18s;
}
.chat-close-btn:hover { background: rgba(255,255,255,.32); }

/* Quick actions */
#chatActions {
  display: flex; gap: 6px; flex-wrap: wrap;
  padding: 10px 12px 8px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0; background: var(--bg-soft);
}
.chat-action-btn {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 5px 11px; border-radius: 20px; font-size: .72rem; font-weight: 600;
  background: var(--bg-card); border: 1px solid var(--border);
  color: var(--text); cursor: pointer;
  transition: background .18s, color .18s, border-color .18s;
  white-space: nowrap;
}
.chat-action-btn:hover { background: var(--brand); border-color: var(--brand); color: #fff; }

/* Messages area */
#chatMessages {
  flex: 1; overflow-y: auto; padding: 14px 12px;
  display: flex; flex-direction: column; gap: 10px;
  scroll-behavior: smooth;
}
#chatMessages::-webkit-scrollbar { width: 4px; }
#chatMessages::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

/* Bubbles */
.cm-row { display: flex; gap: 8px; align-items: flex-end; }
.cm-row.user-row { flex-direction: row-reverse; }

.cm-avatar {
  width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center; font-size: 12px;
}
.bot-avatar  { background: var(--grad-brand); color: #fff; }
.admin-avatar{ background: linear-gradient(135deg,#10b981,#065f46); color: #fff; }

.cm-bubble {
  max-width: 78%; padding: 9px 13px; border-radius: 16px;
  font-size: .82rem; line-height: 1.5; white-space: pre-wrap; word-break: break-word;
}
.bot-bubble, .admin-bubble {
  background: var(--bg-soft); color: var(--text);
  border: 1px solid var(--border);
  border-bottom-left-radius: 4px;
}
.admin-bubble { border-color: rgba(16,185,129,.2); background: rgba(16,185,129,.06); }
.user-bubble  {
  background: var(--grad-brand); color: #fff;
  border-bottom-right-radius: 4px;
}
.cm-time { font-size: 10px; color: var(--text-muted); margin-top: 3px; text-align: right; }
.user-row .cm-time { text-align: left; }

/* Bubble fade-in */
.cm-new { opacity: 0; transform: translateY(6px); transition: opacity .22s ease, transform .22s ease; }
.cm-visible { opacity: 1; transform: translateY(0); }

/* Typing indicator */
#chatTyping { display: none; align-items: flex-end; gap: 8px; }
.typing-dots { display: flex; gap: 4px; padding: 10px 14px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 16px; border-bottom-left-radius: 4px; }
.typing-dots span { width: 6px; height: 6px; border-radius: 50%; background: var(--text-muted); animation: typingBounce .9s infinite ease-in-out; }
.typing-dots span:nth-child(2) { animation-delay: .15s; }
.typing-dots span:nth-child(3) { animation-delay: .3s; }
@keyframes typingBounce { 0%,60%,100% { transform: translateY(0); } 30% { transform: translateY(-6px); } }

/* Input area */
#chatFooter {
  padding: 10px 12px; border-top: 1px solid var(--border);
  display: flex; gap: 8px; align-items: flex-end; flex-shrink: 0;
  background: var(--bg-card);
}
#chatInput {
  flex: 1; resize: none; border-radius: 12px;
  border: 1px solid var(--border); background: var(--bg-soft);
  color: var(--text); font-size: .82rem; padding: 9px 12px;
  outline: none; line-height: 1.4; max-height: 80px;
  transition: border-color .18s;
}
#chatInput:focus { border-color: var(--brand); }
#chatInput::placeholder { color: var(--text-muted); }
#chatSend {
  width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
  background: var(--grad-brand); border: none; color: #fff; font-size: 14px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: opacity .18s, transform .15s;
}
#chatSend:hover { opacity: .88; }
#chatSend:active { transform: scale(.93); }

/* WhatsApp + Human fallback bar */
#chatFooterLinks {
  display: flex; gap: 8px; justify-content: center;
  padding: 0 12px 10px; flex-shrink: 0;
}
.chat-wa-btn {
  flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
  padding: 7px 12px; border-radius: 10px; font-size: .72rem; font-weight: 700;
  background: #25d366; color: #fff; border: none; cursor: pointer; text-decoration: none;
  transition: opacity .18s;
}
.chat-wa-btn:hover { opacity: .85; color: #fff; }
.chat-human-btn {
  flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
  padding: 7px 12px; border-radius: 10px; font-size: .72rem; font-weight: 700;
  background: var(--bg-soft); border: 1px solid var(--border); color: var(--text);
  cursor: pointer; transition: background .18s;
}
.chat-human-btn:hover { background: var(--border); }

/* ── Mobile adjustments ── */
@media (max-width: 767px) {
  #chatWindow {
    bottom: 90px; right: 12px; left: 12px; width: auto; border-radius: 16px;
    /* Cap height so window doesn't bleed under status bar */
    height: min(500px, calc(100vh  - 158px));  /* older browsers */
    height: min(500px, calc(100svh - 158px));  /* modern: shrinks when keyboard open */
  }
  /* Quick actions: horizontal scroll so all pills stay on one row */
  #chatActions {
    flex-wrap: nowrap;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    padding-bottom: 10px;
  }
  #chatActions::-webkit-scrollbar { display: none; }
  .chat-action-btn { flex-shrink: 0; }
  /* Tighter spacing inside window */
  #chatMessages { padding: 10px; gap: 8px; }
  #chatHeader { padding: 12px 14px 10px; }
  #chatFooter { padding: 8px 10px; gap: 7px; }
  #chatFooterLinks { padding: 0 10px 8px; gap: 6px; }
  .chat-wa-btn, .chat-human-btn { padding: 7px 10px; font-size: .7rem; }
  .cm-bubble { font-size: .8rem; padding: 8px 11px; }
}
@media (max-width: 480px) {
  #chatWindow {
    bottom: 84px; right: 10px; left: 10px;
    height: min(460px, calc(100vh  - 148px));
    height: min(460px, calc(100svh - 148px));
  }
}
/* Desktop: keep window away from FAB column */
@media (min-width: 768px) {
  #chatWindow { bottom: 28px; right: 78px; }
}
</style>

<!-- Chat Window -->
<div id="chatWindow" role="dialog" aria-label="Live chat">

  <!-- Header -->
  <div id="chatHeader">
    <div class="chat-avatar"><i class="fa fa-headset"></i></div>
    <div class="chat-header-info">
      <strong><?= e(setting('site_name','Anything.lk')) ?> Support</strong>
      <div class="chat-status"><span class="chat-status-dot"></span> Online — typically replies instantly</div>
    </div>
    <button class="chat-close-btn" id="chatCloseBtn" aria-label="Close chat"><i class="fa fa-times"></i></button>
  </div>

  <!-- Quick action pills -->
  <div id="chatActions">
    <button class="chat-action-btn" data-msg="Track my order">📦 Track Order</button>
    <button class="chat-action-btn" data-msg="What are your shipping options?">🚚 Shipping</button>
    <button class="chat-action-btn" data-msg="I want to talk to a human agent">🙋 Talk to Human</button>
    <button class="chat-action-btn" data-msg="What offers are available today?">🎉 Offers</button>
    <button class="chat-action-btn" data-msg="How do I contact you?">📞 Contact</button>
  </div>

  <!-- Messages -->
  <div id="chatMessages"></div>

  <!-- Typing indicator (inside messages scroll area) -->
  <div id="chatTyping" style="padding:0 12px 8px;">
    <div class="cm-avatar bot-avatar"><i class="fa fa-headset" style="font-size:11px;"></i></div>
    <div class="typing-dots"><span></span><span></span><span></span></div>
  </div>

  <!-- Footer input -->
  <div id="chatFooter">
    <textarea id="chatInput" placeholder="Type a message…" rows="1" maxlength="500"></textarea>
    <button id="chatSend" aria-label="Send"><i class="fa fa-paper-plane"></i></button>
  </div>

  <!-- WhatsApp + Talk to human -->
  <div id="chatFooterLinks">
    <?php $waNum = setting('whatsapp_number','94770000000'); ?>
    <a href="https://wa.me/<?= e($waNum) ?>" target="_blank" rel="noopener" class="chat-wa-btn">
      <i class="fab fa-whatsapp"></i> WhatsApp
    </a>
    <button class="chat-human-btn" id="humanBtn">
      <i class="fa fa-user"></i> Talk to Human
    </button>
  </div>
</div>

<script>
(function () {
  const toggle   = document.getElementById('chatToggle');
  const win      = document.getElementById('chatWindow');
  const closeBtn = document.getElementById('chatCloseBtn');
  const msgArea  = document.getElementById('chatMessages');
  const input    = document.getElementById('chatInput');
  const sendBtn  = document.getElementById('chatSend');
  const typing   = document.getElementById('chatTyping');
  const badge    = document.getElementById('chatBadge');

  let sessionId = null;
  let lastMsgId = 0;
  let isOpen    = false;
  let polling   = null;
  let unread    = 0;

  // ── Open / Close ──────────────────────────────────────
  function openChat() {
    isOpen = true;
    win.classList.add('is-open');
    toggle.classList.add('is-open');
    unread = 0; badge.textContent = '0'; badge.classList.remove('show');
    if (!sessionId) startSession();
    else scrollBottom();
    input.focus();
  }

  function closeChat() {
    isOpen = false;
    win.classList.remove('is-open');
    toggle.classList.remove('is-open');
  }

  toggle.addEventListener('click', () => isOpen ? closeChat() : openChat());
  closeBtn.addEventListener('click', closeChat);

  // Close on outside click
  document.addEventListener('click', e => {
    if (isOpen && !win.contains(e.target) && e.target !== toggle && !toggle.contains(e.target)) closeChat();
  });

  // ── Session start ─────────────────────────────────────
  function startSession() {
    fetch(SITE_URL + '/chat/start', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: '_csrf=' + encodeURIComponent(CSRF_TOKEN)
    })
    .then(r => {
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return r.json();
    })
    .then(res => {
      if (!res.success) throw new Error(res.message || 'Session failed');
      sessionId = res.session_id;
      renderMessages(res.messages);
      startPolling();
    })
    .catch(() => {
      appendBubble({ sender: 'bot', message: "Hi! Our chat service is temporarily unavailable. Please try WhatsApp below.", time: nowTime() });
      scrollBottom();
    });
  }

  // ── Send message ──────────────────────────────────────
  let sending = false;
  function sendMessage(text) {
    const msg = (text || input.value).trim();
    if (!msg || !sessionId || sending) return;
    sending = true;
    input.value = '';
    autoGrow();

    // Show typing indicator immediately
    typing.style.display = 'flex';
    scrollBottom();

    const body = '_csrf=' + encodeURIComponent(CSRF_TOKEN)
               + '&session_id=' + sessionId
               + '&message=' + encodeURIComponent(msg)
               + '&last_id=' + lastMsgId;

    fetch(SITE_URL + '/chat/send', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body
    })
    .then(r => {
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return r.json();
    })
    .then(res => {
      typing.style.display = 'none';
      sending = false;
      if (res.success && res.messages) {
        renderMessages(res.messages);
      } else if (!res.success) {
        appendBubble({ sender: 'bot', message: res.message || 'Something went wrong. Please try again.', time: nowTime() });
        scrollBottom();
      }
    })
    .catch(() => {
      typing.style.display = 'none';
      sending = false;
      appendBubble({ sender: 'bot', message: "Couldn't reach the server. Please check your connection.", time: nowTime() });
      scrollBottom();
    });
  }

  sendBtn.addEventListener('click', () => sendMessage());
  input.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
  });
  input.addEventListener('input', autoGrow);

  function autoGrow() {
    input.style.height = 'auto';
    input.style.height = Math.min(input.scrollHeight, 80) + 'px';
  }

  // ── Quick actions ─────────────────────────────────────
  document.querySelectorAll('.chat-action-btn').forEach(b => {
    b.addEventListener('click', function () {
      const msg = this.dataset.msg;
      if (!isOpen) openChat();
      if (!sessionId) {
        // Wait for session to be established before sending
        const check = setInterval(() => { if (sessionId) { clearInterval(check); sendMessage(msg); } }, 150);
        setTimeout(() => clearInterval(check), 5000); // give up after 5s
      } else {
        sendMessage(msg);
      }
    });
  });

  // ── Talk to Human ─────────────────────────────────────
  document.getElementById('humanBtn').addEventListener('click', () => {
    sendMessage('I want to talk to a human agent');
  });

  // ── Render ────────────────────────────────────────────
  function renderMessages(msgs) {
    if (!msgs || !msgs.length) return;
    msgs.forEach(m => {
      if (m.id <= lastMsgId) return;
      lastMsgId = m.id;
      appendBubble(m);
      if (!isOpen && m.sender !== 'user') {
        unread++;
        badge.textContent = unread;
        badge.classList.add('show');
      }
    });
    scrollBottom();
  }

  function appendBubble(m) {
    const isUser = m.sender === 'user';
    const row = document.createElement('div');
    row.className = 'cm-row' + (isUser ? ' user-row' : '') + ' cm-new';
    requestAnimationFrame(() => row.classList.add('cm-visible'));

    let avatarHtml = '';
    if (!isUser) {
      const cls = m.sender === 'admin' ? 'admin-avatar' : 'bot-avatar';
      const ico  = m.sender === 'admin' ? 'fa-circle-user' : 'fa-headset';
      avatarHtml = `<div class="cm-avatar ${cls}"><i class="fa ${ico}" style="font-size:11px;"></i></div>`;
    }

    const text = String(m.message).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>').replace(/\*\*(.+?)\*\*/g,'<strong>$1</strong>');

    row.innerHTML = avatarHtml + `
      <div>
        <div class="cm-bubble ${m.sender}-bubble">${text}</div>
        <div class="cm-time">${m.time || nowTime()}</div>
      </div>`;
    msgArea.appendChild(row);
  }

  function scrollBottom() { msgArea.scrollTop = msgArea.scrollHeight; }
  function nowTime() { return new Date().toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'}); }

  // ── Poll for admin replies ────────────────────────────
  function startPolling() {
    clearInterval(polling);
    polling = setInterval(() => {
      if (!sessionId) return;
      fetch(SITE_URL + '/chat/poll?session_id=' + sessionId + '&last_id=' + lastMsgId)
        .then(r => r.json()).then(res => { if (res.success) renderMessages(res.messages); });
    }, 6000);
  }

  // Auto-open on return visit (if session was active)
  // (intentionally not auto-opening to avoid annoyance)
})();
</script>
</body>
</html>
