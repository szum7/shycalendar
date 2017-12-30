<?php

/**
 * Description of calender
 *
 * @author Szum7
 */
class calender {

    private $MASTER_DATE = array(
        'from' => null,
        'to' => null
    );

    public function __construct() {
    }
    
    public function SetRelativeMasterDate($timePre, $timePost){
        $this->MASTER_DATE["from"] = date('Y-m-d', time() - $timePre);
        $this->MASTER_DATE["to"] = date('Y-m-d', time() + $timePost);
    }
    
    public function SetFixMasterDate($from, $to){
        $this->MASTER_DATE["from"] = $from;
        $this->MASTER_DATE["to"] = $to;
    }
    
    private function createDateRangeArray($strDateFrom, $strDateTo) {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.
        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange = array();

        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
            while ($iDateFrom < $iDateTo) {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange, date('Y-m-d', $iDateFrom));
            }
        }
        return $aryRange;
    }

    private function getEvents($connection_id) {
        $arr = array();
        $query = "
            SELECT * 
            FROM events 
            WHERE date >= '" . $this->MASTER_DATE["from"] . " 00:00:00' 
            ORDER BY date;";
        $result = mysqli_query($connection_id, $query) or die("BAD QUERY - " . $query);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($arr, array(
                    "id" => $row["id"],
                    "date" => $row["date"],
                    "intro" => $row["intro"],
                    "content" => $row["content"],
                    "type" => $row["type"]
                ));
            }
        }
        return $arr;
    }
    
    public function getCalendarData($connection_id){
        
        $ret = array(
            "today" => date('Y-m-d'),
            "data" => null
        );
        
        $eventArray = $this->getEvents($connection_id);
        $c = 0;
        
        $days = $this->createDateRangeArray(date($this->MASTER_DATE["from"]), $this->MASTER_DATE["to"]);
        
        $calData = array();
        
        for ($i = 0; $i < count($days); $i++) {
            
            // new week
            if (date('w', strtotime($days[$i])) === '1' || count($calData) == 0) {
                if(count($calData) - 1 >= 0){
                    $calData[count($calData) - 1]["endDate"] = $days[$i - 1];
                }
                array_push($calData, array(
                    "startDate" => $days[$i],
                    "endDate" => "",
                    "days" => array()
                ));
            }            
            
            // new day
            array_push($calData[count($calData) - 1]["days"], array(
                "date" => $days[$i],
                "events" => array()
            ));

            // has event
            if ($c < count($eventArray) && $days[$i] == substr($eventArray[$c]["date"], 0, 10)) {
                
                // iterate event
                while ($c < count($eventArray) && $days[$i] == substr($eventArray[$c]["date"], 0, 10)) {
                    array_push($calData[count($calData) - 1]["days"][count($calData[count($calData) - 1]["days"]) - 1]["events"], array(
                        "id" => $eventArray[$c]["id"],
                        "date" => $eventArray[$c]["date"],
                        "intro" => $eventArray[$c]["intro"],
                        "content" => $eventArray[$c]["content"],
                        "type" => $eventArray[$c]["type"]
                    ));
                    $c++;
                }                
            } 
            
        }
        
        $ret["data"] = $calData;
        
        // tmp
        return $ret;
    }

    public function buildCalendar($connection_id) {

        $eventArray = $this->getEvents($connection_id);
        $c = 0;

        $days = $this->createDateRangeArray(date($this->MASTER_DATE["from"]), $this->MASTER_DATE["to"]);

        echo '<div class="year">';

        echo '<div class="week first">';
        for ($i = 0; $i < count($days); $i++) {

            $today = (date('Y-m-d') === $days[$i]) ? "today" : "";

            if ($c < count($eventArray) && $days[$i] == substr($eventArray[$c]["date"], 0, 10)) {
                $this->eventDay($days[$i], $eventArray, $c, $today);
            } else {
                $this->basicDay($days[$i], $today);
            }
        }
        echo '</div>'; // week

        echo '</div>';
    }

    private function basicDay($day, $today) {
        if (date('w', strtotime($day)) === '1') {
            echo '</div>';
            echo '<div class="week">';
        }

        echo '<div class="day basic ' . $today . '" data-date="' . $day . '">';
        echo '<div class="header"><p>';
        echo $this->stringToDate($day);
        echo '</p></div>';
        echo '<div class="body"><ul class="events">';

        echo '</ul></div>';
        echo '</div>';
    }

    private function eventDay($day, $eventArray, &$c, $today) {
        if (date('w', strtotime($day)) === '1') {
            echo '</div>';
            echo '<div class="week">';
        }

        // 10:00:00;Alma és körte;Hosszabb leírás;*
        // 15:30:00;Alma és körte;Hosszabb leírás;*
        $data = "";
        $lis = "";
        while ($c < count($eventArray) && $day == substr($eventArray[$c]["date"], 0, 10)) {

            $time = substr($eventArray[$c]["date"], 11);

            $lis .= '<li title="' . $eventArray[$c]["content"] . '">';
            $lis .= ($time != "00:00:00") ? substr($time, 0, 5) . " " : "";
            $lis .= $eventArray[$c]["intro"];
            $lis .= '</li>';

            $data .= $time . ";" . $eventArray[$c]["intro"] . ";" . $eventArray[$c]["content"] . ";*";

            $c++;
        }

        echo '<div class="day evented ' . $today . '" data-content="' . $data . '" data-date="' . $day . '">';
        echo '<div class="header"><p>';
        echo $this->stringToDate($day);
        echo '</p></div>';
        echo '<div class="body"><ul class="events">';

        echo $lis;

        echo '</ul></div>';
        echo '<div class="closer"><!--<p>+</p>--></div>';
        echo '</div>';
    }

    private function stringToDate($s) {
        $year = substr($s, 0, 4);
        $month = substr($s, 5, 2);
        $day = substr($s, 8, 2);
        switch ($month) {
            case "01": $month = "január";
                break;
            case "02": $month = "február";
                break;
            case "03": $month = "március";
                break;
            case "04": $month = "április";
                break;
            case "05": $month = "május";
                break;
            case "06": $month = "június";
                break;
            case "07": $month = "július";
                break;
            case "08": $month = "augusztus";
                break;
            case "09": $month = "szeptember";
                break;
            case "10": $month = "október";
                break;
            case "11": $month = "november";
                break;
            case "12": $month = "december";
                break;
            default: break;
        }
        return $year . ". " . $month . " " . $day . ".";
    }

}
