<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class TaskDto
{
    #[Assert\NotBlank]
    public string $title;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 1000)]
    public string $description;

    public bool $isDone = false;

}