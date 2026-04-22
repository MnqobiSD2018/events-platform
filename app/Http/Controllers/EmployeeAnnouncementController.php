<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeAnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $allowedCategories = ['company', 'hr', 'wellness', 'general'];
        $selectedCategory = $request->query('category');

        $query = Announcement::query()
            ->latest('published_at')
            ->latest('id');

        if ($selectedCategory && in_array($selectedCategory, $allowedCategories, true)) {
            $query->where('category', $selectedCategory);
        }

        $announcements = $query->paginate(10)->withQueryString();

        $readAnnouncementIds = Auth::user()
            ->announcementReads()
            ->pluck('announcement_id')
            ->all();

        return view('employee.announcements.index', [
            'announcements' => $announcements,
            'readAnnouncementIds' => $readAnnouncementIds,
            'selectedCategory' => $selectedCategory,
            'allowedCategories' => $allowedCategories,
        ]);
    }

    public function markAsRead(Announcement $announcement): RedirectResponse
    {
        $user = Auth::user();

        $user->announcementReads()->updateOrCreate(
            ['announcement_id' => $announcement->id],
            ['read_at' => now()]
        );

        return back()->with('success', 'Announcement marked as read.');
    }
}
