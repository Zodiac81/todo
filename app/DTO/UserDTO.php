<?php

namespace App\DTO;

use App\Foundation\BaseDto;

class UserDTO extends BaseDto
{
    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $email;

    /**
     * @var string
     */
    public string $password;
}
