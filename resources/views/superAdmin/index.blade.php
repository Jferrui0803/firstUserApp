@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg p-4 bg-white border-0">
        <h1 class="text-center text-dark fw-bold">SuperAdmin Dashboard</h1>

        <div class="card-body bg-white px-4 py-5">
            <!-- Botón para abrir la modal de crear usuario -->
            <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="fas fa-user-plus"></i> Add New User
                </button>
            </div>
            
            <!-- Incluir las modales -->
            @include('modal.create')
            @include('modal.edit', ['user' => $users])
            
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-header bg-dark text-white text-center">
                    <h2 class="mb-0">Registered Users</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle text-center">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Verified</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{!! $user->email_verified_at ? '<span class="badge bg-success">YES ✅</span>' : '<span class="badge bg-danger">NO ❌</span>' !!}</td>
                                    <td>{{ ucfirst($user->role) }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">


                            <!-- Botón para Editar -->
                            @if ($user->role === 'superAdmin')
                            <a href="{{ route('user.index') }}" class="btn btn-secondary">
                                Editar
                            </a>
                            @else<button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editUserModal"
                                onclick="fillEditModal('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ addslashes($user->role) }}')">
                                Editar
                            </button>

                            @endif

                            <!-- Botón para Eliminar -->
                            @if ($user->role !== 'superAdmin')
                            <button onclick="deleteUser('{{ $user->id }}')" class="btn btn-danger">
                                Delete
                            </button>
                            @endif

                            @if (!$user->email_verified_at)
                            <button onclick="verifyUser('{{ $user->id }}')" class="btn btn-success">
                                Verify
                            </button>
                            @endif

                            @if ($user->email_verified_at && $user->role !== 'superAdmin')
                            <button onclick="desVerifyUser('{{ $user->id }}')" class="btn btn-dark">
                                Unverify
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

<script>
    function verifyUser(userId) {
        // Construir la URL base
        // Por ejemplo, si la URL actual es:
        // http://44.203.207.210/projects/userManagement/userManagementApp1.0/public/superAdmin?page=3
        // extraemos la parte hasta "public" para luego agregar "/superAdmin/edit/{id}"
        let pathName = window.location.pathname;
        // Buscamos la posición de "/superAdmin" en la ruta
        let pos = pathName.indexOf('/superAdmin');
        // Obtenemos la parte base de la ruta
        let basePath = (pos !== -1) ? pathName.substring(0, pos) : '';
        // Armamos la nueva URL de acción
        let actionUrl = window.location.origin + basePath + '/superAdmin/verify/' + userId;
        document.getElementById('edit-user-form').action = actionUrl;
        if (confirm('¿Estás seguro de que quieres verificar este usuario?')) {
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
                        alert('Usuario verificado correctamente.');
                        location.reload(); // Recarga la página para reflejar los cambios
                    } else {
                        alert('Hubo un problema al verificar el usuario.');
                    }
                })
                .catch(error => {
                    console.error('Error al verificar:', error);
                    alert('Error al intentar verificar el usuario.');
                });
        }
    }

    function desVerifyUser(userId) {
        // Construir la URL base
        // Por ejemplo, si la URL actual es:
        // http://44.203.207.210/projects/userManagement/userManagementApp1.0/public/superAdmin?page=3
        // extraemos la parte hasta "public" para luego agregar "/superAdmin/edit/{id}"
        let pathName = window.location.pathname;
        // Buscamos la posición de "/superAdmin" en la ruta
        let pos = pathName.indexOf('/superAdmin');
        // Obtenemos la parte base de la ruta
        let basePath = (pos !== -1) ? pathName.substring(0, pos) : '';
        // Armamos la nueva URL de acción
        let actionUrl = window.location.origin + basePath + '/superAdmin/desVerify/' + userId;
        document.getElementById('edit-user-form').action = actionUrl;
        if (confirm('¿Estás seguro de que quieres desverificar este usuario?')) {
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
                        alert('Usuario desverificado correctamente.');
                        location.reload(); // Recarga la página para reflejar los cambios
                    } else {
                        alert('Hubo un problema al desverificar el usuario.');
                    }
                })
                .catch(error => {
                    console.error('Error al desverificar:', error);
                    alert('Error al intentar desverificar el usuario.');
                });
        }
    }

    function fillEditModal(id, name, email, role) {
        // Habilitar/deshabilitar según el rol
        const roleField = document.getElementById('edit-role');
        const emailField = document.getElementById('edit-email');
        if (role === 'superAdmin') {
            roleField.disabled = true;
            emailField.readOnly = true;
        } else {
            roleField.disabled = false;
            emailField.readOnly = false;
        }

        // Actualizar campos del formulario
        document.getElementById('edit-name').value = name;
        emailField.value = email;
        roleField.value = role;

        // Construir la URL base
        // Por ejemplo, si la URL actual es:
        // http://44.203.207.210/projects/userManagement/userManagementApp1.0/public/superAdmin?page=3
        // extraemos la parte hasta "public" para luego agregar "/superAdmin/edit/{id}"
        let pathName = window.location.pathname;
        // Buscamos la posición de "/superAdmin" en la ruta
        let pos = pathName.indexOf('/superAdmin');
        // Obtenemos la parte base de la ruta
        let basePath = (pos !== -1) ? pathName.substring(0, pos) : '';
        // Armamos la nueva URL de acción
        let actionUrl = window.location.origin + basePath + '/superAdmin/edit/' + id;
        document.getElementById('edit-user-form').action = actionUrl;
    }



    function deleteUser(userId) {
         // Construir la URL base
        // Por ejemplo, si la URL actual es:
        // http://44.203.207.210/projects/userManagement/userManagementApp1.0/public/superAdmin?page=3
        // extraemos la parte hasta "public" para luego agregar "/superAdmin/edit/{id}"
        let pathName = window.location.pathname;
        // Buscamos la posición de "/superAdmin" en la ruta
        let pos = pathName.indexOf('/superAdmin');
        // Obtenemos la parte base de la ruta
        let basePath = (pos !== -1) ? pathName.substring(0, pos) : '';
        // Armamos la nueva URL de acción
        let actionUrl = window.location.origin + basePath + '/superAdmin/delete/' + userId;
        document.getElementById('edit-user-form').action = actionUrl;
        if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
            fetch(actionUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        alert('Usuario eliminado con éxito.');
                        location.reload();
                    } else {
                        alert('Hubo un problema al eliminar el usuario.');
                    }
                })
                .catch(error => {
                    console.error('Error al eliminar:', error);
                    alert('Error al intentar eliminar el usuario.');
                });
        }
    }
</script>
@endsection