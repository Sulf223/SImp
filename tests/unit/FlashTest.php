<?php
declare(strict_types=1);

namespace OffByOneAcademy\Tests\Unit;

use PHPUnit\Framework\TestCase;

class FlashTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    public function testSetFlashStoresInSession(): void
    {
        set_flash('success', 'Operation successful');
        $this->assertArrayHasKey('flash_messages', $_SESSION);
        $this->assertCount(1, $_SESSION['flash_messages']);
        $this->assertEquals('success', $_SESSION['flash_messages'][0]['type']);
        $this->assertEquals('Operation successful', $_SESSION['flash_messages'][0]['message']);
    }

    public function testDisplayFlashClearsAfterRender(): void
    {
        set_flash('error', 'Operation failed');
        
        ob_start();
        display_flash();
        $output = ob_get_clean();
        
        $this->assertStringContainsString('toast--error', $output);
        $this->assertStringContainsString('Operation failed', $output);
        
        $this->assertArrayNotHasKey('flash_messages', $_SESSION);
    }
}
