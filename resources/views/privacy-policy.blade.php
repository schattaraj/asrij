@extends('layouts.app')

{{-- Page title used by the layout: <title>@yield('title') | BloodConnect Portal</title> --}}
@section('title', 'Privacy Policy')

@section('content')
    {{--
        Privacy Policy page.
        Content extracted from "ASRIJ - Privacy Policy.pdf" (Last Updated: 04/07/2026)
        and converted to clean, semantic, Bootstrap 5 markup.
        All dynamic-looking values are static text from the source document.
    --}}

    {{-- Page-scoped styles: kept in one place instead of scattered inline styles --}}
    @section('styles')
        <style>
            .policy-hero {
                background: var(--primary-color, #c0392b);
                color: #fff;
                padding: 3rem 0;
            }
            .policy-hero h1 {
                font-weight: 700;
                margin-bottom: .25rem;
            }
            .policy-content {
                line-height: 1.75;
            }
            .policy-content h2 {
                font-weight: 700;
                font-size: 1.5rem;
                margin-top: 2.25rem;
                margin-bottom: 1rem;
                scroll-margin-top: 90px;
            }
            .policy-content h3 {
                font-weight: 600;
                font-size: 1.15rem;
                margin-top: 1.5rem;
                margin-bottom: .75rem;
            }
            .policy-content p,
            .policy-content li {
                color: #444;
            }
            .policy-content ul,
            .policy-content ol {
                padding-left: 1.25rem;
            }
            .policy-content li {
                margin-bottom: .5rem;
            }
            .policy-note {
                border-left: 4px solid var(--primary-color, #c0392b);
                background: #f8f9fa;
            }
        </style>
    @endsection

    {{-- ===== HERO / PAGE TITLE ===== --}}
    <section class="policy-hero text-center">
        <div class="container">
            <h1>Privacy Policy</h1>
            <p class="mb-2 opacity-75">ASRIJ Foundation — Every Drop Counts, Every Life Matters</p>
            {{-- <p class="mb-0 small"><strong>Last Updated:</strong> 04/07/2026</p> --}}
        </div>
    </section>

    {{-- ===== POLICY BODY ===== --}}
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <article class="policy-content">

                        {{-- 1. Introduction --}}
                        <h2 id="introduction">1. Introduction</h2>
                        <p>
                            ASRIJ Foundation ("ASRIJ," "we," "us," or "our") is a registered Section 8 non-profit
                            organization (Darpan ID: WB/2026/1035037) based in Bishnupur, Bankura, West Bengal, India.
                            We operate a free, location-based blood donor networking mobile application and website
                            (the "Platform") that connects blood seekers, registered donors, and volunteers.
                        </p>
                        <p>
                            This Privacy Policy explains how we collect, use, store, share, and protect your personal
                            information when you use our Platform. It applies to all users —
                            <strong>blood requesters, donors, and volunteers</strong>. By registering on or using the
                            Platform, you consent to the practices described here.
                        </p>
                        <p>
                            We are committed to complying with the <strong>Digital Personal Data Protection (DPDP)
                            Act, 2023</strong> and other applicable Indian laws.
                        </p>
                        <p class="fw-semibold">
                            ASRIJ Foundation acts strictly as a facilitator connecting blood requesters and donors.
                            We are not a blood bank, medical provider, or healthcare service, and we do not collect,
                            store, test, or handle blood or blood products.
                        </p>

                        {{-- 2. Information We Collect --}}
                        <h2 id="information-we-collect">2. Information We Collect</h2>

                        <h3>2.1 Information You Provide</h3>
                        <ul>
                            <li><strong>Identity information:</strong> Full name, gender, date of birth/age</li>
                            <li><strong>Contact information:</strong> Phone number, email address</li>
                            <li><strong>Location information:</strong> City, area code, and (where enabled) real-time
                                location for donor-patient proximity matching</li>
                            <li><strong>Health-related information (donors only):</strong> Blood group, last donation
                                date, self-declared eligibility/health status</li>
                            <li><strong>Account information:</strong> Username, password (encrypted), profile photo
                                (optional)</li>
                            <li><strong>Volunteer information:</strong> Institutional affiliation (e.g., college,
                                NSS/NCC unit), areas of interest</li>
                        </ul>

                        <h3>2.2 Information Collected Automatically</h3>
                        <ul>
                            <li>Device information (device type, operating system, unique device identifiers)</li>
                            <li>App usage data (login frequency, feature usage, request/response history)</li>
                            <li>Approximate or precise location data (with your permission), used to match nearby donors
                                and requesters</li>
                        </ul>

                        <h3>2.3 Information We Do Not Collect</h3>
                        <p>
                            We do not collect or store medical records, blood test results, diagnostic reports, or any
                            information from hospitals or blood banks. All health-related declarations (e.g., blood
                            group, donation eligibility) are self-reported by the user.
                        </p>

                        {{-- 3. How We Use Your Information --}}
                        <h2 id="how-we-use">3. How We Use Your Information</h2>
                        <p>We use your information solely to:</p>
                        <ol>
                            <li>Match blood requesters with nearby registered, willing donors</li>
                            <li>Notify donors of nearby genuine blood requests</li>
                            <li>Verify donor/volunteer identity and prevent misuse of the Platform</li>
                            <li>Facilitate communication between requesters and donors (contact details are shared only
                                with mutual consent)</li>
                            <li>Maintain donor and volunteer recognition records (e.g., ID cards, tiered recognition)</li>
                            <li>Improve Platform functionality, safety, and matching accuracy</li>
                            <li>Send service-related notifications, alerts, and (optionally) awareness communications</li>
                            <li>Comply with legal, regulatory, or governmental requirements</li>
                        </ol>
                        <p class="fw-semibold">
                            We do not sell, rent, or trade your personal information to any third party for marketing or
                            commercial purposes.
                        </p>

                        {{-- 4. Legal Basis & Consent --}}
                        <h2 id="legal-basis">4. Legal Basis &amp; Consent</h2>
                        <p>We process your personal data based on:</p>
                        <ul>
                            <li><strong>Your explicit consent</strong>, obtained at the time of registration through a
                                clear declaration/consent form</li>
                            <li><strong>Legitimate purpose</strong>, being the facilitation of voluntary, non-commercial
                                blood donation</li>
                            <li><strong>Compliance with legal obligations</strong>, where applicable</li>
                        </ul>
                        <p>
                            You may withdraw consent at any time by contacting us at
                            <a href="mailto:support@asrij.org">support@asrij.org</a>, subject to reasonable notice and
                            any legal retention requirements.
                        </p>

                        {{-- 5. How We Share Your Information --}}
                        <h2 id="how-we-share">5. How We Share Your Information</h2>
                        <p>We may share limited information only in the following circumstances:</p>
                        <ul>
                            <li><strong>Between matched users:</strong> A donor's name, contact number, and blood group
                                may be shared with a verified requester (and vice versa) strictly to facilitate a
                                donation, and only upon mutual willingness</li>
                            <li><strong>With volunteers/field coordinators:</strong> For verification and coordination
                                purposes only</li>
                            <li><strong>With service providers:</strong> Such as hosting, SMS/notification, or
                                map/location service providers (e.g., Google Maps APIs), strictly to operate the
                                Platform</li>
                            <li><strong>Legal requirements:</strong> If required by law, court order, or government
                                authority</li>
                            <li><strong>With your consent:</strong> For any other purpose not listed above</li>
                        </ul>
                        <p>We do not share your data with advertisers or unrelated third parties.</p>

                        {{-- 6. Data Storage & Security --}}
                        <h2 id="data-storage">6. Data Storage &amp; Security</h2>
                        <ul>
                            <li>Your data is stored on secure servers with access restricted to authorized ASRIJ
                                personnel only</li>
                            <li>We implement reasonable technical and organizational safeguards (encryption, access
                                controls) to protect your data from unauthorized access, alteration, or disclosure</li>
                            <li>While we take reasonable precautions, no method of electronic storage or transmission is
                                100% secure, and we cannot guarantee absolute security</li>
                        </ul>

                        {{-- 7. Data Retention --}}
                        <h2 id="data-retention">7. Data Retention</h2>
                        <p>
                            We retain your personal information only as long as necessary to fulfill the purposes
                            described in this policy, or as required by applicable law. You may request deletion of your
                            account and associated data at any time (see Section 9).
                        </p>

                        {{-- 8. Location Data --}}
                        <h2 id="location-data">8. Location Data</h2>
                        <p>
                            Our Platform uses <strong>location-based matching</strong> (not merely PIN code-based) to
                            connect donors and requesters efficiently. Location access is used only to:
                        </p>
                        <ul>
                            <li>Identify nearby donors for an active request</li>
                            <li>Estimate donor-to-patient distance</li>
                        </ul>
                        <p>
                            You may disable location permissions at any time through your device settings, though this
                            may limit matching accuracy.
                        </p>

                        {{-- 9. Your Rights --}}
                        <h2 id="your-rights">9. Your Rights</h2>
                        <p>As a user, you have the right to:</p>
                        <ul>
                            <li><strong>Access</strong> the personal information we hold about you</li>
                            <li><strong>Correct</strong> inaccurate or outdated information</li>
                            <li><strong>Withdraw consent</strong> and opt out of communications</li>
                            <li><strong>Request deletion</strong> of your account and personal data</li>
                            <li><strong>Lodge a grievance</strong> regarding how your data is handled</li>
                            <li><strong>Account deletion</strong> permanently removes your profile, data, and access to
                                a service.</li>
                        </ul>
                        <p>
                            To exercise any of these rights, contact us at
                            <a href="mailto:support@asrij.org">support@asrij.org</a>.
                        </p>

                        {{-- 10. Children's Privacy --}}
                        <h2 id="childrens-privacy">10. Children's Privacy</h2>
                        <p>
                            Our Platform is intended for users who meet the legal age and health eligibility criteria for
                            blood donation as prescribed under Indian law. We do not knowingly collect data from
                            individuals below the eligible age for blood donation without appropriate consent.
                        </p>

                        {{-- 11. Role-Specific Notes --}}
                        <h2 id="role-specific-notes">11. Role-Specific Notes</h2>
                        <p>
                            <strong>Blood Requesters:</strong> Information submitted in a blood request is shared only
                            with matched donors and used solely to facilitate assistance.
                        </p>
                        <p>
                            <strong>Donors:</strong> Health-related self-declarations are used only to determine matching
                            eligibility. ASRIJ does not verify donor health status and is not liable for medical
                            outcomes; donation remains voluntary and non-commercial.
                        </p>
                        <p>
                            <strong>Volunteers:</strong> Volunteer conduct and data use are additionally governed by the
                            Volunteer Declaration Form signed at the time of registration.
                        </p>

                        {{-- 12. Changes to This Policy --}}
                        <h2 id="changes">12. Changes to This Policy</h2>
                        <p>
                            We may update this Privacy Policy from time to time to reflect changes in our practices or
                            legal requirements. The updated version will be posted on this page with a revised
                            "Last Updated: 04/07/2026" date. Continued use of the Platform after changes constitutes
                            acceptance of the revised policy.
                        </p>

                        {{-- 13. Grievance Redressal & Contact --}}
                        <h2 id="contact">13. Grievance Redressal &amp; Contact</h2>
                        <p>
                            For any questions, concerns, or complaints regarding this Privacy Policy or your personal
                            data:
                        </p>
                        <address class="mb-4">
                            <strong>ASRIJ Foundation</strong><br>
                            Bishnupur, Bankura, West Bengal, India<br>
                            Email: <a href="mailto:support@asrij.org">support@asrij.org</a><br>
                            Website: <a href="https://www.asrij.org" target="_blank" rel="noopener">www.asrij.org</a>
                        </address>

                        {{-- Legal disclaimer note from the source document --}}
                        <div class="policy-note p-3 rounded">
                            <p class="mb-0 fst-italic">
                                This Privacy Policy should be reviewed by a qualified legal professional to ensure full
                                compliance with the DPDP Act, 2023, the Drugs and Cosmetics Act, 1940, and other
                                applicable regulations before publication.
                            </p>
                        </div>

                    </article>
                </div>
            </div>
        </div>
    </section>
@endsection
