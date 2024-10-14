<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        .login {
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            min-height: 100vh;
        }

        .login .wrap-login {
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
            background-color: rgba(255, 255, 255, 0.8);
            box-shadow: 10px 10px 4px 0 rgba(0, 0, 0, 0.25);
            padding: 60px;
            border-radius: 10px;
            overflow: hidden;
        }

        .login .wrap-login h4 {
            font-weight: bold;
            margin: 0;
        }

        .login .wrap-login p {
            font-size: 1em;
            font-weight: 500;
            margin: 10px 0 15px 0;
            color: #666666;
        }

        .login .wrap-login .form-control {
            font-size: 1em;
            background-color: #E0E8F9;
            border: none !important;
            border-radius: 10px;
            padding: 30px 15px;
            color: #666666;
        }

        .login .wrap-login .btn {
            width: 100%;
            padding: 15px 15px;
            border-radius: 10px;
            background-color: #445DCC;
            font-weight: bold;
            color: #fff;
        }

        @media (max-width: 991px) {
            .login .wrap-login .svg-boy {
                display: none;
            }


            .login .wrap-login {
                padding: 40px 0 !important;
            }

        }
    </style>
</head>

<body>

    <div class="container-fluid login d-flex align-items-center justify-content-center"
        style="background-image: url('{{ asset('assets/images/login/bg-login.png') }}')">

        <div
            class="col-lg-8 col-11 mx-auto wrap-login"style="background-image: url('{{ asset('assets/images/login/right-login.png') }}')">
            <div class="row m-0">
                <div class="col-lg-5 the-form my-auto">
                    <h4>Selamat Datang</h4>
                    <p>Selamat datang kembali admin ku tersayang, jaga kesehatan yaaa!!!</p>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                required>
                        </div>

                        <div class="form-group m-0">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required>
                        </div>

                        <div class="form-check py-3 pl-4">
                            <input class="form-check-input" type="checkbox" id="coding" name="interests[]"
                                value="coding">
                            <label class="form-check-label" for="coding">
                                Remember me
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">Masuk</button>
                    </form>
                </div>
                <div class="col-lg-7 my-auto">
                    <center><img src="{{ asset('assets/images/login/person.svg') }}" alt=""
                            class="img-fluid svg-boy">
                    </center>
                </div>
            </div>
        </div>
    </div>

</body>

</html>



{{-- <!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>
    <h1>Login</h1>
    @if ($errors->any())
    <div>
        <strong>{{ $errors->first() }}</strong>
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">Login</button>
    </form>
</body>

</html> --}}
