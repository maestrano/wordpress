<?php

class Maestrano_Account_UserIntegrationTest extends PHPUnit_Framework_TestCase
{

  /**
  * Initializes the Test Suite
  */
  public function setUp()
  {
      Maestrano::configure(array(
        'environment' => 'test',
        'api' => array(
          'id' => 'app-1',
          'key' => 'gfcmbu8269wyi0hjazk4t7o1sndpvrqxl53e1'
        )
      ));
  }

  public function testRetrieveAllUsers() {
    $userList = Maestrano_Account_User::all();

    foreach ($userList as $u) {
      if ($u->getId() == 'usr-1') $user = $u;
    }

    $this->assertEquals('usr-1',$user->getId());
    $this->assertEquals('maestrano',$user->getPreset());
    $this->assertEquals('John',$user->getFirstName());
    $this->assertEquals('Doe',$user->getLastName());
    $this->assertEquals('2014-05-21T00:32:35+0000',$user->getCreatedAt()->format(DateTime::ISO8601));
  }

  public function testRetrieveSelectedUsers() {
    $dateAfter = new DateTime('2014-05-21T00:32:35+0000');
    $dateBefore = new DateTime('2014-05-21T00:32:55+0000');
    $userList = Maestrano_Account_User::all(array(
      'createdAtAfter' => $dateAfter,
      'createdAtBefore' => $dateBefore,
    ));

    $this->assertTrue(count($userList) == 1);
    $this->assertEquals('maestrano',$userList[0]->getPreset());
    $this->assertEquals('usr-1',$userList[0]->getId());
  }

  public function testRetrieveSingleUser() {
    $user = Maestrano_Account_User::retrieve("usr-1");

    $this->assertEquals('usr-1',$user->getId());
    $this->assertEquals('maestrano',$user->getPreset());
    $this->assertEquals('John',$user->getFirstName());
    $this->assertEquals('Doe',$user->getLastName());
    $this->assertEquals('2014-05-21T00:32:35+0000',$user->getCreatedAt()->format(DateTime::ISO8601));
  }

}
