<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Factory;

use App\Entity\Project;
use App\Infrastructure\SQL\Repository\ProjectRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Project>
 *
 * @method        Project|Proxy                     create(array|callable $attributes = [])
 * @method static Project|Proxy                     createOne(array $attributes = [])
 * @method static Project|Proxy                     find(object|array|mixed $criteria)
 * @method static Project|Proxy                     findOrCreate(array $attributes)
 * @method static Project|Proxy                     first(string $sortedField = 'id')
 * @method static Project|Proxy                     last(string $sortedField = 'id')
 * @method static Project|Proxy                     random(array $attributes = [])
 * @method static Project|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ProjectRepository|RepositoryProxy repository()
 * @method static Project[]|Proxy[]                 all()
 * @method static Project[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Project[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Project[]|Proxy[]                 findBy(array $attributes)
 * @method static Project[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Project[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ProjectFactory extends ModelFactory
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
            'description' => self::faker()->paragraphs(rand(1, 5), true),
            'name' => self::faker()->domainName(),
            'owner' => UserFactory::random(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            ->afterPersist(function(Project $project): void {
                LinkFactory::new()->many(0, 3)->create(['project' => $project]);
            })
            // ->afterInstantiate(function(Project $project): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Project::class;
    }
}
