@extends('layout')

@section('title', 'Quest Settings')

@section('content')
    <section style="max-width: 960px; margin: 0 auto; padding: 1rem;">
        <header style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #4a5568; border-radius: 0.5rem; background: #2d3748;">
            <h1 style="margin: 0; color: #87ceeb; font-size: 1.75rem;">Quest Settings</h1>
            <p style="margin: 0.5rem 0 0; color: #a0aec0;">Configure your daily quest preferences and run assessments with the same visual style as home.</p>
        </header>

        @if(session('success'))
            <div style="margin-bottom: 1rem; border: 1px solid #38b2ac; background: #2c7a7b1a; padding: 0.75rem; border-radius: 0.375rem; color: #b2f5ea;">
                {{ session('success') }}
            </div>
        @endif

        <div style="background: #2d3748; border: 1px solid #4a5568; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
            <h2 style="margin: 0 0 0.75rem 0; color: #87ceeb; font-size: 1.25rem;">Customize Your Daily Quests</h2>

            <form method="POST" action="{{ route('settings.update') }}" style="display: grid; gap: 1rem;">
                @csrf
                @method('PUT')

                <div style="background: #1a202c; border: 1px solid #4a5568; border-radius: 0.4rem; padding: 0.75rem;">
                    <strong style="color: #a0aec0;">How many daily quests would you like?</strong>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem;">
                        @for ($i = 1; $i <= 3; $i++)
                            <label style="cursor: pointer;">
                                <input type="radio" name="daily_quest_count" value="{{ $i }}" style="margin-right: 0.35rem;" {{ Auth::user()->daily_quest_count == $i ? 'checked' : '' }} />
                                <span style="color: #cbd5e1;">{{ $i }} Quest{{ $i !== 1 ? 's' : '' }}</span>
                            </label>
                        @endfor
                    </div>
                </div>

                <div style="background: #1a202c; border: 1px solid #4a5568; border-radius: 0.4rem; padding: 0.75rem;">
                    <strong style="color: #a0aec0;">Which attributes would you like quests for?</strong>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit,minmax(140px,1fr)); gap: 0.75rem; margin-top: 0.5rem;">
                        @foreach(['strength' => 'Strength', 'constitution' => 'Constitution', 'intelligence' => 'Intelligence', 'charisma' => 'Charisma'] as $value => $label)
                            <label style="display: flex; align-items: center; gap: 0.4rem; border: 1px solid #4a5568; border-radius: 0.4rem; padding: 0.45rem; color: #cbd5e1; background: #1a2332;">
                                <input type="checkbox" name="quest_attributes[]" value="{{ $value }}" style="accent-color: #4fd1c5;" {{ in_array($value, Auth::user()->quest_attributes ?? []) ? 'checked' : '' }} />
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                    @error('quest_attributes')
                        <div style="color: #f56565; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem;">
                    <button type="submit" style="background-color: #4a90e2; color: #fff; border: none; border-radius: 0.4rem; padding: 0.6rem 1rem; font-weight: 600;">Save Preferences</button>
                    <a href="{{ route('home') }}" style="background-color: transparent; border: 1px solid #4a5568; color: #cbd5e1; border-radius: 0.4rem; padding: 0.6rem 1rem; text-decoration: none;">Cancel</a>
                </div>
            </form>
        </div>

        <div style="background: #2d3748; border: 1px solid #4a5568; border-radius: 0.5rem; padding: 1rem;">
            <h2 style="margin: 0 0 0.75rem 0; color: #87ceeb; font-size: 1.25rem;">Stat Assessment</h2>
            <p style="margin: 0 0 1rem; color: #a0aec0;">Retake the stat assessment to recalibrate your attributes based on your current abilities.</p>
            <form action="{{ route('assessment.retake') }}" method="POST">
                @csrf
                <button type="submit" style="background-color: #ecc94b; color: #1a202c; border: none; border-radius: 0.4rem; padding: 0.6rem 1rem; font-weight: 600;" onclick="return confirm('Retake the stat assessment? Your current stats will be reset.');">Retake Test</button>
            </form>
        </div>
    </section>
@endsection