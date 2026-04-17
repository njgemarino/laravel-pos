<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Syne:wght@700&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'DM Sans', sans-serif; box-sizing: border-box; }

        body { background: #f5f4f0; margin: 0; }

        .product-card {
    transition: transform 0.2s ease;
}

.product-card:hover {
    transform: scale(1.03);
}

        /* ── SIDEBAR ── */
        .pos-sidebar {
            position: fixed; top: 0; left: 0;
            width: 220px; height: 100vh;
            background: #0f0f0f;
            display: flex; flex-direction: column;
            z-index: 50; overflow-y: auto;
        }

        .sb-brand {
            padding: 22px 18px 16px;
            border-bottom: 1px solid #1e1e1e;
        }
        .sb-logo-row {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 16px;
        }
        .sb-logo-icon {
            width: 32px; height: 32px;
            background: #c8f55a; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sb-logo-icon svg { width: 16px; height: 16px; }
        .sb-logo-label h1 {
            font-family: 'Syne', sans-serif;
            font-size: 14px; color: #f0f0f0;
            margin: 0; line-height: 1.2;
        }
        .sb-logo-label p { font-size: 10px; color: #444; margin: 0; }

        .sb-user-row { display: flex; align-items: center; gap: 10px; }
        .sb-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: #1e1e1e;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 600; color: #c8f55a;
            flex-shrink: 0; text-transform: uppercase;
        }
        .sb-user-name { font-size: 13px; font-weight: 500; color: #e0e0e0; margin: 0; line-height: 1.3; }
        .sb-user-role { font-size: 10px; color: #c8f55a; margin: 0; text-transform: capitalize; }

        /* ── NAV ── */
        .pos-nav { flex: 1; padding: 10px 10px; }
        .nav-section-label {
            font-size: 9px; color: #333;
            text-transform: uppercase; letter-spacing: 0.1em;
            padding: 10px 10px 4px;
        }
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 10px; border-radius: 8px;
            font-size: 12px; color: #666;
            text-decoration: none; margin-bottom: 1px;
            transition: background 0.15s, color 0.15s;
        }
        .nav-link:hover { background: #1a1a1a; color: #d0d0d0; }
        .nav-link.active { background: #1a1a1a; color: #c8f55a; }
        .nav-link svg { width: 14px; height: 14px; flex-shrink: 0; opacity: 0.6; }
        .nav-link:hover svg, .nav-link.active svg { opacity: 1; }

        /* ── FOOTER ── */
        .sb-footer { padding: 12px 10px; border-top: 1px solid #1e1e1e; }
        .sb-logout {
            width: 100%; padding: 9px;
            background: transparent;
            border: 1px solid #2a1a1a; border-radius: 8px;
            color: #f87171; font-size: 12px;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer; transition: background 0.15s;
        }
        .sb-logout:hover { background: #1a0a0a; }

        /* ── MAIN ── */
        .pos-main {
            margin-left: 220px;
            padding: 36px 40px;
            min-height: 100vh;
        }

        /* ── ALERTS ── */
        .alert {
            border-radius: 10px; padding: 12px 16px;
            font-size: 13px; margin-bottom: 20px;
        }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }

        @media print {
            .pos-sidebar, .sb-footer { display: none !important; }
            body { background: white !important; }
            .pos-main { margin-left: 0 !important; padding: 0 !important; }
        }
    </style>
</head>
<body>
<div style="display:flex; min-height:100vh;">

    {{-- ── SIDEBAR ── --}}
    <aside class="pos-sidebar no-print">

        <div class="sb-brand">
            {{-- Logo --}}
            <div class="sb-logo-row">
                <div class="sb-logo-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#0f0f0f" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                </div>
                <div class="sb-logo-label">
                    <h1>POS System</h1>
                    <p>Gemarino Laravel Project</p>
                </div>
            </div>

            {{-- User --}}
            <div class="sb-user-row">
                <div class="sb-avatar">
                    {{ substr(auth()->user()->name ?? 'G', 0, 2) }}
                </div>
                <div>
                    <p class="sb-user-name">{{ auth()->user()->name ?? 'Guest' }}</p>
                    <p class="sb-user-role">{{ auth()->user()->role ?? '' }}</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="pos-nav">

            @auth
                @if(auth()->user()->role === 'admin')

                    <div class="nav-section-label">Main</div>

                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                        Products
                    </a>
                    <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->routeIs('inventory.index') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
                        Inventory
                    </a>
                    <a href="{{ route('inventory.logs') }}" class="nav-link {{ request()->routeIs('inventory.logs') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        Inventory Logs
                    </a>
                    <a href="{{ route('activity-logs.index') }}"
                    class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                        Activity Logs
                    </a>

                    <div class="nav-section-label">Sales</div>

                    <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                        POS Sales
                    </a>
                    <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        Transactions
                    </a>

                    <div class="nav-section-label">Reports</div>

                    <a href="{{ route('reports.daily-sales') }}" class="nav-link {{ request()->routeIs('reports.daily-sales') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Daily Report
                    </a>
                    <a href="{{ route('reports.monthly-sales') }}" class="nav-link {{ request()->routeIs('reports.monthly-sales') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                        Monthly Report
                    </a>
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                        Users
                    </a>

                @endif
            @endauth

            @auth
                @if(auth()->user()->role === 'cashier')
                    <div class="nav-section-label">Sales</div>
                    <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                        POS Sales
                    </a>
                    <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        Transactions
                    </a>
                @endif
            @endauth

            @auth
                @if(auth()->user()->role === 'inventory')
                    <div class="nav-section-label">Inventory</div>
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                        Products
                    </a>
                    <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->routeIs('inventory.index') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/></svg>
                        Stock Update
                    </a>
                    <a href="{{ route('inventory.logs') }}" class="nav-link {{ request()->routeIs('inventory.logs') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        Inventory Logs
                    </a>
                @endif
            @endauth

        </nav>

        <div class="sb-footer no-print">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sb-logout">Logout</button>
            </form>
        </div>
    </aside>

    {{-- ── MAIN CONTENT ── --}}
    <main class="pos-main" style="flex:1;">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin:0; padding-left:16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

</div>
</body>
</html> 