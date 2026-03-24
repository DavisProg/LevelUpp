<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use \App\Models\Quest;

class QuestsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || !$user->isAdmin()) {
                abort(403, 'Unauthorized access');
            }

            return $next($request);
        });
    }

    public function index()
    {
        $quests = Quest::all();
        return view('quests.index', compact('quests'));
    }
    public function show($id){
        $quest = Quest::findOrFail($id);
        return view('quests.show', compact('quest'));
    }
    public function create()
    {
        return view('quests.create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'stat' => 'required|in:strength,constitution,intelligence,charisma',
            'difficulty' => 'required|in:easy,medium,hard',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $quest = Quest::create($validated);
        return redirect()->route('quests.show', $quest);
    }

    public function edit($id)
    {
        $quest = Quest::findOrFail($id);
        return view('quests.edit', compact('quest'));
    }

    public function update(Request $request, $id)
    {
        $quest = Quest::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'stat' => 'required|in:strength,constitution,intelligence,charisma',
            'difficulty' => 'required|in:easy,medium,hard',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $quest->update($validated);
        return redirect()->route('quests.show', $quest);
    }

    public function destroy($id)
    {
        $quest = Quest::findOrFail($id);
        $quest->delete();
        return redirect()->route('quests.index');
    }
}