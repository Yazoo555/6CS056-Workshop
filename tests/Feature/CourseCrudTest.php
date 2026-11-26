<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CourseCrudTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    private function makeCourse(array $overrides = []): Course
    {
        return $this->user->courses()->create(array_merge([
            'name' => 'Web Development',
            'description' => 'Full stack web development',
            'duration' => 12,
            'fee' => 1500.00,
            'difficulty' => 'Medium',
            'is_active' => true,
        ], $overrides));
    }

    public function test_course_list_displays_records(): void
    {
        $course = $this->makeCourse();

        $response = $this->get('/courses');

        $response->assertStatus(200);
        $response->assertSee('Web Development');
        $response->assertSee((string) $course->id);
    }

    public function test_create_form_contains_csrf_token(): void
    {
        $response = $this->get('/courses/create');

        $response->assertStatus(200);
        $response->assertSee('name="_token"', false);
        $response->assertSee('action="'.route('courses.store').'"', false);
        $response->assertSee('Easy');
        $response->assertSee('Medium');
        $response->assertSee('Hard');
    }

    public function test_course_can_be_created_and_stored(): void
    {
        $response = $this->post('/courses', [
            'name' => 'Data Science',
            'description' => 'Analytics and ML',
            'duration' => 8,
            'fee' => 2000.50,
            'difficulty' => 'Hard',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/courses');

        $this->assertDatabaseHas('courses', [
            'name' => 'Data Science',
            'duration' => 8,
            'difficulty' => 'Hard',
            'is_active' => 1,
        ]);
    }

    public function test_course_creation_validates_input(): void
    {
        $countBefore = Course::count();

        $response = $this->post('/courses', [
            'name' => '',
            'description' => '',
            'duration' => 'abc',
            'fee' => 'free',
            'difficulty' => 'Impossible',
            'is_active' => 'maybe',
        ]);

        $response->assertSessionHasErrors(['name', 'description', 'duration', 'fee', 'difficulty', 'is_active']);
        $this->assertSame($countBefore, Course::count());
    }

    public function test_course_detail_page_displays_record(): void
    {
        $course = $this->makeCourse(['name' => 'Cloud Computing']);

        $response = $this->get('/courses/'.$course->id);

        $response->assertStatus(200);
        $response->assertSee('Cloud Computing');
        $response->assertSee('1500');
    }

    public function test_edit_form_contains_csrf_and_method_spoofing(): void
    {
        $course = $this->makeCourse();

        $response = $this->get('/courses/'.$course->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee('name="_token"', false);
        $response->assertSee('value="PUT"', false);
        $response->assertSee('Web Development');
    }

    public function test_course_can_be_updated(): void
    {
        $course = $this->makeCourse();

        $response = $this->put('/courses/'.$course->id, [
            'name' => 'Advanced Web Development',
            'description' => 'Updated',
            'duration' => 16,
            'fee' => 2500.00,
            'difficulty' => 'Hard',
            'is_active' => '0',
        ]);

        $response->assertRedirect('/courses/'.$course->id);

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'name' => 'Advanced Web Development',
            'duration' => 16,
            'difficulty' => 'Hard',
            'is_active' => 0,
        ]);
    }

    public function test_course_can_be_deleted(): void
    {
        $course = $this->makeCourse();

        $response = $this->delete('/courses/'.$course->id);

        $response->assertRedirect('/courses');
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }
}
