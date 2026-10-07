<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class StudentCrudTest extends TestCase
{
    use DatabaseTransactions;

    public function test_student_list_displays_records(): void
    {
        $student = Student::create([
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
            'phone' => '1234567890',
        ]);

        $response = $this->get('/students');

        $response->assertStatus(200);
        $response->assertSee('Ada Lovelace');
        $response->assertSee('ada@example.com');
        $response->assertSee((string) $student->id);
    }

    public function test_create_form_contains_csrf_token_and_posts_to_students(): void
    {
        $response = $this->get('/student/create');

        $response->assertStatus(200);
        $response->assertSee('name="_token"', false);
        $response->assertSee('method="POST"', false);
        $response->assertSee('action="/students"', false);
    }

    public function test_student_can_be_created_and_stored(): void
    {
        $response = $this->post('/students', [
            'name' => 'Grace Hopper',
            'email' => 'grace@example.com',
            'phone' => '5551234',
            'address' => '1 Navy Way',
            'date_of_birth' => '1906-12-09',
        ]);

        $response->assertRedirect('/students');

        $this->assertDatabaseHas('students', [
            'name' => 'Grace Hopper',
            'email' => 'grace@example.com',
            'phone' => '5551234',
        ]);
    }

    public function test_student_creation_validates_input(): void
    {
        $countBefore = Student::count();

        $response = $this->post('/students', [
            'name' => '',
            'email' => 'not-an-email',
            'phone' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'phone']);
        $this->assertSame($countBefore, Student::count());
    }

    public function test_student_email_must_be_unique(): void
    {
        Student::create([
            'name' => 'Existing',
            'email' => 'taken@example.com',
            'phone' => '123',
        ]);

        $countBefore = Student::count();

        $response = $this->post('/students', [
            'name' => 'Another',
            'email' => 'taken@example.com',
            'phone' => '456',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertSame($countBefore, Student::count());
    }

    public function test_student_detail_page_displays_record(): void
    {
        $student = Student::create([
            'name' => 'Alan Turing',
            'email' => 'alan@example.com',
            'phone' => '999',
        ]);

        $response = $this->get('/students/'.$student->id);

        $response->assertStatus(200);
        $response->assertSee('Alan Turing');
        $response->assertSee('alan@example.com');
    }

    public function test_student_detail_returns_404_for_missing_record(): void
    {
        $this->get('/students/9999')->assertStatus(404);
    }

    public function test_edit_form_contains_csrf_and_method_spoofing(): void
    {
        $student = Student::create([
            'name' => 'Edit Me',
            'email' => 'edit@example.com',
            'phone' => '111',
        ]);

        $response = $this->get('/students/'.$student->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee('name="_token"', false);
        $response->assertSee('value="PUT"', false);
        $response->assertSee('edit@example.com');
    }

    public function test_student_can_be_updated_without_email_unique_conflict(): void
    {
        $student = Student::create([
            'name' => 'Original',
            'email' => 'original@example.com',
            'phone' => '222',
        ]);

        $response = $this->put('/students/'.$student->id, [
            'name' => 'Updated Name',
            'email' => 'original@example.com',
            'phone' => '333',
        ]);

        $response->assertRedirect('/students');

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Updated Name',
            'phone' => '333',
        ]);
    }

    public function test_student_update_rejects_duplicate_email(): void
    {
        Student::create([
            'name' => 'First',
            'email' => 'first@example.com',
            'phone' => '1',
        ]);
        $second = Student::create([
            'name' => 'Second',
            'email' => 'second@example.com',
            'phone' => '2',
        ]);

        $response = $this->put('/students/'.$second->id, [
            'name' => 'Second',
            'email' => 'first@example.com',
            'phone' => '2',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_student_can_be_deleted(): void
    {
        $student = Student::create([
            'name' => 'Delete Me',
            'email' => 'delete@example.com',
            'phone' => '444',
        ]);

        $response = $this->delete('/students/'.$student->id);

        $response->assertRedirect('/students');
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }
}
