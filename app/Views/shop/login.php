
<section class="login">
    <div class="container">
        <div class = "row">
            <div class = "col-md-12">
                <div class="login-container align-center">
                <h2 class ="pt-4">Login</h2>
                    <form class="userLoginForm" action="<?= base_url('user/userAuthenticate') ?>" method="post" py-5>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary my-2">Login</button>
                    </form>
                    <a href="<?= base_url('user/loginWithGoogle') ?>" class="btn btn-danger">
                        <i class="fab fa-google"></i> Login with Google
                    </a>
                    <p>Don't have an account? <a href="<?= base_url('user/register') ?>">Register here</a></p>
                </div>  
            </div>
        </div>
    </div>   
</section>
<?php if (!empty($errorMessage)): ?>
    <div class="alert alert-danger">
        <?= esc($errorMessage) ?>
    </div>
<?php endif; ?>