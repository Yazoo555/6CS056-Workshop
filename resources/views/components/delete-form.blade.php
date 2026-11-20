@props(['action'])

<form action="{{ $action }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this record?');">
    @csrf
    @method('DELETE')

    <button type="submit">Delete</button>
</form>
