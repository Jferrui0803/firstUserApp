@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg p-4 bg-white border-0">
        <h1 class="text-center text-dark fw-bold">Admin Dashboard</h1>

        <!-- Botón para abrir la modal de crear usuario -->
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAdminUserModal">
                <i class="fas fa-user-plus"></i> Add New User
            </button>
        </div>

        <!-- Incluir las modales -->
        @include('modal.admincreate')
        @include('modal.edit', ['user' => $users])

        <div class="card border-0 shadow-sm bg-light">
        <h2 class="text-2xl font-semibold mb-4 text-center">Registered Users</h2>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Verified</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            @if ($user->role !== 'superAdmin')
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{!! $user->email_verified_at ? '<span class="badge bg-success">YES ✅</span>' : '<span class="badge bg-danger">NO ❌</span>' !!}</td>
                                <td>{{ ucfirst($user->role) }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Botón para Editar -->

                                        @if (auth()->user()->id === $user->id)
                                        <!-- Botón para redirigir a otra vista -->
                                        <a href="{{ route('user.index') }}" class="btn btn-secondary">
                                            Edit
                                        </a>
                                        @else
                                        <!-- Botón para abrir el modal -->
                                        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editUserModal"
                                            onclick="fillEditModal('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ addslashes($user->role) }}')">
                                            Edit
                                        </button>
                                        @endif

                                        <!-- Botón para Eliminar -->
                                        <button onclick="deleteUser('{{ $user->id }}')" class="btn btn-danger">
                                            Delete
                                        </button>

                                        @if (!$user->email_verified_at)
                                        <button onclick="verifyUser('{{ $user->id }}')" class="btn btn-success">
                                            Verify
                                        </button>
                                        @endif

                                        @if ($user->email_verified_at)
                                        <button onclick="desVerifyUser('{{ $user->id }}')" class="btn btn-dark">
                                            Unverify
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

            <script>
                function verifyUser(userId) {
                    let pathName = window.location.pathname;

                    let pos = pathName.indexOf('/admin');

                    let basePath = (pos !== -1) ? pathName.substring(0, pos) : '';

                    let actionUrl = window.location.origin + basePath + '/admin/verify/' + userId;
                    document.getElementById('edit-user-form').action = actionUrl;
                    if (confirm('Are you sure you want to verify this user?')) {
                        fetch(actionUrl, {
                                method: 'PATCH',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                },
                            })
                            .then(response => {
                                console.log(response);
                                if (response.ok) {
                                    alert('User verified successfully.');
                                    location.reload();
                                } else {
                                    alert('There was a problem verifying the user.');
                                }
                            })
                            .catch(error => {
                                console.error('Error verifying:', error);
                                alert('Error trying to verify the user.');
                            });
                    }
                }

                function desVerifyUser(userId) {
                    let pathName = window.location.pathname;
                    let pos = pathName.indexOf('/admin');
                    let basePath = (pos !== -1) ? pathName.substring(0, pos) : '';

                    // Cambiado: de '/admin/unverify/' a '/admin/desVerify/'
                    let actionUrl = window.location.origin + basePath + '/admin/desVerify/' + userId;

                    // Opcional: Si este form no es necesario, puedes quitar esta línea.
                    document.getElementById('edit-user-form').action = actionUrl;

                    if (confirm('Are you sure you want to unverify this user?')) {
                        fetch(actionUrl, {
                                method: 'PATCH',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                },
                            })
                            .then(response => {
                                console.log(response);
                                if (response.ok) {
                                    alert('User unverified successfully.');
                                    location.reload();
                                } else {
                                    alert('There was a problem unverifying the user.');
                                }
                            })
                            .catch(error => {
                                console.error('Error unverifying:', error);
                                alert('Error trying to unverify the user.');
                            });
                    }
                }

                function fillEditModal(id, name, email, role) {

                    const roleField = document.getElementById('edit-role');
                    const emailField = document.getElementById('edit-email');
                    if (role === 'superAdmin') {
                        roleField.disabled = true;
                        emailField.readOnly = true;
                    } else {
                        roleField.disabled = false;
                        emailField.readOnly = false;
                    }


                    document.getElementById('edit-name').value = name;
                    emailField.value = email;
                    roleField.value = role;


                    let pathName = window.location.pathname;

                    let pos = pathName.indexOf('/admin');

                    let basePath = (pos !== -1) ? pathName.substring(0, pos) : '';

                    let actionUrl = window.location.origin + basePath + '/admin/edit/' + id;
                    document.getElementById('edit-user-form').action = actionUrl;
                }

                function deleteUser(userId) {

                    let pathName = window.location.pathname;

                    let pos = pathName.indexOf('/admin');

                    let basePath = (pos !== -1) ? pathName.substring(0, pos) : '';

                    let actionUrl = window.location.origin + basePath + '/admin/delete/' + userId;
                    document.getElementById('edit-user-form').action = actionUrl;
                    if (confirm('Are you sure you want to delete this user?')) {
                        fetch(actionUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => {
                                if (response.ok) {
                                    alert('User deleted successfully.');
                                    location.reload();
                                } else {
                                    alert('There was a problem deleting the user.');
                                }
                            })
                            .catch(error => {
                                console.error('Error deleting:', error);
                                alert('Error trying to delete the user.');
                            });
                    }
                }
            </script>
 @endsection