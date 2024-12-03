<?php

declare(strict_types=1);

namespace Psr\Validator\Tests;

use Nyholm\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\MessageInterface;
use Psr\Validator\MessageValidatorInterface;
use Psr\Validator\RequestValidator;

final class RequestValidatorTest extends TestCase
{
    public function test_must_validate_request(): void
    {
        self::expectException(\Exception::class);

        $validator = new RequestValidator(new MessageValidator());

        $request = new Request(
            'GET',
            'https://www.example.com/api/v3/users',
            ['Accept' => 'application/json']
        );

        $validator->__invoke($request);
    }
}

final readonly class MessageValidator implements MessageValidatorInterface
{
    #[\Override]
    public function __invoke(MessageInterface $message): MessageInterface
    {
        throw new \Exception();
    }
}
