# rekalogika/api-lite

A set of tools to simplify working with [API
Platform](https://api-platform.com/) in your projects. Comes with guides,
patterns, and practical examples for building API Platform-based projects.

Apply existing knowledge, experience, and patterns of working with Symfony
controllers to API Platform state providers and processors. Decouple your
persistence layer from the API frontend layer. Apply Domain-Driven Design (DDD)
principles to your API Platform-based projects, as well as other architectural
patterns and best practices, including SOLID, onion architecture, clean
architecture, hexagonal architecture, and others.

'Lite' means we are refraining from using all of API Platform's features and
automations in favor of better readability, simplicity, and flexibility.

Full documentation is available at
[rekalogika.dev/api-lite](https://rekalogika.dev/api-lite/).

## Motivation

API Platform documentation encourages developers to use plain old PHP objects
(POPOs) or data transfer objects (DTOs) as the models for API communication,
instead of using domain entities directly for this purpose. But it does not
establish a practical working patterns for that approach.

Practically all the examples and demos we find on the Internet still
attach `ApiResource` to Doctrine entities.

Sometimes API Platform can feel very rigid. It can be difficult to figure out
how to accomplish things outside its conventions. There are ways around any
problem, just not always immediately obvious. It can feel like that we just want
to express what we need by writing a PHP code, not by figuring out the correct
combination of attributes to use.

Those coming from Symfony controllers might find API Platform's approach very
different, but it does not have to be.

## Installation

```bash
composer require rekalogika/api-lite
```

## Synopsis

```php
use Doctrine\Common\Collections\Collection;
use Rekalogika\ApiLite\State\AbstractProvider;
use Rekalogika\Mapper\CollectionInterface;

#[ApiResource(
    shortName: 'Book',
    operations: [
        new Get(
            uriTemplate: '/books/{id}',
            provider: BookProvider::class
        ),
    ]
)]
class BookDto
{
    public ?Uuid $id = null;
    public ?string $title = null;
    public ?string $description = null;

    /**
     * @var ?CollectionInterface<int,ReviewDto>
     */
    public ?CollectionInterface $reviews = null;
}

/**
 * @extends AbstractProvider<BookDto>
 */
class BookProvider extends AbstractProvider
{
    public function __construct(
        private BookRepository $bookRepository
    ) {
    }

    public function provide(
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): object|array|null {
        $book = $this->bookRepository
            ->find($uriVariables['id'] ?? null)
            ?? throw new NotFoundException('Book not found');

        $this->denyAccessUnlessGranted('view', $book);

        return $this->map($book, BookDto::class);
    }
}
```

## To-Do List

* Figure out & implement filtering.

## Documentation

[rekalogika.dev/api-lite](https://rekalogika.dev/api-lite/)

## License

MIT
