<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoodBite - Feel. Eat. Loved.</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Font aesthetic -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #FFE6E6 0%, #E6F7FF 100%);
            color: #333;
            height: 100vh;
            overflow: hidden;
        }

        .splash-container {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* Background elements */
        .bg-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .element {
            position: absolute;
            opacity: 0.1;
        }

        .circle-1 {
            width: 200px;
            height: 200px;
            background-color: #FF9AA2;
            border-radius: 50%;
            top: 10%;
            left: 5%;
            animation: float 8s infinite ease-in-out;
        }

        .circle-2 {
            width: 150px;
            height: 150px;
            background-color: #FFD166;
            border-radius: 50%;
            bottom: 15%;
            right: 10%;
            animation: float 10s infinite ease-in-out reverse;
        }

        .circle-3 {
            width: 100px;
            height: 100px;
            background-color: #98FF98;
            border-radius: 50%;
            top: 60%;
            left: 15%;
            animation: float 7s infinite ease-in-out;
        }

        /* Main content */
        .main-content {
            text-align: center;
            z-index: 2;
            max-width: 600px;
            padding: 40px;
            animation: fadeIn 1s ease-out;
        }

        /* Logo */
        .logo {
            margin-bottom: 30px;
        }

        .logo h1 {
            font-size: 4.5rem;
            font-weight: 800;
            color: #FF6B8B;
            margin-bottom: 10px;
            text-shadow: 3px 3px 0px rgba(255, 107, 139, 0.2);
        }

        .logo .emoji {
            font-size: 3rem;
            animation: bounce 2s infinite;
        }

        /* Tagline */
        .tagline {
            font-size: 1.5rem;
            color: #666;
            margin-bottom: 50px;
            font-weight: 300;
        }

        /* Login button */
        .login-btn {
            display: inline-block;
            padding: 18px 50px;
            background: linear-gradient(135deg, #FF6B8B 0%, #FFD166 100%);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(255, 107, 139, 0.3);
            margin-bottom: 20px;
        }

        .login-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(255, 107, 139, 0.4);
        }

        .login-btn i {
            margin-right: 10px;
        }

        /* Register link */
        .register-link {
            color: #FF6B8B;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin-top: 15px;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        /* Copyright */
        .copyright {
            position: absolute;
            bottom: 30px;
            color: #999;
            font-size: 0.9rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .logo h1 {
                font-size: 3.5rem;
            }
            
            .tagline {
                font-size: 1.2rem;
            }
            
            .login-btn {
                padding: 16px 40px;
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            .logo h1 {
                font-size: 2.8rem;
            }
            
            .tagline {
                font-size: 1rem;
            }
            
            .login-btn {
                padding: 14px 35px;
                font-size: 1rem;
            }
            
            .circle-1, .circle-2, .circle-3 {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="splash-container">
        <div class="bg-elements">
            <div class="element circle-1"></div>
            <div class="element circle-2"></div>
            <div class="element circle-3"></div>
        </div>
        
        <div class="main-content">
            <div class="logo">
                <h1>MOODBITE <span class="emoji">🍰</span></h1>
                <p class="tagline">Feel. Eat. Loved.</p>
            </div>
            
            <a href="{{ route('login') }}" class="login-btn">
                <i class="fas fa-sign-in-alt"></i>Log In
            </a>
            
            <div>
                <a href="{{ route('register') }}" class="register-link">
                    Belum punya akun? Daftar di sini
                </a>
            </div>
        </div>
        
        <div class="copyright">
            © 2025 MoodBite • Temukan makanan sesuai suasana hatimu
        </div>
    </div>

    <script>
        // Auto check if user already logged in
        setTimeout(() => {
            fetch('/api/check-auth')
                .then(response => response.json())
                .then(data => {
                    if (data.authenticated) {
                        window.location.href = '{{ route("dashboard") }}';
                    }
                })
                .catch(error => {
                    console.log('Auth check skipped');
                });
        }, 2000);
    </script>
</body>
</html>