<?php

class actions {

    public /*APIResponse*/ $response;

    public function __construct() {
        $this->response = new APIResponse();
    }

    public /*boolean*/ function SaveDay($connection_id, /*Day*/ $day){ 

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
        $newEvents = array/*<Event>*/();
        $deleteableEvents = array/*<string>*/();
        $existingEvents = array/*<Event>*/();
        for ($i = 0; $i < count($day->events); $i++) {
            if ($day->events[$i]->date != "" && $day->events[$i]->intro != "") {
                if ($day->events[$i]->id < 0) {
                    array_push($newEvents, $day->events[$i]);
                } else if ($c < count($originalDay->events) && $day->events[$i]->id == $originalDay->events[$c]->id) {
                    array_push($existingEvents, $day->events[$i]);
                    $c++;
                } 
            }
        }

        while ($c < count($originalDay->events)) {
            array_push($deleteableEvents, $originalDay->events[$c]->id);
            $c++;
        }

        // sql actions
        $ia = $this->InsertEvents($connection_id, $newEvents);
        $ua = $this->UpdateEvents($connection_id, $existingEvents);
        $da = $this->DeleteEvents($connection_id, $deleteableEvents);

        // returns
        if ($ia && $ua && $da) {
            $this->response->data = $this->GetDay($connection_id, $day->date);
            return true;
        }
        return false;
    }

    private /*boolean*/ function UpdateEvents($connection_id, /*Array<Event>*/ $events){ 
        if (count($events) == 0) {
            return true;
        }

        $query = "";
        for ($i = 0; $i < count($events); $i++){
            $query .= "
                UPDATE events SET intro = '" . $events[$i]->intro . "', content = '" . $events[$i]->content . "'
                WHERE id = " . $events[$i]->id . ";";
        }

        // # DEBUG BEGIN
        $this->response->AddToDebug($query);
        // # DEBUG END

        if (!$connection_id->multi_query($query)) {
            echo "ERROR(UpdateEvents): (" . $connection_id->errno . ") " . $connection_id->error;
            return false;
        }
        return true;
    }

    private /*boolean*/ function InsertEvents($connection_id, /*Array<Event>*/ $events){ 
        if (count($events) == 0) {
            return true;
        }

        $query = "
            INSERT INTO events (date, intro, content) VALUES ";

        for ($i = 0; $i < count($events); $i++) {
            if ($i != 0) {
                $query .= ", ";
            }

            if (!isset($events[$i]->content)) {
                $events[$i]->content = "NULL";
            } else {
                $events[$i]->content = "'" . $events[$i]->content . "'";
            }

            $query .= "('" . $events[$i]->date . "', '" . $events[$i]->intro . "', " . $events[$i]->content . ")";
        }

        $result = mysqli_query($connection_id, $query) or die("ERROR(DeleteEvents): " . $query);
        
        // # DEBUG BEGIN
        $this->response->AddToDebug($query);
        // # DEBUG END

        return $result;
    }

    private /*boolean*/ function DeleteEvents($connection_id, /*Array<string>*/ $events){ 
        if (count($events) == 0) {
            return true;
        }

        $query = "
            DELETE FROM events
            WHERE id IN (" . implode(",", $events) . ");";

        $result = mysqli_query($connection_id, $query) or die("ERROR(DeleteEvents): " . $query);
        
        // # DEBUG BEGIN
        $this->response->AddToDebug($query);
        // # DEBUG END

        return $result;
    }

    private /*Day*/ function GetDay($connection_id, /*string*/ $date){ 
        $query = "
            SELECT * 
            FROM events 
            WHERE date >= '" . $date . " 00:00:00' 
            AND date <= '" . $date . " 23:59:59' 
            ORDER BY id ASC;";
        $result = mysqli_query($connection_id, $query) or die("BAD QUERY - " . $query);
        
        // # DEBUG BEGIN
        $this->response->AddToDebug($query);
        // # DEBUG END

        $day = new Day();
        $day->Set($date, array());

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
