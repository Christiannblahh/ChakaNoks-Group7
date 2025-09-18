<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ChaKaNok's — Login</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
	<?= link_tag('public/css/login.css') ?>
</head>
<body>
	<div class="page">
		<div class="brand">
			<div class="logo">ChaKaNok's</div>
			<div class="tag">chain supply.</div>
		</div>
		<div class="card">
			<div class="card__title">ChaKanok's</div>
			<form class="form" method="post" action="<?= site_url('login') ?>">
				<div class="field">
					<input type="text" class="input" name="username" placeholder="">
					<label>username</label>
				</div>
				<div class="field">
					<input type="password" class="input" name="password" placeholder="">
					<label>password</label>
					<a class="link" href="#">forgot password</a>
				</div>
				<button class="btn" type="submit">log in</button>
			</form>
		</div>
	</div>
</body>
</html>


