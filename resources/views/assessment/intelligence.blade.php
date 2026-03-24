@extends('layout')

@section('title', 'Stat Assessment - Intelligence')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <div class="badge badge-lg badge-primary">3 of 4</div>
                <h1 class="text-3xl font-bold">Intelligence Assessment</h1>
            </div>
            <div class="w-full bg-base-300 rounded-full h-2">
                <div class="bg-primary h-2 rounded-full" style="width: 75%"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('assessment.next') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="stat" value="intelligence">

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">How many books do you read per month?</span>
                        </label>
                        <select name="intelligence_q1" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">0</option>
                            <option value="4">0.5 (1 every 2 months)</option>
                            <option value="3">1</option>
                            <option value="2">2</option>
                            <option value="1">3+</option>
                        </select>
                        @error('intelligence_q1')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-semibold">How many hours per day do you spend learning/studying?</span>
                        </label>
                        <select name="intelligence_q2" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Less than 15 minutes</option>
                            <option value="4">15-30 minutes</option>
                            <option value="3">30 min - 1 hour</option>
                            <option value="2">1-2 hours</option>
                            <option value="1">2+ hours</option>
                        </select>
                        @error('intelligence_q2')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-semibold">How would you rate your problem-solving skills?</span>
                        </label>
                        <select name="intelligence_q3" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Struggling with basic problems</option>
                            <option value="4">Below average problem solver</option>
                            <option value="3">Average problem solver</option>
                            <option value="2">Good at solving complex issues</option>
                            <option value="1">Genius-level problem solving</option>
                        </select>
                        @error('intelligence_q3')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('assessment.show', 'constitution') }}" class="btn btn-ghost flex-1">← Back: Constitution</a>
                <button type="submit" class="btn btn-primary flex-1">Next: Charisma →</button>
            </div>
        </form>
    </div>
@endsection
