<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Quest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'stat',
        'difficulty',
        'status',
    ];

    public function getParsedDescription(User $user): string
    {
        $description = $this->description;

        $description = preg_replace_callback('/:X(\d+):/', function ($matches) use ($user) {
            $number = (int) $matches[1];
            $multiplier = $user->getStatValue($this->stat);
            return $number * $multiplier;
        }, $description);

        return $description;
    }
}