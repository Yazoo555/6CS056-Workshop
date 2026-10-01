<?php

use App\Models\Student;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// List
Route::get('/students', function () {
    $students = Student::all();
    return view('student.list', ['students' => $students]);
})->name('students.index');

// Create form
Route::get('/student/create', function () {
    return view('student.create');
});

// Create (store)
Route::post('/students', function (Request $request) {
    $validated = $request->validate([
        'name'          => 'required|string|max:255',
        'email'         => 'required|email|max:255|unique:students,email',
        'phone'         => 'required|string|max:20',
        'address'       => 'nullable|string|max:500',
        'date_of_birth' => 'nullable|date',
    ]);

    $student = Student::create($validated);

    return redirect()->route('students.index')
        ->with('success', "Student {$student->name} created successfully!");
});

// Detail
Route::get('/students/{id}', function ($id) {
    $student = Student::findOrFail($id);
    return view('student.detail', ['student' => $student]);
});

// Edit form
Route::get('/students/{id}/edit', function ($id) {
    $student = Student::findOrFail($id);
    return view('student.edit', ['student' => $student]);
});

// Update (PUT)
Route::put('/students/{id}', function (Request $request, $id) {
    $student = Student::findOrFail($id);

    $validated = $request->validate([
        'name'          => 'required|string|max:255',
        'email'         => ['required', 'email', 'max:255',
                            Rule::unique('students')->ignore($student->id)],
        'phone'         => 'required|string|max:20',
        'address'       => 'nullable|string|max:500',
        'date_of_birth' => 'nullable|date',
    ]);

    $student->update($validated);

    return redirect()->route('students.index')
        ->with('success', 'Student updated successfully!');
});

// Delete
Route::delete('/students/{id}', function ($id) {
    $student = Student::findOrFail($id);
    $student->delete();

    return redirect()->route('students.index')
        ->with('success', 'Student deleted successfully!');
});
