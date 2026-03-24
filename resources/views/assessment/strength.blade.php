@extends('layout')

@section('title', 'Stat Assessment - Strength')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <div class="badge badge-lg badge-primary">1 of 4</div>
                <h1 class="text-3xl font-bold">Strength Assessment</h1>
            </div>
            <div class="w-full bg-base-300 rounded-full h-2">
                <div class="bg-primary h-2 rounded-full" style="width: 25%"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('assessment.next') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="stat" value="strength">

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">How many pull-ups can you do?</span>
                        </label>
                        <select name="strength_q1" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Can't do any</option>
                            <option value="4">1-5</option>
                            <option value="3">6-15</option>
                            <option value="2">16-25</option>
                            <option value="1">26+</option>
                        </select>
                        @error('strength_q1')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-semibold">How many push-ups can you do?</span>
                        </label>
                        <select name="strength_q2" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Can't do any</option>
                            <option value="4">1-10</option>
                            <option value="3">11-25</option>
                            <option value="2">26-50</option>
                            <option value="1">51+</option>
                        </select>
                        @error('strength_q2')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-semibold">How often do you exercise per week?</span>
                        </label>
                        <select name="strength_q3" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Rarely or never</option>
                            <option value="4">1-2 times</option>
                            <option value="3">3 times</option>
                            <option value="2">4-5 times</option>
                            <option value="1">6+ times</option>
                        </select>
                        @error('strength_q3')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="btn btn-primary flex-1">Next: Constitution →</button>
            </div>
        </form>
    </div>
@endsection
