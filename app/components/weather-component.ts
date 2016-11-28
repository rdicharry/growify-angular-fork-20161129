import {Component, OnInit} from "@angular/core";
import {ActivatedRoute, Params} from "@angular/router";
import {WeatherService} from "../services/weather-service";
import {Weather} from "../classes/weather";
import {Status} from "../classes/status";

@Component({
	templateUrl: "./templates/weather.php",
	selector: "weather-component"
})

export class WeatherComponent implements OnInit {
 // need @ViewChild ??
	deleted: boolean = false;
	currentWeather: Weather = new Weather(0, 0, 0, 0, 0, 0, 0, "");
	dailyWeather: Weather[] = [];
	status: Status = null;

	constructor(private weatherService: WeatherService, private route: ActivatedRoute){}

	ngOnInit() : void {
		// call getCurrentWeatherAlbuquerque() method of the weather service.
		// this returns an observable, which we subscribe to
		// in the subscribe method, we pass a function(lambda) to be executed
		// when the data is available

		this.route.params.forEach((params: Params)=> {

			let zipcode = params["zipcode"];

			// get current and daily weather

			this.weatherService.getCurrentWeatherByZipcode(zipcode).subscribe(weather=>this.currentWeather = weather);

			this.weatherService.getWeekForecastWeatherByZipcode(zipcode).subscribe(weather=>this.dailyWeather.push(weather));

		});

	}



}