<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LevelUpp')</title>
    <link rel="stylesheet" href="/build/assets/app.css">
</head>
<body>
    <nav class="navbar bg-base-100">
        <div class="navbar-start">
            <a href="/" class="btn btn-ghost normal-case text-xl">LevelUpp</a>
        </div>
        <div class="navbar-end gap-2">
            @auth
            <span class="text-sm">{{ auth()->user()->name }}</span>
            <form method="POST" action="/logout" class="inline">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm">Logout</button>
            </form>
            @else
            <a href="/login" class="btn btn-ghost btn-sm">Sign In</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Sign Up</a>
            @endauth
        </div>
    </nav>

    <main class="container mx-auto mt-4">
        @yield('content')
    </main>

    <script src="/build/assets/app.js"></script>
</body>
</html>