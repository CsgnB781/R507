<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserTest extends TestCase
{
    public function testUsernameAndIdentifier(): void
    {
        $user = new User();
        $user->setUsername('Admin');

        $this->assertNull($user->getId());
        $this->assertSame('Admin', $user->getUsername());
        $this->assertSame('Admin', $user->getUserIdentifier());
    }

    public function testRolesAlwaysContainRoleUser(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);

        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function testInitPasswordHashesPlainPassword(): void
    {
        $hasher = $this->createMock(UserPasswordHasherInterface::class);
        $hasher->method('hashPassword')->willReturn('mot-de-passe-hache');

        $user = new User();
        $user->setPlainPassword('azerty');
        $user->initPassword($hasher);

        $this->assertSame('azerty', $user->getPlainPassword());
        $this->assertSame('mot-de-passe-hache', $user->getPassword());
    }

    public function testInitPasswordWithoutPlainPassword(): void
    {
        $hasher = $this->createMock(UserPasswordHasherInterface::class);
        $hasher->expects($this->never())->method('hashPassword');

        $user = new User();
        $user->initPassword($hasher);

        $this->assertNull($user->getPassword());
    }
}
