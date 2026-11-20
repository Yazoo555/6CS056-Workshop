<x-layouts.app title="Create Course">
    <h1>Create Course</h1>

    @php
        $course = new \App\Models\Course();
    @endphp

    <x-courses.form :course="$course" :action="route('courses.store')" submit-label="Create Course" />

    <br>
    <a href="{{ route('courses.index') }}">Back to Courses</a>
</x-layouts.app>
