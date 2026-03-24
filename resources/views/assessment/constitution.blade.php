@extends('layout')

@section('title', 'Stat Assessment - Constitution')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <div class="badge badge-lg badge-primary">2 of 4</div>
                <h1 class="text-3xl font-bold">Constitution Assessment</h1>
            </div>
            <div class="w-full bg-base-300 rounded-full h-2">
                <div class="bg-primary h-2 rounded-full" style="width: 50%"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('assessment.next') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="stat" value="constitution">

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">How many hours of sleep do you get per night?</span>
                        </label>
                        <select name="constitution_q1" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Less than 5 hours</option>
                            <option value="4">5-6 hours</option>
                            <option value="3">7-8 hours (ideal)</option>
                            <option value="4">8-9 hours</option>
                            <option value="5">9+ hours</option>
                        </select>
                        @error('constitution_q1')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-semibold">How many glasses of water do you drink per day?</span>
                        </label>
                        <select name="constitution_q2" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Less than 4</option>
                            <option value="4">4-5</option>
                            <option value="3">6-8 (recommended)</option>
                            <option value="2">9-10</option>
                            <option value="1">11+</option>
                        </select>
                        @error('constitution_q2')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-semibold">How often do you get sick in a year?</span>
                        </label>
                        <select name="constitution_q3" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="1">5+ times</option>
                            <option value="2">3-4 times</option>
                            <option value="3">1-2 times</option>
                            <option value="4">Never or almost never</option>
                            <option value="5">Legendary immune system</option>
                        </select>
                        @error('constitution_q3')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('assessment.show', 'strength') }}" class="btn btn-ghost flex-1">← Back: Strength</a>
                <button type="submit" class="btn btn-primary flex-1">Next: Intelligence →</button>
            </div>
        </form>
    </div>
@endsection
