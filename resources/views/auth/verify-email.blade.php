<x-guest-layout>
    <h1 class="auth-title">Verify email</h1>
    <p class="auth-subtitle">Check your inbox for the verification link we sent after registration.</p>

    @if (session('status') === 'verification-link-sent')
        <div class="alert alert-success" role="alert">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="d-grid gap-2">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-send me-1"></i>
                Resend verification email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn btn-outline-secondary w-100">
                Log out
            </button>
        </form>
    </div>
</x-guest-layout>
