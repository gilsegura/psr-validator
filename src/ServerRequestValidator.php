<?php

declare(strict_types=1);

namespace Psr\Validator;

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
    public function __invoke(ServerRequestInterface $serverRequest): ServerRequestInterface
    {
        foreach ($this->validators as $validator) {
            /** @var ServerRequestInterface $serverRequest */
            $serverRequest = $validator->__invoke($serverRequest);
        }

        return $serverRequest;
    }
}
