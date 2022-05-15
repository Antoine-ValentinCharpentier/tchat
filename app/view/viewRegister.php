<!DOCTYPE html>
<html>
<head>
	<title>Tchat - Inscription</title>
	<link rel="stylesheet" type="text/css" href="../../public/css/register.css">
</head>
<body>
	<div class="container">
		<h1 class="title">Inscription</h1>
		<?php
			if($error != ""){
				echo "<div class='message error'><p>".$error."</p></div>";
			}
			if($success != ""){
				echo "<div class='message success'><p>".$success."</p></div>";
			}
		?>
		<form action="router.php?action=register" method="POST">
			<div class="row">
				<div class="field">
					<label for="first-name">Prénom</label>
					<input type="text" name="first-name" id="first-name" placeholder="Prénom" required>
				</div>
				<div class="field">
					<label for="last-name">Nom</label>
					<input type="text" name="last-name" id="last-name" placeholder="Nom" required>
				</div>
			</div>
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
				<div class="field">
					<label for="verif-password">Confirmer le mot de passe</label>
					<input type="password" name="verif-password" id="verif-password" placeholder="Votre mot de passe" required>
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
					<input type="submit" name="register" value="S'inscrire">
				</div>
				<div class="field secondary">
					<a href="router.php?action=login">Se connecter</a>
				</div>
				
			</div>
  		</form>
	</div>

    <script src="../../public/js/mdp.js"></script>

</html>