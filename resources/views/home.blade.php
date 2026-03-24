@extends('layout')

@section('title', 'Home')

@section('content')
    <h1>Welcome, {{ Auth::user()->name }}!</h1>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <h2 style="color: #87ceeb; font-size: 1.5rem; margin-bottom: 1rem;">Your Stats</h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; width: 100%;">
        @php
            $getGradeColor = function($grade) {
                return match($grade) {
                    'S' => '#00ff00',
                    'A' => '#32cd32',
                    'B' => '#87ceeb',
                    'C' => '#ffff00',
                    'D' => '#ffa500',
                    'E' => '#ff6347',
                    'F' => '#cc0000',
                    default => '#87ceeb'
                };
            };
        @endphp
        
        <div style="background-color: #2d3748; border: 2px solid #4a90e2; border-radius: 0.5rem; padding: 1.5rem; text-align: center; color: #87ceeb;">
            <h3 style="margin: 0 0 0.5rem 0; color: #4a90e2;">Strength</h3>
            <div style="font-size: 2.5rem; font-weight: bold; margin: 0.5rem 0; color: {{ $getGradeColor(Auth::user()->strength) }};">{{ Auth::user()->strength }}</div>
        </div>

        <div style="background-color: #2d3748; border: 2px solid #50c878; border-radius: 0.5rem; padding: 1.5rem; text-align: center; color: #87ceeb;">
            <h3 style="margin: 0 0 0.5rem 0; color: #50c878;">Constitution</h3>
            <div style="font-size: 2.5rem; font-weight: bold; margin: 0.5rem 0; color: {{ $getGradeColor(Auth::user()->constitution) }};">{{ Auth::user()->constitution }}</div>
        </div>

        <div style="background-color: #2d3748; border: 2px solid #00bcd4; border-radius: 0.5rem; padding: 1.5rem; text-align: center; color: #87ceeb;">
            <h3 style="margin: 0 0 0.5rem 0; color: #00bcd4;">Intelligence</h3>
            <div style="font-size: 2.5rem; font-weight: bold; margin: 0.5rem 0; color: {{ $getGradeColor(Auth::user()->intelligence) }};">{{ Auth::user()->intelligence }}</div>
        </div>

        <div style="background-color: #2d3748; border: 2px solid #ffa500; border-radius: 0.5rem; padding: 1.5rem; text-align: center; color: #87ceeb;">
            <h3 style="margin: 0 0 0.5rem 0; color: #ffa500;">Charisma</h3>
            <div style="font-size: 2.5rem; font-weight: bold; margin: 0.5rem 0; color: {{ $getGradeColor(Auth::user()->charisma) }};">{{ Auth::user()->charisma }}</div>
        </div>
    </div>

    @if(isset($quests) && $quests->count() > 0)
        <div style="background-color: #2d3748; border: 1px solid #4a5568; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 style="color: #87ceeb; font-size: 1.5rem; font-weight: bold; margin: 0;">Daily Quests</h2>
                <div style="font-size: 0.875rem; font-weight: bold; color: #718096;">
                    Refresh in: <span id="timer" style="color: #87ceeb;">00:00:00</span>
                </div>
            </div>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem;">
                @foreach($quests as $quest)
                    <li style="background-color: #1a2332; border: 1px solid #4a5568; border-radius: 0.5rem; padding: 1rem; color: #87ceeb;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
                            <div style="flex: 1;">
                                <h3 style="margin: 0 0 0.5rem 0; color: #87ceeb; font-weight: bold;">{{ $quest->title }}</h3>
                                <p style="margin: 0 0 0.5rem 0; font-size: 0.875rem; color: #a0aec0;">{{ \Illuminate\Support\Str::limit($quest->getParsedDescription(Auth::user()), 90) }}</p>
                                <p style="margin: 0; font-size: 0.75rem; color: #718096;">{{ ucfirst($quest->stat) }} · {{ ucfirst($quest->difficulty) }}</p>
                            </div>
                            <form method="POST" action="{{ route('quest.toggle-status', $quest) }}" style="display: inline; flex-shrink: 0;">
                                @csrf
                                @php
                                    $buttonText = match($quest->user_status) {
                                        'pending' => 'Start',
                                        'started' => 'Finish',
                                        'completed' => 'Done',
                                        default => 'Start'
                                    };
                                    $buttonColor = match($quest->user_status) {
                                        'pending' => '#4a90e2',
                                        'started' => '#50c878',
                                        'completed' => '#718096',
                                        default => '#4a90e2'
                                    };
                                    $disabled = $quest->user_status === 'completed' ? 'disabled' : '';
                                @endphp
                                <button type="submit" style="background-color: {{ $buttonColor }}; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.25rem; cursor: pointer; font-weight: bold;" {{ $disabled }}>{{ $buttonText }}</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <script>
            function updateTimer() {
                function tick() {
                    const now = new Date();
                    const midnight = new Date();
                    midnight.setHours(24, 0, 0, 0);
                    
                    let secondsLeft = Math.floor((midnight - now) / 1000);
                    if (secondsLeft < 0) {
                        location.reload();
                        return;
                    }
                    
                    const hours = Math.floor(secondsLeft / 3600);
                    const minutes = Math.floor((secondsLeft % 3600) / 60);
                    const seconds = secondsLeft % 60;
                    
                    const timerText = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                    const timerElement = document.getElementById('timer');
                    
                    if (timerElement) {
                        timerElement.textContent = timerText;
                    }
                    
                    if (secondsLeft <= 0) {
                        location.reload();
                    } else {
                        setTimeout(tick, 1000);
                    }
                }
                
                tick();
            }
            
            updateTimer();
        </script>
    @else
        <div style="background-color: #2d3748; border: 1px solid #4a5568; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 2rem; text-align: center;">
            <h2 style="color: #87ceeb; font-size: 1.5rem; font-weight: bold; margin: 0 0 1rem 0;">Daily Quests</h2>
            <p style="color: #718096; margin: 0;">No daily quests available right now.</p>
        </div>
    @endif

    <div style="background-color: #2d3748; border: 1px solid #4a5568; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 2rem;">
        <h2 style="color: #87ceeb; font-size: 1.5rem; font-weight: bold; margin: 0 0 1.5rem 0;">Habits</h2>
        
        @if(Auth::user()->habits->count() > 0)
            <ul style="list-style: none; padding: 0; margin: 0 0 1.5rem 0; display: flex; flex-direction: column; gap: 0.75rem;">
                @foreach(Auth::user()->habits as $habit)
                    <li style="background-color: #1a2332; border: 1px solid #4a5568; border-radius: 0.5rem; padding: 1rem; color: #87ceeb;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
                            <div style="flex: 1;">
                                <h3 style="margin: 0 0 0.5rem 0; color: #87ceeb; font-weight: bold;">{{ $habit->name }}</h3>
                                @if($habit->description)
                                    <p style="margin: 0 0 0.5rem 0; font-size: 0.875rem; color: #a0aec0;">{{ $habit->description }}</p>
                                @endif
                                <p style="margin: 0; font-size: 0.75rem; color: #718096;">
                                    Today: <span style="font-weight: bold; color: {{ $habit->getTodayScore() > 0 ? '#50c878' : '#ff6347' }};">{{ $habit->getTodayScore() > 0 ? '+' : '' }}{{ $habit->getTodayScore() }}</span>
                                    | Total: <span style="font-weight: bold; color: {{ $habit->getTotalScore() > 0 ? '#50c878' : '#ff6347' }};">{{ $habit->getTotalScore() > 0 ? '+' : '' }}{{ $habit->getTotalScore() }}</span>
                                </p>
                            </div>
                            <div style="display: flex; gap: 0.5rem; flex-shrink: 0;">
                                <form method="POST" action="{{ route('habit.log', $habit) }}" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="type" value="positive">
                                    <button type="submit" style="background-color: #50c878; color: white; border: none; padding: 0.25rem 0.5rem; border-radius: 0.25rem; cursor: pointer; font-weight: bold;">+</button>
                                </form>
                                <form method="POST" action="{{ route('habit.log', $habit) }}" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="type" value="negative">
                                    <button type="submit" style="background-color: #ff6347; color: white; border: none; padding: 0.25rem 0.5rem; border-radius: 0.25rem; cursor: pointer; font-weight: bold;">−</button>
                                </form>
                                <form method="POST" action="{{ route('habits.destroy', $habit) }}" style="display: inline;" onsubmit="return confirm('Delete this habit?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background-color: transparent; color: #a0aec0; border: none; cursor: pointer; padding: 0.25rem 0.5rem;">✕</button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p style="color: #718096; margin-bottom: 1.5rem;">No habits yet. Add one to get started!</p>
        @endif

        <form method="POST" action="{{ route('habits.store') }}" style="display: flex; flex-direction: column; gap: 0.75rem;">
            @csrf
            <input type="text" name="name" placeholder="Habit name" style="background-color: #1a2332; color: #87ceeb; border: 1px solid #4a5568; padding: 0.5rem; border-radius: 0.25rem;" required>
            <input type="text" name="description" placeholder="Description (optional)" style="background-color: #1a2332; color: #87ceeb; border: 1px solid #4a5568; padding: 0.5rem; border-radius: 0.25rem;">
            <button type="submit" style="background-color: #4a90e2; color: white; border: none; padding: 0.75rem; border-radius: 0.25rem; cursor: pointer; font-weight: bold;">Add Habit</button>
        </form>
    </div>
@endsection