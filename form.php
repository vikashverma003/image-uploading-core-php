<?php
/* form.php */


    session_start();
	
    $_SESSION['message'] = '';
	
    $mysqli = new mysqli("localhost", "root", "", "accounts");

			
				//the form has been submitted with post
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			
			
			//print_r( $_FILES ); die();

			
			//two passwords are equal to each other
			if ($_POST['password'] == $_POST['confirmpassword']) {
				
				//define other variables with submitted values from $_POST
				$username = $mysqli->real_escape_string($_POST['username']);
				$email = $mysqli->real_escape_string($_POST['email']);

				//md5 hash password for security
				$password = md5($_POST['password']);
				

				//path were our avatar image will be stored
				$avatar_path = $mysqli->real_escape_string('images/'.$_FILES['avatar']['name']);
				
				//make sure the file type is image
				if (preg_match("!image!",$_FILES['avatar']['type'])) {
					
					//copy image to images/ folder 
					if (copy($_FILES['avatar']['tmp_name'], $avatar_path)){
						
						//set session variables to display on welcome page
						$_SESSION['username'] = $username;
						$_SESSION['avatar'] = $avatar_path;

						//insert user data into database
						$sql = 
						"INSERT INTO users (username, email, password, avatar) "
						. "VALUES ('$username', '$email', '$password', '$avatar_path')";
						
						//check if mysql query is successful
						if ($mysqli->query($sql) === true){
							$_SESSION['message'] = "Registration successful!"
							. "Added $username to the database!";
							//redirect the user to welcome.php
							header("location: welcome.php");
						}
			else {
                    $_SESSION['message'] = 'User could not be added to the database!';
                }
                $mysqli->close();          
            }
            else {
                $_SESSION['message'] = 'File upload failed!';
            }
        }
        else {
            $_SESSION['message'] = 'Please only upload GIF, JPG or PNG images!';
        }
    }
    else {
        $_SESSION['message'] = 'Two passwords do not match!';
    }
	
		}
//if ($_SERVER["REQUEST_METHOD"] == "POST")

		
?>







<link href="//db.onlinewebfonts.com/c/a4e256ed67403c6ad5d43937ed48a77b?family=Core+Sans+N+W01+35+Light" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="form.css" type="text/css">
<div class="body-content">
  <div class="module">
    <h1>Create an account</h1>
    <form class="form" action="form.php" method="post" enctype="multipart/form-data" autocomplete="off">
      <div class="alert alert-error"><?= $_SESSION['message'] ?> </div>
      <input type="text" placeholder="User Name" name="username" required />
      <input type="email" placeholder="Email" name="email" required />
      <input type="password" placeholder="Password" name="password" autocomplete="new-password" required />
      <input type="password" placeholder="Confirm Password" name="confirmpassword" autocomplete="new-password" required />
      <div class="avatar"><label>Select your avatar: </label><input type="file" name="avatar" accept="image/*" required /></div>
      <input type="submit" value="Register" name="register" class="btn btn-block btn-primary" />
    </form>
  </div>
</div>
