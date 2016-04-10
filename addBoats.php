<?php ini_set('display_errors', 'On'); ?>
<?php

//this script maintains the database and adds new boats anytime
//someone visits the reservation page before loading it

//connect to sql server with global $conn
require 'dbConnect.php';

//set timezone to west coast
date_default_timezone_set('America/Los_Angeles');

//delete all boats older than 60 days to maintain database
$oldDate = new DateTime();
$oldDate->modify('-60 day');
$old = $oldDate->format('Y-m-d');
$conn->query("DELETE FROM boatsDNW WHERE departDate < '" . $old . "'");


$today = date("Y-m-d"); //string of today's date (date of user access)

//reservations cannot be made more than 30 days in advance
// so boats are only created out 30 days - ensure time zeroed for comparisons
$maxDay = new DateTime($today);
$maxDay->modify('+30 day');

//query to get furthest boat in the future
$dateQuery = $conn->query("SELECT DISTINCT departDate
    FROM boatsDNW 
    WHERE departDate >= '" . $today . "' ORDER BY departDate DESC");
$latestDateArr = $dateQuery->fetch(PDO::FETCH_ASSOC);
$latestDateStr  = $latestDateArr['departDate'];

//if there is a furthest boat, set the date and string to one day past
if($latestDateStr){
    $dateDate = new DateTime($latestDateStr);
    $dateDate->modify('+1 day');
    $dateString = $dateDate->format("Y-m-d");
}
// if no boats at all, set the date and string to today
else{
    $dateDate = new DateTime($today);
    $dateString = $dateDate->format("Y-m-d");
}

//get values of memorial day and labor day
$memorialDay = new DateTime("Last Monday of May");
$laborDay = new DateTime("First Monday of September");

//get values of daylight savings time start and end
$daylightSaveEnd = new DateTime("First Sunday of November");
$daylightSaveStart = new DateTime("Second Sunday of March");

//takes a date object, returns a boolean
//1 if between memorial and labor day, 0 if not
function testSummer($testDate) {
    //set as globals so only calculated once
    global $memorialDay, $laborDay;

    if ($testDate > $memorialDay && $testDate < $laborDay){
        return true;
    }
    else{
        return false;
    }

}

//takes a date object, returns a boolean
//1 if between Daylight Savings End to Daylight Savings Start, 0 if not
function testDaylightOff($testDate2) {
    //set as globals so only calculated once
    global $daylightSaveEnd, $dayightSaveStart;

    if ($testDate2 > $daylightSaveEnd && $testDate < $daylightSaveStart){
        return true;
    }
    else{
        return false;
    }

}

//insert boats up to 30 days in advance, will not insert duplicate boats
while ($dateDate <= $maxDay){
    $dayOfWeek = $dateDate->format("l");
    $isSummer = testSummer($dateDate);
    $isDaylightOff = testDaylightOff($dateDate);
    $normalBoats = "INSERT INTO 
        boatsDNW(departDate, departAnacortes, departDecatur)
        VALUES('".$dateString."', '10:00', '11:00'),
        ('".$dateString."', '16:00', '17:00')";
    $fridayBoats = "INSERT INTO 
        boatsDNW(departDate, departAnacortes, departDecatur)
        VALUES('".$dateString."', '10:00', '11:00'),
        ('".$dateString."', '16:00', '17:00'),
        ('".$dateString."', '19:30', '20:30')";
    $fridayBoatsWinter = "INSERT INTO 
        boatsDNW(departDate, departAnacortes, departDecatur)
        VALUES('".$dateString."', '10:00', '11:00'),
        ('".$dateString."', '18:00', '19:00')";     

    switch($dayOfWeek){
        case 'Sunday':
            $conn->query($normalBoats);
            break;
        case 'Monday':
            $conn->query($normalBoats);
            break;
        case 'Tuesday':
            break;
        case 'Wednesday':
            if ($isSummer){
                $conn->query($normalBoats);
            }
            break;
        case 'Thursday':
            $conn->query($normalBoats);
            break;
        case 'Friday':
            if ($isDaylightOff) {
               $conn->query($fridayBoatsWinter);
            }
            else {
               $conn->query($fridayBoats);
            }
            break;
        case 'Saturday':
            $conn->query($normalBoats);
            break;
        default:
            break;
    }
    
    $dateDate->modify('+1 day');
    $dateString = $dateDate->format("Y-m-d");
}

//close the connection
$conn = null;

?>
