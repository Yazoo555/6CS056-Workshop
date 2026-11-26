<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CourseAuthorizationTest extends TestCase
{
    use DatabaseTransactions;

    private User $owner;

    private User $otherUser;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create();
        $this->otherUser = User::factory()->create();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    private function makeCourse(User $user, array $overrides = []): Course
    {
        return $user->courses()->create(array_merge([
            'name' => 'Laravel Basics',
            'description' => 'Intro to Laravel',
            'duration' => 6,
            'fee' => 1000.00,
            'difficulty' => 'Easy',
            'is_active' => true,
        ], $overrides));
    }

    public function test_created_course_is_owned_by_authenticated_user(): void
    {
        $this->actingAs($this->owner)->post('/courses', [
            'name' => 'Owned Course',
            'description' => 'Created by owner',
            'duration' => 4,
            'fee' => 500,
            'difficulty' => 'Easy',
            'is_active' => '1',
        ])->assertRedirect('/courses');

        $this->assertDatabaseHas('courses', [
            'name' => 'Owned Course',
            'user_id' => $this->owner->id,
        ]);
    }

    public function test_user_cannot_access_another_users_course(): void
    {
        $course = $this->makeCourse($this->owner);

        $this->actingAs($this->otherUser)->get('/courses/'.$course->id)->assertForbidden();
        $this->actingAs($this->otherUser)->get('/courses/'.$course->id.'/edit')->assertForbidden();
        $this->actingAs($this->otherUser)->put('/courses/'.$course->id, [
            'name' => 'Hijacked',
            'description' => 'Changed',
            'duration' => 1,
            'fee' => 0,
            'difficulty' => 'Easy',
            'is_active' => '1',
        ])->assertForbidden();
        $this->actingAs($this->otherUser)->delete('/courses/'.$course->id)->assertForbidden();

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'name' => 'Laravel Basics',
        ]);
    }

    public function test_owner_can_manage_their_course(): void
    {
        $course = $this->makeCourse($this->owner);

        $this->actingAs($this->owner)->get('/courses/'.$course->id)->assertOk();
        $this->actingAs($this->owner)->get('/courses/'.$course->id.'/edit')->assertOk();
        $this->actingAs($this->owner)->delete('/courses/'.$course->id)->assertRedirect('/courses');

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    public function test_admin_can_manage_any_course(): void
    {
        $course = $this->makeCourse($this->owner);

        $this->actingAs($this->admin)->get('/courses/'.$course->id)->assertOk();
        $this->actingAs($this->admin)->get('/courses/'.$course->id.'/edit')->assertOk();

        $this->actingAs($this->admin)->put('/courses/'.$course->id, [
            'name' => 'Updated By Admin',
            'description' => 'Admin change',
            'duration' => 8,
            'fee' => 1500,
            'difficulty' => 'Medium',
            'is_active' => '1',
        ])->assertRedirect('/courses/'.$course->id);

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'name' => 'Updated By Admin',
        ]);

        $this->actingAs($this->admin)->delete('/courses/'.$course->id)->assertRedirect('/courses');
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    public function test_course_list_only_shows_owned_courses_for_regular_users(): void
    {
        $this->makeCourse($this->owner, ['name' => 'Owner Course']);
        $this->makeCourse($this->otherUser, ['name' => 'Other Course']);

        $this->actingAs($this->owner)->get('/courses')
            ->assertOk()
            ->assertSee('Owner Course')
            ->assertDontSee('Other Course');
    }

    public function test_admin_course_list_shows_all_courses(): void
    {
        $this->makeCourse($this->owner, ['name' => 'Owner Course']);
        $this->makeCourse($this->otherUser, ['name' => 'Other Course']);

        $this->actingAs($this->admin)->get('/courses')
            ->assertOk()
            ->assertSee('Owner Course')
            ->assertSee('Other Course');
    }
}
