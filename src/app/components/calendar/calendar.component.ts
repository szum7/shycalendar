import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { GlobalsService } from '../../services/globals.service';

@Component({
    selector: 'app-calendar',
    templateUrl: './calendar.component.html',
    styleUrls: [
        './calendar.component.css',
        './../../app.component.css'
    ]
})
export class CalendarComponent implements OnInit {

    private http: HttpClient;
    private globals: GlobalsService;

    calendarData: CalendarData;

    selectedDay: Day;
    originalSelectedDayData: Day;

    today: string;
    newEvent: Event;

    loading: boolean;

    constructor(http: HttpClient, globals: GlobalsService) {
        // inputs
        this.http = http;
        this.globals = globals;

        // locals
        this.calendarData = new CalendarData();
        this.selectedDay = new Day();
        this.newEvent = new Event();
        this.loading = false;
    }

    ngOnInit() {
        var _this = this;

        this.GetCalendarData(function (response) {
            _this.calendarData = response as CalendarData;
            _this.today = (response as CalendarData).today;
        });
    }


    /***
     * Methods
     */

    public OpenDay(day: Day): void {
        this.originalSelectedDayData = <Day>JSON.parse(JSON.stringify(day));
        this.selectedDay = day;
    }

    public CloseDay(): void {
        this.selectedDay = new Day();
    }

    public SaveDay(): void {
        this.PostSaveDay(this.selectedDay, function (response) {
            if (response) {

            } else {

            }
        });
    }

    public CancelDay(): void {
        this.selectedDay = <Day>JSON.parse(JSON.stringify(this.originalSelectedDayData));
        //this.selectedDay = new Day();
    }

    public AddNewEvent(): void {
        this.selectedDay.events.push(<Event>JSON.parse(JSON.stringify(this.newEvent)));
        this.newEvent = new Event();
    }

    public ClearNewEvent(): void {
        this.newEvent = new Event();
    }


    /***
     * Http requests
     */

    private GetCalendarData(callback: (response: CalendarData) => void): void {
        this.http.get(this.globals.BACKEND_URL + 'get/calendar.php').subscribe(function (response) {
            callback(response as CalendarData);
        }, function (error) {
            console.log(error);
        });
    }

    private PostSaveDay(day: Day, callback: (response: boolean) => void): void {
        this.http.post(this.globals.BACKEND_URL + 'post/saveday.php', day).subscribe(function (response) {
            callback(response as boolean);
        }, function (error) {
            console.log(error);
        });
    }

}

class CalendarData {
    today: string;
    data: Array<Week>;
}

class Week {
    readonly startDate: string;
    readonly endDate: string;
    readonly days: Array<Day>;
}

class Day {
    readonly date: string;
    events: Array<Event>;
}

class Event {
    id: string;
    date: string;
    intro: string;
    content: string;
    type: string;
}
