<?php

use App\Models\User;
use App\Models\Quest;
use App\Models\UserQuest;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create test user
    $this->user = User::factory()->create([
        'daily_quest_count' => 3,
        'quest_attributes' => ['strength', 'constitution', 'intelligence', 'charisma'],
        'last_daily_refresh' => now(),
        'assessment_completed' => true,
    ]);

    // Create some test quests
    Quest::factory()->count(10)->create();

    // Authenticate the user
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
    // Create some user quest records from yesterday
    $quest = Quest::first();
    UserQuest::create([
        'user_id' => $this->user->id,
        'quest_id' => $quest->id,
        'status' => 'completed',
    ]);

    expect($this->user->userQuests()->count())->toBe(1);

    // Refresh quests
    $this->user->refreshDailyQuests();

    // Old records should be deleted
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
    // Set last refresh to yesterday
    $this->user->update(['last_daily_refresh' => now()->subDays(1)]);
    
    // Re-authenticate with fresh user data
    $this->actingAs($this->user->fresh());

    // Create user quest from yesterday
    $quest = Quest::first();
    UserQuest::create([
        'user_id' => $this->user->id,
        'quest_id' => $quest->id,
        'status' => 'started',
    ]);

    expect($this->user->needsDailyRefresh())->toBeTrue();
    expect($this->user->userQuests()->count())->toBe(1);

    // Visit home page
    $response = $this->get('/');

    $response->assertStatus(200)->assertViewHas('quests');

    // Refresh user instance
    $this->user->refresh();

    // Old quests should be cleared
    expect($this->user->userQuests()->count())->toBe(0);
    // Timestamp should be updated
    expect($this->user->last_daily_refresh->isSameDay(now()))->toBeTrue();
});

test('home page does not refresh quests when needsDailyRefresh is false', function () {
    // Set last refresh to today
    $this->user->update(['last_daily_refresh' => now()]);
    
    // Re-authenticate with fresh user data
    $this->actingAs($this->user->fresh());

    // Create a user quest from today
    $quest = Quest::first();
    UserQuest::create([
        'user_id' => $this->user->id,
        'quest_id' => $quest->id,
        'status' => 'started',
    ]);

    expect($this->user->needsDailyRefresh())->toBeFalse();
    expect($this->user->userQuests()->count())->toBe(1);

    // Visit home page
    $response = $this->get('/');

    $response->assertStatus(200)->assertViewHas('quests');

    // Refresh user instance
    $this->user->refresh();

    // Quest should still be there (not refreshed)
    expect($this->user->userQuests()->count())->toBe(1);
});

test('home page generates new random quests after refresh', function () {
    // Set last refresh to yesterday
    $this->user->update(['last_daily_refresh' => now()->subDays(1)]);
    
    // Re-authenticate with fresh user data
    $this->actingAs($this->user->fresh());

    // Visit home page twice to check if new quests are generated
    $response1 = $this->get('/');
    $response1->assertStatus(200)->assertViewHas('quests');

    $quests1 = $response1->viewData('quests');
    expect($quests1->count())->toBeGreaterThan(0);

    // Set refresh time back to yesterday again
    $this->user->update(['last_daily_refresh' => now()->subDays(1)]);

    $response2 = $this->get('/');
    $response2->assertStatus(200)->assertViewHas('quests');

    $quests2 = $response2->viewData('quests');
    expect($quests2->count())->toBeGreaterThan(0);

    // Both should have quests (randomly selected)
    expect($quests1->count())->toBe($this->user->daily_quest_count ?? 3);
    expect($quests2->count())->toBe($this->user->daily_quest_count ?? 3);
});

test('timer calculations are correct for different times', function () {
    // Test at various times to ensure timer would count to 0 correctly
    $yesterday = Carbon::now()->subDays(1)->hour(23)->minute(59)->second(59);
    $this->user->update(['last_daily_refresh' => $yesterday]);

    expect($this->user->needsDailyRefresh())->toBeTrue();

    // Today (should not refresh)
    $this->user->update(['last_daily_refresh' => now()]);
    expect($this->user->needsDailyRefresh())->toBeFalse();

    // Just before midnight of the same day (should not refresh)
    $almostMidnight = Carbon::now()->hour(23)->minute(59)->second(59);
    $this->user->update(['last_daily_refresh' => $almostMidnight]);
    expect($this->user->needsDailyRefresh())->toBeFalse();
});
