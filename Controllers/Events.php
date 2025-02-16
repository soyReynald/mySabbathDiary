<?php
function drawCalendar($con){
    $date = splitDate();

    $month = $date['month'];
    $year = $date['year'];

    $firstDay = strtotime($year . '-'. $month . '-1');
    // Necesito estudiar strtotime()
    # $monthName = date('F', $firstDay);
    $firstWeekDay = date('w', $firstDay);

    $monthDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    $lastDay = strtotime($year.'-'.$month.'-'.$monthDays);

    $from = date ('Y-m-d', $firstDay);
    $to = date('Y-m-d', $lastDay);

    $events = getEvents($from, $to, $con);

    if($month == 1){
        $prevMonth = 12;
        $prevYear = $year -1;
    }else{
        $prevMonth = $month - 1;
        $prevYear = $year;
    }

    if($month == 12){
        $nextMonth = 1;
        $nextYear = $year + 1;
    }else{
        $nextMonth = $month + 1;
        $nextYear = $year;
    }

    $prevMonthDays = cal_days_in_month(CAL_GREGORIAN, $prevMonth, $prevYear);
    // Necesito estudiar cal_days_in_month()
    $startWeekDay = $prevMonthDays - $firstWeekDay + 1;
    $weekCount = 1;
    $dayCount = 1;
    $nextDay = 1;

    $calendar = '
    <table class="table">
            <tr>
                <th>Sun</th>
                <th>Mon</th>
                <th>Tue</th>
                <th>Wed</th>
                <th>Thu</th>
                <th>Fri</th>
                <th>Sat</th>
            </tr>
            <tr>';

                    while($firstWeekDay > 0){
                        $calendar .= '<td class="text-muted">' . $startWeekDay++ . '</td>';
                        $firstWeekDay--;
                        $weekCount++;
                    }
                    
                    while($dayCount <= $monthDays){
                        $calendar .= '<td>';
                        $calendar .= '<button data-date="'.$year.'-'.$month.'-'.$dayCount.'" class="btn btn-sm btn-dark">';
                        $calendar .= $dayCount;
                        $calendar .= '</button>';
                        $index = str_pad($dayCount, 2, '0', STR_PAD_LEFT) . $month . $year;
                        if(isset($events[$index]) && is_array($events[$index])){
                            $calendar .= "<small>";
                            $calendar .= '<span class="badge badge-dark float-right">'. count($events[$index]) .' Events </span>';
                            $calendar .= "<ul>";
                                foreach($events[$index] as $event){
                                    $calendar .= '<li><a title="' . strtolower($event->time) . ' - ' . $event->category . '" href="#" data-id="'.$event->id.'" class="btn-event">';
                                    $calendar .= '<i class="'.$event->icon.'"></i> '; 
                                    $calendar .= $event->name;
                                    $calendar .= '</a></li>';
                                }
                            $calendar .= "</ul></small>";
                        }
                        $calendar .= '</td>';
                        $dayCount++;
                        $weekCount++;

                        if($weekCount > 7){
                            $calendar .= '</tr><tr>';
                            $weekCount = 1;
                        }
                    }

                    while($weekCount > 1 && $weekCount <= 7){
                        $calendar .= '<td class="text-muted">' . $nextDay++ . '</td>';
                        $weekCount++;
                    }
        
        $calendar .=    '</tr>
        </table>';

        return $calendar;
}

function getMonthName(){
    $date = splitDate();

    $month = $date['month'];
    $year = $date['year'];

    $firstDay = strtotime($year . '-' . $month . '-1');

    return date('F Y', $firstDay);
}


function splitDate(){
    $pattern = "/[0-9]{2}-[0-9]{4}/";

    if(isset($_GET['month']) && preg_match($pattern, $_GET['month'])){
        $monthArr = explode('-', $_GET['month']);
        $month = $monthArr[0];
        $year = $monthArr[1];
    }else{
        $month = date('m');
        $year = date('Y');
    }

    return array('month'=>$month, 'year'=>$year);
}

?>