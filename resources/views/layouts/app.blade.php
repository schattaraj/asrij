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
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="">
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-mail">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                </path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            info@asrij.in</a>
                        <a href="tel:8653846646">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-phone">
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
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                {{-- <a class="navbar-brand" href="#">Navbar</a> --}}
                <div class="logo"><img src="{{ asset('assets/img/logo4.png') }}" alt=""></div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class=""></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
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
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('conversation') ? 'active' : '' }}" href="{{ route('conversation') }}">Conversation</a>
                    </li> --}}
                </ul>                
                </div>
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
                    <div class="logo mb-3"><img src="{{ asset('assets/img/logo4.png') }}" alt=""></div>
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
                        <li><i class="fa-solid fa-angles-right"></i> <a href="#">Register</a></li>
                        <li><i class="fa-solid fa-angles-right"></i> <a href="#">Login</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul>
                        <li>
                            <i class="fa-solid fa-envelope"></i> <a href="mailto:contact@asrij.com">contact@asrij.com</a>
                        </li>
                        <li>
                            <i class="fa-solid fa-phone"></i> <a href="tel:911234567890">+911234567890</a>
                        </li>
                        <li>
                            <a href="#" class="text-white fs-4 social-link"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-white fs-4 social-link"><i class="fab fa-instagram"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs/dist/purecounter_vanilla.js"></script>
    <script src="{{asset('js/custom.js')}}"></script>
    @yield('scripts')
</body>

</html>
