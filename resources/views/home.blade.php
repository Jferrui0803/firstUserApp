@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">{{ __('Dashboard') }}</div>

                <div class="card-body bg-light">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="text-center">{{ __('You are logged in!') }}</p>

                    <div class="mt-4">
                        @if (Auth::user()->role === 'superAdmin')
                            <a href="{{ route('superAdmin.index') }}" class="btn btn-primary">Ir al Panel de SuperAdmin</a>
                        @elseif (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.index') }}" class="btn btn-primary">Ir al Panel de Admin</a>
                        @else
                            <a href="{{ route('user.index') }}" class="btn btn-primary">Ir al Inicio</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
