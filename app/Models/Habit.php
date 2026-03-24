<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'description'];

    /**
     * Get the user that owns this habit.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all logs for this habit.
     */
    public function logs()
    {
        return $this->hasMany(HabitLog::class);
    }

    /**
     * Get the score for this habit today.
     */
    public function getTodayScore()
    {
        $today = \Carbon\Carbon::today();
        $positive = $this->logs()
            ->where('type', 'positive')
            ->whereDate('created_at', $today)
            ->count();
        
        $negative = $this->logs()
            ->where('type', 'negative')
            ->whereDate('created_at', $today)
            ->count();
        
        return $positive - $negative;
    }

    /**
     * Get the total score for this habit.
     */
    public function getTotalScore()
    {
        $positive = $this->logs()->where('type', 'positive')->count();
        $negative = $this->logs()->where('type', 'negative')->count();
        
        return $positive - $negative;
    }
}
