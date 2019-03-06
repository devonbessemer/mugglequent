# Mugglequent

This is a prototype and not yet production ready.  The purpose of this prototype is to provide simple behaviors and conventions that:
 
1.  Limit the number of ways that Eloquent models can be mutated.
2.  Reliably enable the use of value objects with models.
3.  Decrease the magic that prevents both IDE autocompletion and static analysis.

These items enable larger and more complex systems to be more easily maintained and understood when using Eloquent.  The magic methods and inability to protect attributes can lead large systems to have unexpected mutations and inconsistent usages.

## Available Behaviors

There are three behaviors that can be used all together (extending PrivateModel) or separately.  Behaviors are encapsulated in a trait.

1. DisablesMagicCallers - Disables magic passthrough to the query builder. (__call, __callStatic)
2. DisablesMagicGetters - Disables magic access of attributes (__get)
3. DisablesMagicSetters - Disables magic setting of attributes (__set)

## Conventions

### Using getters and setters for attributes

Since we are disabling the magic getters and setters of Eloquent models, a simple getter and setter for each attribute can be created.

```php
class User extends PrivateModel {

    public function setFirstName(string $name)
    {
        $this->attributes['first_name'] = $name;
    }
    
    public function getFirstName()
    {
        return $this->attributes['first_name'];
    }

}
```

Our implementation already overrides and implements `setCreatedAt` and `setUpdatedAt` for you, which are requred by Eloquent.  You may not need a setter, especially when dealing with keys, so don't automatically implement a getter and setter for every attribute.

### Using value objects for attributes, aka embedded types

Disabling magic allows you to reliably use value objects across your models without touching the underlying Eloquent code.

```php
class User extends PrivateModel {

    public function setName(Name $name)
    {
        $this->attributes['first_name'] = $name->firstName();
        $this->attributes['last_name'] = $name->lastName();
    }
    
    public function getName(): Name
    {
        return new Name($this->attributes['first_name'], $this->attributes['last_name']);
    }

}
```
Note: We use methods here to access `firstName` and `lastName` because we favor immutable value objects.

```php
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
```

### Accessing relationships

Eloquent's convention is to use public methods, but we recommend protected methods and defining a public API for accessing relationships.

Note: The relationship methods cannot be made private, they must be made protected, so the base Model class can access them for loading data.

#### One-to-one relationship example

```php
class User extends PrivateModel {

    protected function address()
    {
        return $this->hasOne(Address::class);
    }

    public function setAddress(Address $address)
    {
        $this->address()->save($address);
    }
    
    public function getAddress(): ?Address
    {
        return $this->getRelationValue("address");
    }
}
```

#### One-to-many relationship example

```php
class User extends PrivateModel {

    protected function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function addAddress(Address $address)
    {
        $this->addresses()->save($address);
    }
    
    public function removeAddress(Address $address)
    {
        $address->delete();
    }
    
    /** @return Collection|Address[] */
    public function getAddresses(): Collection
    {
        return $this->getRelationValue("addresses");
    }
}
```

### Accessing the Eloquent Builder (Query Builder)

Since magic callers are disabled, we require one additional step to access the builder instance from a model:

```php
// Instead of User::where('id', 1)->first()
User::query()->where('id', 1)->first();
```

This simple change of making you go through the query() method accomplishes the following goals:

1. An IDE can correctly autocomplete builder methods without any plugins or meta files.
2. Hopefully, it will encourage users to extract query logic away from their models.

While you can still query from a Model class, like above, we encourage you to use a repository or dedicated query class.