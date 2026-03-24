@extends('layout')

@section('title', 'Quest Details')

@section('content')
    <h1>{{ $quest->title }}</h1>
    <p><strong>Description:</strong> {{ $quest->getParsedDescription(auth()->user()) }}</p>
    <p><strong>Stat:</strong> {{ $quest->stat }}</p>
    <p><strong>Difficulty:</strong> {{ $quest->difficulty }}</p>
    <p><strong>Status:</strong> {{ $quest->status }}</p>
    <a href="{{ route('quests.index') }}" class="btn btn-secondary">Back to Quests</a>
    <a href="{{ route('quests.edit', $quest) }}" class="btn btn-primary ml-2">Edit</a>
@endsection