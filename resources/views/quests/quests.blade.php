<html>
<head>
    <title>Quests</title>
</head>
<body>
    <h1>Welcome, {{ Auth::user()->name }}!</h1>
    <h1>Quests</h1>
    <ul>
        @foreach ($quests as $quest)
            <li>
                <a href="{{ route('quests.show', $quest) }}">{{ $quest->title }}</a>
            </li>
        @endforeach
    </ul>
</body>
</html>