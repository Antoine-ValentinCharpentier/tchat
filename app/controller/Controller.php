
<!-- ----- debut Controller -->
<?php

class Controller {

  public static function register() {
    include 'config.php';

	require $root . 'outil/phpmailer/Exception.php';
	require $root . 'outil/phpmailer/PHPMailer.php';
	require $root . 'outil/phpmailer/SMTP.php';

    $error = '';
    $success = '';

    if(isset($_POST["register"])){
    	session_start();

    	if(isset($_SESSION['user_data'])){
    		header('Location: app/router/router.php?action=chatRoom');
    	}

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
    }

    $vue = $root . '/app/view/viewVerify.php';
    require ($vue);
  }

}

?>
<!-- ----- fin Controller -->


