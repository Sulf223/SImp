<?php
declare(strict_types=1);

namespace OffByOneAcademy\Tests\Unit;

use PHPUnit\Framework\TestCase;

class PasswordValidationTest extends TestCase
{
    public function testPasswordTooShort(): void
    {
        $this->assertFalse(validate_password_complexity('short1A'));
    }

    public function testPasswordNoDigits(): void
    {
        $this->assertFalse(validate_password_complexity('PasswordWithoutDigits'));
    }

    public function testPasswordNoLetters(): void
    {
        $this->assertFalse(validate_password_complexity('123456789'));
    }

    public function testPasswordValid(): void
    {
        $this->assertTrue(validate_password_complexity('Valid1Password!'));
    }
}
