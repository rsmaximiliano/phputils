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
  var $validDateTwo;

  var $stringValidDateOne;

  public function setUp(){
    $this->invalidOldDate = DateTime::createFromFormat(self::FORMAT,'1900-12-24');
    $this->invalidBigDate = DateTime::createFromFormat(self::FORMAT,'3000-12-24');
    $this->currentDate = (new DateTime())->format(self::FORMAT);
    $this->validDateTwo = DateTime::createFromFormat(self::FORMAT,'2016-12-25');

    $this->stringValidDateOne = '2016-12-24';
    $this->validDateOne = DateTime::createFromFormat(self::FORMAT,$this->stringValidDateOne);

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
        $result = $this->dateManager->dateIsMinorThanOtherDate($this->validDateOne, $this->validDateTwo);

        $this->assertEquals(true,$result);
      }

      public function testFirstDateIsNotMinorThanSecondDate(){
        $result = $this->dateManager->dateIsMinorThanOtherDate($this->validDateTwo, $this->validDateOne);

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

        $result = $this->dateManager->dateRangeIsValid($this->validDateTwo, $this->validDateOne);
        $this->assertEquals(false,$result);

        $result = $this->dateManager->dateRangeIsValid($this->validDateOne, $this->validDateTwo);
        $this->assertEquals(true,$result);

        $result = $this->dateManager->dateRangeIsValid($this->validDateOne, $this->validDateOne);
        $this->assertEquals(false,$result);
      }

      public function testDayIsInInterval(){
        $result = $this->dateManager->dateIsInInterval($this->validDateOne, $this->invalidOldDate, $this->invalidBigDate);
        $this->assertEquals(true,$result);
      }

      public function testDayIsInIntervalInclusiveInitial(){
        $result = $this->dateManager->dateIsInInterval($this->validDateOne, $this->validDateOne, $this->validDateTwo);
        $this->assertEquals(true,$result);
      }

      public function testDayIsInIntervalInclusiveFinal(){
        $result = $this->dateManager->dateIsInInterval($this->validDateTwo, $this->validDateOne, $this->validDateTwo);
        $this->assertEquals(true,$result);
      }

      public function testBeforeDayIsNotInInterval(){
        $result = $this->dateManager->dateIsInInterval($this->invalidOldDate, $this->validDateOne, $this->validDateTwo);
        $this->assertEquals(false,$result);
      }

      public function testAfterDayIsNotInInterval(){
        $result = $this->dateManager->dateIsInInterval($this->invalidBigDate, $this->validDateOne, $this->validDateTwo);
        $this->assertEquals(false,$result);
      }

      public function testCurrentDayIsInInterval(){
        $result = $this->dateManager->todayIsInInterval($this->invalidOldDate, $this->invalidBigDate);
        $this->assertEquals(true,$result);
      }

      public function testCurrentDayIsNotInInterval(){
        $result = $this->dateManager->todayIsInInterval($this->invalidOldDate, $this->invalidOldDate);
        $this->assertEquals(false,$result);
      }

      public function testDateCreation(){
        $result = $this->dateManager->create($this->stringValidDateOne);
        $this->assertEquals($this->validDateOne,$result);
      }
}
