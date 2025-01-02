<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <link rel="stylesheet" href="/assets/framework/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/framework/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/error.css">
</head>
<body>
    <div class="container error-container">
        <div class="error-box shadow-lg">
            <h1 class="error-title text-danger">Something went wrong!</h1>
            <p class="error-message"><?= htmlspecialchars($message); ?></p>
            <a href="/" class="btn btn-back px-4 py-2">Back to home</a>
        </div>
    </div>
</body>
</html>
