<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'quest_id', 'status'];

    /**
     * Get the user that owns this quest progress.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the quest.
     */
    public function quest()
    {
        return $this->belongsTo(Quest::class);
    }
}
