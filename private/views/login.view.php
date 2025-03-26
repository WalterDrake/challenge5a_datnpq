<?php $this->view('includes/header'); ?>

<title>Login Page</title>
<link rel="stylesheet" href="<?= ROOT ?>/css/style.css">
</head>

<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="login-wrap py-5">
                        <div class="img d-flex align-items-center justify-content-center" style="background-image: url(<?= ROOT ?>/assets/students.png);"></div>
                        <h3 class="text-center mb-0">Welcome</h3>

                        <!-- Display errors -->
                        <?php if (isset($errors) && count($errors) > 0): ?>
                            <div class="alert alert-danger mt-3">
                                <ul>
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <p class="text-center">Sign in by entering the information below</p>
                        <form action="login" class="login-form" method='POST'>
                            <div class="form-group">
                                <div class="icon d-flex align-items-center justify-content-center"><i class="fa-solid fa-user"></i></div>
                                <input type="text" class="form-control" placeholder="Username"  name='username' required>
                            </div>
                            <div class="form-group">
                                <div class="icon d-flex align-items-center justify-content-center"><i class="fa-solid fa-lock"></i></div>
                                <input type="password" class="form-control" placeholder="Password" name='password' required>
                            </div>
                            <!-- <div class="form-group d-md-flex">
                                <div class="w-100 text-md-right">
                                    <a href="#">Forgot Password</a>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <button type="submit" class="btn form-control btn-primary rounded submit px-3">Get Started</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="<?= ROOT ?>/js/jquery.min.js"></script>
    <script src="<?= ROOT ?>/js/popper.js"></script>
    <script src="<?= ROOT ?>/js/bootstrap.min.js"></script>
    <script src="<?= ROOT ?>/js/main.js"></script>

    <?php $this->view('includes/footer'); ?>