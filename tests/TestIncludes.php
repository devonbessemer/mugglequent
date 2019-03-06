<?php


class User extends \Dev\Mugglequent\PrivateModel
{
    use \Dev\Mugglequent\Behaviors\SupportsSoftDeletes;

    protected $table = "users";

    private $privateProperty = "PRIVATE";

    public function getPrivateProperty()
    {
        return $this->privateProperty;
    }

    public function setPrivateProperty($value)
    {
        $this->privateProperty = $value;
    }

    private function privateMethod()
    {
        // do nothing
    }

    private static function privateStaticMethod()
    {
        // do nothing
    }

    public function setAge(int $age)
    {
        $this->attributes['age'] = $age;
    }

    public function getAge(): int
    {
        return $this->attributes['age'];
    }

    public function setName(Name $name)
    {
        $this->attributes['first_name'] = $name->firstName();
        $this->attributes['last_name'] = $name->lastName();
    }

    public function getName(): Name
    {
        return new Name($this->attributes['first_name'], $this->attributes['last_name']);
    }

    protected function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function addAddress(Address $address)
    {
        $this->addresses()->save($address);
    }

    public function getAddresses()
    {
        return $this->getRelationValue("addresses");
    }
}

class Address extends \Dev\Mugglequent\PrivateModel
{
    protected $table = "addresses";
    public $timestamps = false;

    public function setCity(string $city)
    {
        $this->attributes['city'] = $city;
    }

    public function getCity(): string
    {
        return $this->attributes['city'];
    }
}

class Name
{
    private $firstName;
    private $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }
}


class DummyProvider extends Illuminate\Support\ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }

}
