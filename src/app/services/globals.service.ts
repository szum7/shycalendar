import {Injectable} from '@angular/core';

@Injectable()
export class GlobalsService {
    
    public BACKEND_URL: string = "http://localhost/ShyCalendar/src/backend/responses/";
    public BASE_URL: string = "http://localhost/ShyCalendar/";
    
    constructor() {
        
    }

}
