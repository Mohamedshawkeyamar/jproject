<?php
session_start();
include "db.php";  // هذه لربط الملف اللي فيه الوصول لقاعدة البيانات 
$error = "";

// معالجة تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // تنظيف المدخلات
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // يتحقق انه  مافي اي حقول فارغة يعني مثلا م يقدر يدخل الايميل ويسوي تسجيل دخول فلازم بملا كل الحقول لاجل يمدية ينقر  على تسجيل الدخول
    if (empty($email) || empty($password)) {
        $error = "Please enter your email and password.";
    } else {

        // تشفير كلمة المرور 
        $password = md5($password);

        // حماية  SQL Injection باستخدام  
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();



        //    التحقق من وجود المستخدم اذا هو موجود او لا 
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // تخزين معلومات الدخول
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email']   = $user['email'];



            // هذا هنا اني حطيت رابط لاجل عند النقر على لوج ان ينتقل تلقائيا لصفحة الرئيسية في حال ان كلمة السر والباسورد غلط
           header("Location: homepage.php");
           

            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>







<!DOCTYPE html>
<html>
<head>
	<title>Animated Login Form</title>
	<link rel="stylesheet" type="text/css" href="style1.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
				<h2 class="title">Welcome</h2>

                <!-- رسالة الخطأ -->
                <?php if(!empty($error)) { ?>
                    <p style="color:red; font-size:14px; margin-bottom:10px;">
                        <?php echo $error; ?>
                    </p>
                <?php } ?>



                <!-- Email -->
           		<div class="input-div one">
           		    <div class="i">
           		        <i class="fas fa-envelope"></i>
           		    </div>
           		    <div class="div">
           		        <input type="email" name="email" class="input" placeholder="Enter your Email" required>
           		    </div>
           		</div>



                <!-- Password -->
           		<div class="input-div pass">
           		    <div class="i">
           		        <i class="fas fa-lock"></i>
           		    </div>
           		    <div class="div">
           		        <input type="password" name="password" class="input" placeholder="Enter password" required>
            	   </div>
            	</div>




                
				<a href="register.php">Create New Account</a>
                <input type="submit" class="btn" value="Login">

            </form>
        </div>
    </div>

    <script type="text/javascript" src="main.js"></script>
</body>
</html>
