<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $announcements = Announcement::query()
            ->with('creator')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('judul', 'like', "%{$search}%")
                        ->orWhere('isi', 'like', "%{$search}%");
                });
            })
            ->when(
                in_array(
                    $status,
                    ['draft', 'dipublikasikan'],
                    true
                ),
                function ($query) use ($status) {
                    $query->where('status', $status);
                }
            )
            ->orderByDesc('tanggal_publish')
            ->paginate(10)
            ->withQueryString();

        $totalPengumuman = Announcement::count();

        $totalDraft = Announcement::where(
            'status',
            'draft'
        )->count();

        $totalDipublikasikan = Announcement::where(
            'status',
            'dipublikasikan'
        )->count();

        return view(
            'admin.announcements.index',
            compact(
                'announcements',
                'search',
                'status',
                'totalPengumuman',
                'totalDraft',
                'totalDipublikasikan'
            )
        );
    }

    public function create(): View
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateAnnouncement($request);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request
                ->file('gambar')
                ->store('announcements', 'public');
        }

        $validated['user_id'] = $request->user()->id;

        Announcement::create($validated);

        return redirect()
            ->route('admin.announcements.index')
            ->with(
                'success',
                'Pengumuman berhasil ditambahkan.'
            );
    }

    public function edit(
        Announcement $announcement
    ): View {
        return view(
            'admin.announcements.edit',
            compact('announcement')
        );
    }

    public function update(
        Request $request,
        Announcement $announcement
    ): RedirectResponse {
        $validated = $this->validateAnnouncement($request);

        if ($request->hasFile('gambar')) {
            if (
                $announcement->gambar &&
                Storage::disk('public')->exists(
                    $announcement->gambar
                )
            ) {
                Storage::disk('public')->delete(
                    $announcement->gambar
                );
            }

            $validated['gambar'] = $request
                ->file('gambar')
                ->store('announcements', 'public');
        } else {
            unset($validated['gambar']);
        }

        $announcement->update($validated);

        return redirect()
            ->route('admin.announcements.index')
            ->with(
                'success',
                'Pengumuman berhasil diperbarui.'
            );
    }

    public function destroy(
        Announcement $announcement
    ): RedirectResponse {
        if (
            $announcement->gambar &&
            Storage::disk('public')->exists(
                $announcement->gambar
            )
        ) {
            Storage::disk('public')->delete(
                $announcement->gambar
            );
        }

        $judul = $announcement->judul;

        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with(
                'success',
                "Pengumuman {$judul} berhasil dihapus."
            );
    }

    private function validateAnnouncement(
        Request $request
    ): array {
        return $request->validate(
            [
                'judul' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'isi' => [
                    'required',
                    'string',
                ],
                'tanggal_publish' => [
                    'required',
                    'date',
                ],
                'status' => [
                    'required',
                    'in:draft,dipublikasikan',
                ],
                'gambar' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:2048',
                ],
            ],
            [
                'judul.required' =>
                    'Judul pengumuman wajib diisi.',

                'isi.required' =>
                    'Isi pengumuman wajib diisi.',

                'tanggal_publish.required' =>
                    'Tanggal publikasi wajib diisi.',

                'tanggal_publish.date' =>
                    'Format tanggal publikasi tidak valid.',

                'status.required' =>
                    'Status pengumuman wajib dipilih.',

                'status.in' =>
                    'Status pengumuman tidak valid.',

                'gambar.image' =>
                    'File gambar harus berupa gambar.',

                'gambar.mimes' =>
                    'Gambar harus berformat JPG, JPEG, PNG, atau WEBP.',

                'gambar.max' =>
                    'Ukuran gambar maksimal 2 MB.',
            ]
        );
    }
}