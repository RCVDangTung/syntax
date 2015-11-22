<!DOCTYPE html>
<html lang="en">
<head>
	<title>Bootstrap Example</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		
		<div class="row">
			<div class="col-xs-12 col-sm-8 col-md-8" style="background-color:red;">.col-xs-12 .col-md-8</div>
			<div class="col-xs-12 col-sm-4 col-md-4" style="background-color:black;">.col-xs-6 .col-md-4</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<h3>Column 1</h3>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
				<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
			</div>
			<div class="col-sm-4">
				<h3>Column 2</h3>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
				<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
			</div>
			<div class="col-sm-4">
				<h3>Column 3</h3>        
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
				<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-6 col-sm-12 col-md-6" style="background-color:#810c15;color:#FFF;">
				<p>Left</p>
			</div>
			<div class="col-xs-6 col-sm-12 col-md-6" style="background-color:#333; color:#FFF;">
				<p>Right</p>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6" style="background-color:#810c15;color:#FFF;">
				<p>TRAI 1 Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex quae recusandae deserunt harum tenetur libero consequuntur aliquam tempora vero laborum ut, eos nam inventore dolorem velit est officia ea cupiditate?</p>
			</div>
			<div class="col-md-6" style="background-color:#333; color:#FFF;">
				<p>PHAI 1</p>
			</div>
			<div class="clearfix"></div>
			<div class="col-md-6" style="background-color:#810c15;color:#FFF;">
				<p>TRAI 2</p>
			</div>
			<div class="col-md-6" style="background-color:#333; color:#FFF;">
				<p>PHAI 2</p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-1" style="background-color:#f2f2f2">1</div>
			<div class="col-md-1" style="background-color:#f2f2f2">1</div>
			<div class="col-md-1" style="background-color:#f2f2f2">1</div>
			<div class="col-md-1" style="background-color:#f2f2f2">1</div>
			<div class="col-md-1" style="background-color:#f2f2f2">1</div>
			<div class="col-md-1" style="background-color:#f2f2f2">1</div>
			<div class="col-md-1" style="background-color:#f2f2f2">1</div>
			<div class="col-md-1" style="background-color:#f2f2f2">1</div>
			<div class="col-md-1" style="background-color:#f2f2f2">1</div>
			<div class="col-md-1" style="background-color:#f2f2f2">1</div>
			<div class="col-md-1" style="background-color:#f2f2f2">1</div>
			<div class="col-md-1" style="background-color:#f2f2f2">1</div>
		</div>
		<div class="row">
			<div class="col-md-4 col-md-offset-1" style="background-color:#f2f2f2;border:1px solid #333;">CONTENT</div>
		</div>
		<div class="row">
			<div class="col-md-8 col-md-offset-2" style="background-color:#f2f2f2;border:1px solid #333;">CONTENT</div>
		</div>

		<div class="row">
			<div class="col-sm-8 col-sm-offset-2" style="background-color:#f2f2f2;border:1px solid #333;">CONTENT</div>
		</div>

		<div class="row">
			<div class="col-md-6" style="background-color:#f2f2f2;border:1px solid #333;">
				<div class="row">
					<div class="col-md-6" style="background-color:#333;">
						LEFT-A
					</div>
					<div class="col-md-6" style="background-color:red;">
						LEFT-B
					</div>
				</div>
			</div>
			<div class="col-md-6" style="background-color:#f2f2f2;border:1px solid #333;">
				<div class="row">
					<div class="col-md-8" style="background-color:#333;">
						RIGHT-A
					</div>
					<div class="col-md-4" style="background-color:red;">
						RIGHT-B
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6 col-md-push-6" style="background-color:#f2f2f2;border:1px solid #333;">LEFT</div>
			<div class="col-md-6 col-md-pull-6" style="background-color:#f2f2f2;border:1px solid #333;">RIGHT</div>
		</div>

		<h1>Mobile-first</h1>

		<div class="row">
			<div class="col-md-6 col-sm-6 col-xs-6" style="background-color:#f2f2f2;border:1px solid #333;">LEFT</div>
			<div class="col-md-6 col-sm-6 col-xs-6" style="background-color:red;border:1px solid #333;">RIGHT</div>
		</div>

		<div class="row">
			<div class="col-md-6 col-sm-12 col-xs-6" style="background-color:#f2f2f2;border:1px solid #333;">LEFT</div>
			<div class="col-md-6 col-sm-12 col-xs-6" style="background-color:red;border:1px solid #333;">RIGHT</div>
		</div>
	</div>
</body>
</html>