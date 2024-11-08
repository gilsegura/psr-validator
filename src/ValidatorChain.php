<?php

declare(strict_types=1);

namespace Psr\Validator;

use Psr\Http\Message\MessageInterface;

final readonly class ValidatorChain implements MessageValidatorInterface
{
    /** @var MessageValidatorInterface[] */
    private array $validators;

    public function __construct(
        MessageValidatorInterface ...$validators,
    ) {
        $this->validators = $validators;
    }

    #[\Override]
    public function __invoke(MessageInterface $message): void
    {
        foreach ($this->validators as $validator) {
            $validator->__invoke($message);
        }
    }
}
