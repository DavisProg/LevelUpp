@extends('layout')

@section('title', 'Quests')

@section('content')
    <section style="max-width: 960px; margin: 0 auto; padding: 1rem;">
        <header style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #4a5568; border-radius: 0.5rem; background: #2d3748;">
            <h1 style="margin: 0; color: #87ceeb; font-size: 1.75rem;">Quest Management</h1>
            <p style="margin: 0.5rem 0 0; color: #a0aec0;">Admin controls for creating, editing, and removing quests with a consistent dashboard experience.</p>
        </header>

        <div style="margin-bottom: 1rem; display: flex; flex-wrap: wrap; gap: 0.75rem;">
            <a href="{{ route('quests.create') }}" style="display: inline-flex; align-items: center; justify-content: center; background: #4a90e2; color: #fff; border: none; border-radius: 0.4rem; padding: 0.6rem 1rem; font-weight: 600; text-decoration: none;">Create New Quest</a>
        </div>

        <div style="background: #2d3748; border: 1px solid #4a5568; border-radius: 0.5rem; padding: 1rem;">
            @if ($quests->isEmpty())
                <p style="color: #a0aec0;">No quests created yet. Use the button above to add a new quest.</p>
            @else
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 0.75rem;">
                    @foreach ($quests as $quest)
                        <article style="background: #1a2332; border: 1px solid #4a5568; border-radius: 0.5rem; padding: 1rem; color: #87ceeb;">
                            <h2 style="margin: 0 0 0.5rem 0; font-size: 1.1rem; color: #4a90e2;">{{ $quest->title }}</h2>
                            <p style="margin: 0 0 0.75rem 0; color: #cbd5e1; font-size: 0.9rem; min-height: 40px;">{{ \Illuminate\Support\Str::limit($quest->description ?? 'No description', 100) }}</p>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.45rem; align-items: center;">
                                <a href="{{ route('quests.show', $quest) }}" style="background: #0ea5e9; color: white; border-radius: 0.35rem; padding: 0.4rem 0.7rem; font-size: 0.85rem; text-decoration: none;">View</a>
                                <a href="{{ route('quests.edit', $quest) }}" style="background: #4f46e5; color: white; border-radius: 0.35rem; padding: 0.4rem 0.7rem; font-size: 0.85rem; text-decoration: none;">Edit</a>
                                <form method="POST" action="{{ route('quests.destroy', $quest) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #e53e3e; color: white; border: none; border-radius: 0.35rem; padding: 0.4rem 0.7rem; font-size: 0.85rem; cursor: pointer;" onclick="return confirm('Are you sure?');">Delete</button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection