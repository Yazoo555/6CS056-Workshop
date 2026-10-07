<!DOCTYPE html>
<html>
<head>
    <title>Course Details</title>
    <style>
        p { margin: 6px 0; }
        .label { font-weight: bold; display: inline-block; width: 130px; }
    </style>
</head>
<body>

    <h1>Course Details</h1>

    <p><span class="label">ID:</span> {{ $course->id }}</p>
    <p><span class="label">Name:</span> {{ $course->name }}</p>
    <p><span class="label">Description:</span> {{ $course->description }}</p>
    <p><span class="label">Duration:</span> {{ $course->duration }} weeks</p>
    <p><span class="label">Fee:</span> {{ $course->fee }}</p>
    <p><span class="label">Difficulty:</span> {{ $course->difficulty }}</p>
    <p><span class="label">Active:</span> {{ $course->is_active ? 'Yes' : 'No' }}</p>

    <br>
    <a href="/courses/{{ $course->id }}/edit">Edit Course</a>
    <br><br>
    <a href="/courses">Back to Courses</a>

</body>
</html>
