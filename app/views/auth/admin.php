<section class="vh-75">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-10">
                <div class="card rounded-5 shadow-lg border-0 overflow-hidden">
                    <div class="row g-0">
                        <!-- Admin Login Form -->
                        <div class="col-lg-6">
                            <div class="card-body p-md-5 mx-md-4">
                                <div class="text-center">
                                    <h4 class="mt-1 mb-3 pb-1 text-danger">Admin Login</h4>
                                </div>
                                <form action="/auth/check-admin" method="post">
                                    <p class="lead">Enter your admin credentials to access the portal</p>
                                    <div class="form-floating mb-3 mt-3">
                                        <input type="text" class="form-control" id="username"
                                            placeholder="Enter admin username" name="username" value="poyz">
                                        <label for="username">Username</label>
                                    </div>
                                    <div class="form-floating mt-3 mb-3">
                                        <input type="password" class="form-control" id="password"
                                            placeholder="Enter admin password" name="password" value="123">
                                        <label for="password">Password</label>
                                    </div>
                                    <!-- Error message display -->
                                    <?php if (!empty($message)): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?= htmlspecialchars($message) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="text-center pt-1 mb-3 pb-1">
                                        <button class="btn btn-danger btn-block fa-lg gradient-custom-2 w-75 mb-3">
                                            Log in
                                        </button>   
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Special Gradient Section -->
                        <div class="col-lg-6 d-flex align-items-center gradient-bg-special text-white">
                            <div class="px-3 py-4 p-md-5 mx-md-4 text-center">
                                <h1 class="mb-4 display-4 fw-bold">Admin Portal</h1>
                                <p class="mb-0 fs-5 text-opacity-75">
                                    Welcome to the Giftmas admin panel. Manage users, gifts, and transactions to ensure
                                    a joyful Christmas for everyone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>