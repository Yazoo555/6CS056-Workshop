@props([
    'student',
    'action',
    'submitLabel',
])

<form action="{{ $action }}" method="POST">
    @csrf

    @if($student->exists)
        @method('PUT')
    @endif

    <x-form-input name="name" label="Name" :value="$student->name" />
    <x-form-input name="email" label="Email" type="email" :value="$student->email" />
    <x-form-input name="phone" label="Phone" :value="$student->phone" />
    <x-form-textarea name="address" label="Address" :value="$student->address" />
    <x-form-input name="date_of_birth" label="Date of Birth" type="date" :value="$student->date_of_birth?->format('Y-m-d')" />

    <button type="submit" class="btn">{{ $submitLabel }}</button>
</form>
