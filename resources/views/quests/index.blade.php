@extends('layout')

@section('title', 'Quests')

@section('content')
    <h1>Welcome, {{ Auth::user()->name }}!</h1>
    <h1>Quests</h1>
    <a href="{{ route('quests.create') }}" class="btn btn-primary">Create New Quest</a>
    <ul class="mt-4">
        @foreach ($quests as $quest)
            <li class="mb-2">
                <a href="{{ route('quests.show', $quest) }}" class="link">{{ $quest->title }}</a>
                <a href="{{ route('quests.edit', $quest) }}" class="btn btn-sm btn-secondary ml-2">Edit</a>
                <form method="POST" action="{{ route('quests.destroy', $quest) }}" class="inline ml-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-error" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection