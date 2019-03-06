<?php
require __DIR__ . '/TestIncludes.php';

/**
 * Class AccessTest
 * Test that the "magic" accessors are properly disabled and do not affect other functionality
 *
 */
class AccessTest extends \Orchestra\Testbench\TestCase
{
    /**
     * @test
     */
    function setting_a_private_property_throws_an_exception()
    {
        $this->expectException(\Dev\Mugglequent\Exceptions\ProtectedPropertyException::class);

        $user = new User();
        $user->privateProperty = "Hello World";
    }

    /**
     * @test
     */
    function getting_a_private_property_throws_an_exception()
    {
        $this->expectException(\Dev\Mugglequent\Exceptions\ProtectedPropertyException::class);

        $user = new User();
        $test = $user->privateProperty;
    }

    /**
     * @test
     */
    function setting_an_undefined_property_throws_an_exception()
    {
        $this->expectException(\Dev\Mugglequent\Exceptions\UndefinedPropertyException::class);

        $user = new User();
        $user->undefinedProperty = "Hello World";
    }

    /**
     * @test
     */
    function getting_an_undefined_property_throws_an_exception()
    {
        $this->expectException(\Dev\Mugglequent\Exceptions\UndefinedPropertyException::class);

        $user = new User();
        $test = $user->undefinedProperty;
    }

    /**
     * @test
     */
    function a_public_getter_and_setter_still_work()
    {
        $user = new User();
        $user->setPrivateProperty("Hello World");
        $this->assertEquals("Hello World", $user->getPrivateProperty());
    }


    /**
     * @test
     */
    function set_and_get_attribute_still_work()
    {
        $user = new User();
        $user->setAttribute("test", "Hello World");
        $this->assertEquals("Hello World", $user->getAttribute("test"));
    }

    /**
     * @test
     */
    function calling_a_private_method_throws_an_exception()
    {
        $this->expectException(\Dev\Mugglequent\Exceptions\ProtectedMethodException::class);

        $user = new User();
        $user->privateMethod();
    }

    /**
     * @test
     */
    function calling_an_undefined_method_throws_an_exception()
    {
        $this->expectException(\Dev\Mugglequent\Exceptions\UndefinedMethodException::class);

        $user = new User();
        $user->undefinedMethod();
    }

    /**
     * @test
     */
    function calling_a_private_static_method_throws_an_exception()
    {
        $this->expectException(\Dev\Mugglequent\Exceptions\ProtectedMethodException::class);

        User::privateStaticMethod();
    }

    /**
     * @test
     */
    function calling_an_undefined_static_method_throws_an_exception()
    {
        $this->expectException(\Dev\Mugglequent\Exceptions\UndefinedMethodException::class);

        User::undefinedMethod();
    }

    /**
     * @test
     */
    function calling_query_statically_still_returns_a_query_builder()
    {
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, User::query());
    }

}