@extends('layout')

@section('title', 'Edit Quest')

@section('content')
    <h1>Edit Quest</h1>
    <form method="POST" action="{{ route('quests.update', $quest) }}">
        @csrf
        @method('PUT')
        <div class="form-control">
            <label for="title" class="label">Title</label>
            <input type="text" name="title" id="title" class="input input-bordered" value="{{ $quest->title }}" required>
        </div>
        <div class="form-control">
            <label for="description" class="label">Description</label>
            <textarea name="description" id="description" class="textarea textarea-bordered" required>{{ $quest->description }}</textarea>
        </div>
        <div class="form-control">
            <label for="stat" class="label">Stat</label>
            <select name="stat" id="stat" class="select select-bordered" required>
                <option value="strength" {{ $quest->stat == 'strength' ? 'selected' : '' }}>Strength</option>
                <option value="constitution" {{ $quest->stat == 'constitution' ? 'selected' : '' }}>Constitution</option>
                <option value="intelligence" {{ $quest->stat == 'intelligence' ? 'selected' : '' }}>Intelligence</option>
                <option value="charisma" {{ $quest->stat == 'charisma' ? 'selected' : '' }}>Charisma</option>
            </select>
        </div>
        <div class="form-control">
            <label for="difficulty" class="label">Difficulty</label>
            <select name="difficulty" id="difficulty" class="select select-bordered" required>
                <option value="easy" {{ $quest->difficulty == 'easy' ? 'selected' : '' }}>Easy</option>
                <option value="medium" {{ $quest->difficulty == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="hard" {{ $quest->difficulty == 'hard' ? 'selected' : '' }}>Hard</option>
            </select>
        </div>
        <div class="form-control">
            <label for="status" class="label">Status</label>
            <select name="status" id="status" class="select select-bordered" required>
                <option value="pending" {{ $quest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ $quest->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ $quest->status == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Update Quest</button>
        <a href="{{ route('quests.show', $quest) }}" class="btn btn-secondary mt-4 ml-2">Cancel</a>
    </form>
@endsection