<?php

namespace App\DTO;

use App\Foundation\BaseDto;

class TodoDTO extends BaseDto
{
    /**
     * @var string
     */
    public string $title;

    /**
     * @var string|null
     */
    public ?string $description;
}
