<?php

declare(strict_types=1);

namespace Phpolar\Model;

use Closure;
use Phpolar\Model\Model;
use Phpolar\Model\Tests\Stubs\ModelStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversClassesThatExtendClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(ParsedBodyResolver::class)]
#[CoversClassesThatExtendClass(AbstractModelResolver::class)]
final class ParsedBodyResolverTest extends TestCase
{
    #[TestDox("Shall return a key-value pair with the argument name being the argument name of the model")]
    public function test1()
    {
        $emptyParsedBody = [];
        $expectedKey = "testClass";
        $reflectedObj = new class () {
            public function testMethod(#[Model] ?ModelStub $testClass = null)
            {
            }
        };
        $sut = new ParsedBodyResolver($emptyParsedBody);
        $resultPair = $sut->resolve($reflectedObj, "testMethod");
        $this->assertArrayHasKey($expectedKey, $resultPair);
    }

    #[TestDox("Shall instantiate the object with the Model attribute and return it in a key-value pair")]
    public function test2()
    {
        $parsedBody = [
            "prop1" => "something",
            "prop2" => random_int(1, 200),
            "prop3" => "what",
        ];
        $reflectedObj = new class () {
            public function testMethod(#[Model] ?ModelStub $testClass = null)
            {
            }
        };
        $sut = new ParsedBodyResolver($parsedBody);
        $resultPair = $sut->resolve($reflectedObj, "testMethod");
        $this->assertContainsOnlyInstancesOf(ModelStub::class, $resultPair);
    }

    #[TestDox("Shall return an empty array if no arguments have the Model attribute")]
    public function test3()
    {
        $reflectedObj = new class () {
            public function testMethod(ModelStub $testClass)
            {
            }
        };
        $sut = new ParsedBodyResolver(null);
        $resultPair = $sut->resolve($reflectedObj, "testMethod");
        $this->assertEmpty($resultPair);
    }

    #[TestDox("Shall return an empty array if union type hint is used")]
    public function test3b()
    {
        $reflectedObj = new class () {
            public function testMethod(ModelStub|string $testClass)
            {
            }
        };
        $sut = new ParsedBodyResolver(null);
        $resultPair = $sut->resolve($reflectedObj, "testMethod");
        $this->assertEmpty($resultPair);
    }

    #[TestDox("Shall return an empty array if intersection type hint is used")]
    public function test3c()
    {
        $reflectedObj = new class () {
            public function testMethod(ModelStub&Closure $testClass)
            {
            }
        };
        $sut = new ParsedBodyResolver(null);
        $resultPair = $sut->resolve($reflectedObj, "testMethod");
        $this->assertEmpty($resultPair);
    }

    #[TestDox("Shall throw an exception if the argument is not a subclass of AbstractModel")]
    public function test4()
    {
        $this->expectException(RuntimeException::class);
        $reflectedObj = new class () {
            public function testMethod(#[Model] ?object $testClass = null)
            {
            }
        };
        $sut = new ParsedBodyResolver(null);
        $sut->resolve($reflectedObj, "testMethod");
    }
}
