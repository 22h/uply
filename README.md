# uply
a simple service for checking online times and ssl certificates

## available checks

- status code
- content hash
- ssl certificate expire 

## supervisord
Add new entry in `supervisord.conf` (default: `/etc/supervisor/supervisord.conf`) 
```
[program:uply_job_loop]
command=php /var/www/uply.dev/bin/console job:loop
```
### install supervisord 
```
# install
apt-get install supervisor

# check deamon status
service supervisor status 

# check program status
supervisorctl status

# stop it
supervisorctl stop uply_job_loop

# start it
supervisorctl start uply_job_loop
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
Add new Uply Unit Scrutinizer in `src/Scrutinizer/Services/` with `AbstractScrutinizer`.
```php
/**
 * Scrutinizer
 */
class FooBarScrutinizer implements AbstractScrutinizer
{

    /**
      * @param FooBarRepository $repository
      */
    public function __construct(FooBarRepository $repository)
    {
            parent::__construct($repository);
    }

    /**
     * @param FooBar $unit
     *
     * @throws \Exception
     */
    public function scrutinize(UnitInterface $unit): NotificationData
    {
        // Check something and return a NotificationData Object
        // return $this->notificationDataFactory->createDangerNotificationData(
        //     'foo_bar.danger',
        //     ['%fooBar%' => $fooBar]
        // );
    }
}
```

### 4. Translations
Add new translations in `translations/notification.[a-z].php`.
