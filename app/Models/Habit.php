<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(HabitLog::class);
    }

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

    public function getTotalScore()
    {
        $positive = $this->logs()->where('type', 'positive')->count();
        $negative = $this->logs()->where('type', 'negative')->count();
        
        return $positive - $negative;
    }
}
