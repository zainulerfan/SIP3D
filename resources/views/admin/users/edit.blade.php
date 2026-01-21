@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Edit Role User</h1>
        </div>
    </div>

    <div class="card shadow mb-4" style="max-width: 600px;">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Role</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control" value="{{ $user->email }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role Saat Ini</label>
                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Pilih Role Baru <span class="text-danger">*</span></label>
                    <select name="role" class="form-select" required>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="dosen" {{ $user->role == 'dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="mahasiswa" {{ $user->role == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User (Tanpa Akses)</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection