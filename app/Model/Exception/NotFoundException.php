<?php

namespace App\Model\Exception;

interface NotFoundException
{
    public function getEntityCode(): string;
}
