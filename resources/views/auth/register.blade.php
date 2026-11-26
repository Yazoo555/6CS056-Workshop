<x-layouts.app title="Register">
    <h1>Register</h1>

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <x-form-input name="name" label="Name" />
        <x-form-input name="email" label="Email" type="email" />
        <x-form-input name="password" label="Password" type="password" />
        <x-form-input name="password_confirmation" label="Confirm Password" type="password" />

        <button type="submit" class="btn">Register</button>
    </form>

    <p>
        Already have an account? <a href="{{ route('login') }}">Login</a>
    </p>
</x-layouts.app>
