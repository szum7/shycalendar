<?php

class CalendarData {

    public $today/*string*/;
    public $data/*Array<Week>*/;

    public function Set($today, $data){
        $this->today = $today;
        $this->data = $data;
    }
}

class Week {

    public $startDate/*string*/;
    public $endDate/*string*/;
    public $days/*Array<Day>*/;

    public function Set($startDate, $endDate, $days){
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->days = $days;
    }
}

class Day {

    public $date/*string*/;
    public $events/*Array<Event>*/;

    public function Set($date, $events){
        $this->date = $date;
        $this->events = $events;
    }
}

class Event {

    public $id/*string*/;
    public $date/*string*/;
    public $intro/*string*/;
    public $content/*string*/;
    public $type/*string*/;

    public function Set($id, $date, $intro, $content, $type){
        $this->id = $id;
        $this->date = $date;
        $this->intro = $intro;
        $this->content = $content;
        $this->type = $type;
    }
}
