<!DOCTYPE html>
<html>
<head>
	<title>Tchat - Connexion</title>
	<link rel="stylesheet" type="text/css" href="../../public/css/register.css">
</head>
<body>
	<div class="container">
		<h1 class="title">Connexion</h1>
		<?php
			if($error != ""){
				echo "<div class='message error'><p>".$error."</p></div>";
			}
		?>
		<form action="router.php?action=login" method="POST">
			<div class="row">
				<div class="field">
					<label for="email">E-mail</label>
					<input type="text" name="email" id="email" placeholder="E-mail" required>
				</div>
			</div>
			<div class="row">
				<div class="field">
					<label for="password">Mot de passe</label>
					<input type="password" name="password" id="password" placeholder="Votre mot de passe" required>
				</div>
			</div>
			<div class="row">
				<div class="checkbox-hide-mdp">
  					<input type="checkbox"  type="checkbox" id="hide-mdp">
					<label for="hide-mdp">Afficher le mot de passe</label>
				</div>
			</div>
			<div class="row">
				<div class="field primary">
					<input type="submit" name="login" value="Se connecter">
				</div>
				<div class="field secondary">
					<a href="router.php?action=register">S'inscrire</a>
				</div>
				
			</div>
  		</form>
	</div>

    <script src="../../public/js/mdp.js"></script>

</html>