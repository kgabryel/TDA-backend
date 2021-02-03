<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    private ?string $content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(?string $content)
    {
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view(
            'notification',
            [
                'content' => $this->content
            ]
        );
    }
}
