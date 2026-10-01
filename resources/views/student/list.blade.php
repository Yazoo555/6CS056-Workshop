<!DOCTYPE html>
<html>
<head>
    <title>Students</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        .success { color: green; font-weight: bold; margin-bottom: 10px; }
        .actions a, .actions button { margin-right: 6px; }
    </style>
</head>
<body>

    <h1>Students</h1>

    {{-- Flash success message --}}
    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <p>
        <a href="/student/create">➕ Create Student</a>
    </p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->phone }}</td>
                    <td class="actions">
                        <a href="/students/{{ $student->id }}">View</a>
                        <a href="/students/{{ $student->id }}/edit">Edit</a>

                        <form action="/students/{{ $student->id }}"
                              method="POST"
                              style="display:inline"
                              onsubmit="return confirm('Delete this student?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No students found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
