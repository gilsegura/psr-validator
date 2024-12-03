<?php

declare(strict_types=1);

namespace Psr\Validator\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Validator\Exception\ValidationExceptionInterface;
use Psr\Validator\ResponseValidator;
use Psr\Validator\ServerRequestValidator;

final readonly class ValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ServerRequestValidator $serverRequestValidator,
        private ResponseValidator $responseValidator,
    ) {
    }

    /**
     * @throws ValidationExceptionInterface
     */
    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $this->serverRequestValidator->__invoke($request);

        return $this->responseValidator->__invoke(
            $handler->handle($request)
        );
    }
}
