<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Mondals E-Commerce - Best online shopping in Bangladesh')">
    <title>@yield('title', 'Home') | {{ \App\Models\Setting::get('site_name', 'Mondals E-Commerce') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --lm-primary: {{ themeValue('primary_color', '#f85606') }};
            --lm-secondary: {{ themeValue('secondary_color', '#0f1111') }};
            --lm-accent: {{ themeValue('accent_color', '#ff6b35') }};
            --lm-orange-soft: #fff4ee;
            --lm-bg: #eff0f5;
            --lm-card-bg: #ffffff;
            --lm-text: #212121;
            --lm-text-muted: #757575;
            --lm-border: #e0e0e0;
            --lm-success: #00a862;
            --lm-danger: #f44336;
            --lm-star: #ffc107;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--lm-bg);
            color: var(--lm-text);
            font-size: 14px;
            line-height: 1.6;
        }
        a { color: var(--lm-primary); text-decoration: none; transition: color 0.2s; }
        a:hover { color: #d94400; }

        /* Override Bootstrap Primary */
        .btn-primary, .bg-primary {
            background-color: var(--lm-primary) !important;
            border-color: var(--lm-primary) !important;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #d94400 !important;
            border-color: #d94400 !important;
        }
        .text-primary { color: var(--lm-primary) !important; }
        .btn-outline-primary {
            color: var(--lm-primary) !important;
            border-color: var(--lm-primary) !important;
        }
        .btn-outline-primary:hover {
            background-color: var(--lm-primary) !important;
            color: #fff !important;
        }

        /* Badge Styles */
        .badge-sale { background: var(--lm-danger); color: #fff; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 2px; }
        .badge-hot { background: var(--lm-primary); color: #fff; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 2px; }

        /* Price Styles */
        .price-current { color: var(--lm-primary); font-weight: 700; font-size: 18px; }
        .price-old { color: var(--lm-text-muted); text-decoration: line-through; font-size: 13px; }
        .price-discount { color: var(--lm-primary); font-size: 12px; font-weight: 500; }

        /* Rounded 4 */
        .rounded-4 { border-radius: 0.75rem !important; }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar { height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: var(--lm-primary); border-radius: 10px; }

        /* Card hover */
        .card-hover { transition: all 0.25s ease; border: 1px solid transparent; }
        .card-hover:hover { box-shadow: 0 2px 12px rgba(0,0,0,.12); transform: translateY(-2px); border-color: var(--lm-primary); }

        /* Skeleton shimmer */
        .shimmer { background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }

        /* Section titles Daraz style */
        .section-title-bar {
            background: #fff;
            padding: 12px 20px;
            border-radius: 4px 4px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--lm-primary);
        }
        .section-title-bar h2 {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--lm-text);
            margin: 0;
        }

        /* Pagination override */
        .pagination .page-link {
            color: var(--lm-text);
            border-radius: 0 !important;
            font-size: 13px;
            padding: 6px 12px;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--lm-primary);
            border-color: var(--lm-primary);
        }

        /* Form control focus */
        .form-control:focus, .form-select:focus {
            border-color: var(--lm-primary);
            box-shadow: 0 0 0 0.2rem rgba(248, 86, 6, 0.15);
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Header Section -->
    @include('partials.header')

    <!-- Main Content Area -->
    <main class="min-vh-100">
        @yield('content')
    </main>

    <!-- Footer Section -->
    @include('partials.footer')

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
