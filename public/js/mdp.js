//Affiche / cache le mot de passe lorsque la checkbox est cochée ou non

const passwordFields = document.querySelectorAll("input[type='password']"),
toggleCheckbox = document.querySelector("#hide-mdp");

toggleCheckbox.onclick = () =>{

	passwordFields.forEach((passwordField)=>{
		if(passwordField.type === "password"){
			passwordField.type = "text";
		}else{
			passwordField.type = "password";
		}
	})
  
}
