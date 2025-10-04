<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * buat notifikasi baru
     */
    public function create($userId, $type, $title, $message, $data = null, $actionUrl = null)
    {
        try {
            return Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
                'action_url' => $actionUrl,
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
            Log::error('gagal membuat notifikasi: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * notifikasi ketika mahasiswa submit aplikasi
     */
    public function applicationSubmitted($application)
    {
        $institution = $application->problem->institution;
        
        return $this->create(
            $institution->user_id,
            'application_submitted',
            'Aplikasi Baru Diterima',
            "{$application->student->user->name} telah mengajukan aplikasi untuk proyek {$application->problem->title}",
            [
                'application_id' => $application->id,
                'student_name' => $application->student->user->name,
                'problem_title' => $application->problem->title,
            ],
            route('institution.applications.show', $application->id)
        );
    }

    /**
     * notifikasi ketika aplikasi diterima
     */
    public function applicationAccepted($application)
    {
        return $this->create(
            $application->student->user_id,
            'application_accepted',
            'Aplikasi Diterima! 🎉',
            "Selamat! Aplikasi Anda untuk proyek {$application->problem->title} telah diterima oleh {$application->problem->institution->name}",
            [
                'application_id' => $application->id,
                'problem_title' => $application->problem->title,
                'institution_name' => $application->problem->institution->name,
            ],
            route('student.applications.show', $application->id)
        );
    }

    /**
     * notifikasi ketika aplikasi ditolak
     */
    public function applicationRejected($application, $feedback = null)
    {
        return $this->create(
            $application->student->user_id,
            'application_rejected',
            'Aplikasi Ditolak',
            "Aplikasi Anda untuk proyek {$application->problem->title} belum dapat diterima saat ini.",
            [
                'application_id' => $application->id,
                'problem_title' => $application->problem->title,
                'feedback' => $feedback,
            ],
            route('student.applications.show', $application->id)
        );
    }

    /**
     * notifikasi ketika proyek dimulai
     */
    public function projectStarted($project)
    {
        // notif ke mahasiswa
        $this->create(
            $project->student->user_id,
            'project_started',
            'Proyek Dimulai! 🚀',
            "Proyek {$project->problem->title} telah dimulai. Selamat bekerja!",
            [
                'project_id' => $project->id,
                'problem_title' => $project->problem->title,
            ],
            route('student.projects.show', $project->id)
        );

        // notif ke instansi
        return $this->create(
            $project->institution->user_id,
            'project_started',
            'Proyek Dimulai',
            "Proyek {$project->problem->title} dengan {$project->student->user->name} telah dimulai.",
            [
                'project_id' => $project->id,
                'problem_title' => $project->problem->title,
                'student_name' => $project->student->user->name,
            ],
            route('institution.projects.show', $project->id)
        );
    }

    /**
     * notifikasi ketika milestone baru ditambahkan
     */
    public function milestoneAdded($milestone)
    {
        return $this->create(
            $milestone->project->student->user_id,
            'project_milestone',
            'Milestone Baru',
            "Milestone baru telah ditambahkan: {$milestone->title}",
            [
                'milestone_id' => $milestone->id,
                'project_id' => $milestone->project_id,
                'milestone_title' => $milestone->title,
                'due_date' => $milestone->due_date?->format('d M Y'),
            ],
            route('student.projects.show', $milestone->project_id)
        );
    }

    /**
     * notifikasi ketika laporan disubmit
     */
    public function reportSubmitted($report)
    {
        return $this->create(
            $report->project->institution->user_id,
            'report_submitted',
            'Laporan Baru',
            "{$report->project->student->user->name} telah mengirim laporan {$report->type}",
            [
                'report_id' => $report->id,
                'project_id' => $report->project_id,
                'report_type' => $report->type,
                'student_name' => $report->project->student->user->name,
            ],
            route('institution.projects.show', $report->project_id)
        );
    }

    /**
     * notifikasi ketika laporan disetujui
     */
    public function reportApproved($report)
    {
        return $this->create(
            $report->project->student->user_id,
            'report_approved',
            'Laporan Disetujui ✅',
            "Laporan {$report->type} Anda telah disetujui!",
            [
                'report_id' => $report->id,
                'project_id' => $report->project_id,
                'report_type' => $report->type,
            ],
            route('student.projects.show', $report->project_id)
        );
    }

    /**
     * notifikasi ketika laporan ditolak
     */
    public function reportRejected($report, $feedback = null)
    {
        return $this->create(
            $report->project->student->user_id,
            'report_rejected',
            'Laporan Perlu Revisi',
            "Laporan {$report->type} Anda perlu diperbaiki.",
            [
                'report_id' => $report->id,
                'project_id' => $report->project_id,
                'report_type' => $report->type,
                'feedback' => $feedback,
            ],
            route('student.projects.show', $report->project_id)
        );
    }

    /**
     * notifikasi ketika menerima review
     */
    public function reviewReceived($review)
    {
        return $this->create(
            $review->student->user_id,
            'review_received',
            'Review Baru ⭐',
            "{$review->project->problem->institution->name} telah memberikan review untuk Anda ({$review->rating}/5)",
            [
                'review_id' => $review->id,
                'project_id' => $review->project_id,
                'rating' => $review->rating,
                'institution_name' => $review->project->problem->institution->name,
            ],
            route('student.portfolio.public', $review->student->user_id)
        );
    }

    /**
     * notifikasi ketika problem baru dipublish
     */
    public function problemPublished($problem)
    {
        // bisa kirim ke semua mahasiswa yang match criteria
        // untuk sekarang kita skip dulu, nanti bisa ditambahkan
        // dengan sistem matching berdasarkan skills, lokasi, dll
        
        return null;
    }

    /**
     * notifikasi pengingat deadline
     */
    public function deadlineReminder($user, $title, $deadline, $actionUrl)
    {
        $daysLeft = now()->diffInDays($deadline, false);
        $message = $daysLeft > 0 
            ? "Deadline {$title} akan berakhir dalam {$daysLeft} hari"
            : "Deadline {$title} adalah hari ini!";

        return $this->create(
            $user->id,
            'deadline_reminder',
            'Pengingat Deadline ⏰',
            $message,
            [
                'deadline' => $deadline->format('Y-m-d'),
                'days_left' => $daysLeft,
            ],
            $actionUrl
        );
    }

    /**
     * dapatkan jumlah notifikasi yang belum dibaca
     */
    public function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * dapatkan notifikasi terbaru
     */
    public function getLatest($userId, $limit = 10)
    {
        return Notification::where('user_id', $userId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * tandai semua notifikasi sebagai dibaca
     */
    public function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * hapus notifikasi lama (lebih dari 30 hari dan sudah dibaca)
     */
    public function cleanOldNotifications()
    {
        return Notification::where('is_read', true)
            ->where('read_at', '<', now()->subDays(30))
            ->delete();
    }
}