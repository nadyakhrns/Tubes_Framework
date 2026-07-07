<x-app-layout title="Profile">
    <div class="profile-grid">
        <section class="app-card">
            @include('profile.partials.update-profile-information-form')
        </section>

        <section class="app-card">
            @include('profile.partials.update-password-form')
        </section>

        <section class="app-card">
            @include('profile.partials.delete-user-form')
        </section>
    </div>
</x-app-layout>
