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
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

#[CoversClass(JsonResolver::class)]
#[CoversClassesThatExtendClass(AbstractModelResolver::class)]
final class JsonResolverTest extends TestCase
{
    #[TestDox("Shall return a key-value pair with the argument name being the argument name of the model")]
    public function test1()
    {
        $expectedKey = "testClass";
        $reflectedObj = new class() {
            public function testMethod(#[Model] ?ModelStub $testClass = null) {}
        };
        $requestBody = $this->createStub(StreamInterface::class);
        $serverRequest = $this->createStub(ServerRequestInterface::class);
        $requestBody->method("getContents")->willReturn("[]");
        $serverRequest->method("getBody")->willReturn($requestBody);
        $sut = new JsonResolver($serverRequest);
        $resultPair = $sut->resolve($reflectedObj, "testMethod");
        $this->assertArrayHasKey($expectedKey, $resultPair);
    }

    #[TestDox("Shall instantiate the object with the Model attribute and return it in a key-value pair")]
    public function test2()
    {
        $obj = <<<JSON
        {
            "prop1": "something",
            "prop2": 200,
            "prop3": "what"
        }
        JSON;
        $reflectedObj = new class() {
            public function testMethod(#[Model] ?ModelStub $testClass = null) {}
        };
        $requestBody = $this->createStub(StreamInterface::class);
        $serverRequest = $this->createStub(ServerRequestInterface::class);
        $requestBody->method("getContents")->willReturn($obj);
        $serverRequest->method("getBody")->willReturn($requestBody);
        $sut = new JsonResolver($serverRequest);
        $resultPair = $sut->resolve($reflectedObj, "testMethod");
        $this->assertContainsOnlyInstancesOf(ModelStub::class, $resultPair);
    }

    #[TestDox("Shall return an empty array if no arguments have the Model attribute")]
    public function test3a()
    {
        $reflectedObj = new class() {
            public function testMethod(ModelStub $testClass) {}
        };
        $requestBody = $this->createStub(StreamInterface::class);
        $serverRequest = $this->createStub(ServerRequestInterface::class);
        $requestBody->method("getContents")->willReturn("null");
        $serverRequest->method("getBody")->willReturn($requestBody);
        $sut = new JsonResolver($serverRequest);
        $resultPair = $sut->resolve($reflectedObj, "testMethod");
        $this->assertEmpty($resultPair);
    }

    #[TestDox("Shall return an empty array if union type hint is used")]
    public function test3b()
    {
        $reflectedObj = new class() {
            public function testMethod(ModelStub|string $testClass) {}
        };
        $requestBody = $this->createStub(StreamInterface::class);
        $serverRequest = $this->createStub(ServerRequestInterface::class);
        $requestBody->method("getContents")->willReturn("null");
        $serverRequest->method("getBody")->willReturn($requestBody);
        $sut = new JsonResolver($serverRequest);
        $resultPair = $sut->resolve($reflectedObj, "testMethod");
        $this->assertEmpty($resultPair);
    }

    #[TestDox("Shall return an empty array if intersection type hint is used")]
    public function test3c()
    {
        $reflectedObj = new class() {
            public function testMethod(ModelStub&Closure $testClass) {}
        };
        $requestBody = $this->createStub(StreamInterface::class);
        $serverRequest = $this->createStub(ServerRequestInterface::class);
        $requestBody->method("getContents")->willReturn("null");
        $serverRequest->method("getBody")->willReturn($requestBody);
        $sut = new JsonResolver($serverRequest);
        $resultPair = $sut->resolve($reflectedObj, "testMethod");
        $this->assertEmpty($resultPair);
    }

    #[TestDox("Shall throw an exception if the argument is not a subclass of AbstractModel")]
    public function test4()
    {
        $this->expectException(RuntimeException::class);
        $reflectedObj = new class() {
            public function testMethod(#[Model] ?object $testClass = null) {}
        };
        $requestBody = $this->createStub(StreamInterface::class);
        $serverRequest = $this->createStub(ServerRequestInterface::class);
        $requestBody->method("getContents")->willReturn("null");
        $serverRequest->method("getBody")->willReturn($requestBody);
        $sut = new JsonResolver($serverRequest);
        $sut->resolve($reflectedObj, "testMethod");
    }
}
