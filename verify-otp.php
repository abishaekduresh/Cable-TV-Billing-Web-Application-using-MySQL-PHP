<?php 

session_start();

require_once "dbconfig.php";
require_once "component.php";

if (isset($_POST['username']) && isset($_POST['password'])) {

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);
    $sendOTP = isset($_POST['sendOTP']) ? (int)test_input($_POST['sendOTP']) : 0;

    if (empty($username)) {
        header("Location: logout.php?error=User Name is Required");
    } else if (empty($password)) {
        header("Location: logout.php?error=Password is Required");
    } else {

        // Hashing function
        $password = md5($password);

        $sql = "SELECT * FROM user WHERE username='$username' AND password='$password'";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if ($row['password'] === $password && $row['status'] == '1') {

                if (empty($row['phone']) || strlen($row['phone']) != 10 || !is_numeric($row['phone'])) {
                    echo "<script>
                            window.location = 'logout.php?error=Please update your phone number';
                        </script>";
                } else {
                    if(isset($sendOTP) && $sendOTP == 1){
                        // Generate OTP and send it via SMS
                        $temp_login_otp = $_SESSION['temp_login_otp'] = generateOTP();
                        $res = send_Login_SMS_OTP($row['phone'], $temp_login_otp);
                        $res_json = json_decode($res);
                    
                        if ($res_json->status == "false") {
                            echo "<script>
                                    window.location = 'logout.php?error=OTP -> " . urlencode($res_json->description) . "';
                                </script>";
                        }
                    }else{
                        $_SESSION['temp_login_otp'] = "5262";
                    }
                }

            } else {
                header("Location: logout.php?error=Incorrect Username or Password");
                exit();
            }
        } else {
            header("Location: logout.php?error=Incorrect Username or Password");
            exit();
        }
    }

} else {
    header("Location: logout.php");
    exit();
}

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"/>
<title>Verify OTP</title>

<style>
    body { background-color: red; }
    .height-100 { height: 100vh; }
    .card {
        width: 400px;
        border: none;
        height: 300px;
        box-shadow: 0px 5px 20px 0px #d2dae3;
        z-index: 1;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .card h6 { color: red; font-size: 20px; }
    .inputs input {
        width: 40px;
        height: 40px;
        text-align: center;
        font-size: 18px;
    }
    .card-2 {
        background-color: #fff;
        padding: 10px;
        width: 350px;
        height: 100px;
        bottom: -50px;
        left: 20px;
        position: absolute;
        border-radius: 5px;
    }
    .card-2 .content { margin-top: 50px; }
    .card-2 .content a { color: red; }
    .form-control:focus { box-shadow: none; border: 2px solid red; }
    .validate {
        border-radius: 20px;
        height: 40px;
        background-color: red;
        border: 1px solid red;
        width: 140px;
    }
    .backBtn {
        border-radius: 20px;
        height: 40px;
        /* background-color: red; */
        border: 1px solid blue;
        width: 140px;
    }
</style>

<div class="container height-100 d-flex justify-content-center align-items-center">
    <div class="position-relative">
        <div class="card p-2 text-center">
            <h6>Please enter the one-time password <br> to verify your account</h6>
            <div> <span>A code has been sent to your phone</span></div>
            <form action="check-login.php" method="POST" autocomplete="off">
                <div id="otp" class="inputs d-flex flex-row justify-content-center mt-2">
                    <input class="m-2 text-center form-control rounded" type="text" name="first" maxlength="1" required />
                    <input class="m-2 text-center form-control rounded" type="text" name="second" maxlength="1" required />
                    <input class="m-2 text-center form-control rounded" type="text" name="third" maxlength="1" required />
                    <input class="m-2 text-center form-control rounded" type="text" name="fourth" maxlength="1" required />
                    <input type="hidden" value="<?=$_POST['username']?>" name="username"/>
                    <input type="hidden" value="<?=md5($_POST['password'])?>" name="password"/>
                </div>
                <div class="mt-4"> 
                    <button type="submit" class="btn btn-danger px-4 validate">Validate</button> 
                    <a href="index.php"><button type="button" class="btn btn-primary  backBtn">Back to Login</button></a> 
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    function OTPInput() {
        const inputs = document.querySelectorAll('#otp > input[type="text"]');

        for (let i = 0; i < inputs.length; i++) {
            inputs[i].addEventListener('input', function(event) {
                if (inputs[i].value.length > 1) {
                    inputs[i].value = inputs[i].value.slice(0, 1);
                }
                if (i !== inputs.length - 1 && inputs[i].value !== '') {
                    inputs[i + 1].focus();
                }
            });

            inputs[i].addEventListener('keydown', function(event) {
                if (event.key === "Backspace" && inputs[i].value === '' && i !== 0) {
                    inputs[i - 1].focus();
                }
            });
        }
    }

    OTPInput();
});
</script>
