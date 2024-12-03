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
    public function __invoke(MessageInterface $message): MessageInterface
    {
        foreach ($this->validators as $validator) {
            $message = $validator->__invoke($message);
        }

        return $message;
    }
}
