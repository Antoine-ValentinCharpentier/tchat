<?php

require_once 'Model.php';

class UserModel{

	private $id;
	private $first_name;
	private $last_name;
	private $email;
	private $password;
	private $profile_picture;
	private $desc;
	private $creation_date;
	private $login_status;
	private $verif_code;
	private $profile_verif_status;

	public function setId($id){
		$this->id = $id;
	}

	public function getId(){
		return $this->id;
	}

	public function setFirstName($first_name){
		$this->first_name = $first_name;
	}

	public function getFirstName(){
		return $this->first_name;
	}

	public function setLastName($last_name){
		$this->last_name = $last_name;
	}

	public function getLastName(){
		return $this->last_name;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setPassword($password){
		$this->password = $password;
	}

	public function getPassword(){
		return $this->password;
	}

	public function setProfilePicture($profile_picture){
		$this->profile_picture = $profile_picture;
	}

	public function getProfilePicture(){
		return $this->profile_picture;
	}

	public function setDesc($desc){
		$this->desc = $desc;
	}

	public function getDesc(){
		return $this->desc;
	}

	public function setCreationDate($creation_date){
		$this->creation_date = $creation_date;
	}

	public function getCreationDate(){
		return $this->creation_date;
	}

	public function setLoginStatus($login_status){
		$this->login_status = $login_status;
	}

	public function getLoginStatus(){
		return $this->login_status;
	}

	public function setVerifCode($verif_code){
		$this->verif_code = $verif_code;
	}

	public function getVerifCode(){
		return $this->verif_code;
	}

	public function setProfileVerifStatus($profile_verif_status){
		$this->profile_verif_status = $profile_verif_status;
	}

	public function getProfileVerifStatus(){
		return $this->profile_verif_status;
	}

	public function getUserDataByEmail(){
		/*
			Méthode permettant de récupérer l'ensemble des informations d'un utilisateur à partir de son adresse e-mail.
		*/
		try {
			$database = Model::getInstance();
			$query = "SELECT * FROM user WHERE email = :email";
			$statement = $database->prepare($query);
			$statement->bindParam(':email',$this->email);
			$statement->execute();
			$results = $statement->fetch(PDO::FETCH_ASSOC);
			return $results;
		}catch (PDOException $e) {
			printf("%s - %s<p/>\n", $e->getCode(), $e->getMessage());
			return NULL;
		}
	}

	public function createAccount(){
		/*
			Méthode permettant d'insérer un nouvel utilisateur dans la base de données (avant vérification de l'adresse e-mail).
		*/
        try {
        	$database = Model::getInstance();
            $query = "INSERT INTO user (first_name,last_name,email,password,creation_date,verif_code,profile_verif_status) VALUES (:first_name,:last_name,:email,:password,:creation_date,:verif_code,:profile_verif_status)";
            $statement = $database->prepare($query);

            $statement->bindParam(':first_name',$this->first_name);
            $statement->bindParam(':last_name',$this->last_name);
            $statement->bindParam(':email',$this->email);
            $statement->bindParam(':password',$this->password);
            $statement->bindParam(':creation_date',$this->creation_date);
            $statement->bindParam(':verif_code',$this->verif_code);
            $statement->bindParam(':profile_verif_status',$this->profile_verif_status);

            $statement->execute();

            return true;
        } catch (PDOException $e) {
            printf("%s - %s<p/>\n", $e->getCode(), $e->getMessage());
            return false;
        }
	}

	public function isValidVerifCode(){
		/*
			Méthode permettant de regarder si le code de vérification correcte et qu'il est associé à la bonne adresse e-mail.
		*/
		try {
	    	$database = Model::getInstance();

	    	$query = "SELECT * FROM user WHERE email = :email AND verif_code = :code AND profile_verif_status = 'Disable'";
	    	$statement = $database->prepare($query);
	    	$statement->bindParam(':email',$this->email);
	    	$statement->bindParam(':code',$this->verif_code);
	    	$statement->execute();
	    	$results = $statement->rowCount();

	    	if($results > 0){
	    		return true;
	    	}
	    	return false;
	    } catch (PDOException $e) {
	    	printf("%s - %s<p/>\n", $e->getCode(), $e->getMessage());
	    	return false;
	    }
	}

	public function enableUserAccount(){
		/*
			Méthode permettant d'activer le compte d'un utilisateur à l'aide de son adresse e-mail.
		*/
		try {
	      $database = Model::getInstance();

	      $query = "UPDATE user SET profile_verif_status = :status where email = :email";
	      $statement = $database->prepare($query);
	      $statement->bindParam(':status',$this->profile_verif_status);
	      $statement->bindParam(':email',$this->email);
	      
	      if($statement->execute()){
			return true;
	      }

	      return false;
	    }catch (PDOException $e) {
	      printf("%s - %s<p/>\n", $e->getCode(), $e->getMessage());
	      return false;
	    }
	}

	public function updateLoginStatus(){
		/*
			Méthode permettant de modifier le statut (Connecté/Déconnecté) d'un utilisateur à l'aide de son adresse e-mail.
		*/
		try {
	      $database = Model::getInstance();

	      $query = "UPDATE user SET login_status = :status where email = :email";
	      $statement = $database->prepare($query);
	      $statement->bindParam(':status',$this->login_status);
	      $statement->bindParam(':email',$this->email);
	      
	      if($statement->execute()){
			return true;
	      }

	      return false;
	    }catch (PDOException $e) {
	      printf("%s - %s<p/>\n", $e->getCode(), $e->getMessage());
	      return false;
	    }
	}
}
?>