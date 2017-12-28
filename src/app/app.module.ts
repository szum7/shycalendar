import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';

import { GlobalsService } from './services/globals.service';
import { AppComponent } from './app.component';
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
    HttpClientModule,
    FormsModule
  ],
  // services
  providers: [
    GlobalsService
  ],
  // root component
  bootstrap: [AppComponent]
})
export class AppModule { }
