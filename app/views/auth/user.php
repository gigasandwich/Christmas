<section class="vh-75">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-10">
                <div class="card rounded-3 shadow-lg">
                    <div class="row g-0">
                        <!-- Left Login Form -->
                        <div class="col-lg-6">
                            <div class="card-body p-md-5 mx-md-4">
                                <div class="text-center">
                                    <h4 class="mt-1 mb-3 pb-1 text-primary">Login</h4>
                                </div>
                                <form action="/auth/check-user" method="post">
                                    <p class="lead">Please login to your account</p>
                                    <!-- Error message display -->
                                    <?php if (!empty($message)): ?>
                                        <div class="alert alert-secondary" role="alert">
                                            <?= htmlspecialchars($message) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="form-floating mb-3 mt-3">
                                        <input type="text" class="form-control" id="username"
                                            placeholder="Enter username" name="username">
                                        <label for="username">Username</label>
                                    </div>
                                    <div class="form-floating mt-3 mb-3">
                                        <input type="password" class="form-control" id="password"
                                            placeholder="Enter password" name="password">
                                        <label for="password">Password</label>
                                    </div>
                                    <div class="text-center pt-1 mb-3 pb-1">
                                        <button class="btn btn-danger btn-block fa-lg gradient-custom-2 mb-3">
                                            Log in
                                        </button>
                                        <a class="text-muted" href="#!">Forgot password?</a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center pb-4">
                                        <p class="mb-0 me-2">Don't have an account?</p>
                                        <a href="/auth/register" class="btn btn-outline-danger">Create new</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Right Gradient Section -->
                        <div class="col-lg-6 d-flex align-items-center gradient-bg text-white">
                            <div class="px-3 py-4 p-md-5 mx-md-4 text-center">
                                <h4 class="mb-4 fs-2 fw-bold lh-sm">Celebrate the Festive Spirit!</h4>
                                <p class="mb-0 fs-5 text-opacity-75">
                                    Welcome to Giftmas, the ultimate platform for spreading joy and love. Login to
                                    explore exciting features and make this Christmas unforgettable.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>