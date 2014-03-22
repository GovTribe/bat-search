<!doctype html>
<html>
<head>
<title>BAT Search</title>
<?= stylesheet_link_tag() ?>
</head>	
	<body>
		<div class="container-fluid" id="appWindow">
			@yield('content')
			@include('modal')
		</div>
		@include('footer')
		<?= javascript_include_tag() ?>
	</body>
</html>