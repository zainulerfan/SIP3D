<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Tampilkan semua user
     */
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Form edit role
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update role user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,dosen,mahasiswa,user',
        ]);

        // Cegah ubah role diri sendiri
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat mengubah role akun sendiri!');
        }

        $user->update([
            'role' => $request->role,
        ]);

        // Sync Profile Data (Copy logic or creating a Service would be better, but duplicative for speed now)
        if ($request->role === 'dosen') {
            \App\Models\Dosen::firstOrCreate(
                ['email' => $user->email],
                [
                    'nama' => $user->name,
                    'nidn' => '-',
                    'jabatan' => '-',
                    'fakultas' => '-',
                    'prodi' => '-',
                    'tahun' => date('Y'),
                    'status' => 'Aktif',
                ]
            );
        } elseif ($request->role === 'mahasiswa') {
            $data = [
                'nama' => $user->name,
                'email' => $user->email,
                'nim' => '-',
                'fakultas' => '-',
                'prodi' => '-',
                'angkatan' => date('Y'),
                'status' => 'Aktif',
            ];

            if (\Illuminate\Support\Facades\Schema::hasColumn('mahasiswas', 'user_id')) {
                $data['user_id'] = $user->id;
            }

            \App\Models\Mahasiswa::firstOrCreate(
                ['email' => $user->email],
                $data
            );
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Role user berhasil diperbarui!');
    }

    /**
     * Hapus user
     */
    public function destroy(User $user)
    {
        // Cegah hapus diri sendiri
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }
}
