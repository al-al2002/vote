<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VoteMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Background */
       body {
    background: url("{{ asset('images/vote.jpg') }}") no-repeat center center fixed;
    background-size: cover;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #fff;
}

        /* Overlay for readability */
        .overlay {
            background: rgba(0, 0, 0, 0.7);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* Futuristic Neon Card */
        .card {
            border-radius: 20px;
            max-width: 420px;
            width: 100%;
            background: rgba(10, 25, 47, 0.9);
            backdrop-filter: blur(12px);
            border: 2px solid rgba(0, 255, 255, 0.4);
            box-shadow: 0px 0px 25px rgba(0, 255, 255, 0.6);
        }

       .card h3 {
    color: #00eaff;
    text-shadow: 0 0 10px #00eaff, 0 0 20px #00ffff;
}

    .card p {
    color: #ffffff;   /* pure white for visibility */
    font-weight: 500; /* slightly bold */
    letter-spacing: 0.5px; /* makes text crisp */
}

        /* Input fields */
        input.form-control {
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(0, 255, 255, 0.4);
            color: #fff;
        }

        input.form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #00eaff;
            box-shadow: 0 0 10px #00eaff;
            color: #fff;
        }

        ::placeholder {
            color: #9bbcd1 !important;
        }

        /* Icons inside input */
        .position-absolute {
            color: #00eaff !important;
        }

        /* Tabs */
        .nav-link {
            border-radius: 10px 10px 0 0;
            color: #9bbcd1;
            font-weight: bold;
            background: transparent;
            border: 1px solid rgba(0, 255, 255, 0.2);
        }

        .nav-link.active {
            color: #00eaff !important;
            background: rgba(0, 255, 255, 0.15);
            border-bottom: 2px solid #00eaff;
            box-shadow: 0px 0px 15px rgba(0, 255, 255, 0.5);
        }

        /* Buttons */
        .btn-primary,
        .btn-success {
            border-radius: 30px;
            font-weight: bold;
            background: linear-gradient(90deg, #00eaff, #007bff);
            border: none;
            box-shadow: 0px 0px 20px rgba(0, 234, 255, 0.7);
            transition: all 0.3s ease;
        }

        .btn-primary:hover,
        .btn-success:hover {
            transform: scale(1.05);
            box-shadow: 0px 0px 30px rgba(0, 234, 255, 1);
        }

        /* Links */
        a {
            color: #00eaff;
            transition: 0.3s;
        }

        a:hover {
            color: #00ffff;
            text-shadow: 0 0 8px #00eaff;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="overlay"></div>

    <div class="card shadow-lg rounded-4 border-0 p-4 position-relative">
    <!-- Header -->
    <div class="text-center mb-4">
        <h3 class="fw-bold">VoteMaster</h3>
        <p>Voting Management System</p>
    </div>


        <!-- Tabs -->
        <ul class="nav nav-pills nav-justified mb-3" id="authTabs">
            <li class="nav-item">
                <button class="nav-link active" id="login-tab" onclick="showForm('login')">Login</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="register-tab" onclick="showForm('register')">Register</button>
            </li>
        </ul>

        <!-- Login Form -->
        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3 position-relative">
            <input type="text" name="login" class="form-control ps-5" placeholder="Voter ID/Email " required>

                <i class="bi bi-card-text position-absolute"
                    style="top:50%; left:15px; transform:translateY(-50%);"></i>
            </div>

            <div class="mb-3 position-relative">
                <input id="loginPassword" type="password" name="password" class="form-control ps-5 pe-5"
                    placeholder="Enter your password" required>
                <i class="bi bi-lock position-absolute" style="top:50%; left:15px; transform:translateY(-50%);"></i>
                <i class="bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;"
                    onclick="togglePassword('loginPassword', this)"></i>
            </div>

            <div class="d-flex justify-content-between mb-3">
                <div class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label for="remember" class="form-check-label">Remember me</label>
                </div>
                <a href="#" class="text-decoration-none">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>

        <!-- Register Form -->
        <form id="registerForm" method="POST" action="{{ route('register') }}" class="d-none">
            @csrf
            <div class="mb-3 position-relative">
                <input type="text" name="name" class="form-control ps-5" placeholder="Full Name" required>
                <i class="bi bi-person position-absolute" style="top:50%; left:15px; transform:translateY(-50%);"></i>
            </div>

            <div class="mb-3 position-relative">
                <input type="email" name="email" class="form-control ps-5" placeholder="Email Address" required>
                <i class="bi bi-envelope position-absolute" style="top:50%; left:15px; transform:translateY(-50%);"></i>
            </div>

            <div class="mb-3 position-relative">
                <input type="text" name="voter_id" class="form-control ps-5" placeholder="Voter ID Number" required>
                <i class="bi bi-card-text position-absolute"
                    style="top:50%; left:15px; transform:translateY(-50%);"></i>
            </div>

            <div class="mb-3 position-relative">
                <input id="registerPassword" type="password" name="password" class="form-control ps-5 pe-5"
                    placeholder="Create a strong password" required>
                <i class="bi bi-lock position-absolute" style="top:50%; left:15px; transform:translateY(-50%);"></i>
                <i class="bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;"
                    onclick="togglePassword('registerPassword', this)"></i>
            </div>

            <div class="mb-3 position-relative">
                <input id="registerConfirmPassword" type="password" name="password_confirmation"
                    class="form-control ps-5 pe-5" placeholder="Confirm your password" required>
                <i class="bi bi-lock position-absolute" style="top:50%; left:15px; transform:translateY(-50%);"></i>
                <i class="bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;"
                    onclick="togglePassword('registerConfirmPassword', this)"></i>
            </div>

            <button type="submit" class="btn btn-success w-100">Create Account</button>
        </form>
    </div>

    <script>
        function showForm(form) {
            document.getElementById("loginForm").classList.add("d-none");
            document.getElementById("registerForm").classList.add("d-none");

            if (form === 'login') {
                document.getElementById("loginForm").classList.remove("d-none");
                document.getElementById("login-tab").classList.add("active");
                document.getElementById("register-tab").classList.remove("active");
            } else {
                document.getElementById("registerForm").classList.remove("d-none");
                document.getElementById("register-tab").classList.add("active");
                document.getElementById("login-tab").classList.remove("active");
            }
        }

        function togglePassword(id, icon) {
            const input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        }
    </script>
</body>

</html>
