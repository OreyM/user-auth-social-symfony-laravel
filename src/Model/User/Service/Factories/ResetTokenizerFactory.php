<?php

namespace App\Model\User\Service\Factories;

use App\Model\User\Service\ResetTokenizer;

class ResetTokenizerFactory
{
    public function create(string $interval): ResetTokenizer
    {
        return new ResetTokenizer(new \DateInterval($interval));
    }
}