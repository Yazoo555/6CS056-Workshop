<!DOCTYPE html>
<html>
<head>
    <title>Courses</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        .success { color: green; font-weight: bold; margin-bottom: 10px; }
        .actions a, .actions button { margin-right: 6px; }
    </style>
</head>
<body>

    <h1>Courses</h1>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <p>
        <a href="/course/create">Create Course</a>
    </p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Duration (weeks)</th>
                <th>Fee</th>
                <th>Difficulty</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($courses as $course)
                <tr>
                    <td>{{ $course->id }}</td>
                    <td>{{ $course->name }}</td>
                    <td>{{ $course->description }}</td>
                    <td>{{ $course->duration }}</td>
                    <td>{{ $course->fee }}</td>
                    <td>{{ $course->difficulty }}</td>
                    <td>{{ $course->is_active ? 'Yes' : 'No' }}</td>
                    <td class="actions">
                        <a href="/courses/{{ $course->id }}">View</a>
                        <a href="/courses/{{ $course->id }}/edit">Edit</a>

                        <form action="/courses/{{ $course->id }}"
                              method="POST"
                              style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No courses found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
