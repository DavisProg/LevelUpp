<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LevelUpp')</title>
    <link rel="stylesheet" href="/build/assets/app.css">
    <style>
        header { background: #1e293b; border-bottom: 1px solid #334155; }
        #main-nav { width: 100%; display: flex; flex-wrap: wrap; gap: 0.5rem; }
        #main-nav > button, #main-nav > form > button, #main-nav > span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #334155;
            color: #cbd5e1;
            border: 1px solid #475569;
            border-radius: 0.5rem;
            padding: 0.5rem 0.85rem;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            margin: 0.15rem;
            min-width: 4.5rem;
        }
        #main-nav > button:hover, #main-nav > form > button:hover {
            background: #475569;
            color: #e2e8f0;
        }
        #main-nav > button:focus-visible, #main-nav > form > button:focus-visible {
            outline: 2px solid #7dd3fc;
            outline-offset: 2px;
        }
        #main-nav > span {
            background: transparent;
            border: none;
            color: #cbd5e1;
        }
        #mobile-nav-toggle {
            display: none;
            border: 1px solid #475569;
            background: #334155;
            color: #cbd5e1;
        }
        #mobile-nav-toggle:hover { background: #475569; }

        @media (max-width: 768px) {
            #mobile-nav-toggle { display: inline-flex; }
            #main-nav { flex-direction: row; }
        }
    </style>
</head>
<body class="bg-slate-950 text-sky-300 font-sans" style="background-color: #0f172a; color: #87ceeb;">
    <header class="sticky top-0 z-50 bg-slate-800 shadow-sm" style="background-color: #1e293b;">
        <div class="mx-auto flex w-full flex-wrap items-center justify-between gap-2 px-4 py-3 max-w-6xl">
            <div class="flex items-center gap-2">
                <span class="text-xl font-bold text-sky-300">LevelUpp</span>
                <button id="mobile-nav-toggle" class="md:hidden rounded-md bg-slate-700 px-2 py-1 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-sky-300" aria-label="Toggle navigation">
                    <svg class="h-5 w-5 text-sky-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M3 5h14a1 1 0 110 2H3a1 1 0 010-2zm0 4h14a1 1 0 110 2H3a1 1 0 010-2zm0 4h14a1 1 0 110 2H3a1 1 0 010-2z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <nav id="main-nav" class="hidden w-full flex-row flex-wrap gap-2 border-t border-slate-700 px-4 py-3 md:flex md:w-auto md:border-none md:px-0 md:py-0">
                @auth
                    <button type="button" onclick="window.location.href='/'" class="rounded-md bg-slate-700 px-4 py-2 text-sm md:text-base font-medium text-sky-100 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-sky-300">Home</button>
                    <button type="button" onclick="window.location.href='{{ route('settings') }}'" class="rounded-md bg-slate-700 px-4 py-2 text-sm md:text-base font-medium text-sky-100 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-sky-300">Settings</button>
                    @if(auth()->user()->isAdmin())
                        <button type="button" onclick="window.location.href='{{ route('quests.index') }}'" class="rounded-md bg-slate-700 px-4 py-2 text-sm md:text-base font-medium text-sky-100 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-sky-300">Quests</button>
                    @endif
                    <span class="rounded-md px-4 py-2 text-sm md:text-base text-sky-200">{{ auth()->user()->name }}</span>
                    <form method="POST" action="/logout" class="inline-flex">
                        @csrf
                        <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm md:text-base font-medium text-white hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-300">Logout</button>
                    </form>
                @else
                    <button type="button" onclick="window.location.href='/login'" class="rounded-md bg-slate-700 px-4 py-2 text-sm md:text-base font-medium text-sky-100 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-sky-300">Sign In</button>
                    <button type="button" onclick="window.location.href='{{ route('register') }}'" class="rounded-md bg-indigo-600 px-4 py-2 text-sm md:text-base font-medium text-white hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-300">Sign Up</button>
                @endauth
            </nav>
        </div>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('mobile-nav-toggle');
            const mainNav = document.getElementById('main-nav');
            if (toggle && mainNav) {
                toggle.addEventListener('click', function() {
                    mainNav.classList.toggle('hidden');
                });
            }
        });
    </script>

    <main class="mx-auto mt-4 px-3 sm:px-4 lg:px-6 max-w-6xl text-sky-300">
        @yield('content')
    </main>

    <script src="/build/assets/app.js"></script>
</body>
</html>