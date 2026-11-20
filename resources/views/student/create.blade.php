<x-layouts.app title="Create Student">
    <h1>Create Student</h1>

    @php
        $student = new \App\Models\Student();
    @endphp

    <x-students.form :student="$student" :action="route('students.store')" submit-label="Create Student" />

    <br>
    <a href="{{ route('students.index') }}">Back to Students</a>
</x-layouts.app>
