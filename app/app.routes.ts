import {RouterModule, Routes} from "@angular/router";
import {HomeComponent} from "./components/home-component";


export const allAppComponents = [ HomeComponent];

export const routes: Routes = [
	// note: the order the components are listed in matters!
	// the paths should go from most specific to least specific.
	{path: "", component: HomeComponent}
];

export const appRoutingProviders: any[] = [];

export const routing = RouterModule.forRoot(routes);