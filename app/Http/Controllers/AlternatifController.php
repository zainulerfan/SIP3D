<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;

class AlternatifController extends Controller
{
    /**
     * index: list + search + paginate data dosen untuk TPK
     */
    public function index(Request $request)
    {
        $q = $request->get('q');
        $query = Dosen::query();

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                    ->orWhere('nidn', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $dosens = $query->orderBy('nama')->paginate(10);
        return view('TPK.alternatives', compact('dosens'));
    }

    /**
     * show create form - redirect to dosen create since data is integrated
     */
    public function create()
    {
        return view('TPK.create_alternative');
    }

    /**
     * store new alternative - creates/updates dosen TPK data
     */
    public function store(Request $r)
    {
        $data = $r->validate([
            'nidn' => 'required|string|max:50',
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'prodi' => 'nullable|string|max:100',
            'skor_sinta' => 'nullable|numeric',
            'skor_sinta_3yr' => 'nullable|numeric',
            'jumlah_buku' => 'nullable|integer',
            'jumlah_hibah' => 'nullable|integer',
            'publikasi_scholar' => 'nullable|integer',
        ]);

        // Check if dosen with NIDN exists
        $dosen = Dosen::where('nidn', $data['nidn'])->first();

        if ($dosen) {
            // Update existing dosen TPK data
            $dosen->update([
                'nama' => $data['nama'],
                'email' => $data['email'] ?? $dosen->email,
                'prodi' => $data['prodi'] ?? $dosen->prodi,
                'skor_sinta' => $data['skor_sinta'] ?? 0,
                'skor_sinta_3yr' => $data['skor_sinta_3yr'] ?? 0,
                'jumlah_buku' => $data['jumlah_buku'] ?? 0,
                'jumlah_hibah' => $data['jumlah_hibah'] ?? 0,
                'publikasi_scholar' => $data['publikasi_scholar'] ?? 0,
            ]);
        } else {
            // Create new dosen
            Dosen::create([
                'nidn' => $data['nidn'],
                'nama' => $data['nama'],
                'email' => $data['email'] ?? '-',
                'fakultas' => '-',
                'prodi' => $data['prodi'] ?? '-',
                'jabatan' => '-',
                'tahun' => date('Y'),
                'status' => 'Aktif',
                'skor_sinta' => $data['skor_sinta'] ?? 0,
                'skor_sinta_3yr' => $data['skor_sinta_3yr'] ?? 0,
                'jumlah_buku' => $data['jumlah_buku'] ?? 0,
                'jumlah_hibah' => $data['jumlah_hibah'] ?? 0,
                'publikasi_scholar' => $data['publikasi_scholar'] ?? 0,
            ]);
        }

        return redirect()->route('tpk.index')->with('success', 'Data dosen berhasil disimpan.');
    }

    /**
     * show edit form
     */
    public function edit($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('TPK.edit_alternative', compact('dosen'));
    }

    /**
     * update dosen TPK data
     */
    public function update(Request $r, $id)
    {
        $data = $r->validate([
            'nidn' => 'required|string|max:50',
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'prodi' => 'nullable|string|max:100',
            'skor_sinta' => 'nullable|numeric',
            'skor_sinta_3yr' => 'nullable|numeric',
            'jumlah_buku' => 'nullable|integer',
            'jumlah_hibah' => 'nullable|integer',
            'publikasi_scholar' => 'nullable|integer',
        ]);

        $dosen = Dosen::findOrFail($id);
        $dosen->update([
            'nidn' => $data['nidn'],
            'nama' => $data['nama'],
            'email' => $data['email'] ?? $dosen->email,
            'prodi' => $data['prodi'] ?? $dosen->prodi,
            'skor_sinta' => $data['skor_sinta'] ?? 0,
            'skor_sinta_3yr' => $data['skor_sinta_3yr'] ?? 0,
            'jumlah_buku' => $data['jumlah_buku'] ?? 0,
            'jumlah_hibah' => $data['jumlah_hibah'] ?? 0,
            'publikasi_scholar' => $data['publikasi_scholar'] ?? 0,
        ]);

        return redirect()->route('tpk.index')->with('success', 'Data dosen berhasil diperbarui.');
    }

    /**
     * Note: We don't delete dosen from TPK, only reset their TPK values
     * To fully delete, go to Dosen management
     */
    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);

        // Reset TPK values instead of deleting dosen
        $dosen->update([
            'skor_sinta' => 0,
            'skor_sinta_3yr' => 0,
            'jumlah_buku' => 0,
            'jumlah_hibah' => 0,
            'publikasi_scholar' => 0,
        ]);

        return redirect()->route('tpk.index')->with('success', 'Data TPK dosen berhasil direset.');
    }
}
