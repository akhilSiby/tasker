<?php
session_start();
// Redirect to index if logged in
if (isset($_SESSION['token'])) {
    header("location:./");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./asset/Style.css">

</head>

<body>
    <div class="d-flex justify-content-center align-items-center vh-100 ">
        <div class="col-5">
            <img src="./asset/image/login.jpg" class="img-fluid" alt="Login vector">
        </div>
        <form class="col-4" id="loginUserForm">
            <div id="loginMessage"></div>
            <div class="mb-3">
                <label class="form-label">Mobile</label>
                <input type="number" class="form-control cu-input" name="userMobile">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control cu-input" name="userPassword">
            </div>
            <input type="submit" value="Login" class="cu-button" name="loginUserFormSubmit">
            <div class="mt-3 text-center">
                <p>Create an account ? <a href="./register.php">Register</a> </p>
            </div>
        </form>
    </div>

    <script>
        $('#loginUserForm').on('submit', function(e) {
            // To prevent form from submiting
            e.preventDefault();
            var formData = new FormData(this);
            formData.append("loginUserFormSubmit", true);

            $.ajax({
                url: "./controls/authAction.php",
                type: "POST",
                dataType: "html",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status) {
                        $(location).attr('href', './');
                    } else {
                        $("#loginMessage").html(response.message);
                    }
                },
                error: function(error) {
                    alert("Error");
                    $("#loginMessage").html(JSON.parse(response).message);
                },
            });

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>