<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <title>Document</title>
    <style>
        .register-body {
            background: linear-gradient(135deg, #1E90FF, #00BFFF);

            color: white;
            min-height: 100vh;
            /* Ensures the blue background covers the full height */
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .register-body .card {
            width: 500px;
            max-width: 500px;
            margin: 0 auto;
            padding: 30px 40px  ;
        }
        .register-body h2{
            font-size: 34px;
        }
        .register-body .card-body {
            padding: 0px;
        }
    </style>
</head>

<body>

    <!-- Registration Form -->
    <div class="register-body">
        <section class="mt-5 py-5 py-md-5">
            <div class="container">

                <div class="card border border-light-subtle rounded-3 shadow-sm">
                    <div class="card-body">
                        <div class="text-center mb-3"></div>
                        <h2 class="  text-center mb-5">
                            Register Here
                        </h2>
                        <form action="">
                            <div class="row gy-2 overflow-hidden">
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="firstName" id="firstName"
                                            placeholder="First Name" required>
                                        <label for="firstName" class="form-label">First Name</label>
                                    </div>
                                </div>

                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="name@example.com" required>
                                    <label for="email" class="form-label">Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" name="password" id="password" value=""
                                        placeholder="Password" required>
                                    <label for="password" class="form-label">Password</label>
                                </div>
                            </div>
                    </div>
                    <div class="col-12">
                        <div class="d-grid my-3">
                            <button class="btn btn-primary btn-lg" type="submit">Sign up</button>
                        </div>
                    </div>
                    <div class="col-12">
                        <p class="m-0 text-secondary text-center">Already have an account? <a href="login.php"
                                class="link-primary text-decoration-none">Sign in</a></p>
                    </div>
                    </form>
                </div>
            </div>
    </div>
    </section>
    </div>

</body>

</html>