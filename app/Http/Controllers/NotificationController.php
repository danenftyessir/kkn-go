<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    /**
     * tampilkan daftar semua notifikasi
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Notification::where('user_id', $user->id);

        // filter berdasarkan status read/unread
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'read') {
                $query->read();
            }
        }

        // filter berdasarkan tipe
        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        $notifications = $query->latest()->paginate(20)->withQueryString();

        // statistik
        $stats = [
            'total' => Notification::where('user_id', $user->id)->count(),
            'unread' => Notification::where('user_id', $user->id)->unread()->count(),
            'read' => Notification::where('user_id', $user->id)->read()->count(),
        ];

        return view('notifications.index', compact('notifications', 'stats'));
    }

    /**
     * dapatkan notifikasi terbaru (untuk dropdown)
     */
    public function getLatest()
    {
        $notifications = $this->notificationService->getLatest(auth()->id(), 5);
        $unreadCount = $this->notificationService->getUnreadCount(auth()->id());

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * tandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->markAsRead();

        // redirect ke action url jika ada
        if ($notification->action_url) {
            return redirect($notification->action_url);
        }

        return back()->with('success', 'Notifikasi ditandai sebagai sudah dibaca');
    }

    /**
     * tandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        $count = $this->notificationService->markAllAsRead(auth()->id());

        return back()->with('success', "{$count} notifikasi ditandai sebagai sudah dibaca");
    }

    /**
     * hapus notifikasi
     */
    public function destroy($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus');
    }

    /**
     * hapus semua notifikasi yang sudah dibaca
     */
    public function destroyRead()
    {
        $count = Notification::where('user_id', auth()->id())
            ->read()
            ->delete();

        return back()->with('success', "{$count} notifikasi telah dihapus");
    }
}