@extends('layout')

@section('title', 'Home')

@section('content')
    <h1>Welcome, {{ Auth::user()->name }}!</h1>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <h2 class="card-title">Your Stats</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="stat place-items-center">
                    <div class="stat-title">Strength</div>
                    <div class="stat-value text-primary">{{ Auth::user()->strength }} (×{{ Auth::user()->getStatMultiplier(Auth::user()->strength) }})</div>
                </div>

                <div class="stat place-items-center">
                    <div class="stat-title">Constitution</div>
                    <div class="stat-value text-success">{{ Auth::user()->constitution }} (×{{ Auth::user()->getStatMultiplier(Auth::user()->constitution) }})</div>
                </div>

                <div class="stat place-items-center">
                    <div class="stat-title">Intelligence</div>
                    <div class="stat-value text-info">{{ Auth::user()->intelligence }} (×{{ Auth::user()->getStatMultiplier(Auth::user()->intelligence) }})</div>
                </div>

                <div class="stat place-items-center">
                    <div class="stat-title">Charisma</div>
                    <div class="stat-value text-warning">{{ Auth::user()->charisma }} (×{{ Auth::user()->getStatMultiplier(Auth::user()->charisma) }})</div>
                </div>
            </div>
            <div class="card-actions justify-start mt-4">
                <a href="{{ route('settings') }}" class="btn btn-secondary">Settings</a>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <h2 class="card-title">Habits</h2>
            
            @if(Auth::user()->habits->count() > 0)
                <ul class="space-y-3 mb-4">
                    @foreach(Auth::user()->habits as $habit)
                        <li class="border p-3 rounded-md">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="font-bold text-lg">{{ $habit->name }}</h3>
                                    @if($habit->description)
                                        <p class="text-sm text-gray-600">{{ $habit->description }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">
                                        Today: <span class="font-semibold">{{ $habit->getTodayScore() > 0 ? '+' : '' }}{{ $habit->getTodayScore() }}</span>
                                        | Total: <span class="font-semibold">{{ $habit->getTotalScore() > 0 ? '+' : '' }}{{ $habit->getTotalScore() }}</span>
                                    </p>
                                </div>
                                <div class="flex gap-2 ml-4">
                                    <form method="POST" action="{{ route('habit.log', $habit) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="type" value="positive">
                                        <button type="submit" class="btn btn-sm btn-success">+</button>
                                    </form>
                                    <form method="POST" action="{{ route('habit.log', $habit) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="type" value="negative">
                                        <button type="submit" class="btn btn-sm btn-error">−</button>
                                    </form>
                                    <form method="POST" action="{{ route('habits.destroy', $habit) }}" class="inline" onsubmit="return confirm('Delete this habit?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-ghost">✕</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 mb-4">No habits yet. Add one to get started!</p>
            @endif

            <form method="POST" action="{{ route('habits.store') }}" class="space-y-2">
                @csrf
                <div class="form-control">
                    <input type="text" name="name" placeholder="Habit name" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <input type="text" name="description" placeholder="Description (optional)" class="input input-bordered">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Add Habit</button>
            </form>
        </div>
    </div>

    @if(isset($quests) && $quests->count() > 0)
        <div class="card bg-base-100 shadow-xl mb-6">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="card-title">Daily Quests</h2>
                    <div class="text-sm font-semibold">
                        <span id="timer">Refresh in: 00:00:00</span>
                    </div>
                </div>
                <ul class="space-y-3">
                    @foreach($quests as $quest)
                        <li class="border p-3 rounded-md">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="font-bold text-lg">{{ $quest->title }}</h3>
                                    <p class="text-sm mt-1">{{ \Illuminate\Support\Str::limit($quest->description, 90) }}</p>
                                    <p class="text-xs text-gray-500">Stat: {{ $quest->stat }}, Difficulty: {{ $quest->difficulty }}</p>
                                </div>
                                <form method="POST" action="{{ route('quest.toggle-status', $quest) }}" class="ml-4">
                                    @csrf
                                    @php
                                        $buttonText = match($quest->user_status) {
                                            'pending' => 'Start',
                                            'started' => 'Finish',
                                            'completed' => 'Completed',
                                            default => 'Start'
                                        };
                                        $buttonClass = match($quest->user_status) {
                                            'pending' => 'btn-primary',
                                            'started' => 'btn-success',
                                            'completed' => 'btn-disabled',
                                            default => 'btn-primary'
                                        };
                                        $disabled = $quest->user_status === 'completed' ? 'disabled' : '';
                                    @endphp
                                    <button type="submit" class="btn btn-sm {{ $buttonClass }}" {{ $disabled }}>{{ $buttonText }}</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
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
                        timerElement.textContent = `Refresh in: ${timerText}`;
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
        <div class="alert alert-info mb-6">No daily quests available right now.</div>
    @endif

    <div class="text-center">
        <a href="{{ route('quests.index') }}" class="btn btn-secondary">View All Quests</a>
    </div>
@endsection