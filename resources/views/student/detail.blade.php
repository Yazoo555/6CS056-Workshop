<!DOCTYPE html>
<html>
<head>
    <title>Student Details</title>
    <style>
        p { margin: 6px 0; }
        .label { font-weight: bold; display: inline-block; width: 130px; }
    </style>
</head>
<body>

    <h1>Student Details</h1>

    <p><span class="label">ID:</span> {{ $student->id }}</p>
    <p><span class="label">Name:</span> {{ $student->name }}</p>
    <p><span class="label">Email:</span> {{ $student->email }}</p>
    <p><span class="label">Phone:</span> {{ $student->phone }}</p>
    <p><span class="label">Address:</span> {{ $student->address ?? '—' }}</p>
    <p><span class="label">Date of Birth:</span> {{ $student->date_of_birth ?? '—' }}</p>

    <br>
    <a href="/students/{{ $student->id }}/edit">✏️ Edit Student</a>
    <br><br>
    <a href="/students">⬅ Back to Students</a>

</body>
</html>
