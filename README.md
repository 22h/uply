# uply
a simple service for checking online times and ssl certificates

## available checks

- status code
- google page speed
- ssl certificate expire 

## cronjob
```
* * * * * php /path/to/project/bin/console monitor:loop:start
```
## create new uply unit

### 1. Entity
Add new Doctrine Entity in `src/Entity/Unit/` with `AbstractUnit` and `UnitTrait`.
```php
/**
 * FooBar
 *
 * @ORM\Table(name="unit_foo_bar")
 * @ORM\Entity(repositoryClass="App\Repository\Unit\FooBarRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class FooBar extends AbstractUnit
{

    use UnitTrait;
    
    // your own properties with getter/settter here...
    
    /**
     * @return string
     */
    public function getIdent(): string
    {
        return 'foo_bar';
    }
}
```

### 2. Repository
Add new Doctrine Repository in `src/Repository/Unit/` with `MonitorRepositoryInterface`.
```php
/**
 * FooBarRepository
 */
class FooBarRepository extends AbstractMonitorRepository
{

}
```

### 3. Scrutinizer
Add new Uply Unit Scrutinizer in `src/Unit/FooBar/` with `AbstractUnitService`.
```php
/**
 * Scrutinizer
 */
class Scrutinizer implements ScrutinizerInterface
{
    /**
     * @var NotifyEventDispatcher
     */
    private $notifyEventDispatcher;

    /**
     * Scrutinizer constructor.
     *
     * @param NotifyEventDispatcher $notifyEventDispatcher
     */
    public function __construct(NotifyEventDispatcher $notifyEventDispatcher)
    {
        $this->notifyEventDispatcher = $notifyEventDispatcher;
    }

    /**
     * @param FooBar $unit
     *
     * @throws \Exception
     */
    public function scrutinize($unit): void
    {
        // Check something and if it is not as expected, then trigger 
        // $this->notifyEventDispatcher->dispatchNotification(...); 
        // unless your unit has already been triggered.
    }
}
```

### 4. Unit Service
Add new Uply Unit Service in `src/Unit/` with `UnitServiceInterface`.
```php
/**
 * FooBarService
 */
class FooBarService extends AbstractUnitService
{

    /**
     * FooBarService constructor.
     *
     * @param FooBarRepository $repository
     * @param Scrutinizer          $scrutinizer
     */
    public function __construct(FooBarRepository $repository, Scrutinizer $scrutinizer)
    {
        $this->repository = $repository;
        $this->scrutinizer = $scrutinizer;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return FooBar::class;
    }
}
```

