<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Book;
use App\Repository\BookRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Book>
 *
 * @method        Book|Proxy create(array|callable $attributes = [])
 * @method static Book|Proxy createOne(array $attributes = [])
 * @method static Book|Proxy find(object|array|mixed $criteria)
 * @method static Book|Proxy findOrCreate(array $attributes)
 * @method static Book|Proxy first(string $sortedField = 'id')
 * @method static Book|Proxy last(string $sortedField = 'id')
 * @method static Book|Proxy random(array $attributes = [])
 * @method static Book|Proxy randomOrCreate(array $attributes = [])
 * @method static BookRepository|RepositoryProxy repository()
 * @method static Book[]|Proxy[] all()
 * @method static Book[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Book[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Book[]|Proxy[] findBy(array $attributes)
 * @method static Book[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Book[]|Proxy[] randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<Book> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Book> createOne(array $attributes = [])
 * @phpstan-method static Proxy<Book> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<Book> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<Book> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<Book> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<Book> random(array $attributes = [])
 * @phpstan-method static Proxy<Book> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<Book> repository()
 * @phpstan-method static list<Proxy<Book>> all()
 * @phpstan-method static list<Proxy<Book>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<Book>> createSequence(iterable|callable $sequence)
 * @phpstan-method static list<Proxy<Book>> findBy(array $attributes)
 * @phpstan-method static list<Proxy<Book>> randomRange(int $min, int $max, array $attributes = [])
 * @phpstan-method static list<Proxy<Book>> randomSet(int $number, array $attributes = [])
 */
final class BookFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'description' => self::faker()->text(),
            'title' => self::faker()->text(30),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Book $book): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Book::class;
    }
}
