<?php

declare(strict_types=1);

namespace App\ApiState\Admin\Book;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\Admin\BookDto;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Order;
use Rekalogika\ApiLite\State\AbstractProvider;
use Rekalogika\Collections\Decorator\LazyMatching\LazyMatchingSelectable;
use Symfony\Component\HttpFoundation\Request;

/**
 * Example of a manual filtering. A temporary solution until we have a better
 * way to handle this.
 *
 * @extends AbstractProvider<BookDto>
 */
class BookCollectionWithSearchProvider extends AbstractProvider
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
        // Check for authorization
        $this->denyAccessUnlessGranted('view', $this->bookRepository);

        // make the repository lazy, so that the underlying database query will
        // be performed when the result is actually used.
        // @see https://rekalogika.dev/doctrine-collections-decorator/cookbook/lazy-chained-matching
        $repository = new LazyMatchingSelectable($this->bookRepository);

        // Get the request object
        $request = $context['request'] ?? null;
        if (!$request instanceof Request) {
            throw new \LogicException('The request is missing from the context.');
        }

        // Get the search query from the request
        $search = $request->query->get('search');

        if ((bool) $search) {
            $criteria = Criteria::create()
                ->andWhere(Criteria::expr()->contains('title', $search))
                ->orWhere(Criteria::expr()->contains('description', $search))
                ->orderBy(['id' => Order::Ascending]);

            $repository = $repository->matching($criteria);
        }

        // A Doctrine repository implements Selectable, and our PaginatorApplier
        // supports Selectable, so we can convieniently use it as a collection
        // of entities to map. Here we map the Books to BookDtos, and return
        // them.
        return $this->mapCollection(
            collection: $repository,
            target: BookDto::class,
            operation: $operation,
            context: $context
        );
    }
}
