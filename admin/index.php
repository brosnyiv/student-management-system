<!-- <?php 

session_start();
include('dbconnect.php'); 


if (isset($_SESSION['user_id'])) {
    header("Location: dash.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['email'];
    $password = $_POST['password_hash'];

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // Sanitize input to prevent SQL injection
        $username = mysqli_real_escape_string($conn, $username);
        
        $sql = "SELECT * FROM users WHERE email = '$username'";
        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($result);

        // password_verify($password, $user['password_hash'])
        if ($user && password_verify($password, $user['password_hash'])) {
          // Password is correct
          $_SESSION['user_id'] = $user['user_id'];
          $_SESSION['username'] = $user['username'];
          $_SESSION['email'] = $user['email'];
          $_SESSION['role_id'] = $user['role_id'];
          $_SESSION['access_level'] = $user['access_level'];
          $_SESSION['is_active'] = $user['is_active'];
          $_SESSION['role_name'] = $user['role_name'];
          header("Location: dash.php");
          exit();
      } else {
          $error = "Invalid email or password.";
      }
    }
}

?> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute Portal - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #e6e9f0 100%);
        }
        
        .container {
            display: flex;
            width: 1000px;
            max-width: 95%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        /* Welcome Section */
        .welcome-section {
            flex: 1.2;
            background: linear-gradient(135deg, #003366 0%, #002244 100%);
            color: white;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            align-items: center;
            text-align: center;
        }
        
        .logo-container {
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            width: 100%;
        }
        
        .logo {
            height: 80px;
            width: auto;
            object-fit: contain;
        }
        
        .welcome-content {
            flex-grow: 1;
            width: 100%;
        }
        
        .welcome-section h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        
        .welcome-section p {
            font-size: 15px;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 15px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .features {
            text-align: left;
            margin: 25px 0;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .feature-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        
        .feature-icon {
            margin-right: 10px;
            color: rgba(255,255,255,0.8);
            font-size: 14px;
            margin-top: 3px;
        }
        
        .feature-text {
            font-size: 14px;
            opacity: 0.9;
            line-height: 1.5;
            text-align: left;
        }
        
        .social-media {
            margin-top: 20px;
            width: 100%;
        }
        
        .social-media p {
            margin-bottom: 15px;
            font-size: 14px;
            color: rgba(255,255,255,0.8);
        }
        
        .social-icons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: rgba(255,255,255,0.15);
        }
        
        .social-icon:hover {
            background-color: rgba(255,255,255,0.25);
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }
        
        /* Login Section */
        .login-section {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .form-title {
            color: #003366;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 18px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-size: 14px;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.2s;
            padding-right: 40px;
        }
        
        .form-group input:focus {
            border-color: #003366;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 51, 102, 0.1);
        }
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 38px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
            background: none;
            border: none;
            font-size: 16px;
        }
        
        .password-toggle:hover {
            color: #003366;
        }
        
        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
            font-size: 14px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 8px;
            accent-color: #003366;
        }
        
        .forgot-password a {
            color: #003366;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .forgot-password a:hover {
            color: #002244;
            text-decoration: underline;
        }
        
        .login-button {
            width: 100%;
            padding: 14px;
            background: #003366;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .login-button:hover {
            background: #002244;
            box-shadow: 0 5px 15px rgba(0, 34, 68, 0.15);
        }
        
        .login-button:active {
            transform: scale(0.98);
        }
        
        /* Decorative Elements */
        .decoration-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.03);
        }
        
        .circle-1 {
            width: 180px;
            height: 180px;
            top: -40px;
            right: -40px;
        }
        
        .circle-2 {
            width: 120px;
            height: 120px;
            bottom: -20px;
            left: -20px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                margin: 20px 0;
            }
            
            .welcome-section, .login-section {
                padding: 40px 30px;
            }
            
            .logo {
                height: 70px;
            }
            
            .welcome-section h1 {
                font-size: 28px;
            }
            
            .social-icons {
                gap: 12px;
            }
            
            .features {
                margin: 20px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-section">
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
            
            <div class="logo-container">
                <img src="logo.png" alt="Monaco Institute Logo" class="logo">
            </div>
            
            <div class="welcome-content">
                <h1>Welcome to<br>MONACO INSTITUTE PORTAL</h1>
                <p>Your gateway to academic excellence and institutional collaboration.</p>
                
                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="feature-text">Access academic records and course materials</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="feature-text">Track performance and academic progress</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="feature-text">Receive important updates and announcements</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="feature-text">Connect with faculty and peers</div>
                    </div>
                </div>
                
                <p>Log in to manage your academic journey, stay connected, and explore smarter ways to learn and grow.</p>
            </div>
            
            <div class="social-media">
                <p>Connect with us:</p>
                <div class="social-icons">
                    <a href="#" class="social-icon" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon" title="TikTok"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="social-icon" title="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        
        <div class="login-section">
            <?php if (isset($error)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php } ?>

            <h2 class="form-title">USER LOGIN</h2>
            
            <form id="loginForm" method="post" action="index.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" placeholder="Enter your username" name="email">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" placeholder="Enter your password" name="password_hash">
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                
                <div class="options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <div class="forgot-password">
                        <a href="#">Forgot password?</a>
                    </div>
                </div>
                <input type="submit" name="submit" class="login-button"  value="LOGIN">
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="far fa-eye"></i>' : '<i class="far fa-eye-slash"></i>';
        });

        // Form submission
        // document.getElementById('loginForm').addEventListener('submit', function(e) {
        //     e.preventDefault();
        //     // Add your actual login logic here
        //     console.log('Login form submitted');
        // });
    </script>
</body>
</html>