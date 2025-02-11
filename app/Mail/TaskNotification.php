<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $status;

    /**
     * Create a new message instance.
     */
    public function __construct($task, $status)
    {
        $this->task = $task;
        $this->status = $status;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("A Task has been {$this->status}")
                    ->view('emails.task_notification');
    }
}
