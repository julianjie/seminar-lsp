<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SeminarController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $seminars = Seminar::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('judul', 'like', "%{$search}%")
                        ->orWhere('narasumber', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%");
                });
            })
            ->when(
                in_array(
                    $status,
                    ['draft', 'dipublikasikan', 'selesai'],
                    true
                ),
                function ($query) use ($status) {
                    $query->where('status', $status);
                }
            )
            ->orderByDesc('tanggal')
            ->paginate(10)
            ->withQueryString();

        $totalSeminar = Seminar::count();

        $totalDraft = Seminar::where('status', 'draft')
            ->count();

        $totalDipublikasikan = Seminar::where(
            'status',
            'dipublikasikan'
        )->count();

        $totalSelesai = Seminar::where('status', 'selesai')
            ->count();

        return view('admin.seminars.index', compact(
            'seminars',
            'search',
            'status',
            'totalSeminar',
            'totalDraft',
            'totalDipublikasikan',
            'totalSelesai'
        ));
    }

    public function create(): View
    {
        return view('admin.seminars.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSeminar($request);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request
                ->file('gambar')
                ->store('seminars', 'public');
        }

        Seminar::create($validated);

        return redirect()
            ->route('admin.seminars.index')
            ->with('success', 'Data seminar berhasil ditambahkan.');
    }

    public function edit(Seminar $seminar): View
    {
        return view('admin.seminars.edit', compact('seminar'));
    }

    public function update(
        Request $request,
        Seminar $seminar
    ): RedirectResponse {
        $validated = $this->validateSeminar($request);

        if ($request->hasFile('gambar')) {
            if (
                $seminar->gambar &&
                Storage::disk('public')->exists($seminar->gambar)
            ) {
                Storage::disk('public')->delete($seminar->gambar);
            }

            $validated['gambar'] = $request
                ->file('gambar')
                ->store('seminars', 'public');
        } else {
            unset($validated['gambar']);
        }

        $seminar->update($validated);

        return redirect()
            ->route('admin.seminars.index')
            ->with('success', 'Data seminar berhasil diperbarui.');
    }

    public function destroy(Seminar $seminar): RedirectResponse
    {
        if (
            $seminar->gambar &&
            Storage::disk('public')->exists($seminar->gambar)
        ) {
            Storage::disk('public')->delete($seminar->gambar);
        }

        $judul = $seminar->judul;

        $seminar->delete();

        return redirect()
            ->route('admin.seminars.index')
            ->with(
                'success',
                "Seminar {$judul} berhasil dihapus."
            );
    }

    private function validateSeminar(Request $request): array
    {
        return $request->validate(
            [
                'judul' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'narasumber' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'deskripsi' => [
                    'required',
                    'string',
                ],
                'tanggal' => [
                    'required',
                    'date',
                ],
                'waktu_mulai' => [
                    'required',
                    'date_format:H:i',
                ],
                'waktu_selesai' => [
                    'nullable',
                    'date_format:H:i',
                    'after:waktu_mulai',
                ],
                'lokasi' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'kuota' => [
                    'required',
                    'integer',
                    'min:1',
                ],
                'harga' => [
                    'required',
                    'numeric',
                    'min:0',
                ],
                'gambar' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:2048',
                ],
                'status' => [
                    'required',
                    'in:draft,dipublikasikan,selesai',
                ],
            ],
            [
                'judul.required' => 'Judul seminar wajib diisi.',
                'narasumber.required' => 'Narasumber wajib diisi.',
                'deskripsi.required' => 'Deskripsi wajib diisi.',
                'tanggal.required' => 'Tanggal seminar wajib diisi.',
                'tanggal.date' => 'Format tanggal tidak valid.',
                'waktu_mulai.required' => 'Waktu mulai wajib diisi.',
                'waktu_selesai.after' =>
                    'Waktu selesai harus setelah waktu mulai.',
                'lokasi.required' => 'Lokasi seminar wajib diisi.',
                'kuota.required' => 'Kuota seminar wajib diisi.',
                'kuota.min' => 'Kuota minimal satu peserta.',
                'harga.required' => 'Harga seminar wajib diisi.',
                'harga.min' => 'Harga tidak boleh negatif.',
                'gambar.image' => 'File poster harus berupa gambar.',
                'gambar.mimes' =>
                    'Poster harus berformat JPG, JPEG, PNG, atau WEBP.',
                'gambar.max' => 'Ukuran poster maksimal 2 MB.',
                'status.required' => 'Status seminar wajib dipilih.',
            ]
        );
    }
}