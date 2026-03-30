<?php

use App\Models\User;
use App\Models\Quest;
use App\Models\UserQuest;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'daily_quest_count' => 3,
        'quest_attributes' => ['strength', 'constitution', 'intelligence', 'charisma'],
        'last_daily_refresh' => now(),
        'assessment_completed' => true,
    ]);

    Quest::factory()->count(10)->create();

    $this->actingAs($this->user);
});

test('needsDailyRefresh returns true when last_daily_refresh is null', function () {
    $user = User::factory()->create(['last_daily_refresh' => null]);
    
    expect($user->needsDailyRefresh())->toBeTrue();
});

test('needsDailyRefresh returns false when last_daily_refresh is today', function () {
    $user = User::factory()->create(['last_daily_refresh' => now()]);
    
    expect($user->needsDailyRefresh())->toBeFalse();
});

test('needsDailyRefresh returns true when last_daily_refresh is yesterday', function () {
    $user = User::factory()->create(['last_daily_refresh' => now()->subDays(1)]);
    
    expect($user->needsDailyRefresh())->toBeTrue();
});

test('needsDailyRefresh returns true when last_daily_refresh is before yesterday', function () {
    $user = User::factory()->create(['last_daily_refresh' => now()->subDays(5)]);
    
    expect($user->needsDailyRefresh())->toBeTrue();
});

test('refreshDailyQuests clears old user quest records', function () {
    $quest = Quest::first();
    UserQuest::create([
        'user_id' => $this->user->id,
        'quest_id' => $quest->id,
        'status' => 'completed',
    ]);

    expect($this->user->userQuests()->count())->toBe(1);

    $this->user->refreshDailyQuests();

    expect($this->user->userQuests()->count())->toBe(0);
});

test('refreshDailyQuests updates last_daily_refresh timestamp', function () {
    $oldTime = now()->subDays(1);
    $this->user->update(['last_daily_refresh' => $oldTime]);

    $this->user->refreshDailyQuests();
    $this->user->refresh();

    expect($this->user->last_daily_refresh->diffInSeconds(now()))->toBeLessThan(5);
    expect($this->user->last_daily_refresh->isSameDay(now()))->toBeTrue();
});

test('home page refreshes quests when needsDailyRefresh is true', function () {
    $this->user->update(['last_daily_refresh' => now()->subDays(1)]);
    
    $this->actingAs($this->user->fresh());

    $quest = Quest::first();
    UserQuest::create([
        'user_id' => $this->user->id,
        'quest_id' => $quest->id,
        'status' => 'started',
    ]);

    expect($this->user->needsDailyRefresh())->toBeTrue();
    expect($this->user->userQuests()->count())->toBe(1);

    $response = $this->get('/');

    $response->assertStatus(200)->assertViewHas('quests');

    $this->user->refresh();

    expect($this->user->userQuests()->count())->toBe(0);
    expect($this->user->last_daily_refresh->isSameDay(now()))->toBeTrue();
});

test('home page does not refresh quests when needsDailyRefresh is false', function () {
    $this->user->update(['last_daily_refresh' => now()]);
    
    $this->actingAs($this->user->fresh());

    $quest = Quest::first();
    UserQuest::create([
        'user_id' => $this->user->id,
        'quest_id' => $quest->id,
        'status' => 'started',
    ]);

    expect($this->user->needsDailyRefresh())->toBeFalse();
    expect($this->user->userQuests()->count())->toBe(1);

    $response = $this->get('/');

    $response->assertStatus(200)->assertViewHas('quests');

    $this->user->refresh();

    expect($this->user->userQuests()->count())->toBe(1);
});

test('home page generates new random quests after refresh', function () {
    $this->user->update(['last_daily_refresh' => now()->subDays(1)]);
    
    $this->actingAs($this->user->fresh());

    $response1 = $this->get('/');
    $response1->assertStatus(200)->assertViewHas('quests');

    $quests1 = $response1->viewData('quests');
    expect($quests1->count())->toBeGreaterThan(0);

    $this->user->update(['last_daily_refresh' => now()->subDays(1)]);

    $response2 = $this->get('/');
    $response2->assertStatus(200)->assertViewHas('quests');

    $quests2 = $response2->viewData('quests');
    expect($quests2->count())->toBeGreaterThan(0);

    expect($quests1->count())->toBe($this->user->daily_quest_count ?? 3);
    expect($quests2->count())->toBe($this->user->daily_quest_count ?? 3);
});

test('timer calculations are correct for different times', function () {
    $yesterday = Carbon::now()->subDays(1)->hour(23)->minute(59)->second(59);
    $this->user->update(['last_daily_refresh' => $yesterday]);

    expect($this->user->needsDailyRefresh())->toBeTrue();

    $this->user->update(['last_daily_refresh' => now()]);
    expect($this->user->needsDailyRefresh())->toBeFalse();

    $almostMidnight = Carbon::now()->hour(23)->minute(59)->second(59);
    $this->user->update(['last_daily_refresh' => $almostMidnight]);
    expect($this->user->needsDailyRefresh())->toBeFalse();
});
