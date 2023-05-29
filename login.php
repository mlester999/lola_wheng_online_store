<?php require_once('config.php'); ?>

<!DOCTYPE html>
<html lang="en">
    
    <?php require_once('includes/header.php'); ?>
    
    <body class="hold-transition login-page">
        <!-- <script>
            start_loader();
        </script> -->


        <div class="login-box" style="width: 500px;">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="index.php" class="h1"><b>Lola Wheng's Transfi Online Store</a>
                </div>
                <div class="card-body">
                    <div class="text-center h4 d-none">
                        Inventory Management System <br>& Data Analytics
                    </div>

                    <p class="login-box-msg h3">Login to continue</p>

                    <form action="" method="post" id="login-frm">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="username" autofocus placeholder="Username">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-key"></span>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="row">
                            <!-- <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="remember">
                                    <label for="remember">
                                        Remember Me
                                    </label>
                                </div>
                            </div> -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                                <!-- <button type="submit" class="btn btn-primary  bg-gradient-teal border-0 btn-block">Sign In</button> -->
                            </div>
                        </div>
                    </form>

                    <br>
                    <p class="mb-0">
                        <a href="register.php" class="text-center">Register a new membership</a>
                    </p>
                </div>
            </div>
        </div>

        <?php require_once('includes/footer.php'); ?>

        <script>
            $(document).ready(function(){
            if(sessionStorage.getItem("showmsg")=='1'){
            toastr.success('Account Registration Successfully');
            sessionStorage.removeItem("showmsg");
            }
            });
        </script>

        <!-- <script>
            $(document).ready(function(){
                end_loader();
            });
        </script> -->
        
    </body>
</html>