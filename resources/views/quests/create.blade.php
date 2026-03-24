@extends('layout')

@section('title', 'Create Quest')

@section('content')
    <h1>Create New Quest</h1>
    <form method="POST" action="{{ route('quests.store') }}">
        @csrf
        <div class="form-control">
            <label for="title" class="label">Title</label>
            <input type="text" name="title" id="title" class="input input-bordered" required>
        </div>
        <div class="form-control">
            <label for="description" class="label">Description</label>
            <textarea name="description" id="description" class="textarea textarea-bordered" required></textarea>
        </div>
        <div class="form-control">
            <label for="stat" class="label">Stat</label>
            <select name="stat" id="stat" class="select select-bordered" required>
                <option value="strength">Strength</option>
                <option value="constitution">Constitution</option>
                <option value="intelligence">Intelligence</option>
                <option value="charisma">Charisma</option>
            </select>
        </div>
        <div class="form-control">
            <label for="difficulty" class="label">Difficulty</label>
            <select name="difficulty" id="difficulty" class="select select-bordered" required>
                <option value="easy">Easy</option>
                <option value="medium">Medium</option>
                <option value="hard">Hard</option>
            </select>
        </div>
        <div class="form-control">
            <label for="status" class="label">Status</label>
            <select name="status" id="status" class="select select-bordered" required>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Create Quest</button>
        <a href="{{ route('quests.index') }}" class="btn btn-secondary mt-4 ml-2">Cancel</a>
    </form>
@endsection