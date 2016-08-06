<?php
use PHPUnit\Framework\TestCase;
use Maxi\DateManager;

include 'src\model\DateManager.php';

//For logg: fwrite(STDERR, print_r($var, TRUE));

class DateManagerTest extends TestCase{

  const FORMAT = 'Y-m-d';
  const MAXDATE = '2200-12-24';

  var $dateManager;
  var $invalidOldDate;
  var $invalidBigDate;
  var $currentDate;
  var $validDateOne;
  var $valieDateTwo;

  public function setUp(){
    $this->invalidOldDate = DateTime::createFromFormat(self::FORMAT,'1900-12-24');
    $this->invalidBigDate = DateTime::createFromFormat(self::FORMAT,'3000-12-24');
    $this->currentDate = (new DateTime())->format(self::FORMAT);
    $this->validDateOne = DateTime::createFromFormat(self::FORMAT,'2016-12-24');
    $this->valieDateTwo = DateTime::createFromFormat(self::FORMAT,'2016-12-25');

    $this->dateManager = new DateManager(self::MAXDATE, self::FORMAT);
  }

  public function testThisDayIsEqualOrMajorToCurrentDate(){
       $result = $this->dateManager->isEqualOrMajorToCurrentDate($this->dateManager->getCurrentDate());

       $this->assertEquals(true, $result);
   }

   public function testTomorrowIsEqualOrMajorToCurrentDay(){
        $tomorrow = DateTime::createFromFormat(
          self::FORMAT,
          (new DateTime('+1 day'))->format(self::FORMAT)
        );
        $result = $this->dateManager->isEqualOrMajorToCurrentDate($tomorrow);

        $this->assertEquals(true, $result);
    }

    public function testYesterdayIsNotEqualOrMajorToCurrentDay(){
      $yesterday = $this->dateManager->getYesterdayDate();

      $result = $this->dateManager->isEqualOrMajorToCurrentDate($yesterday);

      $this->assertEquals(false,$result);
     }

      public function testFirstDateIsMinorThanSecondDate(){
        $result = $this->dateManager->dateIsMinorThanOtherDate($this->validDateOne, $this->valieDateTwo);

        $this->assertEquals(true,$result);
      }

      public function testFirstDateIsNotMinorThanSecondDate(){
        $result = $this->dateManager->dateIsMinorThanOtherDate($this->valieDateTwo, $this->validDateOne);

        $this->assertEquals(false,$result);
      }

      public function testDateOverflowLimit(){
        $result = $this->dateManager->dateOverflowLimit($this->invalidBigDate);

        $this->assertEquals(true,$result);
      }
      public function testDateNotOverflowLimit(){
        $result = $this->dateManager->dateOverflowLimit($this->validDateOne);

        $this->assertEquals(false,$result);
      }

      public function testDateRangeIsValid(){
        $result = $this->dateManager->dateRangeIsValid($this->invalidOldDate, $this->invalidBigDate);
        $this->assertEquals(false,$result);

        $result = $this->dateManager->dateRangeIsValid($this->invalidOldDate, $this->validDateOne);
        $this->assertEquals(false,$result);

        $result = $this->dateManager->dateRangeIsValid($this->validDateOne, $this->invalidBigDate);
        $this->assertEquals(false,$result);

        $result = $this->dateManager->dateRangeIsValid($this->invalidBigDate, $this->invalidOldDate);
        $this->assertEquals(false,$result);

        $result = $this->dateManager->dateRangeIsValid($this->valieDateTwo, $this->validDateOne);
        $this->assertEquals(false,$result);

        $result = $this->dateManager->dateRangeIsValid($this->validDateOne, $this->valieDateTwo);
        $this->assertEquals(true,$result);

        $result = $this->dateManager->dateRangeIsValid($this->validDateOne, $this->validDateOne);
        $this->assertEquals(false,$result);
      }
}
