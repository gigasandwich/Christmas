<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Christmas Gift Site</title>
    <!-- Framework css -->
    <link rel="stylesheet" href="/assets/framework/css/bootstrap.min.css">
    <!-- Custom css -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/home.css">
</head>

<body>
    <!-- Navbar -->
    <header class="py-2">
        <nav class="navbar navbar-expand-lg shadow">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand d-flex" href="/">
                    <?php include './layouts/logo.php' ?>
                </a>
                <!-- Toggle Button for Mobile View -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Navbar Links -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto d-flex gap-2 align-items-center">
                        <li class="nav-item me-3">
                            <?php include './layouts/btn-theme.php'; ?>
                        </li>
                        <li class="nav-item mb-2 mb-sm-0">
                            <a class="btn btn-outline-success w-100 me-3" href="auth/user">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-success w-100" href="auth/register">Register</a>
                        </li>
                    </ul>
            </div>

        </nav>
    </header>

    <section class="hero container py-5">
        <div class="row justify-content-center align-items-center gap-5">
            <!-- Text Section -->
            <div class="col-md-5 text-center">
                <h1 class="display-4 fw-bold">Welcome to Giftmas!</h1>
                <p class="lead">Experience the joy of random Christmas gifts for your loved ones. Register now and start your holiday journey!</p>
                <a href="auth/register" class="btn btn-success btn-lg mt-3">Get Started</a>
            </div>

            <!-- Carousel Section -->
            <div class="col-md-6 text-bg-danger rounded p-2" id="landingCarousel">
                <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <!-- Slide 1: User Registration -->
                        <div class="carousel-item active">
                            <img src="/assets/img/christmas/undraw_christmas-mode_nebj.svg" class="d-block w-100 img-fluid"
                                alt="User Registration">
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-3 rounded">
                                <h5 class="text-white">Easy Registration</h5>
                                <p class="text-light">Create an account to start your journey and unlock exciting
                                    Christmas gift suggestions.</p>
                            </div>
                        </div>

                        <!-- Slide 2: Gift Suggestions -->
                        <div class="carousel-item">
                            <img src="/assets/img/christmas/undraw_gifts_0twc.svg" class="d-block w-100 img-fluid"
                                alt="Gift Suggestions">
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-3 rounded">
                                <h5 class="text-white">Tailored Gift Suggestions</h5>
                                <p class="text-light">Get personalized gift ideas for boys, girls, or neutral
                                    preferences—perfect for every child!</p>
                            </div>
                        </div>

                        <!-- Slide 3: Budget Management -->
                        <div class="carousel-item">
                            <img src="/assets/img/christmas/undraw_santa-claus_1z1i.svg"
                                class="d-block w-100 img-fluid" alt="Budget Management">
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-3 rounded">
                                <h5 class="text-white">Smart Budget Control</h5>
                                <p class="text-light">Manage your deposit wisely and ensure the total gift cost fits
                                    within your budget.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Carousel Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

            </div>
        </div>
    </section>


    <!-- Framework Scripts -->
    <script src="/assets/framework/js/jquery-3.7.1.min.js"></script>
    <script src="/assets/framework/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Scripts -->
    <script src="/assets/js/theme.js"></script>
</body>

</html>