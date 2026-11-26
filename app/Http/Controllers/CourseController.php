<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $courses = $user->role === 'admin'
            ? Course::latest()->get()
            : $user->courses()->latest()->get();

        return view('course.list', ['courses' => $courses]);
    }

    public function create(): View
    {
        return view('course.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $course = $request->user()->courses()->create($request->validate($this->rules()));

        return redirect()
            ->route('courses.index')
            ->with('success', "Course {$course->name} created successfully!");
    }

    public function show(Course $course): View
    {
        Gate::authorize('manage-course', $course);

        return view('course.detail', ['course' => $course]);
    }

    public function edit(Course $course): View
    {
        Gate::authorize('manage-course', $course);

        return view('course.edit', ['course' => $course]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        Gate::authorize('manage-course', $course);

        $course->update($request->validate($this->rules()));

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course): RedirectResponse
    {
        Gate::authorize('manage-course', $course);

        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course deleted successfully!');
    }

    private function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'fee' => 'required|numeric|min:0',
            'difficulty' => 'required|in:Easy,Medium,Hard',
            'is_active' => 'required|boolean',
        ];
    }
}
