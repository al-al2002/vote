// === Toggle between login and register forms ===
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

// === Toggle password visibility ===
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

// === SweetAlert Popups ===
document.addEventListener('DOMContentLoaded', function () {
    // Laravel validation errors
    if (window.errors && window.errors.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: window.errors.join('<br>'),
            confirmButtonColor: '#00eaff',
        });
    }

    // Session error
    if (window.sessionError) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: window.sessionError,
            confirmButtonColor: '#00eaff',
        });
    }

    // Session success
    if (window.sessionSuccess) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: window.sessionSuccess,
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            background: 'rgba(10, 25, 47, 0.95)',
            color: '#00eaff',
            iconColor: '#00eaff',
            customClass: {
                popup: 'swal2-neon-popup'
            }
        });
    }

    // === Password match validation for register form ===
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('registerConfirmPassword').value;

            if (password !== confirmPassword) {
                e.preventDefault(); // stop form submission
                Swal.fire({
                    icon: 'error',
                    title: 'Passwords do not match',
                    text: 'Please make sure your password and confirm password are the same.',
                    confirmButtonColor: '#00eaff',
                });
            }
        });
    }
});
