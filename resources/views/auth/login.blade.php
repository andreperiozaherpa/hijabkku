<x-guest-layout>
    <div class="card mb-0 shadow-none border-0 bg-transparent">
        <div class="card-body p-0">
            <div class="text-center mb-5">
                <img src="/img/logo.png" alt="Hijabku Logo" style="height: 80px; border-radius: 12px;" class="mb-4 shadow-sm">
                <h2 class="mb-2 text-dark font-weight-bold">Login Karyawan</h2>
                <p class="text-muted">Gunakan email dan kata sandi Anda untuk mengakses sistem POS Hijabkku.</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success mb-4" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="tooltip-end-bottom">
                @csrf

                <!-- Email Address -->
                <div class="mb-4 filled form-group tooltip-end-top">
                    <i data-acorn-icon="email"></i>
                    <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Email Karyawan" />
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4 filled form-group tooltip-end-top">
                    <i data-acorn-icon="lock-off"></i>
                    <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" placeholder="Kata Sandi" />
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                    <label class="form-check-label" for="remember_me">Ingat saya</label>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-gold btn-lg">
                        Masuk ke POS
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
