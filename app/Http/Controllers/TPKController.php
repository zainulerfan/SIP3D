<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Kriteria;
use Illuminate\Support\Facades\Response;

class TPKController extends Controller
{
    /**
     * Tampilkan halaman utama TPK (SAW) dengan:
     * - tabel alternatif (data dosen)
     * - bobot kriteria
     * - normalisasi
     * - weighted matrix
     * - hasil akhir + ranking
     */
    public function index(Request $request)
    {
        // 1) Ambil data alternatif dari DOSEN. Optional: search param 'q'
        $q = $request->get('q');
        $query = Dosen::query();
        if ($q) {
            $query->where(function ($qr) use ($q) {
                $qr->where('nama', 'like', "%{$q}%")
                    ->orWhere('nidn', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }
        $dosens = $query->get();

        // 2) Tentukan kriteria (kolom di tabel dosens yang akan dipakai)
        $criteria = [
            'skor_sinta',
            'skor_sinta_3yr',
            'jumlah_buku',
            'jumlah_hibah',
            'publikasi_scholar',
        ];

        // Labels yang akan tampil di header tabel
        $criteria_labels = [
            'Skor SINTA',
            'SINTA 3Yr',
            'Jumlah Buku',
            'Jumlah Hibah',
            'Scholar (1Yr)',
        ];

        // 3) Ambil bobot dari model Kriteria
        $weights = $this->getWeightsFallback(count($criteria));
        $weights_per_col = [];
        foreach ($criteria as $idx => $col) {
            $key = 'C' . ($idx + 1);
            $weights_per_col[$col] = $weights[$key] ?? 0;
        }

        // 4) Hitung max tiap kolom (untuk normalisasi benefit)
        $max = [];
        foreach ($criteria as $col) {
            $max[$col] = $dosens->max($col) ?: 0;
        }

        // 5) Normalisasi r_ij = value / max_j
        $normalized = [];
        foreach ($dosens as $d) {
            $row = [
                'id' => $d->id,
                'label' => $d->nama ?? ('ID' . $d->id),
            ];
            foreach ($criteria as $col) {
                $val = (float) ($d->{$col} ?? 0);
                $r = $max[$col] > 0 ? ($val / $max[$col]) : 0;
                $row[$col] = $r;
            }
            $normalized[] = $row;
        }

        // 6) Weighted matrix & score (V)
        $weighted = [];
        foreach ($normalized as $row) {
            $wt = ['id' => $row['id'], 'label' => $row['label'], 'score' => 0];
            $sum = 0;
            foreach ($criteria as $col) {
                $r = $row[$col] ?? 0;
                $w = $weights_per_col[$col] ?? 0;
                $val = $r * $w;
                $wt[$col] = $val;
                $sum += $val;
            }
            $wt['score'] = $sum;
            $weighted[] = $wt;
        }

        // 7) Ranking (sort descending by score)
        usort($weighted, function ($a, $b) {
            return ($b['score'] <=> $a['score']);
        });

        $results = [];
        $rank = 1;
        foreach ($weighted as $w) {
            $results[] = [
                'rank' => $rank++,
                'id' => $w['id'],
                'label' => $w['label'],
                'score' => $w['score'],
            ];
        }

        // 8) Total bobot (untuk tampilan)
        $total_bobot = array_sum(array_values($weights));

        // 9) Data table raw (untuk Data TPK di view) - gunakan dosens
        $dosensTable = $dosens;

        return view('TPK.index', compact(
            'dosensTable',
            'weights',
            'criteria_labels',
            'criteria',
            'normalized',
            'weighted',
            'results',
            'total_bobot'
        ));
    }

    /**
     * Export hasil ranking (V) ke CSV.
     */
    public function exportCsv(Request $request)
    {
        $q = $request->get('q');
        $query = Dosen::query();
        if ($q) {
            $query->where(function ($qr) use ($q) {
                $qr->where('nama', 'like', "%{$q}%")
                    ->orWhere('nidn', 'like', "%{$q}%");
            });
        }
        $dosens = $query->get();

        $criteria = [
            'skor_sinta',
            'skor_sinta_3yr',
            'jumlah_buku',
            'jumlah_hibah',
            'publikasi_scholar',
        ];

        $weights = $this->getWeightsFallback(count($criteria));
        $weights_per_col = [];
        foreach ($criteria as $idx => $col) {
            $key = 'C' . ($idx + 1);
            $weights_per_col[$col] = $weights[$key] ?? 0;
        }

        $max = [];
        foreach ($criteria as $col) {
            $max[$col] = $dosens->max($col) ?: 0;
        }

        $rows = [];
        foreach ($dosens as $d) {
            $label = $d->nama ?? ('ID' . $d->id);
            $score = 0;
            $row = [
                'nidn' => $d->nidn ?? null,
                'name' => $d->nama ?? null,
            ];
            foreach ($criteria as $col) {
                $val = (float) ($d->{$col} ?? 0);
                $r = $max[$col] > 0 ? ($val / $max[$col]) : 0;
                $weightedVal = $r * ($weights_per_col[$col] ?? 0);
                $row[$col] = $val;
                $row[$col . '_normalized'] = $r;
                $row[$col . '_weighted'] = $weightedVal;
                $score += $weightedVal;
            }
            $row['score'] = $score;
            $rows[] = $row;
        }

        usort($rows, function ($a, $b) {
            return ($b['score'] <=> $a['score']);
        });

        $filename = 'tpk_dosen_ranking_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = ['Rank', 'NIDN', 'Nama Dosen'];
        foreach ($criteria as $col) {
            $columns[] = ucfirst(str_replace('_', ' ', $col));
        }
        $columns[] = 'Score';

        $callback = function () use ($rows, $columns, $criteria) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $columns);

            $rank = 1;
            foreach ($rows as $r) {
                $line = [
                    $rank++,
                    $r['nidn'] ?? '',
                    $r['name'] ?? '',
                ];
                foreach ($criteria as $col) {
                    $line[] = $r[$col] ?? 0;
                }
                $line[] = round($r['score'] ?? 0, 4);
                fputcsv($out, $line);
            }

            fclose($out);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Helper: get weights from model Kriteria if exists,
     * otherwise return equal weights keyed by C1..Cn.
     */
    protected function getWeightsFallback(int $nCriteria = 1): array
    {
        if (class_exists(Kriteria::class)) {
            try {
                $krs = Kriteria::orderBy('id')->get();
                if ($krs->count() >= $nCriteria) {
                    $weights = [];
                    $i = 1;
                    foreach ($krs as $kr) {
                        $w = $kr->bobot ?? $kr->weight ?? 0;
                        $weights['C' . $i] = (float) $w;
                        $i++;
                        if ($i > $nCriteria) break;
                    }
                    while ($i <= $nCriteria) {
                        $weights['C' . $i] = 0;
                        $i++;
                    }
                    return $weights;
                }
            } catch (\Throwable $e) {
                // ignore and fallback
            }
        }

        // fallback: equal weights
        $w = $nCriteria ? (1 / $nCriteria) : 0;
        $weights = [];
        for ($i = 1; $i <= $nCriteria; $i++) {
            $weights['C' . $i] = round($w, 4);
        }
        return $weights;
    }
}
