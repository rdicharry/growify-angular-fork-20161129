
	<main>
		<!-- Insert Bootstrap navbar, with Angular-friendly links -->
		<nav class="navbar navbar-default">
			<div class="container">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<!--<span class="icon-bar"></span>-->
					</button>
					<a class="navbar-brand" routerLink="">Growify</a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right">
						<li><a routerLink="">Home</a></li>
						<li><a routerLink="/login">Login</a></li>
						<li><a routerLink="/signup">Sign Up</a></li>
						<li><a routerLink="/garden">Garden</a></li>

						<li><a routerLink="/plants">Plants</a></li>
						<li><a routerLink="/signout">Sign Out</a></li>
						<!--<li><a routerLink="/garden">Garden</a></li>-->

					</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
		<router-outlet></router-outlet>
	</main>








