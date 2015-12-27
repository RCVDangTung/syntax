<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$date = "04/28/2013 07:30:00";

$dates = explode(" ", $date);

$date = strtotime($dates[0]);

$date = strtotime("+6 days", $date);

echo date('m/d/Y', $date) . " " . $dates[1];

echo "<br>";

//$date = date("Y-m-d"); // current date
//
//$dates_1 = strtotime(date("Y-m-d", strtotime($date)) . " +5 day");
//
//$date_2 = date("d-m-Y", $dates_1);
//echo $date_2;


$dates_5 = strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +5 day");
$date_6 = date("d-m-Y", $dates_5);
echo $date_6;
die();
//$date = strtotime(date("Y-m-d", strtotime($date)) . " +1 week");
//$date = strtotime(date("Y-m-d", strtotime($date)) . " +2 week");
//$date = strtotime(date("Y-m-d", strtotime($date)) . " +1 month");
//$date = strtotime(date("Y-m-d", strtotime($date)) . " +30 days");


$some_var = date("Y-m-d", strtotime("+7 day"));

$today = date('d-m-Y');
$next_date = date('d-m-Y', strtotime($today . ' + 90 days'));
echo $next_date;


echo date('Y-m-d H:i:s') . "\n";
echo "<br>";
echo date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date('m'), date('d') + 30, date('Y'))) . "\n";

function addDate($date = '', $diff = '', $format = "d/m/Y") {
    if (empty($date) || empty($diff))
        return false;
    $formatedDate = reformatDate($date, $format, $to_format = 'Y-m-d H:i:s');
    $newdate = strtotime($diff, strtotime($formatedDate));
    return date($format, $newdate);
}

//Aux function
function reformatDate($date, $from_format = 'd/m/Y', $to_format = 'Y-m-d') {
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux, $to_format);
}

$data['created'] = date('Y-m-d H:i:s', strtotime("+1 week"));

class BusinessDaysCalculator {

    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 7;

    /**
     * @param DateTime   $startDate       Date to start calculations from
     * @param DateTime[] $holidays        Array of holidays, holidays are no conisdered business days.
     * @param int[]      $nonBusinessDays Array of days of the week which are not business days.
     */
    public function __construct(DateTime $startDate, array $holidays, array $nonBusinessDays) {
        $this->date = $startDate;
        $this->holidays = $holidays;
        $this->nonBusinessDays = $nonBusinessDays;
    }

    public function addBusinessDays($howManyDays) {
        $i = 0;
        while ($i < $howManyDays) {
            $this->date->modify("+1 day");
            if ($this->isBusinessDay($this->date)) {
                $i++;
            }
        }
    }

    public function getDate() {
        return $this->date;
    }

    private function isBusinessDay(DateTime $date) {
        if (in_array((int) $date->format('N'), $this->nonBusinessDays)) {
            return false; //Date is a nonBusinessDay.
        }
        foreach ($this->holidays as $day) {
            if ($date->format('Y-m-d') == $day->format('Y-m-d')) {
                return false; //Date is a holiday.
            }
        }
        return true; //Date is a business day.
    }

}

$calculator = new BusinessDaysCalculator(
        new DateTime(), // Today
        [new DateTime("2014-06-01"), new DateTime("2014-06-02")], [BusinessDaysCalculator::SATURDAY, BusinessDaysCalculator::FRIDAY]
);

$calculator->addBusinessDays(3); // Add three business days 

var_dump($calculator->getDate());



function add_business_days($startdate,$businessdays,$holidays,$dateformat){  
    $i = 1;
    $date = date('Y-m-d',strtotime($startdate. ' -1 day'));

    while($i <= $businessdays){  
        $date = date('Y-m-d',strtotime($date. ' +1 Weekday'));
        if(!in_array($date,$holidays))$i++;
    }  
    return date($dateformat,strtotime($date));
}