<x-layouts.app title="Edit Student">
    <h1>Edit Student</h1>

    <x-students.form :student="$student" :action="route('students.update', $student)" submit-label="Update Student" />

    <br>
    <a href="{{ route('students.show', $student) }}">View</a> |
    <a href="{{ route('students.index') }}">Back to Students</a>
</x-layouts.app>
