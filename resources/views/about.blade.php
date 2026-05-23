@extends('layouts.app')
@section('title', 'About Us')

@section('content')
    {{-- ===================================================
         HERO — split layout, clean & plugin-free
    =================================================== --}}
    <section class="ax-hero">
        <span class="ax-bg-shape ax-bg-shape-1"></span>
        <span class="ax-bg-shape ax-bg-shape-2"></span>
        <span class="ax-bg-grid"></span>

        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 ax-hero-left">
                    <span class="ax-chip"><i class="fa-solid fa-droplet"></i> Who We Are</span>
                    <h1 class="ax-hero-title">
                        Saving Lives, <span class="ax-accent">One&nbsp;Drop</span> at a Time.
                    </h1>
                    <p class="ax-hero-sub">
                        ASRIJ is a community-driven blood donation network connecting verified donors,
                        receivers and volunteers — backed by technology and powered by empathy.
                    </p>
                    <div class="ax-hero-actions">
                        <a href="{{ route('home') }}#registration-section" class="ax-btn ax-btn-primary">
                            <i class="fa-solid fa-user-plus"></i> Become a Donor
                        </a>
                        <a href="#our-story" class="ax-btn ax-btn-ghost">
                            Read Our Story <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="ax-trust">
                        <div class="ax-trust-item">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Verified Donors</span>
                        </div>
                        <div class="ax-trust-item">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            <span>24/7 Support</span>
                        </div>
                        <div class="ax-trust-item">
                            <i class="fa-solid fa-location-crosshairs"></i>
                            <span>Nationwide</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 ax-hero-right">
                    <div class="ax-hero-visual">
                        <div class="ax-hero-photo">
                            <img src="{{ asset('assets/img/banner1.jpg') }}" alt="ASRIJ in action">
                        </div>
                        <div class="ax-hero-badge ax-hero-badge-tl">
                            <h4>2,300<span>+</span></h4>
                            <p>Units Donated</p>
                        </div>
                        <div class="ax-hero-badge ax-hero-badge-br">
                            <h4>1,100<span>+</span></h4>
                            <p>Lives Touched</p>
                        </div>
                        <div class="ax-hero-decor"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================================================
         OUR STORY
    =================================================== --}}
    <section class="ax-story" id="our-story">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="ax-story-img">
                        <img src="{{ asset('assets/img/banner2.jpg') }}" alt="Our story">
                        <div class="ax-story-meta">
                            <h3>5<span>+</span></h3>
                            <p>Years of Impact</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <span class="ax-chip"><i class="fa-solid fa-book-open"></i> Our Story</span>
                    <h2 class="ax-section-title">A mission born from compassion.</h2>
                    <p class="ax-muted">
                        ASRIJ began as a small community initiative with one belief — that no life should be
                        lost due to the unavailability of blood. Today, we bridge thousands of donors and
                        patients through a transparent, technology-driven network.
                    </p>
                    <ul class="ax-checklist">
                        <li><i class="fa-solid fa-check"></i> Real-time donor matching across cities</li>
                        <li><i class="fa-solid fa-check"></i> Verified, voluntary &amp; safe donations</li>
                        <li><i class="fa-solid fa-check"></i> Emergency response in minutes</li>
                        <li><i class="fa-solid fa-check"></i> Trained volunteer network on the ground</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================================================
         MISSION & VISION
    =================================================== --}}
    <section class="ax-mv">
        <div class="container">
            <div class="ax-section-head">
                <span class="ax-chip"><i class="fa-solid fa-compass"></i> Purpose</span>
                <h2 class="ax-section-title">What drives us forward</h2>
                <p class="ax-muted ax-section-sub">
                    Two simple ideas guide every decision we make and every life we help.
                </p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="ax-mv-card">
                        <span class="ax-mv-bar"></span>
                        <div class="ax-mv-icon"><i class="fa-solid fa-bullseye"></i></div>
                        <h3>Our Mission</h3>
                        <p>
                            To build a reliable, technology-driven blood donation ecosystem that
                            connects voluntary donors with patients in need — bringing transparency,
                            speed and trust to every life-saving moment.
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="ax-mv-card ax-mv-card-dark">
                        <span class="ax-mv-bar"></span>
                        <div class="ax-mv-icon"><i class="fa-solid fa-eye"></i></div>
                        <h3>Our Vision</h3>
                        <p>
                            A world where no one suffers because of a lack of blood — communities
                            united as one network of donors, volunteers and caregivers, ready to
                            respond at a moment's notice.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================================================
         FEATURES — Why us
    =================================================== --}}
    <section class="ax-features">
        <div class="container">
            <div class="ax-section-head">
                <span class="ax-chip"><i class="fa-solid fa-star"></i> Why Us</span>
                <h2 class="ax-section-title">Built for impact, designed for trust</h2>
            </div>
            <div class="row g-4">
                @php
                    $features = [
                        ['icon' => 'fa-shield-heart',  'title' => 'Trusted &amp; Verified', 'text' => 'Every donor is OTP-verified and screened for safe, voluntary donation.'],
                        ['icon' => 'fa-bolt',          'title' => 'Fast Response',          'text' => 'Smart matching connects urgent requests with the nearest available donors.'],
                        ['icon' => 'fa-people-group',  'title' => 'Volunteer Network',      'text' => 'A growing community organising drives, camps and awareness across cities.'],
                        ['icon' => 'fa-mobile-screen', 'title' => 'Easy To Use',            'text' => 'A clean, mobile-first interface — register or request help in minutes.'],
                        ['icon' => 'fa-lock',          'title' => 'Privacy First',          'text' => 'Your data is protected. We only share what is required to save a life.'],
                        ['icon' => 'fa-headset',       'title' => '24/7 Support',           'text' => 'Our team is available round the clock to assist donors and patients alike.'],
                    ];
                @endphp
                @foreach ($features as $f)
                    <div class="col-md-6 col-lg-4">
                        <div class="ax-feature">
                            <div class="ax-feature-icon">
                                <i class="fa-solid {{ $f['icon'] }}"></i>
                            </div>
                            <h4>{!! $f['title'] !!}</h4>
                            <p>{{ $f['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================================================
         STATS BAND (vanilla JS counter)
    =================================================== --}}
    <section class="ax-stats">
        <div class="container">
            <div class="row text-center g-4">
                @php
                    $stats = [
                        ['icon' => 'fa-droplet',              'num' => 2345, 'label' => 'Units Donated'],
                        ['icon' => 'fa-user-plus',            'num' => 1120, 'label' => 'Active Donors'],
                        ['icon' => 'fa-hand-holding-medical', 'num' => 980,  'label' => 'Lives Helped'],
                        ['icon' => 'fa-hands-helping',        'num' => 45,   'label' => 'Volunteers'],
                    ];
                @endphp
                @foreach ($stats as $s)
                    <div class="col-6 col-md-3">
                        <div class="ax-stat">
                            <div class="ax-stat-icon"><i class="fa-solid {{ $s['icon'] }}"></i></div>
                            <h2 class="ax-counter" data-target="{{ $s['num'] }}">0</h2>
                            <p>{{ $s['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================================================
         TEAM
    =================================================== --}}
    <section class="ax-team">
        <div class="container">
            <div class="ax-section-head">
                <span class="ax-chip"><i class="fa-solid fa-users"></i> Our Team</span>
                <h2 class="ax-section-title">The people behind ASRIJ</h2>
            </div>
            <div class="row g-4 justify-content-center">
                @php
                    $team = [
                        ['name' => 'Dr. Anil Mehta', 'role' => 'Founder &amp; Director',  'img' => 'https://i.pravatar.cc/400?img=12'],
                        ['name' => 'Priya Sharma',   'role' => 'Operations Head',         'img' => 'https://i.pravatar.cc/400?img=47'],
                        ['name' => 'Rahul Verma',    'role' => 'Volunteer Coordinator',   'img' => 'https://i.pravatar.cc/400?img=15'],
                        ['name' => 'Ananya Roy',     'role' => 'Community Outreach',      'img' => 'https://i.pravatar.cc/400?img=32'],
                    ];
                @endphp
                @foreach ($team as $m)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="ax-team-card">
                            <div class="ax-team-img">
                                <img src="{{ $m['img'] }}" alt="{{ $m['name'] }}">
                                <div class="ax-team-social">
                                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                                </div>
                            </div>
                            <div class="ax-team-body">
                                <h5>{{ $m['name'] }}</h5>
                                <p>{!! $m['role'] !!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
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
                    <h2>Be the reason someone smiles today.</h2>
                    <p>Register as a donor or volunteer and become part of a community that saves lives every day.</p>
                </div>
                <div class="ax-cta-actions">
                    <a href="{{ route('home') }}#registration-section" class="ax-btn ax-btn-white">
                        <i class="fa-solid fa-user-plus"></i> Register Now
                    </a>
                    <a href="#" class="ax-btn ax-btn-outline-white" data-bs-toggle="modal" data-bs-target="#donateModal">
                        <i class="fa-solid fa-heart"></i> Donate
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Vanilla counter — no plugin.
        (function () {
            const counters = document.querySelectorAll(".ax-counter");
            if (!counters.length) return;

            const animate = (el) => {
                const target = +el.getAttribute("data-target");
                const duration = 1400;
                const start = performance.now();
                const tick = (now) => {
                    const p = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - p, 3); // easeOutCubic
                    el.textContent = Math.floor(eased * target).toLocaleString();
                    if (p < 1) requestAnimationFrame(tick);
                    else el.textContent = target.toLocaleString();
                };
                requestAnimationFrame(tick);
            };

            const io = new IntersectionObserver((entries, obs) => {
                entries.forEach((e) => {
                    if (e.isIntersecting) { animate(e.target); obs.unobserve(e.target); }
                });
            }, { threshold: 0.4 });

            counters.forEach((c) => io.observe(c));
        })();
    </script>
@endsection
