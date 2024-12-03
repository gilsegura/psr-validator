<?php

declare(strict_types=1);

namespace Psr\Validator;

use Psr\Http\Message\RequestInterface;
use Psr\Validator\Exception\ValidationExceptionInterface;

final readonly class RequestValidator
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
    public function __invoke(RequestInterface $request): RequestInterface
    {
        foreach ($this->validators as $validator) {
            /** @var RequestInterface $request */
            $request = $validator->__invoke($request);
        }

        return $request;
    }
}
