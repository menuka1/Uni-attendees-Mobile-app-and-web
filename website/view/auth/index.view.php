<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Connect Plus</title>
    <link rel="stylesheet" href="<?=STATICS?>vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="<?=STATICS?>vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?=STATICS?>vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="<?=STATICS?>css/style.css">
    <link rel="shortcut icon" href="<?=STATICS?>images/favicon.png" />
</head>
<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row flex-grow">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left p-5">
                        <div class="brand-logo">
                            <img src="<?=STATICS?>images/logo-dark.svg" alt="logo">
                        </div>
                        <h4>Hello! let's get started</h4>
                        <h6 class="font-weight-light">Sign in to continue.</h6>
                        <form action="" method="POST" class="pt-3">
                            <span><?=UTIL->promptError("login")?></span>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password">
                            </div>
                            <div class="mt-3">
                                <button type="submit" name="login" value="true" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >SIGN IN</button>
                            </div>
                            <!--
                            <div class="my-2 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <label class="form-check-label text-muted">
                                        <input type="checkbox" class="form-check-input"> Keep me signed in </label>
                                </div>
                                <a href="#" class="auth-link text-black">Forgot password?</a>
                            </div>
                            <div class="mb-2">
                                <button type="button" class="btn btn-block btn-facebook auth-form-btn">
                                    <i class="mdi mdi-facebook mr-2"></i>Connect using facebook </button>
                            </div>
                            <div class="text-center mt-4 font-weight-light"> Don't have an account? <a href="register.html" class="text-primary">Create</a>
                            </div>-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?=STATICS?>vendors/js/vendor.bundle.base.js"></script>
<script src="<?=STATICS?>js/off-canvas.js"></script>
<script src="<?=STATICS?>js/hoverable-collapse.js"></script>
<script src="<?=STATICS?>js/misc.js"></script>
</body>
</html>