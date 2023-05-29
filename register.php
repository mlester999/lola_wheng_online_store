<?php require_once('config.php'); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $_settings->info('title') != false ? $_settings->info('title').' | ' : '' ?><?php echo $_settings->info('name') ?></title>
        <link rel="icon" href="<?php echo validate_image($_settings->info('logo')) ?>" />

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo base_url; ?>plugins/fontawesome-free/css/all.min.css">
        
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="<?php echo base_url; ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url; ?>dist/css/adminlte.min.css">
    
        <!-- Toastr -->
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"/>
    
        <!-- Sweet Alert -->
        <link rel="stylesheet" href="<?php echo base_url; ?>plugins/sweetalert2/sweetalert2.min.css">
    </head>

    <body class="hold-transition register-page">
        <div class="register-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="index.php" class="h1"><b>Lola Weng's</b> IMS</a>
                </div>
                <div class="card-body">
                    <p class="login-box-msg h3">Create an Account</p>
                    <form action="" method="post" id="register-account">
                        <div class="input-group">
                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First name">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-1 mb-3 text-danger text-sm" id="firstNameError"></p>

                        <div class="input-group">
                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last name">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-1 mb-3 text-danger text-sm" id="lastNameError"></p>
                        <input type="hidden" name="full_name" id="full_name" value="" class="form-control" placeholder="Last name">


                        <div class="input-group">
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-1 mb-3 text-danger text-sm" id="usernameError"></p>

                        <div class="input-group">
                            <input type="text" name="contact_number" id="contact_number" class="form-control" placeholder="Contact Number">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-phone"></span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-1 mb-3 text-danger text-sm" id="contactNumberError"></p>

                        <div class="input-group">
                            <input type="text" name="home_address" id="home_address" class="form-control" placeholder="Home Address">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-address-book"></span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-1 mb-3 text-danger text-sm" id="homeAddressError"></p>


                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-1 mb-3 text-danger text-sm" id="passwordError"></p>

                        <input type="hidden" name="date_added" id="date_added" value="" />
                        <input type="hidden" name="type" id="type" value="4" />
                        <input type="hidden" name="unique_key" id="unique_key" value="" />
                        <input type="hidden" name="is_active" id="is_active" value="1" />

                    </form>
                        <div class="text-center">
                            <button class="btn btn-primary btn-block" form="register-account">Register</button>
                        </div>
                    
                    <br>
                    <a href="login.php" class="text-center">I already have an account</a>
                </div>
            </div>
        </div>

        <?php require_once('includes/footer.php'); ?>

        <script>
            function generateRandomString(length) {
                var result = '';
                var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                var charactersLength = characters.length;
                for (var i = 0; i < length; i++) {
                    result += characters.charAt(Math.floor(Math.random() * charactersLength));
                }
                return result;
            }

            function getCurrentDateTime() {
            // Create a new Date object for the current date and time
            var now = new Date();

            // Get the year, month, and day from the Date object
            var year = now.getFullYear();
            var month = ('0' + (now.getMonth() + 1)).slice(-2);
            var day = ('0' + now.getDate()).slice(-2);

            // Get the hours, minutes, and seconds from the Date object
            var hours = ('0' + now.getHours()).slice(-2);
            var minutes = ('0' + now.getMinutes()).slice(-2);
            var seconds = ('0' + now.getSeconds()).slice(-2);

            // Combine the date and time strings in the desired format
            var datetime = year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;

            // Return the resulting string
            return datetime;
            }

            $('#register-account').submit(function(e){
                e.preventDefault();
                start_loader()

                var inputFirstName = $('input#first_name').val();
                var inputLastName = $('input#last_name').val();
                var inputFullName = $('input#full_name').val(inputFirstName + ' ' + inputLastName);
                var inputUsername = $('input#username').val();
                var inputContactNumber = $('input#contact_number').val();
                var inputAddress = $('input#home_address').val();
                var inputPassword = $('input#password').val();
                var inputDateTime = $('input#date_added').val(getCurrentDateTime());
                var inputUniqueKey = $('input#unique_key').val(generateRandomString(50));
                // var inputConfirmPassword = $('input#confirm_password').val();

                if(!inputFirstName) {
                    $('#firstNameError').text('This field is required');
                } else {
                    $('#firstNameError').text('');
                }

                if(!inputLastName) {
                    $('#lastNameError').text('This field is required');
                } else {
                    $('#lastNameError').text('');
                }

                if(!inputUsername) {
                    $('#usernameError').text('This field is required');
                } else {
                    $('#usernameError').text('');
                }

                if(!inputContactNumber) {
                    $('#contactNumberError').text('This field is required');
                } else {
                    $('#contactNumberError').text('');
                }

                if(!inputAddress) {
                    $('#homeAddressError').text('This field is required');
                } else {
                    $('#homeAddressError').text('');
                }

                if(!inputPassword) {
                    $('#passwordError').text('This field is required');
                } else {
                    $('#passwordError').text('');
                }

                // if(!inputConfirmPassword) {
                //     $('#confirmPasswordError').text('This field is required');
                // } else {
                //     $('#confirmPasswordError').text('');
                // }

                if(inputFirstName && inputLastName && inputUsername && inputContactNumber && inputAddress && inputPassword) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        heightAuto: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, register now!'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                    url:_base_url_+'classes/Users.php?f=save',
                                    data: new FormData($(this)[0]),
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    method: 'POST',
                                    type: 'POST',
                                    success:function(resp){
                                        if(resp ==1  ){
                                            // location.href='./?page=accounts';
                                            sessionStorage.setItem("showmsg", "1");
                                            location.replace('./login.php');
                                            // alert_toast("An error occured.",'error');
                                        }else{
                                            $('#msg').html('<div class="alert alert-danger">Username already exist</div>')
                                            end_loader();
                                        }
                                    }
                                })
                            }
                        })
                }
            })    
        </script>
    </body>
</html>
