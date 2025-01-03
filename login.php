<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="assets/css/login/login.css" rel="stylesheet" />
  <style>
    .input-group-append .fa {
      cursor: pointer;
      background: transparent; /* Menghilangkan background */
      border: none; /* Menghilangkan border */
      padding: 0; /* Menghilangkan padding */
    }
    .input-group-append {
      cursor: pointer; /* Menunjukkan bahwa elemen dapat diklik */
    }
 
  </style>
</head>
<body>
  <div class="container">
    <!-- Placeholder for SweetAlert2 -->
    <div id="alert-placeholder"></div>
    <div class="card" >
      <div class="card-header text-center">
        <h3 class="mb-0">LOGIN</h3>
      </div>
      <div class="card-body" style="margin-top:1rem">
      <form action="includes/PROlogin.php" method="post" role="form">
          <div class="form-group">
            <label for="email">Email or Username</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
              </div>
              <input type="text" class="form-control" id="email" name="email" autocomplete="off" placeholder="Email or Username" required>
            </div>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
              </div>
              <input type="password" class="form-control" id="password" name="password" autocomplete="off" placeholder="Password" required>
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="fas fa-eye" id="togglePassword"></i>
                </span>
              </div>
            </div>
          </div>
          <button type="submit" style="margin-top:2rem;" class="btn btn-primary btn-block" value="login">Login</button>
        </form>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <script>
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePassword');

    // Function to show or hide password
    function togglePasswordVisibility() {
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
      }
    }

    // Add event listeners for mousedown and mouseup
    toggleIcon.addEventListener('mousedown', () => {
      passwordInput.type = 'text';
      toggleIcon.classList.remove('fa-eye');
      toggleIcon.classList.add('fa-eye-slash');
    });

    toggleIcon.addEventListener('mouseup', () => {
      passwordInput.type = 'password';
      toggleIcon.classList.remove('fa-eye-slash');
      toggleIcon.classList.add('fa-eye');
    });

    // Handle touch events for mobile devices
    toggleIcon.addEventListener('touchstart', () => {
      passwordInput.type = 'text';
      toggleIcon.classList.remove('fa-eye');
      toggleIcon.classList.add('fa-eye-slash');
    });

    toggleIcon.addEventListener('touchend', () => {
      passwordInput.type = 'password';
      toggleIcon.classList.remove('fa-eye-slash');
      toggleIcon.classList.add('fa-eye');
    });

    // Check for error parameter in the URL
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');

    if (error) {
      // Display SweetAlert2 alert and adjust container padding
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: decodeURIComponent(error),
        didOpen: () => {
          // Adjust the container's bottom padding to accommodate the alert
          document.querySelector('.container').style.paddingBottom = '7rem';
        },
        didClose: () => {
          // Reset the container's bottom padding after alert closes
          document.querySelector('.container').style.paddingBottom = '4rem';
        }
      });
    }
  </script>
</body>
</html>
