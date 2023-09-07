<style>
	@media only screen and (max-width:680px){
		.welcome div.text{
			font-size: .6em !important;
			
		}
		.welcome .welcome-clock{
			font-size: .5em;
		}
	}
	.welcome{
		height: 90vh;
		background-image: radial-gradient(circle, #333 1%, #344 50%, #555);
		font-size: 1rem;
		text-align: center;
		text-shadow: #000;
		position: relative;
		align-items:center;
		justify-content:center;
		box-sizing: border-box;
		display:flex;
	}
	.welcome *{ color: #fff; }
	.welcome h1{ 
		font-family: 'Kristen ITC';
		line-height: 1em;
		font-size: 3em;
		transform: skew(30deg) scale(1,1.7);
		font-weight: 400;
		letter-spacing: .1em;
		margin-bottom: .5em;
	}
	.welcome p{ 
		font-family: 'Agency FB';
		line-height: 1em;
		font-size: 1.5em;
		margin-top: .5em;
		font-weight: 200;
		letter-spacing: .2em;
	}
	.welcome div.text{
		width: 75%;
		position: absolute;
		padding: 2em;
		font-size: 1rem;
		box-shadow: 0px 0px 1px 0px #ddd;
		border-radius: 1em;
		transform: skew(-30deg);
	}
	.welcome .welcome-clock{
		position: absolute;
		bottom: 2em;
		width: 100%;
		text-align: center;
		letter-spacing: .1em;
		font-size: 1.2rem;
	}
	.welcome .welcome-clock span.display{
		line-height: auto;
		width: 80%;
		margin: 0 auto;
		font-family: arial;
		padding: 10px;
		font-weight: 300;
	}
	.welcome .welcome-clock span.display span{
		display: inline-block;
		transform: scale(1,1.5);
		font-size: .5em;
	}
	.welcome .datetime{ margin-left: 3em; }
</style>
<div class="row">
	<div class="welcome">
		<div class="text">
			<h1>Welcome to <?= ucfirst(SITE_NAME) ?></h1>
			<p>The site for Islamic knowledge &amp; Resources sharing...</p>
		</div>
		<div class="welcome-clock">
			<span class="display">
				<span id="hr">00</span>
				<span> : </span>
				<span id="min">00</span>
				<span> : </span>
				<span id="sec">00</span>
				<span class="datetime" id="clockTxt"></span>
				<span class="datetime" id="dateTxt"></span>
			</span>
		</div>
	</div>
</div>