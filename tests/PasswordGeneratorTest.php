<?php

use App\PasswordGenerator;
use PHPUnit\Framework\TestCase;


class PasswordGeneratorTest extends TestCase
{
   
    private PasswordGenerator $passwordGenerator;

    protected function setUp(): void
    {
        $this->passwordGenerator = new PasswordGenerator();
        echo " setUp() выполнен\n";
    }

    public function testGenerateDefaultPasswordLength(): void
    {
        echo " Тест 1: Проверка длины по умолчанию\n";
        $password = $this->passwordGenerator->generate();
        
        $this->assertEquals(12, strlen($password), 
            " Пароль по умолчанию должен быть длиной 12 символов");
        
        echo " Длина пароля: " . strlen($password) . " символов\n";
        echo " Пароль: $password\n";
    }


    public function testGenerateCustomLength(): void
    {
        echo " Тест 2: Проверка кастомной длины\n";
        $customLength = 15;
        $password = $this->passwordGenerator->generate($customLength, false);
        
        $this->assertEquals($customLength, strlen($password), 
            " Пароль должен быть длиной $customLength символов");
        
        echo " Длина пароля: " . strlen($password) . " символов\n";
    }

    
    public function testGenerateWithSpecialCharacters(): void
    {
        echo " Тест 3: Проверка специальных символов\n";
        $password = $this->passwordGenerator->generate(20, true);
        
        $hasSpecialChars = (bool) preg_match('/[!@#$%^&*()_+\-=\[\]{}|;:,.<>?]/', $password);
        $this->assertTrue($hasSpecialChars,
            " Пароль должен содержать специальные символы");
        
        echo " Пароль содержит специальные символы: $password\n";
    }


    public function testGenerateWithoutSpecialCharacters(): void
    {
        echo " Тест 4: Проверка отсутствия специальных символов\n";
        $password = $this->passwordGenerator->generate(15, false);
        
        $hasSpecialChars = (bool) preg_match('/[!@#$%^&*()_+\-=\[\]{}|;:,.<>?]/', $password);
        $this->assertFalse($hasSpecialChars,
            " Пароль без спецсимволов не должен содержать специальные символы");
        
        echo " Пароль не содержит специальные символы: $password\n";
    }

    public function testGenerateWithMinimumLength(): void
    {
        echo " Тест 5: Проверка минимальной длины (edge-case)\n";
        $password = $this->passwordGenerator->generate(6, false);
        
        $this->assertEquals(6, strlen($password),
            " Пароль минимальной длины должен быть 6 символов");
        
        echo " Минимальная длина корректна: $password\n";
    }

    public function testGenerateThrowsExceptionForShortLength(): void
    {
        echo " Тест 6: Проверка исключения (ошибочный сценарий)\n";
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Длина пароля не может быть меньше 6 символов");
        
        $this->passwordGenerator->generate(5);
        echo " Исключение корректно выброшено\n";
    }

    public function testGeneratedPasswordsAreDifferent(): void
    {
        echo " Тест 7: Проверка уникальности паролей\n";
        $password1 = $this->passwordGenerator->generate();
        $password2 = $this->passwordGenerator->generate();
        
        $this->assertNotEquals($password1, $password2,
            " Два сгенерированных пароля должны быть разными");
        
        echo " Пароль 1: $password1\n";
        echo " Пароль 2: $password2\n";
        echo " Пароли уникальны ✓\n";
    }


    public function testPasswordAsCharacterArray(): void
    {
        echo " Тест 8: Проверка массива символов\n";
        $password = $this->passwordGenerator->generate(8, true);
        $characters = str_split($password);
        
        $this->assertCount(8, $characters,
            " Пароль должен состоять из 8 символов");
        
        $allowedChars = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?');
        $this->assertContains($characters[0], $allowedChars,
            " Пароль должен содержать допустимые символы");
        
        echo " Количество символов: " . count($characters) . "\n";
        echo " Первый символ допустим: {$characters[0]}\n";
    }

    public function testMockObjectDemonstration(): void
    {
        echo " Тест 9: Демонстрация mock-объектов\n";
        
        // Создаем mock-объект PasswordGenerator
        $mockGenerator = $this->createMock(PasswordGenerator::class);
        
        // Настраиваем mock чтобы он возвращал фиксированное значение
        $mockGenerator->method('generate')
            ->willReturn('FixedPassword123');
        
        $result = $mockGenerator->generate(10, true);
        $this->assertEquals('FixedPassword123', $result);
        
        echo " Mock-объект работает корректно\n";
        echo " Mock вернул: $result\n";
    }

    
    public function testPasswordContainsVariousCharacterTypes(): void
    {
        echo " Тест 10: Комплексная проверка символов\n";
        $password = $this->passwordGenerator->generate(25, true);
        
        $this->assertMatchesRegularExpression('/[a-z]/', $password,
            " Пароль должен содержать строчные буквы");
        echo " Строчные буквы: ✓\n";
        
        $this->assertMatchesRegularExpression('/[A-Z]/', $password,
            " Пароль должен содержать заглавные буквы");
        echo " Заглавные буквы: ✓\n";
        
        $this->assertMatchesRegularExpression('/[0-9]/', $password,
            " Пароль должен содержать цифры");
        echo " Цифры: ✓\n";
        
        $this->assertMatchesRegularExpression('/[!@#$%^&*()_+\-=\[\]{}|;:,.<>?]/', $password,
            " Пароль должен содержать специальные символы");
        echo " Специальные символы: ✓\n";
        
        echo " Все типы символов присутствуют: $password\n";
    }

   
    protected function tearDown(): void
    {
        unset($this->passwordGenerator);
        echo " tearDown() выполнен - очистка завершена\n\n";
    }
}