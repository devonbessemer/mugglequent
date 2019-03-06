<?php
require __DIR__ . "/TestIncludes.php";

/**
 * Class PersistenceTest
 * Tests that records can still be persisted to, and retrieved from, the database layer
 *
 */
class PersistenceTest extends \Orchestra\Testbench\TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->artisan('migrate', ['--database' => 'testing']);
    }


    /**
     * Get application providers.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            DummyProvider::class,
        ];
    }

    /**
     * @test
     */
    function a_user_can_be_created()
    {
        $user = new User();
        $name = new Name("John", "Doe");
        $user->setName($name);
        $user->setAge(50);
        $user->save();

        $user = User::query()->first();

        $this->assertEquals(50, $user->getAge());
        $this->assertEquals($name, $user->getName());
    }

    /**
     * @test
     */
    function an_address_can_be_attached_to_a_user()
    {
        $user = new User();
        $name = new Name("John", "Doe");
        $user->setName($name);
        $user->setAge(50);
        $user->save();

        $address = new Address();
        $address->setCity("New York");
        $user->addAddress($address);

        $this->assertEquals("New York", $user->fresh()->getAddresses()->first()->getCity());

    }

    /**
     * @test
     */
    function a_model_can_be_hard_deleted()
    {
        $address = new Address();
        $address->setCity("New York");
        $address->setAttribute("user_id", 1);
        $address->save();
        $address->delete();

        $this->assertNull(Address::query()->first());
    }

    /**
     * @test
     */
    function a_model_can_be_soft_deleted()
    {
        $user = new User();
        $name = new Name("John", "Doe");
        $user->setName($name);
        $user->setAge(50);
        $user->save();
        $user->delete();

        $this->assertNull(Address::query()->first());
    }

}
