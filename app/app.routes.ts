import {RouterModule, Routes} from "@angular/router";
import {HomeComponent} from "./components/home-component";
import {LoginComponent} from "./components/login-component";
import {SignupComponent} from "./components/signup-component";


export const allAppComponents = [ HomeComponent, LoginComponent, SignupComponent];

export const routes: Routes = [
	// note: the order the components are listed in matters!
	// the paths should go from most specific to least specific.
	{path: "", component: HomeComponent},
	{path: "login", component: LoginComponent},
	{path: "signup", component: SignupComponent}
];

export const appRoutingProviders: any[] = [];

export const routing = RouterModule.forRoot(routes);