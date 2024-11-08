<?php

declare(strict_types=1);

namespace Psr\Validator;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Validator\Exception\ValidationExceptionInterface;

final readonly class ServerRequestValidator
{
    /** @var MessageValidatorInterface[] */
    private array $validators;

    public function __construct(
        MessageValidatorInterface ...$validators,
    ) {
        $this->validators = $validators;
    }

    /**
     * @throws ValidationExceptionInterface
     */
    public function __invoke(MessageInterface $message): void
    {
        if (!$message instanceof ServerRequestInterface) {
            return;
        }

        foreach ($this->validators as $validator) {
            $validator->__invoke($message);
        }
    }
}
