<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('images/metrologi.png') }}">

        <title>{{ $title ?? config('app.name', 'BP SUML') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

        <!-- Tailwind CDN Fallback -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Alpine.js CDN -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- x-cloak: hide Alpine elements before init -->
        <style> [x-cloak] { display: none !important; } </style>

        <link href="https://jsdelivr.net" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        {{-- Hotwire Turbo --}}
        <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-umd.js"></script>

        <style>
            /* Prevent Transition Flicker on Load */
            .no-transition * {
                transition: none !important;
            }

            /* Suppress Focus Ring Flicker on Refresh */
            *:focus {
                outline: none !important;
                box-shadow: none !important;
            }
        </style>
        <script>
            document.documentElement.classList.add('no-transition');
            window.addEventListener('load', () => {
                setTimeout(() => {
                    document.documentElement.classList.remove('no-transition');
                }, 100);
            });
        </script>

        @stack('head')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <script>
            // ===== ACCOUNT SWITCHER (Shared Logic) =====
            var STORAGE_KEY = 'bpsuml_saved_accounts';
            var CURRENT_USER = {
                id: {{ Auth::id() }},
                name: '{{ addslashes(Auth::user()->name) }}',
                email: '{{ addslashes(Auth::user()->email) }}',
                initials: '{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}',
                role: '{{ Auth::user()->role ?? "user" }}',
                photo: '{{ Auth::user()->profile_photo ? Storage::url(Auth::user()->profile_photo) : "" }}',
                switch_token: '{{ session("switch_token_raw", "") }}'
            };

            function saveCurrentAccount() {
                if (!CURRENT_USER.switch_token) return;
                try {
                    let accounts = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
                    const idx = accounts.findIndex(a => a.id === CURRENT_USER.id);
                    const entry = { ...CURRENT_USER, savedAt: Date.now() };
                    if (idx >= 0) { accounts[idx] = entry; } else { accounts.push(entry); }
                    if (accounts.length > 5) accounts = accounts.slice(-5);
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(accounts));
                } catch(e) { console.error('Failed to save account:', e); }
            }

            document.addEventListener('turbo:load', saveCurrentAccount);
            // Fallback for initial load
            saveCurrentAccount();
        </script>
    </body>
</html>
