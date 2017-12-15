import {Component, OnInit} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {GlobalsService} from '../../services/globals.service';

@Component({
    selector: 'app-calendar',
    templateUrl: './calendar.component.html',
    styleUrls: ['./calendar.component.css']
})
export class CalendarComponent implements OnInit {
    
    // inputs
    private http: HttpClient;
    private globals: GlobalsService;
    
    // local
    calendarData: CalendarData;
    today: string;

    constructor(http: HttpClient, globals: GlobalsService) {
        // inputs
        this.http = http;
        this.globals = globals;
        
        // locals
        this.calendarData = new CalendarData();
    }

    ngOnInit() {
        this.getCalendarData();
        console.log(this.globals.BACKEND_URL);
    }

    getCalendarData() {
        var _this = this;
        this.http.get(this.globals.BACKEND_URL + 'get/calendar.php').subscribe(function (response) {
            console.log(response);
            _this.calendarData = response as CalendarData;
            _this.today = (response as CalendarData).today;
        }, function (error) {
            console.log(error);
        });
    }

}

class CalendarData{
    today: string;
    data: Array<Week>;
}

class Week{
    readonly startDate: string;
    readonly endDate: string;
    readonly days: Array<Day>;
}

class Day{
    readonly date: string;
    events: Array<Event>;
}

class Event{
    date: string;
    intro: string;
    content: string;
    type: string;
}
