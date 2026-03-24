<?php

namespace App\Models;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'strength', 'constitution', 'intelligence', 'charisma', 'is_admin', 'daily_quest_count', 'quest_attributes', 'last_daily_refresh', 'assessment_completed'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'quest_attributes' => 'array',
            'last_daily_refresh' => 'datetime',
            'assessment_completed' => 'boolean',
        ];
    }

    /**
     * Get quest attributes with default fallback.
     */
    public function getQuestAttributesAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }
        if (is_string($value) && !empty($value)) {
            return json_decode($value, true) ?? ['strength', 'constitution', 'intelligence', 'charisma'];
        }
        return ['strength', 'constitution', 'intelligence', 'charisma'];
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * Get the quests this user has interacted with.
     */
    public function userQuests()
    {
        return $this->hasMany(\App\Models\UserQuest::class);
    }

    /**
     * Get the habits this user has created.
     */
    public function habits()
    {
        return $this->hasMany(\App\Models\Habit::class);
    }

    /**
     * Get the habit logs for this user.
     */
    public function habitLogs()
    {
        return $this->hasMany(\App\Models\HabitLog::class);
    }

    /**
     * Get quest status for a specific quest.
     */
    public function getQuestStatus($questId)
    {
        $userQuest = $this->userQuests()->where('quest_id', $questId)->first();
        return $userQuest?->status ?? 'pending';
    }

    /**
     * Check if dailies need to be refreshed (past midnight).
     */
    public function needsDailyRefresh(): bool
    {
        if (!$this->last_daily_refresh) {
            return true;
        }

        $lastRefresh = $this->last_daily_refresh;
        $today = \Carbon\Carbon::now()->startOfDay();

        return $lastRefresh->startOfDay()->isBefore($today);
    }

    /**
     * Refresh daily quests by clearing old user_quests and updating refresh time.
     */
    public function refreshDailyQuests(): void
    {
        $this->userQuests()->delete();
        $this->update(['last_daily_refresh' => \Carbon\Carbon::now()]);
    }

    /**
     * Get seconds until midnight.
     */
    public function getSecondsUntilMidnight(): int
    {
        return (int) \Carbon\Carbon::now()->diffInSeconds(\Carbon\Carbon::now()->endOfDay(), false);
    }

    /**
     * Get the multiplier for a stat grade.
     */
    public function getStatMultiplier(string $grade): int
    {
        return match ($grade) {
            'F' => 1,
            'E' => 2,
            'D' => 3,
            'C' => 4,
            'B' => 5,
            'A' => 6,
            'S' => 7,
            default => 1,
        };
    }

    /**
     * Get the calculated value for a stat.
     */
    public function getStatValue(string $stat): int
    {
        return $this->getStatMultiplier($this->$stat);
    }
}
