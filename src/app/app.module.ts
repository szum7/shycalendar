import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import { HttpModule } from '@angular/http';

import {AppComponent} from './app.component';
import { CalendarComponent } from './components/calendar/calendar.component';
import { HeaderComponent } from './components/header/header.component';
import { FooterComponent } from './components/footer/footer.component';


@NgModule({
    // components
    declarations: [
        AppComponent,
        CalendarComponent,
        HeaderComponent,
        FooterComponent
    ],
    // modules
    imports: [
        BrowserModule,
        HttpModule
    ],
    // services
    providers: [],
    // root component
    bootstrap: [AppComponent]
})
export class AppModule {}
