
<?php

use App\PasswordGenerator;
use PHPUnit\Framework\TestCase;

class PasswordGeneratorTest extends TestCase
{
    private PasswordGenerator $passwordGenerator;

    protected function setUp(): void
    {
        $this->passwordGenerator = new PasswordGenerator();
    }

    public function testGenerateDefaultPasswordLength(): void
    {
        $password = $this->passwordGenerator->generate();
        
        $this->assertEquals(12, strlen($password));
    }

    public function testGenerateCustomLength(): void
    {
        $customLength = 15;
        $password = $this->passwordGenerator->generate($customLength, false);
        
        $this->assertEquals($customLength, strlen($password));
    }

    public function testGenerateWithSpecialCharacters(): void
    {
        $password = $this->passwordGenerator->generate(20, true);
        
        $hasSpecialChars = (bool) preg_match('/[!@#$%^&*()_+\-=\[\]{}|;:,.<>?]/', $password);
        $this->assertTrue($hasSpecialChars);
    }

    public function testGenerateWithoutSpecialCharacters(): void
    {
        $password = $this->passwordGenerator->generate(15, false);
        
        $hasSpecialChars = (bool) preg_match('/[!@#$%^&*()_+\-=\[\]{}|;:,.<>?]/', $password);
        $this->assertFalse($hasSpecialChars);
    }

    public function testGenerateWithMinimumLength(): void
    {
        $password = $this->passwordGenerator->generate(6, false);
        
        $this->assertEquals(6, strlen($password));
    }

    public function testGenerateThrowsExceptionForShortLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Длина пароля не может быть меньше 6 символов");
        
        $this->passwordGenerator->generate(5);
    }

    public function testGeneratedPasswordsAreDifferent(): void
    {
        $password1 = $this->passwordGenerator->generate();
        $password2 = $this->passwordGenerator->generate();
        
        $this->assertNotEquals($password1, $password2);
    }

    public function testPasswordAsCharacterArray(): void
    {
        $password = $this->passwordGenerator->generate(8, true);
        $characters = str_split($password);
        
        $this->assertCount(8, $characters);
        
        $allowedChars = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?');
        $this->assertContains($characters[0], $allowedChars);
    }

    public function testMockObjectDemonstration(): void
    {
        $mockGenerator = $this->createMock(PasswordGenerator::class);
        
        $mockGenerator->method('generate')
            ->willReturn('FixedPassword123');
        
        $result = $mockGenerator->generate(10, true);
        $this->assertEquals('FixedPassword123', $result);
    }

    public function testPasswordContainsVariousCharacterTypes(): void
    {
        $password = $this->passwordGenerator->generate(25, true);
        
        $this->assertMatchesRegularExpression('/[a-z]/', $password);
        
        $this->assertMatchesRegularExpression('/[A-Z]/', $password);
        
        $this->assertMatchesRegularExpression('/[0-9]/', $password);
        
        $this->assertMatchesRegularExpression('/[!@#$%^&*()_+\-=\[\]{}|;:,.<>?]/', $password);
    }

    protected function tearDown(): void
    {
        unset($this->passwordGenerator);
    }
}
