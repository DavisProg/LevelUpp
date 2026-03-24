@extends('layout')

@section('title', 'Quest Settings')

@section('content')
    <h1>Quest Settings</h1>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">Customize Your Daily Quests</h2>

            <form method="POST" action="{{ route('settings.update') }}">
                @csrf
                @method('PUT')

                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-bold">How many daily quests would you like?</span>
                    </label>
                    <div class="flex gap-4">
                        @for ($i = 1; $i <= 3; $i++)
                            <label class="label cursor-pointer">
                                <input 
                                    type="radio" 
                                    name="daily_quest_count" 
                                    value="{{ $i }}" 
                                    class="radio"
                                    {{ Auth::user()->daily_quest_count == $i ? 'checked' : '' }}
                                    required
                                />
                                <span class="label-text ml-2">{{ $i }} Quest{{ $i !== 1 ? 's' : '' }}</span>
                            </label>
                        @endfor
                    </div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-bold">Which attributes would you like quests for?</span>
                    </label>
                    <div class="space-y-2">
                        @foreach(['strength' => 'Strength', 'constitution' => 'Constitution', 'intelligence' => 'Intelligence', 'charisma' => 'Charisma'] as $value => $label)
                            <label class="label cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="quest_attributes[]" 
                                    value="{{ $value }}" 
                                    class="checkbox"
                                    {{ in_array($value, Auth::user()->quest_attributes ?? []) ? 'checked' : '' }}
                                />
                                <span class="label-text ml-2">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('quest_attributes')
                        <span class="text-error text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="card-actions justify-start mt-6">
                    <button type="submit" class="btn btn-primary">Save Preferences</button>
                    <a href="{{ route('home') }}" class="btn btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card bg-base-100 shadow-xl mt-6">
        <div class="card-body">
            <h2 class="card-title">Stat Assessment</h2>
            <p class="text-gray-600 mb-4">Retake the stat assessment to recalibrate your attributes based on your current abilities.</p>
            <form action="{{ route('assessment.retake') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-warning" onclick="return confirm('Retake the stat assessment? Your current stats will be reset.');">Retake Test</button>
            </form>
        </div>
    </div>
@endsection