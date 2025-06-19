<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- 社員（会社名を送る） -->
        <label>
            <input type="radio" name="company" value="Y's tec"> 社員
        </label>
        <label>
            <input type="radio" name="company" value="null"> 下請け
        </label>


        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <!-- 権限ラジオボタン -->
        <div class="mt-4">
            <div class="mt-1">
                <label>
                    <input type="radio" name="role" value="0" {{ old('role') == '0' ? 'checked' : '' }}>
                    全権限あり
                </label><br>
                <label>
                    <input type="radio" name="role" value="1" {{ old('role') == '1' ? 'checked' : '' }}>
                    一部権限あり
                </label><br>
                <label>
                    <input type="radio" name="role" value="2" {{ old('role') == '2' ? 'checked' : '' }}>
                    権限なし
                </label><br>
                <label>
                    <input type="radio" name="role" value="3" {{ old('role') == '3' ? 'checked' : '' }}>
                    常用
                </label><br>
                <label>
                    <input type="radio" name="role" value="4" {{ old('role') == '4' ? 'checked' : '' }}>
                    外注
                </label>
            </div>
        </div>
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
