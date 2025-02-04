@extends('layouts.app')

@section('content')
<div class="bg-light container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-4 text-center">Admin Dashboard</h1>

    <!-- Botón para abrir la modal de crear usuario -->
    <button class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#createAdminUserModal">
        Add New User
    </button>

    <!-- Incluir las modales -->
    @include('modal.admincreate')
    @include('modal.edit', ['user' => $users])

    <!-- Lista de Usuarios -->
    <div class="bg-white shadow-lg rounded-lg p-4">
        <h2 class="text-2xl font-semibold mb-4 text-center">Registered Users</h2>
        <a href="{{ route('user.index') }}" class="btn btn-info mb-4">
            Edit Myself
        </a>
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center">Name</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Verified</th>
                    <th class="text-center">Role</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                @if ($user->role !== 'superAdmin')
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->email_verified_at ? 'YES ✅' : 'NO ❌' }}</td>
                    <td>{{ $user->role }}</td>
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

        <!-- Paginación -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
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