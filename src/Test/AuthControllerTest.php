<?php

use App\Controller\Auth\AuthController;
use App\Controller\Auth\Methods\FacebookAuthService;
use App\Controller\Auth\Models\AuthResult;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthControllerTest extends WebTestCase
{
    private $facebookAuthServiceMock;

    public function setUp(): void
    {
        parent::setUp();
        putenv('KEY=test_key');
        $this->facebookAuthServiceMock = $this->getMockBuilder(FacebookAuthService::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testSuccessfulAuth()
    {
        $mockRequestContent = json_encode([
            'authMethod' => "facebook",
            'token' => "valid_token",
            'appId' => "valid_app_id",
        ]);

        $authResult = new AuthResult(true, 'some_user_id');
        $this->facebookAuthServiceMock->method('login')
            ->willReturn($authResult);

        $request = new Request([], [], [], [], [], [], $mockRequestContent);
        $request->headers->set('signature', 't=1692622609106,v1=f5a29dc8a5ca7dc6c6542a6700a29e45d85f1def1ed4befcf4ea7e5213820512');

        $controller = new AuthController($this->facebookAuthServiceMock);

        $response = $controller->auth($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals("valid", $responseData['status']);
    }

    public function testUnsuccessfulAuth()
    {
        $mockRequestContent = json_encode([
            'authMethod' => 'unsupported_method',
            'token' => 'valid_token',
            'appId' => 'valid_app_id',
        ]);

        $controller = new AuthController($this->facebookAuthServiceMock);

        $request = new Request([], [], [], [], [], [], $mockRequestContent);
        $request->headers->set('signature', 't=1692622392319,v1=7657f5c6f65807d017b4136398f4b8fec4660ce94398f3f59f8e6a80501bb47c');

        $response = $controller->auth($request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testUnsuccessfulAuthInvalidSignature()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid signature format');

        $mockRequestContent = json_encode([
            'authMethod' => 'unsupported_method',
            'token' => 'valid_token',
            'appId' => 'valid_app_id',
        ]);

        $request = new Request([], [], [], [], [], [], $mockRequestContent);
        $request->headers->set('signature', 'invalid_signature');

        $controller = new AuthController($this->facebookAuthServiceMock);

        $controller->auth($request);
    }
}
