<?php

namespace App\Http\Controllers;

use App\Models\Pengabdian;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengabdianController extends Controller
{
    public function index()
    {
        $query = Pengabdian::with(['ketuaDosen', 'mahasiswa']);

        // Jika user adalah dosen, filter hanya pengabdian miliknya
        if (Auth::user()->role === 'dosen') {
            $dosen = Dosen::where('email', Auth::user()->email)->first();
            if ($dosen) {
                $query->where('ketua_dosen_id', $dosen->id)
                    ->orWhereHas('anggotaDosens', function ($q) use ($dosen) {
                        $q->where('dosens.id', $dosen->id);
                    });
            }
        }

        $pengabdians = $query->latest()->get();
        return view('pengabdian.index', compact('pengabdians'));
    }

    public function create()
    {
        return view('pengabdian.create', [
            'dosens' => Dosen::all(),
            'mahasiswas' => Mahasiswa::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ketua_dosen_id' => 'required|exists:dosens,id',
            'judul' => 'required|string',
            'bidang' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date',
            'status' => 'required',
            'tahun' => 'required|integer',
            'mahasiswa_id' => 'nullable|exists:mahasiswas,id',
            'anggota_dosen' => 'nullable|array',
            'google_drive_folder' => 'nullable|url|max:500',
        ]);

        $pengabdian = Pengabdian::create($validated);

        if ($request->filled('anggota_dosen')) {
            $pengabdian->anggotaDosens()->sync($request->anggota_dosen);
        }

        $route = Auth::user()->role === 'dosen' ? 'dosen.pengabdian.index' : 'pengabdian.index';
        return redirect()->route($route)
            ->with('success', 'Data pengabdian berhasil ditambahkan');
    }

    public function edit(Pengabdian $pengabdian)
    {
        return view('pengabdian.edit', [
            'pengabdian' => $pengabdian,
            'dosens' => Dosen::all(),
            'mahasiswas' => Mahasiswa::all(),
            'anggota' => $pengabdian->anggotaDosens->pluck('id')->toArray(),
        ]);
    }

    public function update(Request $request, Pengabdian $pengabdian)
    {
        $validated = $request->validate([
            'ketua_dosen_id' => 'required|exists:dosens,id',
            'judul' => 'required|string',
            'bidang' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date',
            'status' => 'required',
            'tahun' => 'required|integer',
            'mahasiswa_id' => 'nullable|exists:mahasiswas,id',
            'anggota_dosen' => 'nullable|array',
            'google_drive_folder' => 'nullable|url|max:500',
        ]);

        $pengabdian->update($validated);
        $pengabdian->anggotaDosens()->sync($request->anggota_dosen ?? []);

        $route = Auth::user()->role === 'dosen' ? 'dosen.pengabdian.index' : 'pengabdian.index';
        return redirect()->route($route)
            ->with('success', 'Data pengabdian berhasil diperbarui');
    }

    public function destroy(Pengabdian $pengabdian)
    {
        $pengabdian->delete();

        $route = Auth::user()->role === 'dosen' ? 'dosen.pengabdian.index' : 'pengabdian.index';
        return redirect()->route($route)
            ->with('success', 'Data pengabdian berhasil dihapus');
    }

    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PengabdianExport, 'pengabdian.xlsx');
    }
}
