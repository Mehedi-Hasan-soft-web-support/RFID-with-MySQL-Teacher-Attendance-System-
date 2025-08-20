<!DOCTYPE html>
<html>
<head>
    <title>Teacher Signup</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('DIU Campus (1).jpg');
            background-size: cover;
            background-position: center;
            z-index: -1;
        }

        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .particle:nth-child(1) { left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 1s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 2s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 3s; }
        .particle:nth-child(5) { left: 50%; animation-delay: 4s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 5s; }
        .particle:nth-child(7) { left: 70%; animation-delay: 0.5s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 1.5s; }

        @keyframes float {
            0%, 100% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
        }

        #signup-form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 50px 40px;
            border-radius: 30px;
            box-shadow: 
                20px 20px 60px rgba(0, 0, 0, 0.1),
                -20px -20px 60px rgba(255, 255, 255, 0.9),
                inset 5px 5px 15px rgba(0, 0, 0, 0.05),
                inset -5px -5px 15px rgba(255, 255, 255, 0.9);
            width: 100%;
            max-width: 420px;
            text-align: center;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        #signup-form:hover {
            transform: translateY(-10px);
            box-shadow: 
                25px 25px 80px rgba(0, 0, 0, 0.15),
                -25px -25px 80px rgba(255, 255, 255, 0.95),
                inset 5px 5px 15px rgba(0, 0, 0, 0.05),
                inset -5px -5px 15px rgba(255, 255, 255, 0.9);
        }

        h2 {
            color: #4a5568;
            font-size: 2.2em;
            font-weight: 300;
            margin-bottom: 40px;
            position: relative;
            letter-spacing: 2px;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        .input-group {
            margin-bottom: 30px;
            position: relative;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            color: #4a5568;
            font-weight: 500;
            font-size: 0.95em;
            letter-spacing: 0.5px;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 18px 20px;
            border: none;
            border-radius: 15px;
            background: #f7fafc;
            box-shadow: 
                inset 8px 8px 16px rgba(0, 0, 0, 0.1),
                inset -8px -8px 16px rgba(255, 255, 255, 0.9);
            font-size: 16px;
            color: #2d3748;
            transition: all 0.3s ease;
            outline: none;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            box-shadow: 
                inset 6px 6px 12px rgba(0, 0, 0, 0.15),
                inset -6px -6px 12px rgba(255, 255, 255, 1),
                0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        input[type="text"]::placeholder, input[type="email"]::placeholder, input[type="password"]::placeholder {
            color: #a0aec0;
        }

        button {
            width: 100%;
            padding: 18px;
            border: none;
            border-radius: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 
                8px 8px 20px rgba(102, 126, 234, 0.3),
                -8px -8px 20px rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        button:hover::before {
            left: 100%;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 
                12px 12px 30px rgba(102, 126, 234, 0.4),
                -12px -12px 30px rgba(255, 255, 255, 0.1);
        }

        button:active {
            transform: translateY(0px);
            box-shadow: 
                4px 4px 10px rgba(102, 126, 234, 0.3),
                -4px -4px 10px rgba(255, 255, 255, 0.1);
        }

        .teacher-icon {
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 
                8px 8px 20px rgba(102, 126, 234, 0.3),
                -8px -8px 20px rgba(255, 255, 255, 0.9);
            color: white;
            font-size: 24px;
        }

        @media (max-width: 480px) {
            #signup-form {
                margin: 20px;
                padding: 30px 25px;
            }
            h2 {
                font-size: 2em;
            }
            input[type="text"], input[type="email"], input[type="password"], button {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>
    <form id="signup-form" action="signup_process.php" method="POST">
        
        <h2>Teacher Signup</h2>
        <div class="input-group">
            <label>Name</label>
            <input type="text" name="name" placeholder="Enter your name" required>
        </div>
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Choose a username" required>
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Create a password" required>
        </div>
        <button type="submit">Sign Up</button>
        <p style="margin-top:20px;">Already have an account? <a href="index.php">Sign in</a></p>
    </form>
    <script>
        // Add floating animation to particles
        document.querySelectorAll('.particle').forEach(particle => {
            const randomDelay = Math.random() * 6;
            const randomDuration = 6 + Math.random() * 4;
            particle.style.animationDelay = randomDelay + 's';
            particle.style.animationDuration = randomDuration + 's';
        });
    </script>
</body>
</html>