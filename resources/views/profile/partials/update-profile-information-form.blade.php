<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="flex flex-wrap items-center gap-4">
            <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-full bg-emerald-100 text-2xl font-bold text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">
                <img id="profile-photo-preview" src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : '' }}" alt="Foto profil" class="{{ $user->profile_photo_path ? '' : 'hidden' }} h-full w-full object-cover">
                <span id="profile-photo-initial" class="{{ $user->profile_photo_path ? 'hidden' : '' }}">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            </div>
            <div class="min-w-0">
                <label for="photo" class="admin-btn-secondary min-h-11 cursor-pointer text-slate-700 dark:text-slate-100">
                    <i class="fas fa-camera" aria-hidden="true"></i>Ganti foto
                </label>
                <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only" aria-describedby="photo-help">
                <p id="photo-help" class="mt-2 text-xs text-slate-500 dark:text-slate-400">JPG, PNG, atau WebP. Maksimal 2 MB.</p>
                <x-input-error class="mt-2" :messages="$errors->get('photo')" />
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

<script>
    (() => {
        const input = document.getElementById('photo');
        const preview = document.getElementById('profile-photo-preview');
        const initial = document.getElementById('profile-photo-initial');
        let previewUrl;

        input?.addEventListener('change', () => {
            const file = input.files?.[0];
            if (!file) return;

            if (previewUrl) URL.revokeObjectURL(previewUrl);
            previewUrl = URL.createObjectURL(file);
            preview.src = previewUrl;
            preview.classList.remove('hidden');
            initial?.classList.add('hidden');
        });
    })();
</script>
