<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ActivityService;
use App\Models\Activity;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $activityService;
    protected $user;
    protected $comment;

    protected function setUp(): void
    {
        parent::setUp();
        $this->activityService = new ActivityService();
        $this->user = User::factory()->create();
        $this->comment = Comment::factory()->create();
    }

    public function test_can_create_activity_log()
    {
        $data = [
            'user_id' => $this->user->id,
            'action' => 'create',
            'module' => 'comments',
            'comment_id' => $this->comment->id,
            'old_values' => [],
            'new_values' => $this->comment->toArray()
        ];

        $activity = $this->activityService->create($data);

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertEquals($this->user->id, $activity->user_id);
        $this->assertEquals('create', $activity->action);
        $this->assertEquals('comments', $activity->module);
        $this->assertEquals($this->comment->id, $activity->comment_id);
    }

    public function test_can_create_activity_log_without_optional_fields()
    {
        $data = [
            'user_id' => $this->user->id,
            'action' => 'create',
            'module' => 'comments',
            'comment_id' => $this->comment->id
        ];

        $activity = $this->activityService->create($data);

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertNull($activity->old_values);
        $this->assertNull($activity->new_values);
    }

    public function test_cannot_create_activity_log_without_required_fields()
    {
        $this->expectException(\InvalidArgumentException::class);

        $data = [
            'user_id' => $this->user->id,
            'action' => 'create'
        ];

        $this->activityService->create($data);
    }
} 