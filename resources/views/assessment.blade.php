@extends('layout')

@section('title', 'Stat Assessment')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Determine Your Stats</h1>
        <p class="text-gray-600 mb-6">Answer a few quick questions to determine your starting stats. Be honest!</p>

        <form method="POST" action="{{ route('assessment.submit') }}" class="space-y-6">
            @csrf

            <!-- Strength Questions -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Strength</h2>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">How many push-ups can you do?</span>
                        </label>
                        <select name="strength_q1" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Less than 10</option>
                            <option value="4">10-25</option>
                            <option value="3">26-50</option>
                            <option value="2">51-75</option>
                            <option value="1">76+</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">How much can you bench press or deadlift (relative to your weight)?</span>
                        </label>
                        <select name="strength_q2" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Can't lift my body weight</option>
                            <option value="4">1x body weight</option>
                            <option value="3">1.5x body weight</option>
                            <option value="2">2x body weight</option>
                            <option value="1">2.5x+ body weight</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">How often do you exercise per week?</span>
                        </label>
                        <select name="strength_q3" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Rarely or never</option>
                            <option value="4">1-2 times</option>
                            <option value="3">3 times</option>
                            <option value="2">4-5 times</option>
                            <option value="1">6+ times</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Constitution Questions -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Constitution</h2>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">How many hours of sleep do you get per night?</span>
                        </label>
                        <select name="constitution_q1" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Less than 5 hours</option>
                            <option value="4">5-6 hours</option>
                            <option value="2">7-8 hours (ideal)</option>
                            <option value="4">8-9 hours</option>
                            <option value="5">9+ hours</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">How many glasses of water do you drink per day?</span>
                        </label>
                        <select name="constitution_q2" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Less than 4</option>
                            <option value="4">4-6</option>
                            <option value="2">8 (recommended)</option>
                            <option value="4">9-10</option>
                            <option value="5">11+</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">How often do you get sick in a year?</span>
                        </label>
                        <select name="constitution_q3" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="1">5+ times</option>
                            <option value="2">3-4 times</option>
                            <option value="3">1-2 times</option>
                            <option value="4">Never or almost never</option>
                            <option value="5">Legendary immune system</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Intelligence Questions -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Intelligence</h2>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">How many books do you read per month?</span>
                        </label>
                        <select name="intelligence_q1" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">0</option>
                            <option value="4">1</option>
                            <option value="3">2-3</option>
                            <option value="2">4-5</option>
                            <option value="1">6+</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">How many hours per day do you spend learning/studying?</span>
                        </label>
                        <select name="intelligence_q2" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Less than 30 minutes</option>
                            <option value="4">30 min - 1 hour</option>
                            <option value="3">1-2 hours</option>
                            <option value="2">2-3 hours</option>
                            <option value="1">3+ hours</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">How would you rate your problem-solving skills?</span>
                        </label>
                        <select name="intelligence_q3" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Struggling with basic problems</option>
                            <option value="4">Average problem solver</option>
                            <option value="3">Good at solving complex issues</option>
                            <option value="2">Very sharp and analytical</option>
                            <option value="1">Genius-level problem solving</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Charisma Questions -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Charisma</h2>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">How comfortable are you speaking in front of groups?</span>
                        </label>
                        <select name="charisma_q1" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Terrified and avoid it</option>
                            <option value="4">Nervous but can do it</option>
                            <option value="3">Fairly comfortable</option>
                            <option value="2">Very comfortable</option>
                            <option value="1">Love public speaking</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">How many new people do you meet/befriend per month?</span>
                        </label>
                        <select name="charisma_q2" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Very few (1-2)</option>
                            <option value="4">A few (3-5)</option>
                            <option value="3">Several (6-10)</option>
                            <option value="2">Many (11-20)</option>
                            <option value="1">Tons of new connections</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">How would others describe your personality?</span>
                        </label>
                        <select name="charisma_q3" class="select select-bordered" required>
                            <option value="">Select...</option>
                            <option value="5">Introverted and reserved</option>
                            <option value="4">Quiet but friendly</option>
                            <option value="3">Balanced introvert/extrovert</option>
                            <option value="2">Outgoing and fun</option>
                            <option value="1">Life of the party</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-full">Complete Assessment</button>
        </form>
    </div>
@endsection