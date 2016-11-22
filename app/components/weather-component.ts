import {Component, OnInit} from "@angular/core";
import {ActivatedRoute, Params} from "@angular/router";
import {WeatherService} from "../services/weather-service";
import {Weather} from "../classes/weather";
import {Status} from "../classes/status";

@Component({
	templateUrl: "./templates/weather.php",
	selector: "current-weather"
})

export class WeatherComponent implements OnInit {

	deleted: boolean = false;
	weather: Weather = new Weather(0, 0, 0, 0);
	status: Status = null;

	constructor(private weatherService: WeatherService, private route: ActivatedRoute){}

	ngOnInit() : void {
		// call getCurrentWeatherAlbuquerque() method of the weather service.
		// this returns an observable, which we subscribe to
		// in the subscribe method, we pass a function(lambda) to be executed
		// when the data is available
		this.weatherService.getCurrentWeatherAlbuquerque().subscribe(weather=>this.weather = weather);
	}

}