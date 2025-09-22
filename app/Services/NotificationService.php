<?php

namespace App\Services;

use App\Models\Bug;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification when a bug is reported
     */
    public function notifyBugReported(Bug $bug): void
    {
        $data = [
            'type' => 'bug_reported',
            'title' => 'New Bug Reported',
            'message' => "A new bug '{$bug->title}' has been reported in {$bug->project->name}",
            'bug_id' => $bug->id,
            'project_id' => $bug->project_id,
            'severity' => $bug->severity,
            'priority' => $bug->priority,
        ];

        // Notify project creator and admins
        $this->notifyUsers([
            $bug->project->creator,
            ...User::where('role', 'admin')->get()
        ], $data);
    }

    /**
     * Send notification when a bug is assigned
     */
    public function notifyBugAssigned(Bug $bug, User $assignee): void
    {
        $data = [
            'type' => 'bug_assigned',
            'title' => 'Bug Assigned to You',
            'message' => "Bug '{$bug->title}' has been assigned to you",
            'bug_id' => $bug->id,
            'project_id' => $bug->project_id,
            'severity' => $bug->severity,
            'priority' => $bug->priority,
        ];

        $this->notifyUsers([$assignee], $data);
    }

    /**
     * Send notification when a bug status is updated
     */
    public function notifyBugStatusUpdated(Bug $bug, string $oldStatus, string $newStatus): void
    {
        $data = [
            'type' => 'bug_status_updated',
            'title' => 'Bug Status Updated',
            'message' => "Bug '{$bug->title}' status changed from " . ucfirst(str_replace('_', ' ', $oldStatus)) . " to " . ucfirst(str_replace('_', ' ', $newStatus)),
            'bug_id' => $bug->id,
            'project_id' => $bug->project_id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
        ];

        // Notify reporter, assignee, and project creator
        $users = collect([$bug->reporter, $bug->project->creator]);

        if ($bug->assignee) {
            $users->push($bug->assignee);
        }

        $this->notifyUsers($users->unique('id'), $data);
    }

    /**
     * Send notification when a bug is resolved
     */
    public function notifyBugResolved(Bug $bug): void
    {
        $data = [
            'type' => 'bug_resolved',
            'title' => 'Bug Resolved',
            'message' => "Bug '{$bug->title}' has been resolved",
            'bug_id' => $bug->id,
            'project_id' => $bug->project_id,
            'resolved_by' => $bug->assignee?->name ?? 'System',
        ];

        // Notify reporter and project creator
        $this->notifyUsers([
            $bug->reporter,
            $bug->project->creator
        ], $data);
    }

    /**
     * Send notification to multiple users
     */
    private function notifyUsers($users, array $data): void
    {
        foreach ($users as $user) {
            if ($user) {
                $this->createNotification($user, $data);
            }
        }
    }

    /**
     * Create a notification record
     */
    private function createNotification(User $user, array $data): void
    {
        try {
            Notification::create([
                'type' => $data['type'],
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'data' => $data,
            ]);

            // Here you could also send real-time notifications via Pusher, WebSockets, etc.
            // $this->sendRealTimeNotification($user, $data);

        } catch (\Exception $e) {
            Log::error('Failed to create notification', [
                'user_id' => $user->id,
                'data' => $data,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send real-time notification (placeholder for Pusher/WebSocket implementation)
     */
    private function sendRealTimeNotification(User $user, array $data): void
    {
        // This would integrate with Pusher, Laravel WebSockets, or similar
        // For now, we'll just log it
        Log::info('Real-time notification would be sent', [
            'user_id' => $user->id,
            'data' => $data
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId, User $user): bool
    {
        $notification = Notification::where('id', $notificationId)
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', $user->id)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(User $user): int
    {
        return Notification::where('notifiable_type', User::class)
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Get unread notifications count for a user
     */
    public function getUnreadCount(User $user): int
    {
        return Notification::where('notifiable_type', User::class)
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Get notifications for a user
     */
    public function getNotifications(User $user, int $limit = 20)
    {
        return Notification::where('notifiable_type', User::class)
            ->where('notifiable_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
