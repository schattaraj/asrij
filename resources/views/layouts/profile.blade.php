<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Asrij</title>
    <!-- plugins:css -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="dist/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="dist/assets/vendors/ti-icons/css/themify-icons.css">
    <!-- <link rel="stylesheet" href="dist/assets/vendors/css/vendor.bundle.base.css"> -->
    <!-- <link rel="stylesheet" href="dist/assets/vendors/font-awesome/css/font-awesome.min.css"> -->
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- <link rel="stylesheet" href="dist/assets/vendors/font-awesome/css/font-awesome.min.css" /> -->
    <!-- <link rel="stylesheet" href="dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css"> -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="dist/assets/css/style.css">
    <link rel="stylesheet" href="dist/assets/css/custom.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/img/favicon.png" />
    <style>
.pac-container {
  z-index: 1070 !important; /* higher than Bootstrap modal (1055) */
}
.clear-location {
  position: absolute;
  top: 50%;
  right: 8px;
  transform: translateY(-50%);
  color: #999;
  cursor: pointer;
  font-size: 18px;
  display: none;
}

.clear-location:hover {
  color: #dc3545;
}
    </style>
</head>

<body>
    <div class="loader-container" id="loader">
        <div class="loader"></div>
    </div>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <a class="navbar-brand brand-logo" href="{{ route('home') }}"><img src="assets/img/logo.png"
                        alt="logo" style="height:40px" /></a>
                {{-- <a class="navbar-brand brand-logo-mini" href="{{ route('home') }}"><img
                        src="dist/assets/images/logo-mini.svg" alt="logo" /></a> --}}
                        <a class="navbar-brand brand-logo-mini" href="{{ route('home') }}"><img
                            src="assets/img/logo.png" alt="logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>
                <div class="search-field d-none d-md-block">
                    {{-- <form class="d-flex align-items-center h-100" action="#">
                        <div class="input-group">
                            <div class="input-group-prepend bg-transparent">
                                <i class="input-group-text border-0 mdi mdi-magnify"></i>
                            </div>
                            <input type="text" class="form-control bg-transparent border-0"
                                placeholder="Search projects">
                        </div>
                    </form> --}}
                </div>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="nav-profile-img">
                                <img src="dist/assets/images/faces-clipart/pic-1.png" alt="image">
                                <span class="availability-status online"></span>
                            </div>
                            <div class="nav-profile-text">
                                {{-- <p class="mb-1 text-black">{{$user->name}}</p> --}}
                            </div>
                        </a>
                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                            {{-- <a class="dropdown-item" href="#">
                                <i class="mdi mdi-cached me-2 text-success"></i> Activity Log </a> --}}
                            {{-- <div class="dropdown-divider"></div> --}}
                            <a class="dropdown-item" href="#" onclick="logout()">
                                <i class="mdi mdi-logout me-2 text-primary"></i> Signout </a>
                        </div>
                    </li>
                    <li class="nav-item d-none d-lg-block full-screen-link">
                        <a class="nav-link">
                            <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
                        </a>
                    </li>
                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-email-outline"></i>
                            <span class="count-symbol bg-warning"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list"
                            aria-labelledby="messageDropdown">
                            <h6 class="p-3 mb-0">Messages</h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <img src="dist/assets/images/faces/face4.jpg" alt="image" class="profile-pic">
                                </div>
                                <div
                                    class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Mark send you a
                                        message</h6>
                                    <p class="text-gray mb-0"> 1 Minutes ago </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <img src="dist/assets/images/faces/face2.jpg" alt="image" class="profile-pic">
                                </div>
                                <div
                                    class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Cregh send you a
                                        message</h6>
                                    <p class="text-gray mb-0"> 15 Minutes ago </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <img src="dist/assets/images/faces/face3.jpg" alt="image" class="profile-pic">
                                </div>
                                <div
                                    class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Profile picture
                                        updated</h6>
                                    <p class="text-gray mb-0"> 18 Minutes ago </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <h6 class="p-3 mb-0 text-center">4 new messages</h6>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                            data-bs-toggle="dropdown">
                            <i class="mdi mdi-bell-outline"></i>
                            <span class="count-symbol bg-danger"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list"
                            aria-labelledby="notificationDropdown">
                            <h6 class="p-3 mb-0">Notifications</h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-success">
                                        <i class="mdi mdi-calendar"></i>
                                    </div>
                                </div>
                                <div
                                    class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="preview-subject font-weight-normal mb-1">Event today</h6>
                                    <p class="text-gray ellipsis mb-0"> Just a reminder that you have an event today
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-warning">
                                        <i class="mdi mdi-cog"></i>
                                    </div>
                                </div>
                                <div
                                    class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="preview-subject font-weight-normal mb-1">Settings</h6>
                                    <p class="text-gray ellipsis mb-0"> Update dashboard </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-info">
                                        <i class="mdi mdi-link-variant"></i>
                                    </div>
                                </div>
                                <div
                                    class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="preview-subject font-weight-normal mb-1">Launch Admin</h6>
                                    <p class="text-gray ellipsis mb-0"> New admin wow! </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <h6 class="p-3 mb-0 text-center">See all notifications</h6>
                        </div>
                    </li> --}}
                    <li class="nav-item nav-logout d-none d-lg-block">
                        <a class="nav-link" href="#">
                            <i class="mdi mdi-power"></i>
                        </a>
                    </li>
                    <!-- <li class="nav-item nav-settings d-none d-lg-block">
                        <a class="nav-link" href="#">
                            <i class="mdi mdi-format-line-spacing"></i>
                        </a>
                    </li> -->
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item nav-profile">
                        <a href="#" class="nav-link">
                            <div class="nav-profile-image">
                                <img src="dist/assets/images/faces-clipart/pic-1.png" alt="profile" />
                                <span class="login-status online"></span>
                                <!--change to offline or busy as needed-->
                            </div>
                            <div class="nav-profile-text d-flex flex-column">
                                {{-- <span class="font-weight-bold mb-2">{{$user->name}}</span> --}}
                                {{-- <span class="text-secondary text-small">{{$user->role}}</span> --}}
                            </div>
                            <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile') }}">
                            <span class="menu-title">Profile</span>
                            {{-- <i class="mdi mdi-home menu-icon"></i> --}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('bloodDonations')}}">
                            <span class="menu-title">My Blood Donations</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('bloodRequests')}}">
                            <span class="menu-title">My Blood Requests</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <span class="menu-title">Back to Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="#" onclick="event.preventDefault(); logout();">
                            <span class="menu-title">Logout</span>
                        </a>
                    </li>
                    {{-- @if ($user->role == 'volunteer')
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('bloodCamps')}}">
                            <span class="menu-title">Manage Blood Camps</span>
                        </a>
                    </li>
                    @endif --}}
                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                    <!-- main-panel ends -->
                </div>
                <!-- page-body-wrapper ends -->
            </div>
            <!-- container-scroller -->
            <!-- plugins:js -->
            <script src="dist/assets/vendors/js/vendor.bundle.base.js"></script>
            <!-- endinject -->
            <!-- Plugin js for this page -->
            <script src="dist/assets/vendors/chart.js/chart.umd.js"></script>
            <script src="dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
            <!-- End plugin js for this page -->
            <!-- inject:js -->
            <script src="dist/assets/js/off-canvas.js"></script>
            <script src="dist/assets/js/misc.js"></script>
            <script src="dist/assets/js/settings.js"></script>
            <script src="dist/assets/js/todolist.js"></script>
            <script src="dist/assets/js/jquery.cookie.js"></script>
            <!-- endinject -->
            <!-- Custom js for this page -->
            <!-- <script src="dist/assets/js/dashboard.js"></script> -->
            <!-- End custom js for this page -->
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2026 <a
                        href="https://www.asrij.in/" target="_blank">Asrij</a>. All rights reserved.</span>
                {{-- <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="mdi mdi-heart text-danger"></i></span> --}}
            </div>
        </footer>
        <!-- partial -->
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&libraries=places"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            getUser();
        });
        function getUser() {
            const token = localStorage.getItem("token");

            const loginBtn = document.getElementById("loginBtn");
            const profileBtn = document.getElementById("profileBtn");

            // Pages that require login
            const protectedPages = [
                "profile",
                "dashboard",
                "blood-donations",
                "blood-request"
            ];

            const currentPath = window.location.pathname;
            console.log("currentPath",currentPath.split("/")[currentPath.split("/").length - 1]);
            function redirectToHome() {
                Swal.fire({
                    icon: "warning",
                    title: "Session Expired",
                    text: "Please login to continue.",
                    confirmButtonText: "Go to Home"
                }).then(() => {
                    window.location.href = "{{ url('/') }}";
                });
            }

            if (token) {
                showLoader();
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

                            // Token invalid or expired
                            // localStorage.removeItem("token");

                            if (protectedPages.includes(currentPath)) {
                                redirectToHome();
                            }

                            throw new Error("Unauthorized");
                        }

                        return data;
                    })
                    .then(data => {
                        userData = data;
                        if(data?.avatar){
                            document.querySelector("#sidebar .nav-profile-image img").src="{{url('/')}}/storage/app/public/"+data?.avatar;
                        }
                        // if (data.roles && data.roles.includes("admin")) {
                        //     profileBtn.href = "{{ route('admin.dashboard') }}";
                        // } else {
                        //     profileBtn.href = "{{ route('profile') }}";
                        // }

                    })
                    .catch(err => {
                        console.log(err);
                    });
                    hideLoader();

            } else {
                // If user visits protected page without login
                if (protectedPages.includes(currentPath.split("/")[currentPath.split("/").length - 1])) {
                    redirectToHome();
                }
            }
        }
    </script>
    @yield('scripts')
</body>

</html>
