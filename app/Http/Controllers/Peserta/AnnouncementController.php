<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $announcements = Announcement::query()
            ->where('status', 'dipublikasikan')
            ->where('tanggal_publish', '<=', now())
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('judul', 'like', "%{$search}%")
                        ->orWhere('isi', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('tanggal_publish')
            ->paginate(9)
            ->withQueryString();

        return view(
            'peserta.announcements.index',
            compact('announcements', 'search')
        );
    }

    public function show(Announcement $announcement): View
    {
        abort_unless(
            $announcement->status === 'dipublikasikan'
            && $announcement->tanggal_publish->lte(now()),
            404
        );

        return view(
            'peserta.announcements.show',
            compact('announcement')
        );
    }
}