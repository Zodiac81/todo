<?php

namespace App\DTO;
use App\Foundation\BaseDto;

class ApiTodoDTO
{
    /**
     * @param string|null $id
     * @param string|null $title
     * @param string|null $description
     */
    public function __construct(
        public ?string $id,
        public ?string $title,
        public ?string $description,
    ) {}

}
