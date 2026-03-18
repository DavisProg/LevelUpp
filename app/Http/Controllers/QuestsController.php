<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use \App\Models\Quest;

class QuestsController extends Controller
{
    public function index()
    {
        $quests = Quest::all();
        return view('quests.index', compact('quests'));
    }
    public function show($id){
        $quest = Quest::findOrFail($id);
        return view('quests.show', compact('quest'));
    }
    public function create(Request $request){
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
}