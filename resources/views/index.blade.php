@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Hola
                </div>
                <div class="card-body">
                    <a href="{{ url('login') }}">Login</a>
                    <br>
                    <a href="{{ route('verificado') }}">Verificado</a>
                    <br>
                    <a href=" {{ route('home') }} ">Logueado</a>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection