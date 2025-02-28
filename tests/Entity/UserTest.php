<?php
namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

class UserTest extends FrameworkTestCase{

    public function testTheAutomaticApiTokenSettingWhenUserIsCreated():void{
        $user = new User();
        $this->assertNotNull($user->getApiToken());
    }
    public function testThatOneUserHasAtLeastRoleUser(): void{
        $user = new User();
        $this->assertContains('ROLE_USER', $user->getRoles());
    }
}
