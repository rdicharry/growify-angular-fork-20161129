export class Weather {
	constructor(public currentTemperature: number, public temperatureMin: number, public temperatureMax: number, public windSpeed: number,public humidity: number, public precipitationProbability: number, public timestamp: number, public summary: string){}


}