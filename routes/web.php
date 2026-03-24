<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController as Register;
use App\Http\Controllers\Auth\LoginController as Login;
use App\Http\Controllers\QuestsController as Quests;

Route::get('/', function () {
    $user = Auth::user();
    
    if ($user->needsDailyRefresh()) {
        $user->refreshDailyQuests();
    }
    
    $questCount = $user->daily_quest_count ?? 3;
    $attributes = $user->quest_attributes ?? ['strength', 'constitution', 'intelligence', 'charisma'];
    
    $quests = App\Models\Quest::whereIn('stat', $attributes)
        ->inRandomOrder()
        ->limit($questCount)
        ->get();
    
    $quests->each(function ($quest) use ($user) {
        $quest->user_status = $user->getQuestStatus($quest->id);
    });
    
    $habits = $user->habits()->get();
    
    return view('home', compact('quests', 'habits'));
})->middleware('auth', 'require.completed.assessment')->name('home');

Route::get('/assessment', function () {
    session()->forget('assessment_answers');
    return redirect()->route('assessment.show', 'strength');
})->middleware('auth', 'not.completed.assessment')->name('assessment');

Route::get('/assessment/{stat}', function (string $stat) {
    $validStats = ['strength', 'constitution', 'intelligence', 'charisma'];
    
    if (!in_array($stat, $validStats)) {
        return redirect()->route('assessment.show', 'strength');
    }
    
    return view("assessment.{$stat}");
})->middleware('auth', 'not.completed.assessment')->name('assessment.show');

Route::post('/assessment', function (Request $request) {
    $stat = $request->stat;
    
    $validations = [
        'strength' => [
            'strength_q1' => 'required|integer|between:1,5',
            'strength_q2' => 'required|integer|between:1,5',
            'strength_q3' => 'required|integer|between:1,5',
        ],
        'constitution' => [
            'constitution_q1' => 'required|integer|between:1,5',
            'constitution_q2' => 'required|integer|between:1,5',
            'constitution_q3' => 'required|integer|between:1,5',
        ],
        'intelligence' => [
            'intelligence_q1' => 'required|integer|between:1,5',
            'intelligence_q2' => 'required|integer|between:1,5',
            'intelligence_q3' => 'required|integer|between:1,5',
        ],
        'charisma' => [
            'charisma_q1' => 'required|integer|between:1,5',
            'charisma_q2' => 'required|integer|between:1,5',
            'charisma_q3' => 'required|integer|between:1,5',
        ],
    ];
    
    $request->validate($validations[$stat]);
    
    $answers = session()->get('assessment_answers', []);
    
    if ($stat === 'strength') {
        $answers['strength_q1'] = $request->strength_q1;
        $answers['strength_q2'] = $request->strength_q2;
        $answers['strength_q3'] = $request->strength_q3;
        session()->put('assessment_answers', $answers);
        return redirect()->route('assessment.show', 'constitution');
    } elseif ($stat === 'constitution') {
        $answers['constitution_q1'] = $request->constitution_q1;
        $answers['constitution_q2'] = $request->constitution_q2;
        $answers['constitution_q3'] = $request->constitution_q3;
        session()->put('assessment_answers', $answers);
        return redirect()->route('assessment.show', 'intelligence');
    } elseif ($stat === 'intelligence') {
        $answers['intelligence_q1'] = $request->intelligence_q1;
        $answers['intelligence_q2'] = $request->intelligence_q2;
        $answers['intelligence_q3'] = $request->intelligence_q3;
        session()->put('assessment_answers', $answers);
        return redirect()->route('assessment.show', 'charisma');
    } elseif ($stat === 'charisma') {
        $answers['charisma_q1'] = $request->charisma_q1;
        $answers['charisma_q2'] = $request->charisma_q2;
        $answers['charisma_q3'] = $request->charisma_q3;
        session()->put('assessment_answers', $answers);
        
        $user = Auth::user();

        $strengthScore = ($answers['strength_q1'] + $answers['strength_q2'] + $answers['strength_q3']) / 3;
        $constitutionScore = ($answers['constitution_q1'] + $answers['constitution_q2'] + $answers['constitution_q3']) / 3;
        $intelligenceScore = ($answers['intelligence_q1'] + $answers['intelligence_q2'] + $answers['intelligence_q3']) / 3;
        $charismaScore = ($answers['charisma_q1'] + $answers['charisma_q2'] + $answers['charisma_q3']) / 3;

        function scoreToGrade($score) {
            return match(true) {
                $score <= 1.5 => 'S',
                $score < 2.3 => 'A',
                $score < 3.0 => 'B',
                $score < 3.7 => 'C',
                $score < 4.3 => 'D',
                $score < 4.8 => 'E',
                default => 'F'
            };
        }

        $user->update([
            'strength' => scoreToGrade($strengthScore),
            'constitution' => scoreToGrade($constitutionScore),
            'intelligence' => scoreToGrade($intelligenceScore),
            'charisma' => scoreToGrade($charismaScore),
            'assessment_completed' => true,
        ]);

        session()->forget('assessment_answers');
        return redirect()->route('home')->with('success', 'Assessment complete! Your stats have been determined.');
    }
})->middleware('auth', 'not.completed.assessment')->name('assessment.next');

Route::post('/assessment/retake', function () {
    $user = Auth::user();
    $user->update(['assessment_completed' => false]);
    Auth::setUser($user->fresh());
    session()->forget('assessment_answers');
    return redirect()->route('assessment.show', 'strength')->with('success', 'Assessment reset. Please retake the test.');
})->middleware('auth')->name('assessment.retake');

Route::get('/register', function () {
    return view('auth.register');
})->middleware('guest')->name('register');
Route::post('/register', [Register::class, 'register'])->middleware('guest')->name('register.post');

Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');
Route::post('/login', [Login::class, 'login'])->middleware('guest')->name('login.post');
Route::post('/logout', [Login::class, 'logout'])->middleware('auth')->name('logout');

Route::resource('quests', Quests::class)->middleware('auth');

Route::get('/settings', function () {
    return view('settings');
})->middleware('auth')->name('settings');

Route::put('/settings', function (Request $request) {
    $request->validate([
        'daily_quest_count' => 'required|integer|between:1,3',
        'quest_attributes' => 'required|array|min:1',
        'quest_attributes.*' => 'in:strength,constitution,intelligence,charisma',
    ]);

    $user = Auth::user();
    $user->update([
        'daily_quest_count' => $request->daily_quest_count,
        'quest_attributes' => $request->quest_attributes,
    ]);

    return redirect()->route('settings')->with('success', 'Quest preferences updated successfully!');
})->middleware('auth')->name('settings.update');

Route::post('/quests/{quest}/toggle-status', function (Request $request, App\Models\Quest $quest) {
    $user = Auth::user();
    
    $userQuest = \App\Models\UserQuest::firstOrCreate(
        ['user_id' => $user->id, 'quest_id' => $quest->id],
        ['status' => 'pending']
    );
    
    $statusMap = [
        'pending' => 'started',
        'started' => 'completed',
        'completed' => 'completed',
    ];
    
    $userQuest->status = $statusMap[$userQuest->status];
    $userQuest->save();
    
    return redirect()->back();
})->middleware('auth')->name('quest.toggle-status');

Route::post('/habits', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    Auth::user()->habits()->create($request->only(['name', 'description']));

    return redirect()->back()->with('success', 'Habit created!');
})->middleware('auth')->name('habits.store');

Route::post('/habits/{habit}/log', function (Request $request, App\Models\Habit $habit) {
    $request->validate([
        'type' => 'required|in:positive,negative',
    ]);

    $habit->logs()->create([
        'user_id' => Auth::id(),
        'type' => $request->type,
    ]);

    return redirect()->back();
})->middleware('auth')->name('habit.log');

Route::delete('/habits/{habit}', function (Request $request, App\Models\Habit $habit) {
    if ($habit->user_id !== Auth::id()) {
        abort(403);
    }
    
    $habit->delete();

    return redirect()->back()->with('success', 'Habit deleted.');
})->middleware('auth')->name('habits.destroy');
