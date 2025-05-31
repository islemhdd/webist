<?php

namespace App\Notifications;



use App\Models\Report;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Queue\SerializesModels;

class DesitionMade extends Notification
{
    use Queueable;
    use SerializesModels;
    public int $reportId;
    public  $title;
    public $corps;
    public $status;
    public $officerId;

    /**
     * Create a new notification instance.
     */
    public function __construct(Report $report)
    {

        $this->reportId = $report->id;

        $this->title = $report->title;
        $this->corps = $report->corps;
        $this->status = $report->status;
        $this->officerId = $report->officer_id;
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

    public function toDatabase(Object $notifiable): array
    {
        return [
            'report_id' => $this->reportId,
            'title' => $this->title,
            'message' => "New report: " . $this->title,
            'url' => route('report.show', ['id' => $this->officerId, 'report_id' => $this->reportId])
        ];
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
        ];
    }
    public function toBroadcast(object $notifiable): array
    {
        return [
            'report_id' => $this->reportId,
            'title' => $this->title,
            'message' => "New report: " . $this->title,
            'url' => route('report.show', ['id' => $this->officerId, 'report_id' => $this->reportId])
        ];
    }
    public function broadcastOn(): array
    {
        return [
            new Channel('App.Models.Officer.' . $this->officerId),
        ];
    }
}
