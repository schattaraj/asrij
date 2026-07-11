@extends('layouts.app')

@section('title', 'Account Deletion — ASRIJ')

@section('styles')
<style>
    .policy-hero {
        background: var(--primary-color, #c0392b);
        color: #fff;
        padding: 3rem 0;
    }
    .policy-hero h1 { font-weight: 700; margin-bottom: .25rem; }
    .policy-hero p  { opacity: .85; margin-bottom: 0; }

    .policy-content { line-height: 1.8; color: #374151; }
    .policy-content h2 {
        font-weight: 700;
        font-size: 1.35rem;
        margin-top: 2.25rem;
        margin-bottom: .85rem;
        color: #111827;
        scroll-margin-top: 90px;
    }
    .policy-content p,
    .policy-content li { font-size: .97rem; }

    .step-card {
        border-left: 4px solid #c0392b;
        background: #fff7f7;
        border-radius: 0 10px 10px 0;
        padding: 1rem 1.25rem;
        margin-bottom: .75rem;
    }
    .step-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px; height: 28px;
        border-radius: 50%;
        background: #c0392b;
        color: #fff;
        font-weight: 700;
        font-size: .85rem;
        margin-right: .65rem;
        flex-shrink: 0;
    }
    .badge-deleted  { background: #fee2e2; color: #991b1b; }
    .badge-retained { background: #fef3c7; color: #92400e; }
    .data-table th { background: #f3f4f6; }
    .alert-grace {
        background: #ecfdf5;
        border-left: 4px solid #10b981;
        border-radius: 0 10px 10px 0;
        padding: 1rem 1.25rem;
    }
</style>
@endsection

@section('content')

{{-- ── Hero ── --}}
<section class="policy-hero">
    <div class="container">
        <h1><i class="bi bi-trash3 me-2"></i>Account &amp; Data Deletion</h1>
        <p class="mt-1">ASRIJ Blood Donation App &nbsp;·&nbsp; Last updated: {{ date('d F Y') }}</p>
    </div>
</section>

{{-- ── Body ── --}}
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 policy-content">

                <p>
                    ASRIJ ("the App") is a blood donation platform developed and operated by the
                    <strong>ASRIJ Foundation</strong>. We respect your right to have your personal
                    data removed from our systems. This page explains exactly how to request account
                    deletion, what data will be erased, and what may be retained.
                </p>

                {{-- ── How to delete ── --}}
                <h2><i class="bi bi-phone me-1"></i> How to Request Account Deletion</h2>
                <p>You can request deletion in two ways:</p>

                <p><strong>Option 1 — Inside the ASRIJ App (recommended)</strong></p>

                <div class="step-card d-flex align-items-start">
                    <span class="step-number">1</span>
                    <span>Open the <strong>ASRIJ</strong> app and sign in to your account.</span>
                </div>
                <div class="step-card d-flex align-items-start">
                    <span class="step-number">2</span>
                    <span>Tap the <strong>Profile</strong> icon in the bottom navigation bar.</span>
                </div>
                <div class="step-card d-flex align-items-start">
                    <span class="step-number">3</span>
                    <span>Tap <strong>Settings</strong> (gear icon, top-right of the profile screen).</span>
                </div>
                <div class="step-card d-flex align-items-start">
                    <span class="step-number">4</span>
                    <span>Select <strong>Account &amp; Profile</strong>.</span>
                </div>
                <div class="step-card d-flex align-items-start">
                    <span class="step-number">5</span>
                    <span>Scroll to the bottom and tap <strong>Delete Account</strong>.</span>
                </div>
                <div class="step-card d-flex align-items-start">
                    <span class="step-number">6</span>
                    <span>
                        Follow the 4-step confirmation wizard: select a reason, review what will be lost,
                        confirm your registered mobile number, then tap <strong>"Delete forever"</strong>.
                    </span>
                </div>

                <div class="alert-grace d-flex align-items-start mt-3 mb-4">
                    <i class="bi bi-clock-history fs-5 me-3 text-success mt-1"></i>
                    <div>
                        <strong>30-day grace period:</strong> Your account is scheduled for deletion
                        30 days from the date of your request. You can log back in at any time during
                        those 30 days to cancel the deletion. After 30 days the deletion is permanent
                        and cannot be reversed.
                    </div>
                </div>

                <p><strong>Option 2 — Email request</strong></p>
                <p>
                    If you cannot access the app, send an email to
                    <a href="mailto:support@asrij.org"><strong>support@asrij.org</strong></a>
                    with the subject line <em>"Account Deletion Request"</em> and include the
                    mobile number registered to your account. We will process your request within
                    <strong>7 business days</strong> and confirm by reply.
                </p>

                {{-- ── What is deleted ── --}}
                <h2><i class="bi bi-trash me-1"></i> Data That Will Be Permanently Deleted</h2>
                <p>
                    After the 30-day grace period the following data is <strong>permanently and
                    irreversibly erased</strong> from our servers:
                </p>
                <div class="table-responsive">
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>Data Category</th>
                                <th>Examples</th>
                                <th>Retention after request</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Account &amp; identity</td>
                                <td>Name, email, mobile number, password hash, avatar</td>
                                <td><span class="badge badge-deleted px-2 py-1 rounded">Deleted after 30 days</span></td>
                            </tr>
                            <tr>
                                <td>Blood requests</td>
                                <td>All requests you submitted (hospital, blood group, urgency, etc.)</td>
                                <td><span class="badge badge-deleted px-2 py-1 rounded">Deleted after 30 days</span></td>
                            </tr>
                            <tr>
                                <td>Donation responses</td>
                                <td>Records of requests you responded to as a donor</td>
                                <td><span class="badge badge-deleted px-2 py-1 rounded">Deleted after 30 days</span></td>
                            </tr>
                            <tr>
                                <td>Donor profile</td>
                                <td>Blood group, availability, last donation date, health info</td>
                                <td><span class="badge badge-deleted px-2 py-1 rounded">Deleted after 30 days</span></td>
                            </tr>
                            <tr>
                                <td>Emergency contacts</td>
                                <td>Names and numbers you saved as emergency contacts</td>
                                <td><span class="badge badge-deleted px-2 py-1 rounded">Deleted after 30 days</span></td>
                            </tr>
                            <tr>
                                <td>Reward points &amp; achievements</td>
                                <td>Donation points, badges</td>
                                <td><span class="badge badge-deleted px-2 py-1 rounded">Deleted after 30 days</span></td>
                            </tr>
                            <tr>
                                <td>Push notification tokens</td>
                                <td>FCM device tokens</td>
                                <td><span class="badge badge-deleted px-2 py-1 rounded">Deleted immediately on request</span></td>
                            </tr>
                            <tr>
                                <td>Location data</td>
                                <td>Saved address, latitude/longitude used for donor matching</td>
                                <td><span class="badge badge-deleted px-2 py-1 rounded">Deleted after 30 days</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- ── What is retained ── --}}
                <h2><i class="bi bi-archive me-1"></i> Data That May Be Retained</h2>
                <p>
                    A small amount of data may be retained after deletion for <strong>legal,
                    safety, and operational reasons</strong>:
                </p>
                <div class="table-responsive">
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>Data Category</th>
                                <th>Why retained</th>
                                <th>Retention period</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Deletion request audit log</td>
                                <td>Proof that the deletion was carried out</td>
                                <td><span class="badge badge-retained px-2 py-1 rounded">Up to 1 year</span></td>
                            </tr>
                            <tr>
                                <td>Anonymised aggregate statistics</td>
                                <td>
                                    Total donation counts, lives helped — no personally identifiable
                                    information is stored
                                </td>
                                <td><span class="badge badge-retained px-2 py-1 rounded">Indefinitely (anonymised)</span></td>
                            </tr>
                            <tr>
                                <td>Support correspondence</td>
                                <td>Emails or messages sent to support@asrij.org</td>
                                <td><span class="badge badge-retained px-2 py-1 rounded">Up to 2 years</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-muted" style="font-size:.9rem;">
                    We do <strong>not</strong> sell your data. Retained data is used solely for
                    compliance and internal reporting and is never shared with third parties
                    beyond what is described in our
                    <a href="{{ route('privacy-policy') }}">Privacy Policy</a>.
                </p>

                {{-- ── Contact ── --}}
                <h2><i class="bi bi-envelope me-1"></i> Questions?</h2>
                <p>
                    If you have any questions about this process or your data rights, contact us at:
                </p>
                <ul>
                    <li>Email: <a href="mailto:support@asrij.org">support@asrij.org</a></li>
                    <li>Address: Sai Plaza Ground Floor, Police Chowki, Bishnupur, Bankura, West Bengal, India 722122</li>
                </ul>

            </div>
        </div>
    </div>
</section>

@endsection
