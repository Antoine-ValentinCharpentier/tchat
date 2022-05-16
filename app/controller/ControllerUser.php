
<!-- ----- debut Controller -->
<?php

class ControllerUser {

  public static function register() {
  	/*
	Méthode qui s'occupe du système d'inscription' de l'utilisateur.
  	*/
    include 'config.php';

	require $root . 'outil/phpmailer/Exception.php';
	require $root . 'outil/phpmailer/PHPMailer.php';
	require $root . 'outil/phpmailer/SMTP.php';

    $error = '';
    $success = '';
    session_start();

    if(isset($_SESSION['user_data'])){
		header('Location: router.php?action=chatRoom');
	}

    if(isset($_POST["register"])){
    	
    	require_once $root . '/app/model/UserModel.php';

    	$user = new UserModel;
    	$user->setFirstName(htmlspecialchars($_POST['first-name']));
    	$user->setLastName(htmlspecialchars($_POST['last-name']));
    	$user->setEmail(htmlspecialchars($_POST['email']));
    	$user->setPassword(hash('sha256', htmlspecialchars($_POST['password'])));
    	$user->setProfileVerifStatus("Disable");
    	$user->setCreationDate(date('Y-m-d H:i:s'));
    	$user->setVerifCode(substr(md5(uniqid()), 0,9));

    	//on regarde si les deux mots de passes sot identiques
    	if(htmlspecialchars($_POST['verif-password']) == htmlspecialchars($_POST['password'])){
    		//on regarde si cette adresse email existe déjà dans la base de données

	    	$user_data = $user->getUserDataByEmail();

	    	if(is_array($user_data) && count($user_data) > 0){
	    		if($user_data["profile_verif_status"] == "Disable"){
	    			$error="Cette adresse email est déjà utilisée. Vous devez confirmer l'email reçu.";
	    		}else{
	    			$error="Cette adresse email est déjà utilisée.";
	    		}
	    		
	    	}else{
	    		if($user->createAccount()){
	    			//mail de vérification
	    			include $root . '/app/model/SMTPCredentials.php';

	    			$mail = new PHPMailer\PHPMailer\PHPMailer(true);
	    			$mail->isSMTP();
	    			$mail->Host = $SMTPHost;
	    			$mail->SMTPAuth = true;
	    			$mail->Username = $SMTPUsername;
	    			$mail->Password = $SMTPPassword;
	    			$mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
	    			$mail->Port = 587;
	    			$mail->setFrom($SMTPFromEmail,$SMTPFromName);
	    			$mail->addAddress($user->getEmail());
	    			$mail->isHTML(true);
	    			$mail->CharSet = 'UTF-8';
	    			$mail->Subject = "Tchat - Vérification de l'inscription";
	    			$mail->Body = '
	    				<p>Pour confirmer votre inscription, veuillez cliquer sur le lien ci-dessous</p>
	    				<a href="http://localhost/tchat/app/router/router.php?action=verify&code='.$user->getVerifCode().'&email='.$user->getEmail().'">Cliquez sur ce lien pour confirmer</a>
	    			';
	    			$mail->send();
	    			$mail->smtpClose();

	    			$success = "Un email de confirmation vous a été envoyé à l'adress : ".$user->getEmail().".";
	    		}else{
	    			$error = "Un problème est survenu.";
	    		}
	    	}
    	}else{
    		$error = 'Les deux mots de passes doivent être identiques.';
    	}
    	
    }


    $vue = $root . '/app/view/viewRegister.php';
    require ($vue);
  }

	public static function verify() {
		/*
		Méthode qui s'occupe du système de vérification de l'adresse email de l'utilisateur.
		*/	
		include 'config.php';
		$success = '';
		$error = '';
		session_start();

		if(isset($_GET['code']) && isset($_GET['email'])){
			require_once $root . '/app/model/UserModel.php';
			$user = new UserModel;
			$user->setVerifCode(htmlspecialchars($_GET['code']));
			$user->setEmail(htmlspecialchars($_GET['email']));

			if($user->isValidVerifCode()){
				$user->setProfileVerifStatus("Enable");
				if($user->enableUserAccount()){
					$success = "Votre adresse e-mail a été <span class=\"green\">vérifiée</span> avec succès. Vous pouvez dès à présent vous <span class=\"green\">connecter</span> avec l'adresse e-mail : <span class=\"green\">".$user->getEmail()."</span>.";
				}else{
					$error = "Une erreur est survenue lors de l'activation du compte. Veuillez contacter un administrateur du site.";
				}
			}else{
				$error = 'Un problème est survenu. Veuillez réessayer plus tard.';
			}
		}else{
			$error = 'Vous devez cliquer sur le lien présent dans le mail pour confirmer votre adresse e-mail.';
		}

		$vue = $root . '/app/view/viewVerify.php';
		require ($vue);
	}

	public static function login() {
		/*
		Méthode qui s'occupe du système de connexion de l'utilisateur.
		*/

		include 'config.php';
		$success = '';
		$error = '';
		session_start();


		//S'il est déjà connecté, alors on le redirige vers la chat room. Il n'a pas besoin de se reconnecter tout de suite.
		if(isset($_SESSION['user_data'])){
			header('Location: router.php?action=chatRoom');
		}

		//s'il a cliqué sur le bouton se connecter
		if(isset($_POST['login'])){
			//on créé un objet user qui stocke les informations de l'utilisateur
			require_once $root . '/app/model/UserModel.php';
    		$user = new UserModel;
    		//on lui attribue les valeurs renseignées dans le formulaire
    		$user->setEmail(htmlspecialchars($_POST['email']));
    		$user->setPassword(hash('sha256', htmlspecialchars($_POST['password'])));

    		//on récupère l'ensemble des informations associé à l'adresse e-mail
    		$user_data = $user->getUserDataByEmail();
    		//on regarde s'il y a un utilisateur avec cette adresse e-mail
    		if(is_array($user_data) && count($user_data) > 0){
    			//on regarde s'il a saisi le bon mot de passe
    			if($user_data['password'] == $user->getPassword()){
    				//on regarde si le compte a été activé
    				if($user_data['profile_verif_status'] == "Enable"){
    					//$user_data et $user sont alors identiques, on a fait toutes les vérifications
    					$user->setId($user_data['id']);
    					$user->setLoginStatus('Login');
    					//on met à jour le statut de connexion de l'utilisateur
    					if($user->updateLoginStatus()){
    						echo "2";
    						echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
    						$_SESSION['user_data'] = [
    							"id" => $user_data['id'],
    							"first_name" => $user_data["first_name"],
    							"last_name" => $user_data["last_name"],
    							"email" => $user_data["email"],
    							"profile_picture" => $user_data["profile_picture"],
    							"desc" => $user_data["desc"],
    							"creation_date" => $user_data["creation_date"],
    						];
    						//on redirige alors l'utilisateur
    						header('Location: router.php?action=chatRoom');
    					}
    				}else{
    					$error = "Merci de confirmer votre adresse e-mail avant de pouvoir vous connecter. Pour ce faire, vous devez cliquer sur le lien reçu par e-mail.";
    				}
    			}else{
    				$error = "Le mot de passe saisi ne correspond pas à celui associé à cette adresse e-mail.";
    			}
    		}else{
    			$error = "Aucun utilisateur de notre base de données ne possède cette adresse e-mail. Veuillez vous inscrire avant de vous connecter.";
    		}
		}

		$vue = $root . '/app/view/viewLogin.php';
		require ($vue);
	}

}

?>
<!-- ----- fin Controller -->


