<?php
declare(strict_types=1);

namespace SImp\Tests\Unit;

use PHPUnit\Framework\TestCase;

class CsrfTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset session before each test
        $_SESSION = [];
        $_POST = [];
    }

    public function testGenerateTokenIsHex64Chars(): void
    {
        $token = get_csrf_token();
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $token);
        $this->assertEquals($token, $_SESSION['csrf_token']);
    }

    public function testVerifyCsrfReturnsTrueForValidToken(): void
    {
        $token = get_csrf_token();
        $_POST['csrf_token'] = $token;
        
        // verify_csrf() calls exit on failure, so if it returns true (or doesn't die), it's good.
        // We'll capture output/exit using a workaround if needed, but since our current verify_csrf
        // terminates script execution on failure, we just want to ensure it doesn't terminate.
        $this->expectNotToPerformAssertions();
        verify_csrf();
    }

    // Notice: testVerifyCsrfReturnsFalseForInvalidToken is tricky to test since the function currently calls exit;
    // For a strict unit test we might need to modify verify_csrf() to throw an Exception or return a boolean.
    // As per the prompt constraints, we shouldn't rewrite existing functions, but let's test what we can.
}
