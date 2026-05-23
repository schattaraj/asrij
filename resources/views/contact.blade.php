@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')
    {{-- ===================================================
         HERO — split layout, plugin-free
    =================================================== --}}
    <section class="ax-hero">
        <span class="ax-bg-shape ax-bg-shape-1"></span>
        <span class="ax-bg-shape ax-bg-shape-2"></span>
        <span class="ax-bg-grid"></span>

        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 ax-hero-left">
                    <span class="ax-chip"><i class="fa-solid fa-paper-plane"></i> Get In Touch</span>
                    <h1 class="ax-hero-title">
                        Let's <span class="ax-accent">Talk</span> — we're here to help.
                    </h1>
                    <p class="ax-hero-sub">
                        Whether it's a question, a blood request, or you'd like to volunteer — our team
                        is just a message away. Reach out and we'll respond as fast as humanly possible.
                    </p>

                    <div class="ax-quick-contact">
                        <a href="tel:+917048115559" class="ax-qc-card">
                            <div class="ax-qc-icon"><i class="fa-solid fa-phone"></i></div>
                            <div>
                                <small>Call us 24/7</small>
                                <strong>+91 70481 15559</strong>
                            </div>
                        </a>
                        <a href="mailto:info@asrij.in" class="ax-qc-card">
                            <div class="ax-qc-icon ax-qc-icon-alt"><i class="fa-solid fa-envelope"></i></div>
                            <div>
                                <small>Drop an email</small>
                                <strong>info@asrij.in</strong>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 ax-hero-right">
                    <div class="ax-hero-visual">
                        <div class="ax-hero-photo">
                            <img src="{{ asset('assets/img/banner3.jpg') }}" alt="Contact ASRIJ">
                        </div>
                        <div class="ax-hero-badge ax-hero-badge-tl">
                            <div class="ax-online">
                                <span class="ax-online-dot"></span>
                                <strong>Online Now</strong>
                            </div>
                            <p>Avg. response &lt; 10 min</p>
                        </div>
                        <div class="ax-hero-badge ax-hero-badge-br">
                            <h4><i class="fa-solid fa-comments"></i> Real People</h4>
                            <p>No bots, ever.</p>
                        </div>
                        <div class="ax-hero-decor"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================================================
         CONTACT INFO CARDS
    =================================================== --}}
    <section class="ax-contact-cards">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="ax-ci-card">
                        <div class="ax-ci-icon"><i class="fa-solid fa-location-dot"></i></div>
                        <h5>Visit Our Office</h5>
                        <p>
                            Sai Plaza, Ground Floor,<br>
                            Police Chowki, Bishnupur,<br>
                            Bankura, West Bengal 722122
                        </p>
                        <a href="#map" class="ax-ci-link">Get directions <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="ax-ci-card">
                        <div class="ax-ci-icon"><i class="fa-solid fa-envelope"></i></div>
                        <h5>Email Us</h5>
                        <p>
                            <a href="mailto:info@asrij.in">info@asrij.in</a><br>
                            <a href="mailto:contact@asrij.com">contact@asrij.com</a><br>
                            <span class="ax-soft">We reply within 24 hrs</span>
                        </p>
                        <a href="mailto:info@asrij.in" class="ax-ci-link">Send email <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="ax-ci-card">
                        <div class="ax-ci-icon"><i class="fa-solid fa-phone"></i></div>
                        <h5>Call Us</h5>
                        <p>
                            <a href="tel:+917048115559">+91 70481 15559</a><br>
                            <span class="ax-soft">Mon — Sat<br>9:00 AM — 7:00 PM</span>
                        </p>
                        <a href="tel:+917048115559" class="ax-ci-link">Call now <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================================================
         CONTACT FORM
    =================================================== --}}
    <div class="contact-us ax-contact-form-wrap">
        <div class="container">
            <div class="row contact-form">
                <div class="col-md-8 left">
                    <h3>Send Us a Message</h3>
                    @if (session('contact_success'))
                        <div class="alert alert-success">{{ session('contact_success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="name">Your Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" placeholder="John Doe" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="email">Your Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}" placeholder="example@gmail.com" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="phone">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    value="{{ old('phone') }}" placeholder="98765 43210" maxlength="10">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="subject">Subject</label>
                                <input type="text" class="form-control" id="subject" name="subject"
                                    value="{{ old('subject') }}" placeholder="How can we help?" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" class="form-control"
                                placeholder="Write your message..." required>{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <span>Send Message</span>
                            <i class="fa-solid fa-arrow-right ms-2"></i>
                        </button>
                    </form>
                </div>
                <div class="col-md-4 right">
                    <div class="contact-info">
                        <h3>Contact Information</h3>
                        <a href="#">
                            <div class="item">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>Sai Plaza Ground Floor, Police Chowki, Bishnupur, Bankura, West Bengal 722122</span>
                            </div>
                        </a>
                        <a href="mailto:info@asrij.in">
                            <div class="item"><i class="fa-solid fa-envelope"></i><span>info@asrij.in</span></div>
                        </a>
                        <a href="tel:+917048115559">
                            <div class="item"><i class="fa-solid fa-phone"></i><span>+91 70481 15559</span></div>
                        </a>
                    </div>
                    <div class="social-links">
                        <h3>Follow Us</h3>
                        <a href="https://www.facebook.com/profile.php?id=61586204187656&mibextid=rS40aB7S9Ucbxw6v"
                            target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                        <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================================================
         MAP
    =================================================== --}}
    <section class="ax-map" id="map">
        <div class="container">
            <div class="ax-section-head">
                <span class="ax-chip"><i class="fa-solid fa-map-location-dot"></i> Find Us</span>
                <h2 class="ax-section-title">We're closer than you think</h2>
            </div>
        </div>
        <div class="ax-map-frame">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3686.027330197116!2d87.31!3d23.07!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zQmlzaG51cHVyLCBCYW5rdXJh!5e0!3m2!1sen!2sin!4v1700000000000"
                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

    {{-- ===================================================
         FAQ
    =================================================== --}}
    <section class="ax-faq">
        <div class="container">
            <div class="ax-section-head">
                <span class="ax-chip"><i class="fa-solid fa-circle-question"></i> FAQ</span>
                <h2 class="ax-section-title">Answers to common questions</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="accordion ax-accordion" id="faqAccordion">
                        @php
                            $faqs = [
                                ['q' => 'Who can donate blood?', 'a' => 'Anyone aged 18-65, weighing above 50kg and in good health can typically donate blood. A short screening is done before every donation to ensure your safety.'],
                                ['q' => 'How often can I donate?', 'a' => 'Whole blood donations can be made once every 90 days for men and every 120 days for women.'],
                                ['q' => 'Is blood donation safe?', 'a' => 'Yes — we use sterile, single-use equipment for every donor. The process takes about 10-15 minutes and is completely safe.'],
                                ['q' => 'How do I request blood urgently?', 'a' => 'Simply click "Request Blood" on our homepage and fill out the form. Nearby verified donors are notified instantly.'],
                                ['q' => 'Can I volunteer with ASRIJ?', 'a' => 'Absolutely! Register as a volunteer and our coordinator will reach out with upcoming camps and drives in your area.'],
                                ['q' => 'Is my personal information safe?', 'a' => 'We follow strict privacy guidelines. Your data is only shared with verified recipients when required for a donation.'],
                            ];
                        @endphp
                        @foreach ($faqs as $i => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqH{{ $i }}">
                                    <button class="accordion-button {{ $i === 0 ? '' : 'collapsed' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faqC{{ $i }}"
                                        aria-expanded="{{ $i === 0 ? 'true' : 'false' }}"
                                        aria-controls="faqC{{ $i }}">
                                        {{ $faq['q'] }}
                                    </button>
                                </h2>
                                <div id="faqC{{ $i }}"
                                    class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                                    aria-labelledby="faqH{{ $i }}" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">{{ $faq['a'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================================================
         CTA BAND
    =================================================== --}}
    <section class="ax-cta-wrap">
        <div class="container">
            <div class="ax-cta">
                <span class="ax-cta-bg"></span>
                <div class="ax-cta-text">
                    <h2>Need help in an emergency?</h2>
                    <p>Our team and volunteer network are available 24/7. Reach out and we'll act fast.</p>
                </div>
                <div class="ax-cta-actions">
                    <a href="tel:+917048115559" class="ax-btn ax-btn-white">
                        <i class="fa-solid fa-phone"></i> Call Now
                    </a>
                    <a href="{{ route('home') }}#registration-section" class="ax-btn ax-btn-outline-white">
                        <i class="fa-solid fa-user-plus"></i> Register
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
