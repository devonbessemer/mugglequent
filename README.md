# Eloquent Private Model

This is a prototype and not yet production ready.  The purpose of this prototype is to:
 
1.  Limit the number of ways that Eloquent models can be mutated.
2.  Allow for the use of value objects with models.
3.  Decrease the necessity of PHPDoc blocks in order to use IDE autocompletion.

The above 3 items enable larger-complex systems to be more easily maintained when using Eloquent.

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

Often times, you don't need a setter for a field.  Our implementation already overrides and implements `setCreatedAt` and `setUpdatedAt` for you, but you also probably don't need a setter for your `id` attribute.

### Using value objects for attributes, aka embedded types

The power in disabling magic is to allow you to reliably use value objects with your model without touching the underlying Eloquent code.

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

### Accessing relationships

While not required, we recommend the following method of using relationships.  Eloquent's convention is to use public methods, but we recommend protected methods and defining a public API for accessing relationships.  
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