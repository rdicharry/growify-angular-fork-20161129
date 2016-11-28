import {Injectable} from "@angular/core";
import {Http} from "@angular/http";
import {Observable} from "rxjs/Observable";
import {BaseService} from "./base-service";
import {Weather} from "../classes/weather";
import {Status} from "../classes/status";

@Injectable()
export class WeatherService extends BaseService {
	constructor(protected http: Http){
		super(http);
	}

	private weatherUrl = "api/weather/";

	getCurrentWeatherAlbuquerque() : Observable<Weather>{
		return(this.http.get(this.weatherUrl)
			.map(this.extractData)
			.catch(this.handleError));
	}

	// add exclude=["minutely", "hourly"]
	getCurrentWeatherByZipcode(zipcode: string):Observable<Weather>{
		let current = true;
		return(this.http.get(this.weatherUrl + current + zipcode)
			.map(this.extractData)
			.catch(this.handleError));
	}

	// add get week forecast for zip code
	getWeekForecastWeatherByZipcode(zipcode: string):Observable<Weather>{
		let current = false;
		return(this.http.get(this.weatherUrl + current + zipcode)
			.map(this.extractData)
			.catch(this.handleError));
	}

}