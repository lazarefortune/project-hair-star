<?php

namespace App\Flash;

class FlashMessage
{
    public function __construct(
        private readonly string $message,
        private readonly string $title = '',
        private readonly string $type = 'info',
        private readonly int    $duration = 5000,
        private readonly string $position = 'top-right'
    )
    {
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

    public function getDuration() : int
    {
        return $this->duration;
    }

    public function getPosition() : string
    {
        return $this->position;
    }
}