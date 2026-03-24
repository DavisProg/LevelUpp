@extends('layout')

@section('title', 'Stat Assessment - Charisma')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <div class="badge badge-lg badge-primary">4 of 4</div>
                <h1 class="text-3xl font-bold">Charisma Assessment</h1>
            </div>
            <div class="w-full bg-base-300 rounded-full h-2">
                <div class="bg-primary h-2 rounded-full" style="width: 100%"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('assessment.next') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="stat" value="charisma">

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">How comfortable are you speaking in front of groups?</span>
                        </label>
                        <select name="charisma_q1" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Terrified and avoid it</option>
                            <option value="4">Nervous but can do it</option>
                            <option value="3">Fairly comfortable</option>
                            <option value="2">Very comfortable</option>
                            <option value="1">Love public speaking</option>
                        </select>
                        @error('charisma_q1')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-semibold">How many new people do you meet/befriend per month?</span>
                        </label>
                        <select name="charisma_q2" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">None (0)</option>
                            <option value="5">Very few (1-2)</option>
                            <option value="4">A few (3-5)</option>
                            <option value="3">Several (6-8)</option>
                            <option value="2">Many (9+)</option>
                        </select>
                        @error('charisma_q2')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-semibold">How would others describe your personality?</span>
                        </label>
                        <select name="charisma_q3" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Introverted and reserved</option>
                            <option value="4">Quiet but friendly</option>
                            <option value="3">Balanced introvert/extrovert</option>
                            <option value="2">Outgoing and fun</option>
                            <option value="1">Life of the party</option>
                        </select>
                        @error('charisma_q3')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('assessment.show', 'intelligence') }}" class="btn btn-ghost flex-1">← Back: Intelligence</a>
                <button type="submit" class="btn btn-primary flex-1">Complete Assessment →</button>
            </div>
        </form>
    </div>
@endsection
