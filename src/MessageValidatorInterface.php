<?php

declare(strict_types=1);

namespace Psr\Validator;

use Psr\Http\Message\MessageInterface;
use Psr\Validator\Exception\ValidationExceptionInterface;

interface MessageValidatorInterface
{
    /**
     * @throws ValidationExceptionInterface
     */
    public function __invoke(MessageInterface $message): MessageInterface;
}
