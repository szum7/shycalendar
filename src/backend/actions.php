<?php

class actions {

    public function __construct() {
        
    }

    public function SaveDay($connection_id, $day/*Day*/){ /*boolean*/

        // cast to object (should be: Day)
        $day = (object)$day;
        for ($i = 0; $i < count($day->events); $i++){
            $day->events[$i] = (object)$day->events[$i];
        }

        // get the original day
        $originalDay = $this->GetDay($connection_id, $day->date);
        $c = 0;

        // sort $day by id
        usort($day->events, "CompareById");

        // sort the events to different arrays
        $newEvents = array();
        $deleteableEvents = array();
        $existingEvents = array();
        for ($i = 0; $i < count($day->events); $i++){
            if ($day->events[$i]->id < 0){
                array_push($newEvents, $day->events[$i]);
            } else if ($c < count($originalDay->events) && $day->events[$i]->id == $originalDay->events[$c]->id){
                array_push($existingEvents, $day->events[$i]);
                $c++;
            } else {
                array_push($deleteableEvents, $day->events[$i]);
            }
        }

        // sql actions
        $ia = $this->InsertEvents($connection_id, $newEvents);
        $ua = $this->UpdateEvents($connection_id, $existingEvents);
        $da = $this->DeleteEvents($connection_id, $deleteableEvents);

        return $ia && $ua && $da;
    }

    private function UpdateEvents($connection_id, $events/*Array<Event>*/){ /*boolean*/
        if (count($events) == 0){
            return true;
        }

        $query = "";
        for ($i = 0; $i < count($events); $i++){
            $query .= "
                UPDATE events SET intro = '" . $events[$i]->intro . "', content = '" . $events[$i]->content . "'
                WHERE id = " . $events[$i]->id . ";";
        }

        if (!$connection_id->multi_query($query)) {
            echo "ERROR(UpdateEvents): (" . $connection_id->errno . ") " . $connection_id->error;
            return false;
        }
        return true;
    }

    private function InsertEvents($connection_id, $events/*Array<Event>*/){ /*boolean*/
        if (count($events) == 0){
            return true;
        }

        $query = "
            INSERT INTO events (date, intro, content) VALUES ";

        for ($i = 0; $i < count($events); $i++){
            if($i != 0){
                $query .= ", ";
            }
            $query .= "('" . $events[$i]->date . "', '" . $events[$i]->intro . "', '" . $events[$i]->content . "')";
        }

        $result = mysqli_query($connection_id, $query) or die("ERROR(DeleteEvents): " . $query);

        return $result;
    }

    private function DeleteEvents($connection_id, $events/*Array<Event>*/){ /*boolean*/
        if (count($events) == 0){
            return true;
        }

        $query = "
            DELETE FROM events
            WHERE id IN (" . implode(",", $events) . ");";

        $result = mysqli_query($connection_id, $query) or die("ERROR(DeleteEvents): " . $query);

        return $result;
    }

    private function GetDay($connection_id, $date/*string*/){ /*Day*/
        $day = new Day();
        $day->Set($date, array());
        $query = "
            SELECT * 
            FROM events 
            WHERE date >= '" . $date . " 00:00:00'
            AND date <= '" . $date . " 23:59:59'
            ORDER BY id ASC;";
        $result = mysqli_query($connection_id, $query) or die("BAD QUERY - " . $query);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {

                $event = new Event();
                $event->Set($row["id"], $row["date"], $row["intro"], $row["content"], $row["type"]);

                array_push($day->events, $event);

            }
        }
        return $day;
    }

}
