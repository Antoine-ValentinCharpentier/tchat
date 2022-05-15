<!DOCTYPE html>
<html>
<head>
	<title>Tchat - Vérification</title>
	<link rel="stylesheet" type="text/css" href="../../public/css/register.css">
</head>
<body>
	<div class="container">
		<h1 class="title">Inscription</h1>
		<?php
			if($error != ""){?>
				<div class='message'>
					<p><?php echo $error; ?></p>
				</div>
				<div class="row">
					<div class="field secondary small">
						<a href="router.php?action=register">S'inscrire</a>
					</div>
				</div>
			<?php
			}
			if($success != ""){?>
				<div class='message'>
					<p><?php echo $success; ?></p>
				</div>
				<div class="row">
					<div class="field secondary small">
						<a href="router.php?action=login">Se connecter</a>
					</div>
				</div>
			<?php
			}
		?>
		
		

	</div>

</html>