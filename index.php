<!DOCTYPE html>
<html>
<head>
	<title>Cable TV Software | Login</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<style>
body {
  background: #007bff;
  background: linear-gradient(to bottom, #0062E6, #33AEFF);
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh; /* This ensures the background covers the entire viewport height */
}

@keyframes zoomInOut {
  0% {
    transform: scale(0);
    opacity: 0;
  }
  50% {
    transform: scale(1.2);
    opacity: 1;
  }
  100% {
    transform: scale(1);
  }
}

.container {
  animation: zoomInOut 0.5s ease-in-out;
}



.container {
  /* background-color: white; /* If you want a white background for your container */
  padding: 20px; /* Adjust the padding as needed */
  border-radius: 30px; /* Add rounded corners if desired */
  /* box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Add a subtle shadow if desired */
}

.btn-login {
  font-size: 0.9rem;
  letter-spacing: 0.05rem;
  padding: 0.75rem 1rem;
}


.btn-google {
  color: white !important;
  background-color: #ea4335;
}

.btn-facebook {
  color: white !important;
  background-color: #3b5998;
}
</style>
</head>
<!-- This snippet uses Font Awesome 5 Free as a dependency. You can download it at fontawesome.io! -->

<body>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card border-0 shadow rounded-3 my-5">
          <div class="card-body p-4 p-sm-5">
          <h4 class="text-center">CABLE TV Billing Software</h4><br/>
			<form action="check-login.php" method="post" >
      	      <?php if (isset($_GET['error'])) { ?>
      	      <div class="alert alert-danger" role="alert">
				  <?=$_GET['error']?>
			  </div>
			  <?php } ?>
              <div class="form-floating mb-3">
			  <input type="text" class="form-control" name="username" id="username">
                <label for="username">User ID</label>
              </div>
              <div class="form-floating mb-3">
			  <input type="password" name="password" class="form-control" id="password">
                <label for="password">Password</label>
              </div>

              <!-- <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="" id="rememberPasswordCheck">
                <label class="form-check-label" for="rememberPasswordCheck">
                  Remember password
                </label>
              </div> -->
              <div class="d-grid">
                <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit">
					Sign in
				</button>
              </div>
              <!-- <hr class="my-4">
              <div class="d-grid mb-2">
                <button class="btn btn-google btn-login text-uppercase fw-bold" type="submit">
                  <i class="fab fa-google me-2"></i> Sign in with Google
                </button>
              </div>
              <div class="d-grid">
                <button class="btn btn-facebook btn-login text-uppercase fw-bold" type="submit">
                  <i class="fab fa-facebook-f me-2"></i> Sign in with Facebook
                </button> -->
              </div>
		          <center><h6>Powered by <a href="https://www.dureshtech.com/" target="_blank" style="text-decoration: none;">Duresh Tech</a></h6></center>
    <p align="center">13th-Commit</p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>