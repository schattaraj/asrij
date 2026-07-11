@extends('layouts.app')

@section('title', 'Data Deletion — ASRIJ Blood Donation App')

@section('styles')
<style>
    /* ── Hero ── */
    .dd-hero {
        background: linear-gradient(135deg, #c0392b 0%, #922b21 100%);
        color: #fff;
        padding: 3.25rem 0 2.75rem;
    }
    .dd-hero h1 { font-weight: 800; font-size: 2rem; margin-bottom: .35rem; }
    .dd-hero p  { opacity: .87; margin-bottom: 0; font-size: 1rem; }

    /* ── General prose ── */
    .dd-body { line-height: 1.8; color: #374151; }
    .dd-body h2 {
        font-weight: 700;
        font-size: 1.3rem;
        margin-top: 2.25rem;
        margin-bottom: .75rem;
        color: #111827;
        scroll-margin-top: 90px;
    }
    .dd-body p, .dd-body li { font-size: .96rem; }

    /* ── Option cards ── */
    .option-card {
        border: 1.5px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .option-card-header {
        padding: .85rem 1.25rem;
        display: flex;
        align-items: center;
        gap: .65rem;
        font-weight: 700;
        font-size: 1rem;
    }
    .option-card-header.partial  { background: #ecfdf5; color: #065f46; border-bottom: 1.5px solid #a7f3d0; }
    .option-card-header.full     { background: #fef2f2; color: #7f1d1d; border-bottom: 1.5px solid #fca5a5; }
    .option-card-body { padding: 1.1rem 1.25rem; }

    /* ── Step cards ── */
    .step-item {
        display: flex;
        align-items: flex-start;
        gap: .75rem;
        padding: .65rem .9rem;
        border-left: 3px solid #c0392b;
        background: #fff7f7;
        border-radius: 0 8px 8px 0;
        margin-bottom: .55rem;
        font-size: .94rem;
    }
    .step-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px; height: 24px;
        border-radius: 50%;
        background: #c0392b;
        color: #fff;
        font-weight: 700;
        font-size: .78rem;
        flex-shrink: 0;
        margin-top: 1px;
    }
    .step-item.green  { border-left-color: #10b981; background: #f0fdf4; }
    .step-num.green   { background: #10b981; }

    /* ── Data tables ── */
    .data-table th { background: #f3f4f6; font-size: .875rem; }
    .data-table td { font-size: .875rem; vertical-align: middle; }
    .badge-del  { background: #fee2e2; color: #991b1b; font-weight: 600; padding: .25rem .6rem; border-radius: 6px; white-space: nowrap; }
    .badge-kept { background: #fef3c7; color: #92400e; font-weight: 600; padding: .25rem .6rem; border-radius: 6px; white-space: nowrap; }
    .badge-imm  { background: #dcfce7; color: #166534; font-weight: 600; padding: .25rem .6rem; border-radius: 6px; white-space: nowrap; }

    /* ── Info boxes ── */
    .info-box {
        border-left: 4px solid #10b981;
        background: #ecfdf5;
        border-radius: 0 10px 10px 0;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
    }
    .info-box.blue {
        border-left-color: #3b82f6;
        background: #eff6ff;
    }

    /* ── Section divider ── */
    .section-label {
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #9ca3af;
        margin-bottom: .5rem;
        margin-top: 2rem;
    }
</style>
@endsection

@section('content')

{{-- ── Hero ── --}}
<section class="dd-hero">
    <div class="container">
        <h1><i class="bi bi-shield-lock me-2"></i>Data Deletion Request</h1>
        <p class="mt-1">
            ASRIJ Blood Donation App &nbsp;·&nbsp; Developer: ASRIJ Foundation
            &nbsp;·&nbsp; Last updated: {{ date('d F Y') }}
        </p>
    </div>
</section>

{{-- ── Body ── --}}
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 dd-body">

                <p>
                    ASRIJ is a blood donation platform built and operated by the
                    <strong>ASRIJ Foundation</strong>. This page explains the two ways you can
                    request deletion of your personal data — either selectively (without closing your
                    account) or entirely (by deleting your account).
                </p>

                {{-- ── Option 1: Partial ── --}}
                <p class="section-label">Option 1 &mdash; Partial data deletion</p>

                <div class="option-card">
                    <div class="option-card-header partial">
                        <i class="bi bi-eraser fs-5"></i>
                        Delete specific data &mdash; keep your account
                    </div>
                    <div class="option-card-body">
                        <p class="mb-3">
                            You can delete individual data categories at any time from inside the
                            ASRIJ app without losing your account or donation history (unless you
                            choose to delete those too).
                        </p>

                        <div class="step-item green">
                            <span class="step-num green">1</span>
                            <span>Open the <strong>ASRIJ</strong> app and sign in.</span>
                        </div>
                        <div class="step-item green">
                            <span class="step-num green">2</span>
                            <span>Tap the <strong>Profile</strong> icon in the bottom navigation bar.</span>
                        </div>
                        <div class="step-item green">
                            <span class="step-num green">3</span>
                            <span>Tap the <strong>Settings</strong> icon (top-right of the profile screen).</span>
                        </div>
                        <div class="step-item green">
                            <span class="step-num green">4</span>
                            <span>Scroll to <strong>Data &amp; Privacy</strong> and tap <strong>Manage My Data</strong>.</span>
                        </div>
                        <div class="step-item green">
                            <span class="step-num green">5</span>
                            <span>
                                Toggle on the categories you want to delete
                                (Location, Profile Photo, Emergency Contacts, Donation History),
                                then tap <strong>Clear Selected Data</strong> and confirm.
                            </span>
                        </div>

                        <div class="info-box mt-3">
                            <i class="bi bi-lightning-charge me-2 text-success"></i>
                            <strong>Instant:</strong> selected data is permanently removed from our
                            servers as soon as you confirm. This action cannot be undone.
                        </div>

                        <p class="mb-2"><strong>Data categories you can delete independently:</strong></p>
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>What it includes</th>
                                        <th>After clearing</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><i class="bi bi-geo-alt me-1 text-warning"></i> Location &amp; Address</td>
                                        <td>Saved address, pin code, GPS latitude/longitude used for donor–recipient matching</td>
                                        <td><span class="badge-imm">Deleted immediately</span></td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-camera me-1 text-info"></i> Profile Photo</td>
                                        <td>Your avatar image stored on ASRIJ servers</td>
                                        <td><span class="badge-imm">Deleted immediately</span></td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-telephone me-1 text-danger"></i> Emergency Contacts</td>
                                        <td>Names and phone numbers you saved as emergency contacts for SOS</td>
                                        <td><span class="badge-imm">Deleted immediately</span></td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-droplet me-1 text-danger"></i> Donation History</td>
                                        <td>Records of blood donation requests you responded to as a donor</td>
                                        <td><span class="badge-imm">Deleted immediately</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ── Option 2: Full / Account deletion ── --}}
                <p class="section-label">Option 2 &mdash; Full data deletion (delete your account)</p>

                <div class="option-card">
                    <div class="option-card-header full">
                        <i class="bi bi-trash3 fs-5"></i>
                        Delete all data &mdash; close your account permanently
                    </div>
                    <div class="option-card-body">
                        <p class="mb-3">
                            Deleting your account erases <strong>all</strong> personal data after a
                            30-day grace period. You can cancel within those 30 days by logging back in.
                        </p>

                        <p class="fw-semibold mb-2">Option A &mdash; In-app (recommended)</p>
                        <div class="step-item">
                            <span class="step-num">1</span>
                            <span>Open the <strong>ASRIJ</strong> app and sign in.</span>
                        </div>
                        <div class="step-item">
                            <span class="step-num">2</span>
                            <span>Tap <strong>Profile</strong> → <strong>Settings</strong> → <strong>Account &amp; Profile</strong>.</span>
                        </div>
                        <div class="step-item">
                            <span class="step-num">3</span>
                            <span>Scroll to the bottom and tap <strong>Delete Account</strong>.</span>
                        </div>
                        <div class="step-item">
                            <span class="step-num">4</span>
                            <span>
                                Complete the 4-step wizard: choose a reason → review what will be lost
                                → type your registered mobile number to confirm → tap
                                <strong>"Delete forever"</strong>.
                            </span>
                        </div>

                        <p class="fw-semibold mt-3 mb-2">Option B &mdash; Email request</p>
                        <p>
                            If you cannot access the app, email
                            <a href="mailto:support@asrij.org"><strong>support@asrij.org</strong></a>
                            with subject <em>"Account Deletion Request"</em> and the mobile number
                            registered to your account. We process requests within
                            <strong>7 business days</strong>.
                        </p>

                        <div class="info-box blue mt-3">
                            <i class="bi bi-clock-history me-2 text-primary"></i>
                            <strong>30-day grace period:</strong> your account is scheduled for
                            deletion 30 days from the request date. Log in any time during those 30
                            days to cancel. After 30 days, deletion is permanent.
                        </div>

                        <p class="mb-2 mt-2"><strong>Data deleted after 30 days:</strong></p>
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr><th>Data</th><th>Examples</th><th>Retention</th></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Account &amp; identity</td>
                                        <td>Name, email, mobile, password hash, avatar</td>
                                        <td><span class="badge-del">Deleted after 30 days</span></td>
                                    </tr>
                                    <tr>
                                        <td>Blood requests</td>
                                        <td>All requests you submitted as a recipient</td>
                                        <td><span class="badge-del">Deleted after 30 days</span></td>
                                    </tr>
                                    <tr>
                                        <td>Donation responses</td>
                                        <td>Records of requests you responded to as a donor</td>
                                        <td><span class="badge-del">Deleted after 30 days</span></td>
                                    </tr>
                                    <tr>
                                        <td>Donor profile</td>
                                        <td>Blood group, availability, last donation date, health info</td>
                                        <td><span class="badge-del">Deleted after 30 days</span></td>
                                    </tr>
                                    <tr>
                                        <td>Emergency contacts</td>
                                        <td>Saved SOS contact names and numbers</td>
                                        <td><span class="badge-del">Deleted after 30 days</span></td>
                                    </tr>
                                    <tr>
                                        <td>Location data</td>
                                        <td>Address, latitude, longitude</td>
                                        <td><span class="badge-del">Deleted after 30 days</span></td>
                                    </tr>
                                    <tr>
                                        <td>Reward points &amp; achievements</td>
                                        <td>Donation points, badges</td>
                                        <td><span class="badge-del">Deleted after 30 days</span></td>
                                    </tr>
                                    <tr>
                                        <td>Push notification tokens</td>
                                        <td>FCM device tokens</td>
                                        <td><span class="badge-imm">Deleted immediately on request</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <p class="mb-2 mt-1"><strong>Data retained after account deletion:</strong></p>
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr><th>Data</th><th>Why retained</th><th>Period</th></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Deletion audit log</td>
                                        <td>Proof that the deletion was carried out</td>
                                        <td><span class="badge-kept">Up to 1 year</span></td>
                                    </tr>
                                    <tr>
                                        <td>Anonymised statistics</td>
                                        <td>Aggregate donation counts — no personal identifiers stored</td>
                                        <td><span class="badge-kept">Indefinitely (anonymised)</span></td>
                                    </tr>
                                    <tr>
                                        <td>Support correspondence</td>
                                        <td>Emails / messages sent to support@asrij.org</td>
                                        <td><span class="badge-kept">Up to 2 years</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ── Contact ── --}}
                <h2><i class="bi bi-envelope me-1"></i> Contact</h2>
                <p>
                    For questions about your data or this process, reach us at:
                </p>
                <ul>
                    <li>Email: <a href="mailto:support@asrij.org">support@asrij.org</a></li>
                    <li>Address: Sai Plaza Ground Floor, Police Chowki, Bishnupur, Bankura, West Bengal, India 722122</li>
                </ul>
                <p class="text-muted" style="font-size:.88rem;">
                    We do not sell your data. See our full
                    <a href="{{ route('privacy-policy') }}">Privacy Policy</a> for details on how
                    data is collected and used.
                </p>

            </div>
        </div>
    </div>
</section>

@endsection
