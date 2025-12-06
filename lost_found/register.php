<?php
include "db.php";

$message = "";

// معالجة التسجيل
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phone    = trim($_POST['phone']);
    $college  = trim($_POST['college']);
    $major    = trim($_POST['major']);
    $date     = trim($_POST['date']);

    // هنا يتحقق إذا البريد موجود مسبقًا
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        echo "<script>alert('Email already exists!');</script>";
    } else {
        $password_hashed = md5($password);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, college, major, date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $email, $password_hashed, $phone, $college, $major, $date);

        if($stmt->execute()){
            echo "<script>alert('Account created successfully!'); window.location.href='login.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error creating account.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<link rel="stylesheet" type="text/css" href="style1.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js" crossorigin="anonymous"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /*  لجعل الحقول حتكون متناسقه*/
        select.input-select, input.input {
            background: none;
            border: none;
            width: 100%;
            font-size: 16px;
            padding: 8px;
            color: #555;
            outline: none;
        }

        select.input-select option[value=""] {
            color: #999;
        }

        .input-div.select-div {
            display: grid;
            grid-template-columns: 7% 93%;
            margin: 20px 0;
            padding: 5px 0;
            border-bottom: 2px solid #d9d9d9;
            height: 45px;
        }

        .input-div.select-div .div select {
            height: 100%;
        }
    </style>
</head>
<body>
	<img class="wave" src="wave.png">
	<div class="container">
		<div class="img">
			<img src="bg1.png">
		</div>

		<div class="login-content">
			<form method="POST" action="">
				<img src="avatar.svg">
				<h3 class="title">Create New Account</h3>



                
                <!-- من هنا جزئية الحقول الادخال  -->
                <div class="input-div one">
           		   <div class="i"><i class="fas fa-user"></i></div>
           		   <div class="div">
           		   		<input type="text" name="name" class="input"  placeholder="Full Name "   required>
           		   </div>
           		</div>

           		<div class="input-div one">
           		   <div class="i"><i class="fas fa-envelope"></i></div>
           		   <div class="div">
           		   		<input type="email" name="email" class="input" placeholder="Email"  required>
           		   </div>
           		</div>

           		<div class="input-div pass">
           		   <div class="i"><i class="fas fa-lock"></i></div>
           		   <div class="div">
           		    	
           		    	<input type="password" name="password" class="input"  placeholder="Password" required>
            	   </div>
            	</div>

                <!-- Phone -->
                <div class="input-div one">
           		   <div class="i"><i class="fas fa-phone"></i></div>
           		   <div class="div">
           		   		<input type="text" name="phone" class="input" placeholder="Phone Number" required>
           		   </div>
           		</div>

                <div class="input-div select-div">
           		   <div class="i"><i class="fas fa-university"></i></div>
           		   <div class="div">
                    <h5>Select College</h5>
                        <select name="college" placeholder="Select College" required class="input-select">
                            <option value=""></option>
                            <option value="Engineering">Faculty of Engineering</option>
                            <option value="Medicine">Faculty of Medicine</option>
                            <option value="Computer Science">College of Computer Science</option>
                            <option value="Business">Faculty of Business Administration</option>
                            <option value="Education">Faculty of Education</option>
                            <option value="Law">Faculty of Law</option>
                        </select>
           		   </div>
           		</div>

                <div class="input-div one">
           		   <div class="i"><i class="fas fa-layer-group"></i></div>
           		   <div class="div">
           		   		<input type="text" name="major" class="input"placeholder="Department / Major" required>
           		   </div>
           		</div>

                
                <div class="input-div one">
           		   <div class="i"><i class="fas fa-calendar"></i></div>
           		   <div class="div">
           		   		<input type="date" name="date" class="input" placeholder="Data " required>
           		   </div>
           		</div>

            	<a href="login.php">Already have an account?</a>
            	<input type="submit" class="btn" value="Register">
            </form>
        </div>
    </div>
    <script type="text/javascript" src="main.js"></script>
</body>
</html>
