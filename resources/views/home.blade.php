@extends('layouts.app')
@section('title', 'Home')

@section('content')
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('assets/img/banner1.jpg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption">
                    <h3>Donate Blood, Save Lives</h3>
                    <p>Your one drop of blood can give someone a new life.</p>
                    <a href="#" class="btn btn-primary">Register Now</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/img/banner2.jpg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption">
                    <h3>Help People</h3>
                    <p>Some representative placeholder content for the second slide.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/img/banner3.jpg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption">
                    <h3>The World Needs Your Help</h3>
                    <p>Some representative placeholder content for the third slide.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <!-- ===== STATS SECTION ===== -->
    <section class="stats">
        <div class="stat-box">
            <h2 class="count"
            data-target="2345"
            >0</h2>
            <p>Total Units Donated</p>
        </div>
        <div class="stat-box">
            <h2 class="count" data-target="1120">0</h2>
            <p>Total Donors</p>
        </div>
        <div class="stat-box">
            <h2 class="count" data-target="980">0</h2>
            <p>Total Receivers</p>
        </div>
        <div class="stat-box">
          <h2 class="count" data-target="45">0</h2>
            <p>Total Volunteers</p>
        </div>
    </section>
    <section class="registration-section">
      <div class="container">
        <h1 style="font-size: 24px;font-weight:600">Register as a Donor / Receiver</h1>
    
        <div class="registration-tabs">
          <button class="tab-btn active" data-tab="donor">Donor</button>
          <button class="tab-btn" data-tab="receiver">Receiver</button>
        </div>
    
        <!-- Donor Registration -->
        <div id="donor" class="tab-content active">
          <form class="registration-form">
            <input type="text" name="name" placeholder="Full Name" required>
            <select name="blood_group" required>
              <option value="">Select Blood Group</option>
              <option>A+</option><option>A-</option>
              <option>B+</option><option>B-</option>
              <option>AB+</option><option>AB-</option>
              <option>O+</option><option>O-</option>
            </select>
            <input type="date" name="last_donation" placeholder="Last Date of Donation" required>
            <input type="text" name="contact" placeholder="Contact Number" required>
            <textarea name="address" placeholder="Address with Pin Code" required></textarea>
            <button type="submit" class="btn-primary">Submit Registration</button>
          </form>
        </div>
    
        <!-- Receiver Registration -->
        <div id="receiver" class="tab-content">
          <form class="registration-form">
            <select name="receiver_type" required>
              <option value="">Select Receiver Type</option>
              <option>Thalassemia Patient</option>
              <option>Emergency - Accident Case</option>
              <option>Admitted Patient</option>
              <option>Other</option>
            </select>
            <input type="text" name="name" placeholder="Patient Name" required>
            <input type="text" name="hospital" placeholder="Hospital Name" required>
            <input type="text" name="contact" placeholder="Contact Number" required>
            <textarea name="address" placeholder="Address with Pin Code" required></textarea>
            <button type="submit" class="btn-primary">Submit Registration</button>
          </form>
        </div>
    
        <!-- Volunteer Registration -->
        <div id="volunteer" class="tab-content">
          <form class="registration-form">
            <input type="text" name="organization" placeholder="Organization / Trust Name" required>
            <input type="text" name="contact" placeholder="Contact Number" required>
            <input type="email" name="email" placeholder="Email ID" required>
            <textarea name="address" placeholder="Address with Pin Code" required></textarea>
            <button type="submit" class="btn-primary">Submit Registration</button>
          </form>
        </div>
    
        <!-- Blood Bank / Hospital -->
        <div id="hospital" class="tab-content">
          <form class="registration-form">
            <input type="text" name="hospital_name" placeholder="Hospital / Blood Bank Name" required>
            <input type="text" name="license" placeholder="Registration / License Number" required>
            <input type="text" name="contact" placeholder="Contact Number" required>
            <input type="email" name="email" placeholder="Email ID" required>
            <textarea name="address" placeholder="Address with Pin Code" required></textarea>
            <button type="submit" class="btn-primary">Submit Registration</button>
          </form>
        </div>
      </div>
    </section>
    <!-- Donation Process Section -->
    <section class="donation-process py-5" data-aos="fade-down">
        <div class="container text-center">
            <h2 class="mb-5 section-title heading">Donation Process</h2>
            <div class="row justify-content-center">
                <!-- Step 1: Registration -->
                <div class="col-md-4 mb-4">
                    <div class="process-step">
                        <div class="step-icon mb-3">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <h5 class="fw-bold">Registration</h5>
                        <p class="text-muted">
                            Fill out a quick registration form with your basic information to get started.
                        </p>
                        <span class="step">1</span>
                    </div>
                </div>

                <!-- Step 2: Screening -->
                <div class="col-md-4 mb-4">
                    <div class="process-step">
                        <div class="step-icon mb-3">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                        <h5 class="fw-bold">Screening</h5>
                        <p class="text-muted">
                            Undergo a simple health screening to ensure you’re eligible to donate safely.
                        </p>
                        <span class="step">2</span>
                    </div>
                </div>

                <!-- Step 3: Donation -->
                <div class="col-md-4 mb-4">
                    <div class="process-step">
                        <div class="step-icon mb-3">
                            <i class="bi bi-droplet-half"></i>
                        </div>
                        <h5 class="fw-bold">Donation</h5>
                        <p class="text-muted">
                            Complete your donation in a comfortable and caring environment.
                        </p>
                        <span class="step">3</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ===== OUR VISION / ABOUT ===== -->
    <section class="d-none vision py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <!-- Text Section -->
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-down" data-aos-duration="800" data-aos-delay="200">
                    <h2 class="fw-bold mb-3 text-uppercase" style="color: var(--primary-color);">Our Vision</h2>
                    <p class="lead text-secondary">
                        We aim to build a digital bridge connecting donors and receivers across communities.
                        Through this portal, we promote timely blood donations and emergency response systems
                        for saving lives efficiently.
                    </p>
                    <a href="#donate" class="btn btn-primary mt-3 px-4 py-2 shadow-sm">
                        Learn More <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>

                <!-- Image Section -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <div class="vision-img position-relative overflow-hidden rounded-4 shadow">
                        <img src="{{ asset('assets/img/banner1.jpg') }}" alt="Our Vision"
                            class="img-fluid w-100 h-100 object-fit-cover" style="max-height: 400px;" />
                        {{-- <div class="overlay position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-25" style="opacity: 25%"></div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ===== BLOOD CAMP GALLERY ===== -->
    <section class="gallery">
        <div class="container">
            <h2 data-aos="fade-down">Our Blood Camps</h2>
            {{-- <div class="gallery-container">
            <img src="{{asset('assets/img/camp1.jpg')}}" alt="Blood Camp 1">
            <img src="{{asset('assets/img/camp2.jpg')}}" alt="Blood Camp 2">
            <img src="{{asset('assets/img/camp1.jpg')}}" alt="Blood Camp 3">
        </div> --}}
            <div class="swiper" data-aos="fade-down">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->
                    <div class="swiper-slide"><img src="{{ asset('assets/img/camp1.jpg') }}" alt="Blood Camp 1"></div>
                    <div class="swiper-slide"><img src="{{ asset('assets/img/camp2.jpg') }}" alt="Blood Camp 1"></div>
                    <div class="swiper-slide"><img src="{{ asset('assets/img/camp1.jpg') }}" alt="Blood Camp 1"></div>
                    <div class="swiper-slide"><img src="{{ asset('assets/img/camp1.jpg') }}" alt="Blood Camp 1"></div>
                </div>
                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>

                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

                <!-- If we need scrollbar -->
                {{-- <div class="swiper-scrollbar"></div> --}}
            </div>
        </div>
    </section>
    <!-- ===== EMERGENCY AMBULANCE CONTACT ===== -->
    <section class="emergency py-5 text-center text-white" data-aos="fade-up">
        <div class="container">
            <h2 class="fw-bold mb-4">Find Emergency Ambulance Contact</h2>
            <form class="d-flex justify-content-center flex-wrap">
                <input type="text" class="form-control w-auto px-3" placeholder="Enter Pin Code"
                    style="min-width: 250px;">
                <button type="button" class="btn btn-light fw-semibold px-4">
                    Search
                </button>
            </form>
            <p class="note mt-3 mb-0 text-light opacity-75">
                Emergency support is available <strong>24/7</strong>
            </p>
        </div>
    </section>
    <!-- ===== ACCIDENTAL SUPPORT ===== -->
    {{-- <section class="accident-support py-5 bg-light"  data-aos="fade-up">
        <div class="container">
            <h2 class="fw-bold text-center mb-4" style="color: var(--primary-color);">
                On-Road Accidental Support
            </h2>
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <form class="p-4 rounded-4 shadow-sm bg-white">
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Enter your location">
                        </div>
                        <div class="mb-3">
                            <input type="file" class="form-control">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" rows="4" placeholder="Describe the situation"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-semibold py-2">
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section> --}}
    <!-- On-Road Accidental Support Section -->
    <section class="accident-support d-flex align-items-center py-5" data-aos="fade-up">
      <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
          <div class="col-lg-8">
            <h2 class="fw-bold text-center mb-5 section-title">
              <i class="bi bi-truck-front me-2"></i> On-Road Accidental Support
            </h2>
    
            <form class="support-form p-4 p-md-5 rounded-4 shadow-lg bg-white">
              <div class="text-center mb-4">
                <div class="support-icon mb-3">
                  <i class="bi bi-geo-alt"></i>
                </div>
                <p class="text-muted mb-0">
                  Report an on-road emergency and get immediate support.
                </p>
              </div>
    
              <div class="mb-3">
                <label class="form-label fw-semibold">Your Location</label>
                <input type="text" class="form-control" placeholder="Enter your location">
              </div>
    
              <div class="mb-3">
                <label class="form-label fw-semibold">Upload Image (optional)</label>
                <input type="file" class="form-control">
              </div>
    
              <div class="mb-4">
                <label class="form-label fw-semibold">Describe the Situation</label>
                <textarea class="form-control" rows="4" placeholder="Provide details about the accident..."></textarea>
              </div>
    
              <div class="d-grid">
                <button type="submit" class="btn btn-danger fw-semibold py-2 btn-glow">
                  <i class="bi bi-send-fill me-1"></i> Submit Request
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
    
    <!-- ===== OUR VISION / ABOUT ===== -->
    {{-- <section class="vision">
        <div class="vision-text">
            <h2>Our Vision</h2>
            <p>We aim to build a digital bridge connecting donors and receivers across communities. 
            Through this portal, we promote timely blood donations and emergency response systems 
            for saving lives efficiently.</p>
        </div>
        <div class="vision-img">
            <img src="images/banner.jpg" alt="Our Vision">
        </div>
    </section> --}}
    <!-- ===== CONTACT SECTION ===== -->
    {{-- <section class="contact">
        <h2>Contact Us</h2>
        <p><i class="fas fa-envelope"></i> info@bloodconnect.org</p>
        <p><i class="fas fa-phone"></i> +91 98765 43210</p>
        <p><i class="fas fa-map-marker-alt"></i> 123, Red Cross Road, New Delhi</p>
    </section> --}}
    <!-- ===== CONTACT SECTION ===== -->
    {{-- <section class="contact py-5 text-center text-white" style="background-color: var(--primary-color);">
  <div class="container">
    <h2 class="fw-bold mb-4 text-uppercase">Contact Us</h2>
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="contact-info fs-5">
          <p class="mb-3">
            <i class="fas fa-envelope me-2"></i>
            <a href="mailto:info@bloodconnect.org" class="text-white text-decoration-none hover-underline">
              info@bloodconnect.org
            </a>
          </p>
          <p class="mb-3">
            <i class="fas fa-phone me-2"></i>
            <a href="tel:+919876543210" class="text-white text-decoration-none hover-underline">
              +91 98765 43210
            </a>
          </p>
          <p class="mb-0">
            <i class="fas fa-map-marker-alt me-2"></i>
            123, Red Cross Road, New Delhi
          </p>
        </div>
      </div>
    </div>
  </div>
</section> --}}
    <!-- ===== CONTACT SECTION ===== -->
    {{-- <section class="contact py-5 bg-light">
  <div class="container text-center">
    <h2 class="fw-bold mb-4 text-uppercase" style="color: var(--primary-color);">Contact Us</h2>

    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="contact-card p-4 rounded-4 shadow-sm bg-white">
          <p class="mb-3 fs-5">
            <i class="fas fa-envelope me-2" style="color: var(--primary-color);"></i>
            <a href="mailto:info@bloodconnect.org" class="text-dark text-decoration-none hover-highlight">
              info@bloodconnect.org
            </a>
          </p>
          <p class="mb-3 fs-5">
            <i class="fas fa-phone me-2" style="color: var(--primary-color);"></i>
            <a href="tel:+919876543210" class="text-dark text-decoration-none hover-highlight">
              +91 98765 43210
            </a>
          </p>
          <p class="mb-0 fs-5">
            <i class="fas fa-map-marker-alt me-2" style="color: var(--primary-color);"></i>
            123, Red Cross Road, New Delhi
          </p>
        </div>
      </div>
    </div>
  </div>
</section> --}}
    <!-- ===== CONTACT SECTION ===== -->
    {{-- <section class="contact py-5 bg-light">
  <div class="container text-center">
    <h2 class="fw-bold mb-4 text-uppercase" style="color: var(--primary-color);">
      Contact Us
    </h2>

    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="contact-card p-4 rounded-4 shadow-sm bg-white">
          <!-- Contact Details -->
          <div class="mb-4">
            <p class="mb-3 fs-5">
              <i class="fas fa-envelope me-2" style="color: var(--primary-color);"></i>
              <a href="mailto:info@bloodconnect.org" class="text-dark text-decoration-none hover-highlight">
                info@bloodconnect.org
              </a>
            </p>
            <p class="mb-3 fs-5">
              <i class="fas fa-phone me-2" style="color: var(--primary-color);"></i>
              <a href="tel:+919876543210" class="text-dark text-decoration-none hover-highlight">
                +91 98765 43210
              </a>
            </p>
            <p class="mb-0 fs-5">
              <i class="fas fa-map-marker-alt me-2" style="color: var(--primary-color);"></i>
              123, Red Cross Road, New Delhi
            </p>
          </div>

          <hr class="my-4">

          <!-- Contact Form -->
          <form>
            <div class="mb-3">
              <input type="text" class="form-control" placeholder="Your Name" required>
            </div>
            <div class="mb-3">
              <input type="email" class="form-control" placeholder="Your Email" required>
            </div>
            <div class="mb-3">
              <textarea class="form-control" rows="4" placeholder="Your Message" required></textarea>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary fw-semibold py-2">
                Send Message
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</section> --}}
    <!-- ===== CONTACT SECTION ===== -->
    {{-- <section class="contact py-5">
  <div class="container">
    <div class="row align-items-center justify-content-center g-4">

      <!-- Left Side: Image -->
      <div class="col-lg-5">
        <div class="contact-img overflow-hidden rounded-4 shadow-sm">
          <img 
            src="{{asset('assets/img/banner1.jpg')}}" 
            alt="Contact BloodConnect" 
            class="img-fluid w-100 h-100 object-fit-cover"
            style="max-height: 500px;"
          >
        </div>
      </div>

      <!-- Right Side: Content + Form -->
      <div class="col-lg-6">
        <div class="contact-card p-4 p-md-5 rounded-4 bg-white shadow-sm">
          <h2 class="fw-bold mb-4 text-uppercase" style="color: var(--primary-color);">
            Contact Us
          </h2>

          <!-- Contact Details -->
          <div class="mb-4">
            <p class="mb-3 fs-5">
              <i class="fas fa-envelope me-2" style="color: var(--primary-color);"></i>
              <a href="mailto:info@bloodconnect.org" class="text-dark text-decoration-none hover-highlight">
                info@bloodconnect.org
              </a>
            </p>
            <p class="mb-3 fs-5">
              <i class="fas fa-phone me-2" style="color: var(--primary-color);"></i>
              <a href="tel:+919876543210" class="text-dark text-decoration-none hover-highlight">
                +91 98765 43210
              </a>
            </p>
            <p class="mb-0 fs-5">
              <i class="fas fa-map-marker-alt me-2" style="color: var(--primary-color);"></i>
              123, Red Cross Road, New Delhi
            </p>
          </div>

          <hr class="my-4">

          <!-- Contact Form -->
          <form>
            <div class="mb-3">
              <input type="text" class="form-control" placeholder="Your Name" required>
            </div>
            <div class="mb-3">
              <input type="email" class="form-control" placeholder="Your Email" required>
            </div>
            <div class="mb-3">
              <textarea class="form-control" rows="4" placeholder="Your Message" required></textarea>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary fw-semibold py-2">
                Send Message
              </button>
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </div>
</section> --}}
    <section class="testimonial-section">
        <div class="container">
            <h2 class="testimonial-title heading"  data-aos="fade-down">Testimonial</h2>

            <!-- Swiper -->
            <div class="swiper mySwiper" data-aos="fade-down">
                <div class="swiper-wrapper">

                    <!-- Testimonial 1 -->
                    <div class="swiper-slide">
                        <img src="https://i.pravatar.cc/100?img=3" alt="User" class="testimonial-img" />
                        <h3 class="testimonial-name">Sarah Johnson</h3>
                        <p class="testimonial-text">
                            “Absolutely amazing experience! The team was professional, friendly, and went above and beyond
                            my expectations.”
                        </p>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="swiper-slide">
                        <img src="https://i.pravatar.cc/100?img=5" alt="User" class="testimonial-img" />
                        <h3 class="testimonial-name">Michael Smith</h3>
                        <p class="testimonial-text">
                            “I’ve seen great results since using their service. Highly recommended to anyone looking for
                            quality and reliability!”
                        </p>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="swiper-slide">
                        <img src="https://i.pravatar.cc/100?img=8" alt="User" class="testimonial-img" />
                        <h3 class="testimonial-name">Emily Davis</h3>
                        <p class="testimonial-text">
                            “Fantastic customer support and beautiful results. I’ll definitely be returning for future
                            projects.”
                        </p>
                    </div>

                </div>

                <!-- Pagination -->
                <div class="swiper-pagination"></div>

                <!-- Navigation buttons -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>
    <div class="contact-us my-sm-5">
        <div class="container">
            <div class="row contact-form">
                <div class="col-md-8 left" data-aos="fade-right">
                    <h3>Send Us a Message</h3>
                    <form action="/">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="name">Your Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Morger Dior" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="email">Your Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="example@gmail.com" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message">Messages</label>
                            <textarea name="message" id="message" class="form-control" required></textarea>
                        </div>
                        <button class="btn btn-primary">
                            <span>Send Message</span> <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
                <div class="col-md-4 right" data-aos="fade-left">
                    <div class="contact-info">
                        <h3>Contact Information</h3>
                        <a href="https://maps.app.goo.gl/ZJaR9YWVEXCS45539" target="_blank">
                            <div class="item"><i class="fa-solid fa-location-dot"></i><span>Ganesh Glory, Gota,
                                    Ahmedabad, Gujarat, 382481</span></div>
                        </a>
                        <a href="mailto:info@asrij.com">
                            <div class="item"><i class="fa-solid fa-envelope"></i><span>info@asrij.com</span></div>
                        </a>
                        <a href="tel:+919907234335" target="_blank">
                            <div class="item"><i class="fa-solid fa-phone"></i><span>+91 9907234335</span></div>
                        </a>
                    </div>
                    <div class="social-links">
                        <h3>Follow Us</h3>
                        <a href="https://www.instagram.com/fullstop.pvt.ltd?igsh=MWRiNXJ4OXkydmRmcw==" target="_blank"><i
                                class="fa-brands fa-instagram"></i></a>
                        <a href="https://www.facebook.com/profile.php?id=61572378570370&mibextid=ZbWKwL"
                            target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            {{-- <div class="row">
          <div class="map mt-5 w-100">
              <iframe
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3669.5411924386317!2d72.541278!3d23.11388555!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e82dd003ff749%3A0x359e803f537cea25!2sGANESH%20GLORY%2C%20Gota%2C%20Ahmedabad%2C%20Gujarat%20382481!5e0!3m2!1sen!2sin!4v1740900895991!5m2!1sen!2sin"
                  style="border:0; width: 100%;" allowfullscreen="" loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
      </div> --}}
        </div>
    </div>
    {{-- @include('partials.home-content') --}}
@section('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const counters = document.querySelectorAll(".count");
    const speed = 200; // lower = faster
  
    const animateCounter = (counter) => {
      const updateCount = () => {
        const target = +counter.getAttribute("data-target");
        const count = +counter.innerText;
        const increment = target / speed;
  
        if (count < target) {
          counter.innerText = Math.ceil(count + increment);
          requestAnimationFrame(updateCount);
        } else {
          counter.innerText = target.toLocaleString(); // formatted with commas
        }
      };
      updateCount();
    };
  
    // Use IntersectionObserver to trigger only when visible
    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          obs.unobserve(entry.target); // stop observing once animated
        }
      });
    }, { threshold: 0.5 });
  
    counters.forEach(counter => observer.observe(counter));
  });
  </script>
  <script>
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
  
    tabBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        tabBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        tabContents.forEach(content => content.classList.remove('active'));
        document.getElementById(btn.dataset.tab).classList.add('active');
      });
    });
  
    // Optional mock submission
    document.querySelectorAll('.registration-form').forEach(form => {
      form.addEventListener('submit', e => {
        e.preventDefault();
        alert('Registration submitted successfully!');
        form.reset();
      });
    });
  </script>  
@endsection    
@endsection
