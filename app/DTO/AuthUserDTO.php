<?php

namespace App\DTO;

use App\Foundation\BaseDto;

class AuthUserDTO extends BaseDto
{
    /**
     * @var string
     */
    public string $email;

    /**
     * @var string
     */
    public string $password;

}
