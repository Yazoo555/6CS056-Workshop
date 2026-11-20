<x-layouts.app title="Student Details">
    <h1>Student Details</h1>

    <p><span class="label">ID:</span> {{ $student->id }}</p>
    <p><span class="label">Name:</span> {{ $student->name }}</p>
    <p><span class="label">Email:</span> {{ $student->email }}</p>
    <p><span class="label">Phone:</span> {{ $student->phone }}</p>
    <p><span class="label">Address:</span> {{ $student->address ?? '—' }}</p>
    <p><span class="label">Date of Birth:</span> {{ $student->date_of_birth?->format('Y-m-d') ?? '—' }}</p>
    <p><span class="label">Age:</span> {{ $student->age ?? '—' }}</p>

    <br>
    <a href="{{ route('students.edit', $student) }}">Edit Student</a>
    <br><br>
    <a href="{{ route('students.index') }}">Back to Students</a>
</x-layouts.app>
