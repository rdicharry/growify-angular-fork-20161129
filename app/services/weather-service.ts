import {Injectable} from "@angular/core";
import {Http} from "@angular/http";
import {Observable} from "rxjs/Observable";
//import 'rxjs/Rx';
import {BaseService} from "./base-service";
import {Weather} from "../classes/weather";
import {Status} from "../classes/status";

@Injectable()
export class WeatherService extends BaseService {
	constructor(protected http: Http){
		super(http);
	}

	private weatherUrl = "api/weather/"; // works with this URL - but of course doesn't return anything
	// perhaps not catching an error correctly??

	getCurrentWeatherAlbuquerque() : Observable<Weather>{
		let albuquerqueWeatherUrl = "api/weather/?current=true&zipcode=87106";
		return(this.http.get(albuquerqueWeatherUrl)
			.map(this.extractData)
			.catch(this.handleError));
	}



	getCurrentWeatherByZipcode(zipcode: string):Observable<Weather>{
		let current = true;
		return(this.http.get(this.weatherUrl + current + zipcode)
			.map(this.extractData)
			.catch(this.handleError));
	}

	// add get week forecast for zip code
	getWeekForecastWeatherByZipcode(zipcode: string):Observable<Weather[]>{
		let current = false;
		return(this.http.get(this.weatherUrl + current + zipcode)
			.map(this.extractData)
			.catch(this.handleError));
	}

}