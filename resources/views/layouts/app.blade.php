<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | BloodConnect Portal</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    {{-- <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="">
    <div class="body">
        <header>
            {{-- <div class="logo">🩸 BloodConnect</div> --}}
            {{-- <div class="logo"><img src="{{asset('assets/img/logo.png')}}" alt=""></div>
    <nav>
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('registration') }}">Register</a></li>
        <li><a href="{{ route('login') }}">Log In</a></li>
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('conversation') }}">Conversation</a></li>
      </ul>
    </nav> --}}
            <div class="top-header">
                <div class="container">
                    <div class="flex">
                        <div class="left">
                            <a href="mailto:info@asrij.in">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                                    <path
                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                    </path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                                info@asrij.in</a>
                            <a href="tel:8653846646">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                    </path>
                                </svg>
                                865 384 6646</a>
                        </div>
                        <div class="right">
                            <a href="#">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="#">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                            <a href="#">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="navbar navbar-light">
                <div class="container">
                    {{-- <a class="navbar-brand" href="#">Navbar</a> --}}
                    <div class="logo"><a href="{{ route('home') }}"><img src="{{ asset('assets/img/logo4.png') }}"
                                alt=""></a></div>
                    <div class="d-flex align-items-center">
                        @guest
                            <a href="#exampleModal" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                class="btn btn-primary d-none d-md-block">
                                Login
                            </a>
                        @endguest

                        @auth
                            @if (auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="btn d-none d-md-block"
                                    style="font-size:30px;padding:8px;">
                                    <i class="fa-regular fa-circle-user"></i>
                                </a>
                            @else
                                <a href="{{ route('profile') }}" class="btn d-none d-md-block"
                                    style="font-size: 30px;padding:8px;">
                                    <i class="fa-regular fa-circle-user"></i>
                                </a>
                            @endif
                        @endauth
                        <button class="navbar-toggler" onclick="handleMenu()" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class=""></span>
                        </button>
                    </div>
                    {{-- <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="#">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="#">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('registration') ? 'active' : '' }}" href="{{ route('registration') }}">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                    </li>
                   </ul>                
                </div> --}}
                </div>
            </nav>
        </header>

        <main>
            @yield('content')
        </main>

        {{-- <footer>
    <p>© 2025 BloodConnect Portal | Designed with ❤️</p>
    <div class="social">
      <a href="#"><i class="fab fa-facebook"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
  </footer> --}}
        <!-- ===== FOOTER ===== -->
        <footer class="footer py-4 text-white">
            <div class="container">
                {{-- <p class="mb-3 mb-md-2 fs-6">
                © 2025 <strong>BloodConnect Portal</strong> | Designed with ❤️
            </p>
            <div class="social d-flex justify-content-center gap-4">
                <a href="#" class="text-white fs-4 social-link"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-white fs-4 social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white fs-4 social-link"><i class="fab fa-instagram"></i></a>
            </div> --}}
                <div class="row">
                    <div class="col-md-5">
                        <div class="logo mb-3"><a href="{{ route('home') }}"><img
                                    src="{{ asset('assets/img/logo4.png') }}" alt=""></a></div>
                        <p>
                            Safe and voluntary blood donation.
                            Connecting donors with patients in need.
                            Committed to saving lives every day.
                        </p>
                    </div>
                    <div class="col-md-3">
                        <ul class="links">
                            <li><i class="fa-solid fa-angles-right"></i> <a href="{{ route('home') }}">Home</a></li>
                            <li><i class="fa-solid fa-angles-right"></i> <a href="#">About Us</a></li>
                            <li><i class="fa-solid fa-angles-right"></i> <a href="#">Contact Us</a></li>
                            <li><i class="fa-solid fa-angles-right"></i> <a href="#registration-section">Register</a>
                            </li>
                            <li><i class="fa-solid fa-angles-right"></i> <a href="#exampleModal"
                                    data-bs-toggle="modal" data-bs-target="#exampleModal">Login</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <ul>
                            <li>
                                <i class="fa-solid fa-envelope"></i> <a
                                    href="mailto:contact@asrij.com">contact@asrij.com</a>
                            </li>
                            <li>
                                <i class="fa-solid fa-phone"></i> <a href="tel:911234567890">+911234567890</a>
                            </li>
                            <li>
                                <a href="#" class="text-white fs-4 social-link"><i
                                        class="fab fa-facebook-f"></i></a>
                                <a href="#" class="text-white fs-4 social-link"><i
                                        class="fab fa-instagram"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#donateModal">Donate Now</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <div class="menu">
        <button class="cross" onclick="handleMenu()"><i class="fa-solid fa-xmark"></i></button>
        <ul class="navbar-nav mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                    href="{{ route('home') }}">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="#">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="#">Contact Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('registration') ? 'active' : '' }}"
                    href="{{ route('registration') }}">Register</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-bs-toggle="modal"
                data-bs-target="#donateModal">Donate Now</a>
            </li>
        </ul>
    </div>
    <!-- Bootstrap 5 Modal with Floating Labels -->
    <div class="modal fade login" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Login Form -->
                    <form autocomplete="off" id="login" action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="login" autocomplete="off"
                                    id="email" placeholder="name@example.com" required>
                                <label for="email">Email address or Mobile Number</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="password" name="password" class="form-control" autocomplete="off"
                                    id="password" placeholder="Password" required>
                                <label for="password">Password</label>
                                <button onclick="togglePassword()" type="button"><i
                                        class="fa-solid fa-eye-slash"></i></button>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <div class="text-end">
                            <a href="#" class="color-primary" id="forgotPasswordLink">Forgot password?</a>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="login()">Login</button>
                </div>
                {{-- <div class="or">Or</div>
                <a href="#" class="text-center">Do you have an account?</a> --}}
            </div>
        </div>
    </div>
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordLabel">Forgot Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="forgotPasswordForm" action="" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="forgotEmail" class="form-label">Enter your email address</label>
                            <input type="email" class="form-control" id="forgotEmail" name="email"
                                placeholder="name@example.com" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Send Reset Link</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="donateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- Header with Gradient -->
                <div class="modal-header text-white border-0 py-4" style="">
                    <div>
                        <h3 class="mb-1 fw-bold">Make a Difference</h3>
                        <p class="mb-0 opacity-75">
                            Your generosity helps us change lives
                        </p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body py-2 px-4">
                    <form action="{{ route('donate.store') }}" method="POST">
                        @csrf

                        <div class="row g-4">

                            <!-- Donation Amount (Highlighted) -->
                            <div class="col-12">
                                <label class="form-label fw-semibold fs-5">
                                    Donation Amount
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="amount" class="form-control" min="1"
                                        required>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control form-control-lg" required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control form-control-lg" required>
                            </div>

                            <!-- Donation Type -->
                            <div class="col-md-12">
                                <label class="form-label">Donation Type</label>
                                <select name="donation_type" class="form-select form-select-lg" required>
                                    <option value="">Select donation type</option>
                                    <option value="one-time">One-time</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>

                            <!-- Message -->
                            <div class="col-md-12">
                                <label class="form-label">Message (Optional)</label>
                                <textarea name="message" rows="3" class="form-control form-control-lg"></textarea>
                            </div>

                        </div>

                        <!-- Footer CTA -->
                        <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                            <span class="text-muted fs-6">
                                🔒 100% Secure Donation
                            </span>

                            <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill text-white">
                                Donate Now
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>


    <script>
        function login() {
            document.getElementById('login').submit();
            //   const email = document.getElementById('email').value;
            //   const password = document.getElementById('password').value;
            //   const rememberMe = document.getElementById('rememberMe').checked;

            //   // Simple form validation
            //   if (email && password) {
            //     alert(`Logged in with: ${email}\nRemember me: ${rememberMe ? 'Yes' : 'No'}`);
            //   } else {
            //     alert('Please enter both email and password.');
            //   }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs/dist/purecounter_vanilla.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    @yield('scripts')
    <script>
        function togglePassword() {
            let input = document.getElementById('password');
            let eye = document.querySelector('#password ~ button i');
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            } else {
                input.type = 'password';
                eye.classList.add('fa-eye-slash');
                eye.classList.remove('fa-eye');
            }
        }
        document.getElementById('forgotPasswordLink').addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default link behavior

            // Hide login modal
            const loginModal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
            if (loginModal) loginModal.hide();

            // Show forgot password modal
            const forgotModalEl = document.getElementById('forgotPasswordModal');
            const forgotModal = new bootstrap.Modal(forgotModalEl);
            forgotModal.show();
        });
    </script>
</body>

</html>
