<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>@yield('title', 'SVRS Coin')</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('admin/img/favicon.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        /* ═══════════════════════════════════════════
           CSS VARIABLES — App Color Scheme
        ═══════════════════════════════════════════ */
        :root {
            --bg: #050B18;
            --bg-card: #0D1626;
            --bg-card2: #111C30;
            --bg-card3: #0A1220;
            --gold: #F0A500;
            --gold-light: #FFCC44;
            --gold-dark: #B87800;
            --accent: #00D4AA;
            --accent-blue: #3B82F6;
            --red: #EF4444;
            --green: #10B981;
            --white: #FFFFFF;
            --white70: rgba(255, 255, 255, 0.70);
            --white40: rgba(255, 255, 255, 0.40);
            --white15: rgba(255, 255, 255, 0.08);
            --border: #1E2D45;
            --border2: rgba(30, 45, 69, 0.6);
            --muted: #6B7A9A;
            --nav-h: 64px;
            --bottom-h: 72px;
            --max-w: 430px;
            --radius: 16px;
            --radius-sm: 10px;
            --radius-xs: 8px;
        }

        /* ═══════════════════════════════════════════
           RESET & BASE
        ═══════════════════════════════════════════ */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            background: #020810;
            min-height: 100%;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--white);
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            /* Center the app shell on desktop */
            display: flex;
            justify-content: center;
        }

        /* ═══════════════════════════════════════════
           APP SHELL — phone-like container
        ═══════════════════════════════════════════ */
        #app-shell {
            width: 100%;
            max-width: var(--max-w);
            min-height: 100vh;
            background: var(--bg);
            position: relative;
            display: flex;
            flex-direction: column;
            /* Subtle phone shadow on desktop */
            box-shadow: 0 0 60px rgba(0, 0, 0, 0.8);
        }

        /* ═══════════════════════════════════════════
           TOP NAV BAR
        ═══════════════════════════════════════════ */
        #top-nav {
            height: var(--nav-h);
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 20px;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(12px);
        }

        #top-nav .nav-back {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--white15);
            border-radius: 10px;
            color: var(--white);
            text-decoration: none;
            font-size: 16px;
            flex-shrink: 0;
            transition: background 0.2s;
        }

        #top-nav .nav-back:hover {
            background: var(--border);
        }

        #top-nav .nav-title {
            flex: 1;
            font-size: 17px;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 0.2px;
        }

        #top-nav .nav-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        #top-nav .nav-action-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--white15);
            border-radius: 10px;
            color: var(--white70);
            border: none;
            cursor: pointer;
            font-size: 15px;
            text-decoration: none;
            transition: background 0.2s;
        }

        #top-nav .nav-action-btn:hover {
            background: var(--border);
            color: var(--white);
        }

        /* Avatar in nav */
        #top-nav .nav-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: #000;
            flex-shrink: 0;
            overflow: hidden;
        }

        #top-nav .nav-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Member code badge in nav */
        #top-nav .nav-member-code {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(240, 165, 0, 0.12);
            border: 1px solid rgba(240, 165, 0, 0.3);
            border-radius: 20px;
            padding: 4px 10px;
            font-size: 12px;
            font-family: 'Space Mono', monospace;
            color: var(--gold);
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
        }

        #top-nav .nav-member-code:hover {
            background: rgba(240, 165, 0, 0.2);
        }

        #top-nav .nav-member-code i {
            font-size: 10px;
            color: var(--gold-dark);
        }

        /* Logout btn */
        #top-nav .nav-logout {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(239, 68, 68, 0.1);
            border-radius: 10px;
            color: var(--red);
            border: none;
            cursor: pointer;
            font-size: 15px;
            text-decoration: none;
            transition: background 0.2s;
        }

        #top-nav .nav-logout:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        /* ═══════════════════════════════════════════
           PAGE SCROLL AREA
        ═══════════════════════════════════════════ */
        #page-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: calc(var(--bottom-h) + 16px);
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
        }

        #page-content::-webkit-scrollbar {
            display: none;
        }

        /* ═══════════════════════════════════════════
           BOTTOM TAB BAR
        ═══════════════════════════════════════════ */
        #bottom-nav {
            height: var(--bottom-h);
            background: var(--bg-card);
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 8px;
            position: sticky;
            bottom: 0;
            z-index: 100;
        }

        .tab-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 8px 4px;
            text-decoration: none;
            color: var(--muted);
            border-radius: 12px;
            transition: all 0.2s ease;
            position: relative;
        }

        .tab-item.active {
            color: var(--gold);
        }

        .tab-item .tab-icon {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            font-size: 20px;
            transition: all 0.2s ease;
        }

        .tab-item.active .tab-icon {
            background: rgba(240, 165, 0, 0.15);
            color: var(--gold);
        }

        .tab-item span.tab-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        /* ═══════════════════════════════════════════
           COMMON COMPONENTS
        ═══════════════════════════════════════════ */

        /* Section Label */
        .section-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            font-weight: 700;
            color: var(--white);
            padding: 0 20px;
            margin: 24px 0 12px;
        }

        .section-label::before {
            content: '';
            width: 3px;
            height: 18px;
            background: var(--gold);
            border-radius: 2px;
            flex-shrink: 0;
        }

        /* Cards */
        .app-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .app-card-inner {
            padding: 16px;
        }

        /* Gold gradient card */
        .gold-card {
            background: linear-gradient(135deg, rgba(240, 165, 0, 0.15) 0%, rgba(184, 120, 0, 0.08) 100%);
            border: 1px solid rgba(240, 165, 0, 0.25);
            border-radius: var(--radius);
        }

        /* Accent (teal) card */
        .accent-card {
            background: linear-gradient(135deg, rgba(0, 212, 170, 0.12) 0%, rgba(0, 180, 140, 0.05) 100%);
            border: 1px solid rgba(0, 212, 170, 0.2);
            border-radius: var(--radius);
        }

        /* Blue card */
        .blue-card {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(37, 99, 235, 0.08) 100%);
            border: 1px solid rgba(59, 130, 246, 0.25);
            border-radius: var(--radius);
        }

        /* List items */
        .list-row {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            gap: 12px;
            border-bottom: 1px solid var(--border2);
            text-decoration: none;
            color: var(--white);
            transition: background 0.15s;
        }

        .list-row:last-child {
            border-bottom: none;
        }

        .list-row:hover {
            background: var(--white15);
        }

        .list-row:active {
            background: var(--border2);
        }

        .list-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .list-icon.gold {
            background: rgba(240, 165, 0, 0.15);
            color: var(--gold);
        }

        .list-icon.teal {
            background: rgba(0, 212, 170, 0.15);
            color: var(--accent);
        }

        .list-icon.blue {
            background: rgba(59, 130, 246, 0.15);
            color: var(--accent-blue);
        }

        .list-icon.red {
            background: rgba(239, 68, 68, 0.15);
            color: var(--red);
        }

        .list-icon.green {
            background: rgba(16, 185, 129, 0.15);
            color: var(--green);
        }

        .list-body {
            flex: 1;
            min-width: 0;
        }

        .list-body .title {
            font-size: 14px;
            font-weight: 600;
            color: var(--white);
        }

        .list-body .sub {
            font-size: 12px;
            color: var(--muted);
            margin-top: 2px;
        }

        .list-chevron {
            color: var(--muted);
            font-size: 13px;
        }

        /* Stat pill */
        .stat-pill {
            background: var(--white15);
            border-radius: 20px;
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .stat-pill.gold {
            background: rgba(240, 165, 0, 0.12);
            color: var(--gold);
        }

        .stat-pill.teal {
            background: rgba(0, 212, 170, 0.12);
            color: var(--accent);
        }

        .stat-pill.green {
            background: rgba(16, 185, 129, 0.12);
            color: var(--green);
        }

        .stat-pill.red {
            background: rgba(239, 68, 68, 0.12);
            color: var(--red);
        }

        .stat-pill.blue {
            background: rgba(59, 130, 246, 0.12);
            color: var(--accent-blue);
        }

        /* Button styles */
        .btn-app {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 16px;
            border-radius: var(--radius);
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.3px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-app:active {
            transform: scale(0.98);
        }

        .btn-gold {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
            color: #000;
            box-shadow: 0 4px 20px rgba(240, 165, 0, 0.3);
        }

        .btn-gold:hover {
            box-shadow: 0 6px 28px rgba(240, 165, 0, 0.45);
            color: #000;
        }

        .btn-teal {
            background: linear-gradient(135deg, var(--accent) 0%, #00A888 100%);
            color: #000;
            box-shadow: 0 4px 20px rgba(0, 212, 170, 0.25);
        }

        .btn-outline-gold {
            background: rgba(240, 165, 0, 0.08);
            color: var(--gold);
            border: 1.5px solid rgba(240, 165, 0, 0.35);
        }

        .btn-outline-gold:hover {
            background: rgba(240, 165, 0, 0.15);
            color: var(--gold);
        }

        .btn-outline-teal {
            background: rgba(0, 212, 170, 0.08);
            color: var(--accent);
            border: 1.5px solid rgba(0, 212, 170, 0.3);
        }

        .btn-sm-app {
            padding: 10px 16px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        /* Progress bar */
        .progress-app {
            height: 8px;
            background: var(--border);
            border-radius: 99px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
            transition: width 0.5s ease;
        }

        .progress-fill.teal {
            background: linear-gradient(90deg, var(--accent), #00F0C4);
        }

        .progress-fill.green {
            background: linear-gradient(90deg, var(--green), #34D399);
        }

        /* Info row */
        .info-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--border2);
            font-size: 14px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row .key {
            color: var(--muted);
        }

        .info-row .val {
            color: var(--white);
            font-weight: 600;
        }

        /* Alert box */
        .alert-app {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: var(--radius-sm);
            padding: 12px 14px;
            font-size: 13px;
            color: var(--white70);
            line-height: 1.5;
            margin: 0 20px;
        }

        .alert-app.gold-alert {
            background: rgba(240, 165, 0, 0.08);
            border-color: rgba(240, 165, 0, 0.2);
        }

        .alert-app i {
            font-size: 15px;
            margin-top: 1px;
            flex-shrink: 0;
        }

        /* Input field */
        .input-app {
            width: 100%;
            background: var(--bg-card2);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 14px 16px;
            font-size: 15px;
            color: var(--white);
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.2s;
            outline: none;
        }

        .input-app:focus {
            border-color: var(--gold);
        }

        .input-app::placeholder {
            color: var(--muted);
        }

        .input-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 6px;
            display: block;
        }

        /* Badge */
        .badge-app {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-gold {
            background: rgba(240, 165, 0, 0.15);
            color: var(--gold);
        }

        .badge-teal {
            background: rgba(0, 212, 170, 0.15);
            color: var(--accent);
        }

        .badge-green {
            background: rgba(16, 185, 129, 0.15);
            color: var(--green);
        }

        .badge-red {
            background: rgba(239, 68, 68, 0.15);
            color: var(--red);
        }

        .badge-muted {
            background: var(--white15);
            color: var(--muted);
        }

        .badge-blue {
            background: rgba(59, 130, 246, 0.15);
            color: var(--accent-blue);
        }

        /* Segment control (tab switcher) */
        .segment-ctrl {
            display: flex;
            background: var(--bg-card2);
            border-radius: var(--radius-sm);
            padding: 4px;
            gap: 2px;
        }

        .segment-ctrl button {
            flex: 1;
            padding: 9px 8px;
            border: none;
            background: transparent;
            color: var(--muted);
            font-size: 13px;
            font-weight: 600;
            border-radius: 7px;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
        }

        .segment-ctrl button.active {
            background: var(--bg-card);
            color: var(--gold);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        /* Divider */
        .divider {
            height: 1px;
            background: var(--border);
            margin: 0;
        }

        /* Page padding shortcut */
        .px {
            padding-left: 20px;
            padding-right: 20px;
        }

        .py {
            padding-top: 16px;
            padding-bottom: 16px;
        }

        /* ═══════════════════════════════════════════
           SPINNER / LOADING
        ═══════════════════════════════════════════ */
        .spin {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(0, 0, 0, 0.2);
            border-top-color: #000;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ═══════════════════════════════════════════
           TOASTR OVERRIDE
        ═══════════════════════════════════════════ */
        #toast-container {
            max-width: var(--max-w);
        }

        #toast-container>div {
            border-radius: 12px !important;
            font-family: 'DM Sans', sans-serif !important;
        }

        /* ═══════════════════════════════════════════
           MODAL / BOTTOM SHEET
        ═══════════════════════════════════════════ */
        .sheet-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 200;
            backdrop-filter: blur(4px);
            justify-content: center;
            align-items: flex-end;
        }

        .sheet-overlay.open {
            display: flex;
        }

        .bottom-sheet {
            width: 100%;
            max-width: var(--max-w);
            background: var(--bg-card);
            border-radius: 24px 24px 0 0;
            padding: 20px;
            animation: slideUp 0.3s ease;
            max-height: 90vh;
            overflow-y: auto;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .sheet-handle {
            width: 40px;
            height: 4px;
            background: var(--border);
            border-radius: 99px;
            margin: 0 auto 20px;
        }

        .sheet-title {
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        /* ═══════════════════════════════════════════
           TABLE (for reports)
        ═══════════════════════════════════════════ */
        .app-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .app-table th {
            background: var(--bg-card2);
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 12px;
            text-align: left;
        }

        .app-table td {
            padding: 12px 12px;
            border-bottom: 1px solid var(--border2);
            color: var(--white70);
            vertical-align: middle;
        }

        .app-table tr:last-child td {
            border-bottom: none;
        }

        .app-table tr:hover td {
            background: var(--white15);
        }

        /* ═══════════════════════════════════════════
           EMPTY STATE
        ═══════════════════════════════════════════ */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: var(--muted);
        }

        .empty-state i {
            font-size: 48px;
            opacity: 0.4;
            display: block;
            margin-bottom: 12px;
        }

        .empty-state p {
            font-size: 14px;
        }

        /* ═══════════════════════════════════════════
           STATUS BAR (cosmetic, desktop only)
        ═══════════════════════════════════════════ */
        @media (min-width: 431px) {
            body {
                background: #010509;
                padding: 0;
            }

            #app-shell {
                margin: 0 auto;
                border-radius: 0;
            }
        }

        /* ═══════════════════════════════════════════
           YIELD-SPECIFIC UTILITIES
        ═══════════════════════════════════════════ */
        .big-amount {
            font-size: 36px;
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--white);
        }

        .big-amount.gold {
            color: var(--gold);
        }

        .big-amount.teal {
            color: var(--accent);
        }

        .amount-sub {
            font-size: 13px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 4px;
        }

        .amount-sub .approx {
            color: var(--gold);
            font-weight: 600;
        }

        /* Percentage quick picks */
        .pct-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-top: 10px;
        }

        .pct-btn {
            background: var(--bg-card2);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-xs);
            padding: 10px 4px;
            font-size: 13px;
            font-weight: 700;
            color: var(--muted);
            cursor: pointer;
            text-align: center;
            transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
        }

        .pct-btn:hover {
            border-color: var(--gold);
            color: var(--gold);
        }

        .pct-btn.active {
            background: rgba(240, 165, 0, 0.12);
            border-color: var(--gold);
            color: var(--gold);
        }

        /* Copy row */
        .copy-row {
            display: flex;
            align-items: center;
            background: var(--bg-card2);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            overflow: hidden;
        }

        .copy-row input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 13px 14px;
            color: var(--gold);
            font-family: 'Space Mono', monospace;
            font-size: 13px;
            outline: none;
        }

        .copy-row button {
            border: none;
            background: rgba(240, 165, 0, 0.12);
            color: var(--gold);
            padding: 0 16px;
            height: 46px;
            cursor: pointer;
            font-size: 15px;
            transition: background 0.2s;
            border-left: 1.5px solid var(--border);
        }

        .copy-row button:hover {
            background: rgba(240, 165, 0, 0.22);
        }

        /* Milestone card */
        .milestone-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px;
            position: relative;
            overflow: hidden;
        }

        .milestone-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
        }

        .milestone-card.completed {
            border-color: rgba(16, 185, 129, 0.3);
        }

        .milestone-card.completed::before {
            background: linear-gradient(90deg, var(--green), #34D399);
        }

        /* Quick action grid */
        .quick-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding: 0 20px;
        }

        .quick-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--white);
            transition: all 0.2s;
        }

        .quick-card:hover {
            border-color: rgba(240, 165, 0, 0.3);
            background: rgba(240, 165, 0, 0.04);
        }

        .quick-card:active {
            transform: scale(0.97);
        }

        .quick-card .qc-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .quick-card .qc-body .qc-title {
            font-size: 13px;
            font-weight: 700;
        }

        .quick-card .qc-body .qc-sub {
            font-size: 11px;
            color: var(--muted);
            margin-top: 2px;
        }

        .quick-card .qc-arrow {
            margin-left: auto;
            color: var(--muted);
            font-size: 12px;
        }

        /* Refer & Earn upgrade banner */
        .upgrade-banner {
            margin: 0 20px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(99, 102, 241, 0.08) 100%);
            border: 1px solid rgba(59, 130, 246, 0.25);
            border-radius: var(--radius);
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .upgrade-banner:hover {
            border-color: rgba(59, 130, 246, 0.45);
        }

        .upgrade-banner .ub-icon {
            width: 44px;
            height: 44px;
            background: rgba(59, 130, 246, 0.15);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: var(--accent-blue);
            flex-shrink: 0;
        }

        .upgrade-banner .ub-body {
            flex: 1;
        }

        .upgrade-banner .ub-body .ub-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--white);
        }

        .upgrade-banner .ub-body .ub-sub {
            font-size: 12px;
            color: var(--muted);
            margin-top: 2px;
        }

        .upgrade-banner .ub-arrow {
            color: var(--accent-blue);
        }

        /* Floating scroll to top — hidden for now */
    </style>

    @stack('styles')
</head>

<body>

    <div id="app-shell">

        {{-- TOP NAV --}}
        <nav id="top-nav">
            @hasSection('nav-back')
                <a href="@yield('nav-back-url', 'javascript:history.back()')" class="nav-back">
                    <i class="fa fa-arrow-left"></i>
                </a>
            @else
                {{-- Dashboard: show avatar --}}
                <div class="nav-avatar">
                    @php $u = auth()->user(); @endphp
                    @if($u->profile_image)
                        <img src="{{ asset($u->profile_image) }}" alt="">
                    @else
                        {{ strtoupper(substr($u->first_name, 0, 1)) }}
                    @endif
                </div>
            @endif

            <div class="nav-title">@yield('nav-title', config('app.name', 'SVRS Coin'))</div>

            <div class="nav-actions">
                @yield('nav-actions')

                @unless(View::hasSection('hide-member-code'))
                    <div class="nav-member-code" onclick="copyMemberCode('{{ auth()->user()->member_code ?? '' }}')"
                        title="Copy member code">
                        {{ auth()->user()->member_code ?? '' }}
                        <i class="fa fa-copy"></i>
                    </div>
                @endunless

                <a href="{{ route('member.logout') }}" class="nav-logout" title="Logout">
                    <i class="fa fa-sign-out-alt"></i>
                </a>
            </div>
        </nav>

        {{-- PAGE CONTENT --}}
        <main id="page-content">
            @yield('content')
        </main>

        {{-- BOTTOM TAB BAR --}}
        <nav id="bottom-nav">
            @php
                $route = Route::currentRouteName();
                $coinActive = str_contains($route, 'coin');
                $dashActive = str_contains($route, 'dashboard');
                $walletActive = str_contains($route, 'wallet') || str_contains($route, 'my.wallet');
                $profileActive = str_contains($route, 'profile') || str_contains($route, 'membership') || str_contains($route, 'reward') || str_contains($route, 'referral');
            @endphp

            <a href="{{ route('member.coin') }}" class="tab-item {{ $coinActive ? 'active' : '' }}">
                <div class="tab-icon"><i class="fa fa-bitcoin-sign"></i></div>
                <span class="tab-label">Coin</span>
            </a>

            <a href="{{ route('member.dashboard') }}" class="tab-item {{ $dashActive ? 'active' : '' }}">
                <div class="tab-icon"><i class="fa fa-th-large" style="font-size:17px;"></i></div>
                <span class="tab-label">Dashboard</span>
            </a>

            <a href="{{ route('member.my.wallet') }}" class="tab-item {{ $walletActive ? 'active' : '' }}">
                <div class="tab-icon"><i class="fa fa-wallet"></i></div>
                <span class="tab-label">Wallet</span>
            </a>

            <a href="{{ route('member.profile') }}" class="tab-item {{ $profileActive ? 'active' : '' }}">
                <div class="tab-icon"><i class="fa fa-folder-open" style="font-size:17px;"></i></div>
                <span class="tab-label">Profile</span>
            </a>
        </nav>

    </div>{{-- #app-shell --}}

    <!-- Scripts -->
    <script src="{{ asset('admin/js/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Toastr config
        toastr.options = {
            positionClass: 'toast-top-center',
            timeOut: 3000,
            closeButton: false,
            progressBar: true,
            newestOnTop: true,
        };

        // Copy member code
        function copyMemberCode(code) {
            if (!code) return;
            navigator.clipboard.writeText(code).then(() => toastr.success('Member code copied!'));
        }

        // Generic copy
        function copyText(value) {
            navigator.clipboard.writeText(value).then(() => toastr.success('Copied!'));
        }

        // Bottom sheet helpers
        function openSheet(id) {
            document.getElementById(id).classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeSheet(id) {
            document.getElementById(id).classList.remove('open');
            document.body.style.overflow = '';
        }
        // Close on backdrop click
        document.querySelectorAll('.sheet-overlay').forEach(el => {
            el.addEventListener('click', function (e) {
                if (e.target === this) closeSheet(this.id);
            });
        });
    </script>

    @stack('scripts')

</body>

</html>