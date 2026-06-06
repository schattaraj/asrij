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
    <div class="loader-container" id="loader">
        <div class="loader"></div>
    </div>
    <div class="body">
@unless(Route::is('login'))
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
                            <a href="tel:7048115559">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                    </path>
                                </svg>
                                704 811 5559</a>
                        </div>
                        <div class="right">
                            <a href="https://www.facebook.com/profile.php?id=61586204187656&mibextid=rS40aB7S9Ucbxw6v" target="_blank">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com/asrij_foundation/" target="_blank">
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
                    <div class="logo"><a href="{{ route('home') }}"><img src="{{ asset('assets/img/logo.png') }}"
                                alt=""></a></div>
                    <div class="d-flex align-items-center">
                        <!-- Login Button -->
                        <a href="#loginModal" data-bs-toggle="modal" data-bs-target="#loginModal" id="loginBtn"
                            class="btn btn-primary d-none">{{-- d-md-block --}}
                            Login
                        </a>

                        <!-- Profile Button -->
                        {{-- <a href="#" id="profileBtn" class="btn d-none"
                            style="font-size:30px;padding:8px;">
                            <i class="fa-regular fa-circle-user"></i>
                        </a> --}}
                        <div class="dropdown profile-dropdown">
                            <a href="#" id="profileBtn" class="btn dropdown-toggle p-0 border-0 d-none"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-user" style="font-size:32px;"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end p-0 shadow-lg border-0">

                                <!-- Header -->
                                <div class="dropdown-header py-3 border-bottom">
                                    <strong id="user_name"></strong><br>
                                    <small class="text-muted" id="user_mobile"></small>
                                </div>

                                <!-- Menu -->
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('profile') }}">
                                    <i class="fa-regular fa-user me-2"></i> My Profile
                                </a>

                                <a class="dropdown-item d-flex align-items-center"
                                    href="{{ route('bloodDonations') }}">
                                    <i class="fa-solid fa-hand-holding-heart me-2"></i>Donation Activity
                                </a>

                                <a class="dropdown-item d-flex align-items-center"
                                    href="{{ route('bloodRequests') }}">
                                    <i class="fa-solid fa-list me-2"></i> My Requests
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item text-danger d-flex align-items-center" href="#"
                                    onclick="logout()">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                                </a>
                            </div>
                        </div>
                        {{-- @guest
                            <a href="#loginModal" data-bs-toggle="modal" data-bs-target="#loginModal"
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
                                <a href="{{ auth()->user()->roles === 'admin' ? route('admin.dashboard') : route('profile') }}"
                                    class="btn d-none d-md-block" style="font-size: 30px;padding:8px;">
                                    <i class="fa-regular fa-circle-user"></i>
                                </a>
                            @endif
                        @endauth --}}
                        <button class="navbar-toggler" onclick="handleMenu()" type="button"
                            data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent" aria-expanded="false"
                            aria-label="Toggle navigation">
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
 @endunless
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
  @unless(Route::is('login'))
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
                        <div class="logo mb-3"><a href="{{ route('home') }}"><img style="max-width: 100%;height:90px;object-fit:contain"
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
                            <li><i class="fa-solid fa-angles-right"></i> <a href="{{ route('about') }}">About Us</a></li>
                            <li><i class="fa-solid fa-angles-right"></i> <a href="{{ route('contact') }}">Contact Us</a></li>
                            <li><i class="fa-solid fa-angles-right"></i> <a href="{{ route('home') }}#registration-section">Register</a>
                            </li>
                            <li><i class="fa-solid fa-angles-right"></i> <a href="#loginModal" data-bs-toggle="modal"
                                    data-bs-target="#loginModal">Login</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <ul>
                            <li>
                                <i class="fa-solid fa-envelope"></i> <a
                                    href="mailto:contact@asrij.com">contact@asrij.com</a>
                            </li>
                            <li>
                                <i class="fa-solid fa-phone"></i> <a href="tel:917048115559">+917048115559</a>
                            </li>
                            <li>
                                <a href="https://www.facebook.com/profile.php?id=61586204187656&mibextid=rS40aB7S9Ucbxw6v"
                                    class="text-white fs-4 social-link" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://www.instagram.com/asrij_foundation/" class="text-white fs-4 social-link" target="_blank"><i
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
 @endunless        
    </div>
    <div class="menu">
        <button class="cross" onclick="handleMenu()"><i class="fa-solid fa-xmark"></i></button>
        <ul class="navbar-nav mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                    href="{{ route('home') }}">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link openRegister" href="#">Sign Up</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#donateModal">Donate
                    Now</a>
            </li>
        </ul>
    </div>
    <!-- Bootstrap 5 Modal with Floating Labels -->
    <div class="modal fade register login" id="registerModal" tabindex="-1" aria-labelledby="registrationModal"
        aria-hidden="true">
        <div class="modal-dialog" style="max-width: 600px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Sign Up</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Login Form -->
                    <form autocomplete="off" id="register" action="" method="post">
                        @csrf
                        <input type="hidden" id="register_latitude" class="latitude" name="latitude">
                        <input type="hidden" id="register_longitude" class="longitude" name="longitude">
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="mobile" autocomplete="off"
                                    id="mobile" placeholder="9112345678" maxlength="10" required>
                                <label for="mobile">Mobile Number</label>
                                <!-- ✅ Tick Icon -->
                                <i id="mobileVerifiedIcon"
                                    class="fa-solid fa-circle-check text-success position-absolute d-none"
                                    style="right: 15px; top: 50%; transform: translateY(-50%); font-size: 18px;"></i>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="button" id="sendOtpBtn" class="btn btn-primary w-100"
                                onclick="sendRegisterOtp(this)">Send OTP</button>
                        </div>
                        <div class="mb-3 d-none" id="otpRegistraionSection">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="otp" placeholder="Enter OTP">
                                <label for="otp">Enter OTP</label>
                            </div>
                            <button type="button" id="verifyOtpBtn" class="btn btn-success w-100 mt-2"
                                onclick="verifyRegisterOtp()">Verify OTP</button>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="name" autocomplete="off"
                                    id="name" placeholder="Name" required>
                                <label for="email">Name</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="dob" autocomplete="off"
                                    id="dob" placeholder="Date of Birth" required>
                                <label for="dob">Date of Birth</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <select class="form-control" name="gender" id="gender" required>
                                    <option value="" disabled selected>Select Your Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <label for="gender">Gender</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <select class="form-control" name="blood_group" id="blood_group" required>
                                    <option value="" disabled selected>Select Your Blood Group</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                                <label for="blood_group">Blood Group</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <div class="form-floating flex-grow-1">
                                    <input type="text" class="form-control" name="address" autocomplete="off"
                                        id="register_address" placeholder="Address" readonly required>
                                    <label for="address">Address</label>
                                </div>
                                <button type="button" class="btn btn-outline-danger open-location-modal"
                                    data-bs-toggle="modal" data-bs-target="#locationModal"
                                    data-location-input="register_address" data-lat="register_latitude"
                                    data-lng="register_longitude">
                                    Change
                                </button>
                            </div>
                        </div>
                        {{-- <div class="mb-3">
                            <div class="form-floating">
                                <input type="password" name="password" class="form-control" autocomplete="off"
                                    id="password" placeholder="Password" required>
                                <label for="password">Password</label>
                                <button onclick="togglePassword()" type="button"><i
                                        class="fa-solid fa-eye-slash"></i></button>
                            </div>
                        </div> --}}
                    </form>
                </div>
                <div class="modal-footer justify-content-center flex-column">
                    <button type="button" class="btn btn-primary" onclick="registerUser()">Submit</button>
                    <div class="text-center mt-3">
                        <p class="mb-1">Already have an account?</p>
                        <a href="#loginModal" id="openLogin">Login</a>
                    </div>
                </div>
                {{-- <div class="or">Or</div>
            <a href="#" class="text-center">Do you have an account?</a> --}}
            </div>
        </div>
    </div>
    <div class="modal fade login" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Login Form -->
                    <form autocomplete="off" id="login" action="{{ route('login') }}" method="post">
                        @csrf
                        <div id="mobileStep">
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="mobileNumber"
                                        placeholder="name@example.com" required>
                                    <label for="mobileNumber">Mobile Number</label>
                                </div>
                            </div>

                            <button class="btn btn-primary w-100" type="button" onclick="sendOtp()">
                                Send OTP
                            </button>

                            <div class="text-center mt-3">
                                <a href="#" id="switchToPassword1" class="small text-decoration-none d-none">
                                    Login with Password
                                </a>
                            </div>

                        </div>
                        <!-- OTP SECTION (DEFAULT VISIBLE) -->
                        <div id="otpSection" class="d-none">

                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">Enter 6-digit OTP</small>
                                <a href="#" id="switchToPassword" class="small text-decoration-none d-none">
                                    Login with Password
                                </a>
                            </div>

                            <p class="small text-muted mb-2" id="otpSentText"></p>
                            <!-- OTP INPUT BOXES -->
                            <div class="d-flex justify-content-between otp-inputs mb-3">
                                <input type="text" maxlength="1" class="form-control text-center otp-box me-1">
                                <input type="text" maxlength="1" class="form-control text-center otp-box me-1">
                                <input type="text" maxlength="1" class="form-control text-center otp-box me-1">
                                <input type="text" maxlength="1" class="form-control text-center otp-box me-1">
                                <input type="text" maxlength="1" class="form-control text-center otp-box me-1">
                                <input type="text" maxlength="1" class="form-control text-center otp-box">
                            </div>

                            <div class="text-end mb-3">
                                <small id="resendTimer" class="text-muted">Resend OTP in 30s</small>
                                <a href="#" id="resendOtp" class="small d-none" onclick="resendOTP()">Resend OTP</a>
                            </div>

                            <button id="verify" class="btn btn-primary w-100 disabled" type="button" onclick="verifyOtp()">Verify
                                OTP</button>
                        </div>
                        <!-- PASSWORD SECTION (HIDDEN DEFAULT) -->
                        <div id="passwordSection" class="d-none">
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="mobileNumber2"
                                        placeholder="0000000000" required>
                                    <label for="mobileNumber2">Mobile Number</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="password" name="password" class="form-control" autocomplete="off"
                                        id="loginPassword" placeholder="Password" required>
                                    <label for="loginPassword">Password</label>
                                    <button onclick="togglePassword()" type="button"><i
                                            class="fa-solid fa-eye-slash"></i></button>
                                </div>
                            </div>

                            {{-- <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div> --}}
                            <div class="text-end">
                                <a href="#" class="color-primary" id="forgotPasswordLink">Forgot password?</a>
                            </div>
                            <div class="text-center mb-3">
                                <button type="button" class="btn btn-primary"
                                    onclick="loginWithPassword()">Login</button>
                            </div>
                            <div class="d-flex justify-content-center mb-2">
                                {{-- <small class="text-muted">Enter your password</small> --}}
                                <a href="#" onclick="showMobileStep()" id="switchToOtp"
                                    class="small text-decoration-none">
                                    Login with OTP
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer flex-column">
                    {{-- <button type="button" class="btn btn-primary" onclick="login()">Login</button> --}}
                    <div class="text-center w-100">
                        <p class="or">OR</p>
                        <a href="#" class="openRegister">Create an account</a>
                    </div>
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

    <div class="modal fade" id="locationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4">

                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">
                        <i class="bi bi-geo-alt-fill me-1"></i> Select Location
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Search -->
                    <div class="mb-3">
                        <div class="position-relative">
                            <input type="text" id="mapSearchInput" class="form-control"
                                placeholder="Search location" style="padding-right: 32px">
                            <i class="fa fa-times-circle clear-location" id="clearLocationBtn"></i>
                        </div>
                        <input type="hidden" name="map_latitude" value="" id="map_latitude">
                        <input type="hidden" name="map_longitude" value="" id="map_longitude">
                    </div>

                    <!-- Map -->
                    <div id="map" class="rounded" style="height: 350px;"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>

                    <button type="button" class="btn btn-danger" id="confirmLocation">
                        Confirm Location
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs/dist/purecounter_vanilla.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&libraries=places">
    </script>
    <script src="{{ asset('js/custom.js') }}"></script>
    @yield('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            autoDetectLocation({
                locationInputId: "locationInput",
                latInputId: "support_latitude",
                lngInputId: "support_longitude"
            });
        });
        const registerModal = document.getElementById("registerModal");
        registerModal.addEventListener("shown.bs.modal", () => {
            autoDetectLocation({
                locationInputId: "register_address",
                latInputId: "register_latitude",
                lngInputId: "register_longitude"
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => {
                hideLoader();
            }, 1000);
            getUser();
        });

        function getUserLocation() {
            return new Promise((resolve, reject) => {
                if (!navigator.geolocation) {
                    reject("Geolocation not supported");
                }
                const form = document.getElementById('register');
                navigator.geolocation.getCurrentPosition(
                    position => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        form.querySelector('.latitude').value = lat;
                        form.querySelector('.longitude').value = lng;

                        resolve({
                            lat,
                            lng
                        });
                    },
                    error => {
                        reject("Location permission denied");
                    }
                );
            });
        }

        function getAddressFromLatLng(lat, lng) {
            const apiKey = "{{ env('GOOGLE_API_KEY') }}";

            return fetch(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=${apiKey}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === "OK") {
                        const address = data.results[0].formatted_address;
                        document.getElementById('map_address').value = address;
                        return address;
                    } else {
                        throw "Unable to fetch address";
                    }
                });
        }


        function getUser() {
            const token = localStorage.getItem("token");

            const loginBtn = document.getElementById("loginBtn");
            const profileBtn = document.getElementById("profileBtn");
            const user_name = document.getElementById("user_name");
            const user_mobile = document.getElementById("user_mobile");
            if (token) {
                showLoader();
                // Fetch user info
                fetch("{{ url('/') }}/api/v1/user", {
                        method: "GET",
                        headers: {
                            "Authorization": "Bearer " + token,
                            "Accept": "application/json"
                        }
                    })
                    .then(async (res) => {
                        const data = await res.json();
                        if (!res.ok) {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: data.message || "Something went wrong"
                            });
                            localStorage.removeItem("token");
                            if (!loginBtn.classList.contains("d-md-block")) {
                                loginBtn.classList.add("d-md-block");
                            }

                            if (profileBtn.classList.contains("d-block")) {
                                profileBtn.classList.remove("d-block");
                            }
                            throw new Error("Request failed");
                        }
                        return data;
                    })
                    .then(data => {
                        userData = data;
                        // Hide login button
                        if (loginBtn.classList.contains("d-md-block")) {
                            loginBtn.classList.remove("d-md-block");
                        }

                        // Show profile button
                        if (!profileBtn.classList.contains("d-block")) {
                            profileBtn.classList.add("d-block");
                            profileBtn.classList.remove("d-none");
                        }
                        let userRoles = data?.roles;
                        if (userRoles && userRoles.includes("admin")) {
                            profileBtn.href = "{{ route('admin.dashboard') }}";
                        } else {
                            profileBtn.href = "{{ route('profile') }}";
                        }
                        if (userRoles.includes("volunteer")) {
                            toggleRole(userRoles, "volunteer", "volunteer");
                        }
                        if (userRoles.includes("donor")) {
                            toggleRole(userRoles, "donor", "donor");
                        }
                        user_name.innerText = userData?.name;
                        user_mobile.innerText = userData?.mobile;
                    })
                    .catch(err => {
                        console.log(err);
                    })
                    .finally(() => {
                        hideLoader();
                    });

            } else {
                if (!loginBtn.classList.contains("d-md-block")) {
                    loginBtn.classList.add("d-md-block");
                }

                if (profileBtn.classList.contains("d-block")) {
                    profileBtn.classList.remove("d-block");
                    profileBtn.classList.add("d-none");
                }

            }
        }

        function toggleRole(userRoles, sectionId, roleName) {
            const section = document.getElementById(sectionId);
            const form = section.querySelector(".registration-form");
            const message = section.querySelector(".role-message");
            const request_for = section.querySelector("input[value='self'] + label");
            if (userRoles.includes("donor")) {
                // form.classList.add("d-none");
                // message.classList.remove("d-none");
                message.innerText = "You are already a donor";
                request_for.style.pointerEvents = "none";
                request_for.style.opacity = "0.6";
            } else {
                // form.classList.remove("d-none");
                // message.classList.add("d-none");
            }
        }

        function sendOtp() {
            const mobile = document.getElementById("mobileNumber").value;
            window.currentMobileForOTP = mobile;
            if (mobile.length !== 10) {
                Swal.fire({
                    icon: "error",
                    title: "Invalid Mobile Number",
                    text: "Please enter a valid 10 digit mobile number"
                });
                return;
            }
            showLoader();
            fetch('{{ url('/') }}/api/v1/send-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        mobile: mobile
                    })
                })
                .then(async (res) => {
                    const data = await res.json();
                    if (!res.ok) {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: data.message || "Something went wrong"
                        });
                        throw new Error("Request failed");
                    }
                    return data;
                })
                .then(data => {
                    alert(data.message);
                    // Mask Mobile Number
                    const masked = mobile.substring(0, 2) + "******" + mobile.substring(8);
                    document.getElementById("otpSentText").innerText =
                        "OTP sent to +91 " + masked;

                    document.getElementById("mobileStep").classList.add("d-none");
                    document.getElementById("otpSection").classList.remove("d-none");

                    startResendTimer();
                    document.querySelector(".otp-box").focus();
                })
                .finally(() => {
                    hideLoader();
                });

        }
        document.querySelectorAll('.otp-box').forEach(input => {
            input.addEventListener("input",checkOtpLength);
            });
        function checkOtpLength(){
            let otp = getOtpValue();
            if (otp.length == 6) {
                document.querySelector("#verify").classList.remove("disabled");
            }
            else{
                document.querySelector("#verify").classList.add("disabled");
            }
        }
        function getOtpValue() {
            let otp = '';
            document.querySelectorAll('.otp-box').forEach(input => {
                otp += input.value;
            });
            return otp;
        }

        function verifyOtp() {
            let otp = getOtpValue();
            if (otp.length !== 6) {
                Swal.fire({
                    icon: "error",
                    title: "Invalid OTP",
                    text: "Please enter the 6 digit OTP"
                });
                return;
            }
            loader.style.display = "flex";
            fetch('{{ url('/') }}/api/v1/verify-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        mobile: document.getElementById('mobileNumber').value,
                        otp: otp
                    })
                })
                .then(async (res) => {
                    const data = await res.json();
                    if (!res.ok) {
                        Swal.fire({
                            icon: "error",
                            title: "Verification Failed",
                            text: data.message || "Invalid or expired OTP"
                        });
                        throw new Error("OTP verification failed");
                    }
                    return data;
                }).then(data => {
                    // Save token 
                    localStorage.setItem('token', data.token);
                    Swal.fire({
                        icon: "success",
                        title: "Login Successful",
                        text: "You are now logged in",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    // redirect if needed 
                    // window.location.href = "/dashboard";
                    getUser();
                    const modalElement = document.getElementById('loginModal');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    modal.hide();
                }).catch(error => {
                    console.log(error);
                }).finally(() => {
                    loader.style.display = "none";
                });
        }

        function editNumber() {
            document.getElementById("otpSection").classList.add("d-none");
            document.getElementById("mobileStep").classList.remove("d-none");
        }

        function showMobileStep() {
            document.getElementById("passwordSection").classList.add("d-none");
            document.getElementById("mobileStep").classList.remove("d-none");
        }

        function loginWithPassword() {
            const mobile = document.getElementById('mobileNumber2').value;
            const password = document.getElementById('loginPassword').value;

            if (mobile.length !== 10) {
                Swal.fire({
                    icon: "error",
                    title: "Invalid Mobile Number",
                    text: "Please enter a valid 10 digit mobile number"
                });
                return;
            }

            if (password === "") {
                Swal.fire({
                    icon: "error",
                    title: "Password Required",
                    text: "Please enter your password"
                });
                return;
            }

            loader.style.display = "flex";

            fetch('{{ url('/') }}/api/v1/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        mobile: mobile,
                        password: password
                    })
                })
                .then(async (res) => {

                    const data = await res.json();

                    if (!res.ok) {
                        Swal.fire({
                            icon: "error",
                            title: "Login Failed",
                            text: data.message || "Invalid mobile or password"
                        });
                        throw new Error("Login failed");
                    }

                    return data;
                })
                .then(data => {

                    // Save token
                    localStorage.setItem('token', data.token);

                    Swal.fire({
                        icon: "success",
                        title: "Login Successful",
                        text: "Welcome back!",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    getUser();
                    const modalElement = document.getElementById('loginModal');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    modal.hide();
                    // redirect if needed
                    // window.location.href = "/dashboard";

                })
                .catch(error => {
                    console.log(error);
                })
                .finally(() => {
                    loader.style.display = "none";
                });
        }
        document.getElementById("switchToPassword1").addEventListener("click", function() {
            document.getElementById("mobileStep").classList.add("d-none");
            document.getElementById("passwordSection").classList.remove("d-none");
        });

        // OTP Auto Move
        const otpInputs = document.querySelectorAll(".otp-box");

        otpInputs.forEach((input, index) => {
            input.addEventListener("input", () => {
                input.value = input.value.replace(/[^0-9]/g, '');
                if (input.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener("keydown", (e) => {
                if (e.key === "Backspace" && input.value === "" && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        // Resend Timer
        function startResendTimer() {
            let timeLeft = 30;
            const timer = document.getElementById("resendTimer");
            const resendBtn = document.getElementById("resendOtp");

            timer.classList.remove("d-none");
            resendBtn.classList.add("d-none");

            const countdown = setInterval(() => {
                timeLeft--;
                timer.innerText = "Resend in " + timeLeft + "s";

                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    timer.classList.add("d-none");
                    resendBtn.classList.remove("d-none");
                }
            }, 1000);
        }



        // function logout() {
        //     localStorage.removeItem("token");
        //     getUser();
        //     Swal.fire({
        //         icon: "success",
        //         title: "Logged Out",
        //         text: "You have been logged out successfully",
        //         timer: 1500,
        //         showConfirmButton: false
        //     });
        // }

        function togglePassword() {
            let input = document.getElementById('loginPassword');
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
            const loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
            if (loginModal) loginModal.hide();

            // Show forgot password modal
            const forgotModalEl = document.getElementById('forgotPasswordModal');
            const forgotModal = new bootstrap.Modal(forgotModalEl);
            forgotModal.show();
        });

        document.querySelectorAll('.openRegister').forEach(function(element) {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                console.log("clicked");
                let loginModalEl = document.getElementById('loginModal');
                let registerModalEl = document.getElementById('registerModal');

                let loginModal = bootstrap.Modal.getOrCreateInstance(loginModalEl);
                let registerModal = bootstrap.Modal.getOrCreateInstance(registerModalEl);

                if (loginModalEl.classList.contains('show')) {
                    loginModalEl.addEventListener('hidden.bs.modal', function() {
                        registerModal.show();
                    }, {
                        once: true
                    });

                    loginModal.hide();
                } else {
                    registerModal.show();
                }
            });
        });

        document.getElementById('openLogin').addEventListener('click', function(e) {
            e.preventDefault();

            let loginModalEl = document.getElementById('loginModal');
            let registerModalEl = document.getElementById('registerModal');

            let loginModal = bootstrap.Modal.getOrCreateInstance(loginModalEl);
            let registerModal = bootstrap.Modal.getOrCreateInstance(registerModalEl);

            registerModalEl.addEventListener('hidden.bs.modal', function() {
                loginModal.show();
            }, {
                once: true
            });

            registerModal.hide();
        });

        let registerOtpVerified = false;

        // ✅ Send OTP (Registration)
        function sendRegisterOtp(elm) {
            const mobile = document.getElementById('mobile').value;

            if (!mobile || mobile.length !== 10) {
                showAlert('warning', 'Enter valid mobile number');
                return;
            }
            loader.style.display = "flex";
            fetch('{{ url('/') }}/api/v1/send-registration-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        mobile: mobile
                    })
                })
                .then(async res => {
                    const data = await res.json();
                    if (res.ok) {
                        showAlert('success', data.message || 'OTP sent successfully');
                        document.getElementById('otpRegistraionSection').classList.remove('d-none');
                        elm.innerHTML = "Resend OTP";
                        console.log(data);
                    } else {
                        // Handle validation errors (422)
                        if (res.status === 422 && data.errors) {
                            let errorMessages = Object.values(data.errors)
                                .flat()
                                .join('\n');

                            showAlert('error', errorMessages);
                        } else {
                            showAlert('error', data.message || 'Something went wrong');
                        }
                    }
                })
                .catch(err => {
                    showAlert('error', err.message || 'Error sending OTP');
                    console.error(err);
                })
                .finally(() => {
                    loader.style.display = "none";
                });
        }

        // ✅ Verify OTP (Registration)
        function verifyRegisterOtp() {
            const mobile = document.getElementById('mobile').value;
            const otp = document.getElementById('otp').value;

            if (!otp) {
                showAlert('warning', 'Enter OTP');
                return;
            }
            loader.style.display = "flex";
            fetch('{{ url('/') }}/api/v1/verify-registration-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        mobile: mobile,
                        otp: otp
                    })
                })
                .then(async res => {
                    const data = await res.json();

                    if (!res.ok) throw data;

                    if (data.message === 'OTP verified successfully') {
                        showAlert('success', 'OTP verified successfully');

                        registerOtpVerified = true;

                        // ✅ Disable OTP input
                        document.getElementById('otp').disabled = true;

                        // ✅ Show tick icon
                        document.getElementById('mobileVerifiedIcon').classList.remove('d-none');

                        // ✅ Hide buttons
                        document.getElementById('sendOtpBtn').classList.add('d-none');
                        document.getElementById('verifyOtpBtn').classList.add('d-none');

                    } else {
                        showAlert('error', data.message || 'Invalid OTP');
                    }
                })
                .catch(err => {
                    showAlert('error', err.message || 'Invalid OTP');
                    console.error(err);
                })
                .finally(() => {
                    loader.style.display = "none";
                });
        }

        // ✅ Register User
        async function registerUser() {
            const form = document.getElementById('register');
            try {
                if (!form.querySelector('.latitude').value && !form.querySelector('.longitude').value) {
                    const location = await getUserLocation();
                }

            } catch (error) {
                showAlert('error', 'Location permission is required for registration');
                return;
            }
            if (!registerOtpVerified) {
                showAlert('warning', 'Please verify OTP first');
                return;
            }

            const formData = new FormData(form);

            // Convert FormData to plain object
            const data = Object.fromEntries(formData.entries());
            loader.style.display = "flex";
            fetch('{{ url('/') }}/api/v1/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(async res => {
                    const response = await res.json();
                    if (!res.ok) {
                        if (res.status === 403 && response.errors) {
                            let errorMessages = Object.values(response.errors)
                                .flat()
                                .join('\n');

                            showAlert('error', errorMessages);
                        } else {
                            showAlert('error', response.message || 'Registration failed');
                        }
                        throw response;
                    }

                    showAlert('success', 'Registration successful');

                    form.reset();
                    document.getElementById('otpRegistraionSection').classList.add('d-none');
                    registerOtpVerified = false;

                    const modal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
                    modal.hide();
                          if (response?.token && response?.user){
                            localStorage.setItem('token', response.token);
                            getUser();
                            }
                })
                .catch(err => {
                    if (err && typeof err === 'object') {
                        let firstError = Object.values(err)[0];
                        if (Array.isArray(firstError)) firstError = firstError[0];
                        showAlert('error', firstError);
                    } else {
                        showAlert('error', 'Registration failed');
                    }
                    console.error(err);
                })
                .finally(() => {
                    loader.style.display = "none";
                });
        }
    </script>
</body>

</html>
