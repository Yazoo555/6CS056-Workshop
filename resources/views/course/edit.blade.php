<x-layouts.app title="Edit Course">
    <h1>Edit Course</h1>

    <x-courses.form :course="$course" :action="route('courses.update', $course)" submit-label="Update Course" />

    <br>
    <a href="{{ route('courses.show', $course) }}">View</a> |
    <a href="{{ route('courses.index') }}">Back to Courses</a>
</x-layouts.app>
