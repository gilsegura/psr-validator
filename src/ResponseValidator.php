<?php

declare(strict_types=1);

namespace Psr\Validator;

use Psr\Http\Message\ResponseInterface;
use Psr\Validator\Exception\ValidationExceptionInterface;

final readonly class ResponseValidator
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
    public function __invoke(ResponseInterface $response): ResponseInterface
    {
        foreach ($this->validators as $validator) {
            /** @var ResponseInterface $response */
            $response = $validator->__invoke($response);
        }

        return $response;
    }
}
