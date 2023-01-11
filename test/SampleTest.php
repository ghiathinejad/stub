<?php

use App\User;
use System\Iterator\ModelIterator;
use PHPUnit\Framework\TestCase;

final class SampleTest extends TestCase
{
    private string $indexAddress = __DIR__ . '/../index.php';

    public function setUp(): void
    {
        if (file_exists(__DIR__ . "/../app/User.php")) {
            unlink(__DIR__ . "/../app/User.php");
        }
    }
    public function testCreateModel1()
    {
        $shell = shell_exec("php {$this->indexAddress} app:create-model users");
        self::assertFileExists(__DIR__ . '/../app/User.php');
        self::assertStringContainsString("class User extends Model", file_get_contents(__DIR__ . '/../app/User.php'));
        $this->assertSame("successfully created:)", $shell);
    }

}