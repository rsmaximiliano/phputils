<?php
namespace Maxi;

use \DateTime;
use \DateInterval;

class DateManager{

  const DEFAULT_FORMAT = 'Y-m-d';
  const DEFAULT_MAXDATE = '2200-01-01';

  var $format;
  var $max_date;

  function __construct($maxdate = null, $format = null){
    $this->format = (is_null($format)) ? self::DEFAULT_FORMAT : $format;
    $this->max_date = (is_null($maxdate)) ?
      DateTime::createFromFormat($this->format, self::DEFAULT_MAXDATE) :
      DateTime::createFromFormat($this->format, $maxdate);
  }

  function getCurrentDate(){
    return $this->create((new DateTime())->format($this->format));
  }

  function getYesterdayDate(){
    $yesterday = new DateTime();
    $interval = new DateInterval('P1D');
    $yesterday->sub($interval);
    $yesterday = $yesterday->format($this->format);
    return $yesterday;
  }

  function isEqualOrMajorToCurrentDate($date){
    return ($date >= $this->getCurrentDate());
  }

  function dateIsMinorThanOtherDate($date, $otherDate){
    return ($date < $otherDate);
  }

  function dateOverflowLimit($date){
    return ($date > $this->max_date);
  }

  function dateRangeIsValid($initialDate, $finalDate){
    if (!$this->isEqualOrMajorToCurrentDate($initialDate))
      return false;
    if (!$this->dateIsMinorThanOtherDate($initialDate, $finalDate))
      return false;
    if ($this->dateOverflowLimit($finalDate))
      return false;
    return true;
  }

  function dateIsInInterval($date, $start, $final){
    return ($date >= $start) && ($date <= $final);
  }

  function todayIsInInterval($start, $final){
    return ($this->getCurrentDate() >= $start) && ($this->getCurrentDate() <= $final);
  }

  function create($stringDate){
    return DateTime::createFromFormat($this->format,$stringDate);
  }
}
