<?php

declare(strict_types=1);

namespace Psr\Validator\Tests\Middleware;

use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Validator\MessageValidatorInterface;
use Psr\Validator\Middleware\ValidationMiddleware;
use Psr\Validator\ResponseValidator;
use Psr\Validator\Schema\SchemaValidator;
use Psr\Validator\SchemaFactory\FileFactory;
use Psr\Validator\ServerRequestValidator;
use Psr\Validator\ValidatorChain;

final class ValidationMiddlewareTest extends TestCase
{
    public function test_must_validate_handled_server_request(): void
    {
        $schema = (new FileFactory(__DIR__.'/../schemas/schema.json'))->__invoke();

        $validator = new ValidationMiddleware(
            new ServerRequestValidator(new ValidatorChain(new ContentTypeValidator(), new AcceptValidator()), new BodyValidator($schema)),
            new ResponseValidator(new ContentTypeValidator(), new BodyValidator($schema))
        );

        $request = new ServerRequest(
            'POST',
            'https://www.example.com/api/v3/users',
            ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            <<<'JSON'
            {
                "id": 10,
                "username": "john_doe",
                "firstname": "John",
                "surname": "Doe",
                "email": "john@email.com",
                "password": "password",
                "phone": "600000000"
            }
            JSON
        );
        $handler = new RequestHandler();

        $response = $validator->process($request, $handler);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }
}

final readonly class RequestHandler implements RequestHandlerInterface
{
    #[\Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            <<<'JSON'
            {
                "id": 10,
                "username": "john_doe",
                "firstname": "John",
                "surname": "Doe",
                "email": "john@email.com",
                "password": "password",
                "phone": "600000000"
            }
            JSON
        );
    }
}

final readonly class ContentTypeValidator implements MessageValidatorInterface
{
    #[\Override]
    public function __invoke(MessageInterface $message): MessageInterface
    {
        if ('application/json' !== $message->getHeaderLine('content-type')) {
            throw new \Exception();
        }

        return $message;
    }
}

final readonly class AcceptValidator implements MessageValidatorInterface
{
    #[\Override]
    public function __invoke(MessageInterface $message): MessageInterface
    {
        if ('application/json' !== $message->getHeaderLine('accept')) {
            throw new \Exception();
        }

        return $message;
    }
}

final readonly class BodyValidator implements MessageValidatorInterface
{
    public function __construct(
        private object $schema,
    ) {
    }

    #[\Override]
    public function __invoke(MessageInterface $message): MessageInterface
    {
        $stream = $message->getBody();
        $body = $stream->__toString();

        if ($stream->isSeekable()) {
            $stream->rewind();
        }

        if (!json_validate($body)) {
            throw new \Exception();
        }

        try {
            $json = (object) json_decode($body, false, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw new \Exception();
        }

        $errors = (new SchemaValidator())->__invoke(clone $json, clone $this->schema);

        if ([] !== $errors) {
            throw new \Exception();
        }

        return $message;
    }
}
