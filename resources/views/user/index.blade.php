@extends('layouts.app') <!-- Extiende el layout principal -->

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h3 class="mb-0 fw-bold">My Profile Information</h3>
                </div>
                <div class="card-body bg-light px-4 py-5">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('user.update') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="name" class="form-label text-dark fw-semibold">* Full Name</label>
                            <input type="text" class="form-control form-control-lg shadow-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ $user->name }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label text-dark fw-semibold">* Email Address</label>
                            <input type="email" class="form-control form-control-lg shadow-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required {{ $user->role === 'superAdmin' ? 'readonly' : '' }}>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label text-dark fw-semibold">Update your password:</label>
                            <input type="password" class="form-control form-control-lg shadow-sm @error('password') is-invalid @enderror" id="password" name="password" placeholder="Leave blank to keep current password">
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label text-dark fw-semibold">Confirm Your New Password</label>
                            <input type="password" class="form-control form-control-lg shadow-sm" id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-dark btn-lg shadow">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function resetEmailField() {
        var emailField = document.getElementById('email');
        if (emailField.value.trim() === '') {
            emailField.value = '{{ $user->email }}'; // Vuelve a poner el correo original
        }
    }
</script>

@endsection