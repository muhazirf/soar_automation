<x-layouts.auth title="Create Account">
    <div class="glass-card rounded-2xl p-8 shadow-glow max-w-md mx-auto">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary/20 rounded-xl border border-primary/30 mb-4">
                <span class="material-symbols-outlined text-4xl text-primary" style="font-variation-settings: 'FILL' 1;">
                    security
                </span>
            </div>
            <h1 class="text-2xl font-bold text-on-surface mb-2">Join Cyber Sentinel</h1>
            <p class="text-sm text-on-surface-variant">Create your account to get started</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="registerForm()">
            @csrf

            <!-- Name -->
            <x-ui.input
                name="name"
                type="text"
                label="Full Name"
                placeholder="John Doe"
                :value="old('name')"
                required="true"
                :error="$errors->first('name')"
                autofocus="true"
            />

            <!-- Email -->
            <x-ui.input
                name="email"
                type="email"
                label="Email Address"
                placeholder="agent@cybersentinel.com"
                :value="old('email')"
                required="true"
                :error="$errors->first('email')"
            />

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-sm font-medium text-on-surface">
                        Password
                    </label>
                    <span :class="passwordStrengthColor" class="text-xs font-medium x-cloak" x-text="passwordStrengthText"></span>
                </div>
                <div class="relative">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        placeholder="••••••••"
                        required
                        autocomplete="new-password"
                        x-model="password"
                        @input="checkStrength"
                        class="w-full px-4 py-2.5 pr-12 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-success/50 focus:border-success/50 transition-colors"
                    >
                    <button
                        type="button"
                        onclick="togglePassword('password', 'password-icon')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors"
                    >
                        <span class="material-symbols-outlined text-lg" id="password-icon">visibility_off</span>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-error mt-1">{{ $message }}</p>
                @enderror

                <!-- Password Strength Bar -->
                <div class="mt-2 h-1 rounded-full bg-surface-container overflow-hidden">
                    <div
                        class="h-full transition-all duration-300 x-cloak"
                        :class="passwordStrengthColor.replace('text-', 'bg-')"
                        :style="`width: ${passwordStrength}%`"
                    ></div>
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-on-surface">
                    Confirm Password
                </label>
                <div class="relative mt-1.5">
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        placeholder="••••••••"
                        required
                        autocomplete="new-password"
                        x-model="passwordConfirmation"
                        class="w-full px-4 py-2.5 pr-12 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-success/50 focus:border-success/50 transition-colors"
                    >
                    <button
                        type="button"
                        onclick="togglePassword('password_confirmation', 'confirm-icon')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors"
                    >
                        <span class="material-symbols-outlined text-lg" id="confirm-icon">visibility_off</span>
                    </button>
                </div>
                <p x-show="password && passwordConfirmation && password !== passwordConfirmation" class="text-xs text-error mt-1 x-cloak">
                    Passwords do not match
                </p>
            </div>

            <!-- Terms -->
            <div class="flex items-start gap-3">
                <input
                    id="terms"
                    name="terms"
                    type="checkbox"
                    required
                    class="mt-0.5 w-4 h-4 rounded border-outline-variant/30 text-success focus:ring-success/50 bg-surface-container"
                >
                <label for="terms" class="text-sm text-on-surface-variant">
                    I agree to the <a href="#" class="text-primary hover:text-primary/80">Terms of Service</a> and <a href="#" class="text-primary hover:text-primary/80">Privacy Policy</a>
                </label>
            </div>

            <!-- Submit Button -->
            <x-ui.button type="submit" variant="primary" size="md" class="w-full">
                <span class="material-symbols-outlined">person_add</span>
                Create Account
            </x-ui.button>
        </form>

        <!-- Login Link -->
        <p class="text-center text-sm text-on-surface-variant mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-primary hover:text-primary/80 font-medium transition-colors">
                Sign in instead
            </a>
        </p>
    </div>

    @push('scripts')
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility_off';
            }
        }

        function registerForm() {
            return {
                password: '',
                passwordConfirmation: '',
                passwordStrength: 0,
                passwordStrengthText: '',
                passwordStrengthColor: 'text-on-surface-variant',

                checkStrength() {
                    if (!this.password) {
                        this.passwordStrength = 0;
                        this.passwordStrengthText = '';
                        this.passwordStrengthColor = 'text-on-surface-variant';
                        return;
                    }

                    let strength = 0;

                    // Length check
                    if (this.password.length >= 8) strength += 25;
                    if (this.password.length >= 12) strength += 15;

                    // Character variety
                    if (/[a-z]/.test(this.password)) strength += 15;
                    if (/[A-Z]/.test(this.password)) strength += 15;
                    if (/[0-9]/.test(this.password)) strength += 15;
                    if (/[^a-zA-Z0-9]/.test(this.password)) strength += 15;

                    this.passwordStrength = Math.min(strength, 100);

                    if (this.passwordStrength < 30) {
                        this.passwordStrengthText = 'Weak';
                        this.passwordStrengthColor = 'text-error';
                    } else if (this.passwordStrength < 60) {
                        this.passwordStrengthText = 'Fair';
                        this.passwordStrengthColor = 'text-warning';
                    } else if (this.passwordStrength < 80) {
                        this.passwordStrengthText = 'Good';
                        this.passwordStrengthColor = 'text-info';
                    } else {
                        this.passwordStrengthText = 'Strong';
                        this.passwordStrengthColor = 'text-success';
                    }
                }
            }
        }
    </script>
    @endpush
</x-layouts.auth>
