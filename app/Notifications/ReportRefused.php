<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportRefused extends Notification
{
    use Queueable;
    public int $reportId;
    public  $title;
    public $corps;
    public $status;
    public $motif;

    /**
     * Create a new notification instance.
     */
    public function __construct(Report $report, string $motif)
    {
        $this->reportId = $report->id;
        $this->title = $report->title;
        $this->corps = $report->corps;
        $this->status = $report->status;
        $this->motif = $motif;
    }




    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'report_id' => $this->reportId,
            'title' => $this->title,
            'corps' => $this->corps,
            'status' => $this->status,
            'motif' => $this->motif,
        ];
    }
    public function toDatabase(object $notifiable): array
    {
        return [
            'report_id' => $this->reportId,
            'title' => $this->title,
            'corps' => $this->corps,
            'status' => $this->status,
            'motif' => $this->motif,
        ];
    }
    public function toBroadcast(object $notifiable): array
    {
        return [
            'report_id' => $this->reportId,
            'title' => $this->title,
            'corps' => $this->corps,
            'status' => $this->status,
            'motif' => $this->motif,
        ];
    }
    public function broadcastAs(): string
    {
        return 'ReportRefused';
    }
    public function broadcastWith(): array
    {
        return [
            'report_id' => $this->reportId,
            'title' => $this->title,
            'corps' => $this->corps,
            'status' => $this->status,
            'motif' => $this->motif,
        ];
    }
}
