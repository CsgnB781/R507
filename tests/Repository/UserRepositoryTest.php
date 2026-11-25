<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserRepositoryTest extends KernelTestCase
{
    public function testAdminUserExists(): void
    {
        self::bootKernel();
        $repository = static::getContainer()->get(UserRepository::class);

        $admin = $repository->findOneBy(['username' => 'Admin']);

        $this->assertNotNull($admin);
        $this->assertContains('ROLE_ADMIN', $admin->getRoles());
    }

    public function testUpgradePassword(): void
    {
        self::bootKernel();
        $repository = static::getContainer()->get(UserRepository::class);

        $admin = $repository->findOneBy(['username' => 'Admin']);
        $repository->upgradePassword($admin, 'nouveau-hash');

        $this->assertSame('nouveau-hash', $admin->getPassword());
    }

    public function testUpgradePasswordRejectsOtherUsers(): void
    {
        self::bootKernel();
        $repository = static::getContainer()->get(UserRepository::class);

        $otherUser = $this->createMock(PasswordAuthenticatedUserInterface::class);

        $this->expectException(UnsupportedUserException::class);
        $repository->upgradePassword($otherUser, 'nouveau-hash');
    }
}