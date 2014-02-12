<!doctype html>
<html>
<head>
<title>BAT Search</title>
<?= stylesheet_link_tag() ?>
</head>	
	<body>
	<div class="container-fluid">
		@include('navbar')
		@yield('content')
	</div>
	@include('footer')
	<?= javascript_include_tag() ?>
	</body>
</html>