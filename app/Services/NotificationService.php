<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * buat notifikasi baru
     * FIXED: validasi action_url agar tidak invalid
     */
    public function create($userId, $type, $title, $message, $data = null, $actionUrl = null)
    {
        try {
            // FIXED: validasi action_url
            // pastikan action_url tidak mengarah ke endpoint API atau invalid URL
            if ($actionUrl) {
                $invalidPaths = [
                    '/notifications/latest',
                    '/notifications/getLatest',
                    '/api/',
                ];
                
                foreach ($invalidPaths as $invalid) {
                    if (str_contains($actionUrl, $invalid)) {
                        Log::warning('Invalid action_url detected, setting to null', [
                            'action_url' => $actionUrl,
                            'type' => $type
                        ]);
                        $actionUrl = null;
                        break;
                    }
                }
                
                // jika action_url hanya '#' atau kosong, set null
                if (in_array($actionUrl, ['#', '', ' '])) {
                    $actionUrl = null;
                }
            }
            
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
        $message = "Aplikasi Anda untuk proyek {$application->problem->title} belum dapat diterima saat ini.";
        if ($feedback) {
            $message .= " Feedback: {$feedback}";
        }
        
        return $this->create(
            $application->student->user_id,
            'application_rejected',
            'Aplikasi Ditolak',
            $message,
            [
                'application_id' => $application->id,
                'problem_title' => $application->problem->title,
                'feedback' => $feedback,
            ],
            route('student.applications.show', $application->id)
        );
    }

    /**
     * notifikasi ketika laporan disubmit
     */
    public function reportSubmitted($report, $project)
    {
        return $this->create(
            $project->institution->user_id,
            'report_submitted',
            'Laporan Baru Disubmit',
            "{$project->student->user->name} telah mengupload laporan untuk proyek {$project->problem->title}",
            [
                'report_id' => $report->id,
                'project_id' => $project->id,
                'student_name' => $project->student->user->name,
            ],
            route('institution.projects.show', $project->id)
        );
    }

    /**
     * notifikasi ketika proyek dimulai
     */
    public function projectStarted($project)
    {
        // notifikasi untuk mahasiswa
        $this->create(
            $project->student->user_id,
            'project_started',
            'Proyek Dimulai! 🚀',
            "Proyek {$project->problem->title} telah resmi dimulai. Selamat bekerja!",
            [
                'project_id' => $project->id,
                'problem_title' => $project->problem->title,
            ],
            route('student.projects.show', $project->id)
        );

        // notifikasi untuk instansi
        return $this->create(
            $project->institution->user_id,
            'project_started',
            'Proyek Dimulai',
            "Proyek {$project->problem->title} dengan mahasiswa {$project->student->user->name} telah dimulai",
            [
                'project_id' => $project->id,
                'problem_title' => $project->problem->title,
                'student_name' => $project->student->user->name,
            ],
            route('institution.projects.show', $project->id)
        );
    }

    /**
     * notifikasi ketika review diterima
     */
    public function reviewReceived($review, $reviewer)
    {
        return $this->create(
            $review->reviewee_id,
            'review_received',
            'Review Diterima',
            "Anda menerima review dari {$reviewer->name}",
            [
                'review_id' => $review->id,
                'rating' => $review->rating,
            ],
            route('student.portfolio')
        );
    }

    /**
     * dapatkan notifikasi terbaru
     */
    public function getLatest($userId, $limit = 5)
    {
        return Notification::where('user_id', $userId)
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'action_url' => $notification->action_url,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->toIso8601String(),
                ];
            });
    }

    /**
     * hitung jumlah notifikasi yang belum dibaca
     */
    public function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->unread()
            ->count();
    }

    /**
     * tandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }
}