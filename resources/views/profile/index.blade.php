@extends('layouts.profile')

@section('content')
<style>
    /* ────────  Profile-page-scoped styles  ──────── */
    .profile-page .card { border: 1px solid #f1e6e8; border-radius: 14px; }
    .profile-page .section-title {
        font-size: 16px; font-weight: 700; color: #0f1724;
        margin-bottom: 14px; letter-spacing: -0.3px;
    }
    .profile-page .muted { color: #6a7280; font-size: 13.5px; }

    /* Avatar */
    .pp-avatar-wrap { position: relative; width: 110px; height: 110px; margin-right: 22px; flex-shrink: 0; }
    .pp-avatar {
        width: 110px; height: 110px; border-radius: 50%;
        object-fit: cover; border: 4px solid #fff;
        box-shadow: 0 8px 22px rgba(199,0,57,0.15);
        background: #ffe4eb;
    }
    .pp-avatar-fallback {
        width: 100%; height: 100%; border-radius: 50%;
        background: linear-gradient(135deg,#c70039,#ff4a6e);
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 32px; letter-spacing: -1px;
    }
    .pp-avatar-edit {
        position: absolute; bottom: 4px; right: 4px;
        background: #c70039; color: #fff; border: 2px solid #fff;
        width: 32px; height: 32px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
    }
    .pp-avatar-edit:hover { background: #a5002f; }

    /* Role chips */
    .role-chip {
        display: inline-flex; align-items: center; gap: 6px;
        background: #ffe4eb; color: #c70039;
        padding: 5px 12px; border-radius: 30px;
        font-size: 12px; font-weight: 600; letter-spacing: 0.4px;
        text-transform: uppercase; margin-right: 6px; margin-bottom: 6px;
    }
    .role-chip.add {
        background: #fff; color: #c70039;
        border: 1px dashed #c70039; cursor: pointer;
        text-decoration: none;
    }
    .role-chip.add:hover { background: #ffe4eb; }
    .verified-tick { color: #16a34a; }

    /* Activity tiles */
    .activity-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
    @media (max-width: 767.98px) { .activity-row { grid-template-columns: repeat(2, 1fr); } }
    .activity-tile {
        background: #fff; border: 1px solid #f1e6e8; border-radius: 14px;
        padding: 16px 14px; text-align: center;
    }
    .activity-tile .num {
        font-weight: 800; color: #c70039; font-size: 1.6rem; letter-spacing: -0.6px;
    }
    .activity-tile .lbl { color: #6a7280; font-size: 12px; font-weight: 500; }

    /* Devices */
    .device-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 14px; border: 1px solid #f0f1f5; border-radius: 12px;
        margin-bottom: 10px;
    }
    .device-row .device-info code {
        background: #f5f5f7; padding: 2px 8px; border-radius: 6px; font-size: 12px;
        color: #444;
    }
    .device-row .device-meta { color: #6a7280; font-size: 12px; margin-top: 4px; }
    .device-row .btn-revoke { font-size: 12px; }

    /* Tabs */
    .profile-page .nav-tabs .nav-link {
        color: #444; font-weight: 600; border-radius: 0;
    }
    .profile-page .nav-tabs .nav-link.active {
        color: #c70039; border-bottom: 2px solid #c70039; background: transparent;
    }

    /* Forms */
    .profile-page .form-control:focus,
    .profile-page .form-select:focus {
        border-color: rgba(199, 0, 57, 0.5);
        box-shadow: 0 0 0 0.2rem rgba(199, 0, 57, 0.18);
    }
    .pp-skel { background: #f5f5f7; border-radius: 6px; min-height: 14px; }
</style>

<div class="container mb-5 profile-page">
    {{-- HEADER CARD ──────────────────────────────────────────────── --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center flex-wrap">
                <div class="pp-avatar-wrap">
                    <img id="ppAvatar" class="pp-avatar d-none" alt="Avatar">
                    <div id="ppAvatarFallback" class="pp-avatar"><div class="pp-avatar-fallback">U</div></div>
                    <label for="ppAvatarFile" class="pp-avatar-edit" title="Change photo">
                        <i class="fa-solid fa-camera" style="font-size:14px;"></i>
                    </label>
                    <input type="file" id="ppAvatarFile" accept="image/*" hidden>
                </div>

                <div class="flex-grow-1" style="min-width:240px;">
                    <h3 class="mb-1" id="ppHeaderName"><div class="pp-skel" style="width:220px;height:22px;"></div></h3>
                    <div class="muted mb-2" id="ppHeaderMobile">
                        <div class="pp-skel" style="width:160px;height:12px;"></div>
                    </div>
                    <div id="ppRoleChips"></div>
                </div>

                <div class="text-end ms-auto mt-3 mt-md-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="ppRefresh()">
                        <i class="fa-solid fa-rotate-right me-1"></i> Refresh
                    </button>
                    @if(!empty($user) && (preg_match('/donor|receiver|volunteer/', json_encode($user->roles ?? []))))
                        <a href="#" class="btn btn-sm btn-outline-danger ms-2" onclick="ppRemoveAvatar()">
                            <i class="fa-solid fa-trash me-1"></i> Remove photo
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ACTIVITY ─────────────────────────────────────────────────── --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="section-title">My Activity</h5>
            <div class="activity-row">
                <div class="activity-tile">
                    <div class="num" id="actDonations">0</div>
                    <div class="lbl">Donations</div>
                </div>
                <div class="activity-tile">
                    <div class="num" id="actResponses">0</div>
                    <div class="lbl">Responses</div>
                </div>
                <div class="activity-tile">
                    <div class="num" id="actRequests">0</div>
                    <div class="lbl">Requests</div>
                </div>
                <div class="activity-tile">
                    <div class="num" id="actOpen">0</div>
                    <div class="lbl">Open Requests</div>
                </div>
            </div>
            <p class="muted mt-3 mb-0" id="actLast"></p>
        </div>
    </div>

    {{-- TABS ─────────────────────────────────────────────────────── --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-profile" type="button">Profile</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-role"    type="button">Role Details</button></li>
                {{-- <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-password" type="button">Change Password</button></li> --}}
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-devices"  type="button">Devices</button></li>
            </ul>

            <div class="tab-content pt-4">

                {{-- ─── PROFILE TAB ──────────────────────────────────── --}}
                <div class="tab-pane fade show active" id="tab-profile">
                    <form id="profileForm" onsubmit="event.preventDefault(); ppSaveProfile();" autocomplete="off">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" id="userName" name="name" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile</label>
                                <div class="input-group">
                                    <input type="text" id="userMobile" class="form-control" readonly>
                                    <button class="btn btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#mobileChangeModal">
                                        Change
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" id="userEmail" name="email" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Blood Group</label>
                                <select id="userBlood" name="blood_group" class="form-control">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+">A+</option><option value="A-">A-</option>
                                    <option value="B+">B+</option><option value="B-">B-</option>
                                    <option value="O+">O+</option><option value="O-">O-</option>
                                    <option value="AB+">AB+</option><option value="AB-">AB-</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">DOB</label>
                                <input type="date" id="userDOB" name="dob" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender</label>
                                <select id="userGender" name="gender" class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Address</label>
                                <div class="input-group">
                                    <input type="text" id="userAddress" name="address" class="form-control" readonly>
                                    <button type="button" class="btn btn-outline-danger open-location-modal"
                                            data-bs-toggle="modal" data-bs-target="#locationModal"
                                            data-location-input="userAddress"
                                            data-lat="userLat" data-lng="userLng">
                                        Change
                                    </button>
                                </div>
                                <input type="hidden" id="userLat" name="latitude">
                                <input type="hidden" id="userLng" name="longitude">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pin Code</label>
                                <input type="text" id="userPinCode" name="pin_code" class="form-control" maxlength="10">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">WhatsApp Number</label>
                                <input type="text" id="userWhatsapp" name="whatsapp_number" class="form-control" maxlength="20">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="ppSaveBtn">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Update Profile
                        </button>
                    </form>
                </div>

                {{-- ─── ROLE TAB ─────────────────────────────────────── --}}
                <div class="tab-pane fade" id="tab-role">
                    <div id="ppRoleSection">
                        <p class="muted mb-0">Loading role details…</p>
                    </div>
                </div>

                {{-- ─── PASSWORD TAB ─────────────────────────────────── --}}
                <div class="tab-pane fade" id="tab-password">
                    <form id="passwordForm" onsubmit="event.preventDefault(); ppChangePassword();" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="curPwd" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPwd" minlength="8" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="newPwdConfirm" minlength="8" required>
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fa-solid fa-key me-1"></i> Update Password
                        </button>
                    </form>
                </div>

                {{-- ─── DEVICES TAB ──────────────────────────────────── --}}
                <div class="tab-pane fade" id="tab-devices">
                    <p class="muted">Devices currently signed in &amp; eligible to receive push notifications.</p>
                    <div id="ppDeviceList"><div class="pp-skel" style="width:100%;height:60px;"></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MOBILE CHANGE MODAL ───────────────────────────────────────────── --}}
<div class="modal fade" id="mobileChangeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title">Change Mobile Number</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="mcStep1">
                    <label class="form-label">New mobile number</label>
                    <input type="text" class="form-control" id="mcNewMobile" maxlength="10" inputmode="numeric"
                           placeholder="10-digit number">
                    <small class="muted d-block mt-2">We'll send a 6-digit OTP to verify ownership.</small>
                </div>
                <div id="mcStep2" class="d-none">
                    <p class="muted small mb-2" id="mcOtpSentTo"></p>
                    <label class="form-label">Enter OTP</label>
                    <input type="text" class="form-control" id="mcOtp" maxlength="6" inputmode="numeric">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="mcPrimaryBtn" onclick="ppMobileSendOtp()">
                    Send OTP
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
/* ───────────────────────────────────────────────────────────────────
   Fully API-driven profile page. No PHP-rendered values here — the
   same endpoints are consumed by the React Native app.
───────────────────────────────────────────────────────────────────── */
const PROFILE_API_BASE = "{{ url('/api/v1') }}";

let _profileData = null;

function ppHeaders(json = true) {
    const t = localStorage.getItem('token');
    const h = { Accept: 'application/json' };
    if (json) h['Content-Type'] = 'application/json';
    if (t) h['Authorization'] = 'Bearer ' + t;
    return h;
}

function ppFire(icon, title, text) {
    Swal.fire({ icon, title, text,
        confirmButtonColor: icon === 'error' ? '#dc3545' : '#c70039' });
}

/* ── HYDRATION ───────────────────────────────────────────────────── */
async function ppLoadProfile() {
    showLoader && showLoader();
    try {
        const res  = await fetch(`${PROFILE_API_BASE}/profile`, { headers: ppHeaders() });
        const json = await res.json();

        if (!res.ok || !json.status) throw new Error(json.message || 'Failed to load profile.');

        _profileData = json.data;
        ppRenderHeader(json.data);
        ppRenderActivity(json.data.activity);
        ppRenderForm(json.data.user);
        ppRenderRoleSection(json.data);
    } catch (err) {
        ppFire('error', 'Oops!', err.message || 'Could not load your profile.');
    } finally {
        hideLoader && hideLoader();
    }
    ppLoadDevices(); // independent fetch
}

function ppRenderHeader(d) {
    const u = d.user;
    document.getElementById('ppHeaderName').innerText = u.name || '—';
    document.getElementById('ppHeaderMobile').innerHTML =
        `<i class="fa-solid fa-phone me-1"></i>${u.mobile || '—'}
         ${u.is_verified ? '<span class="verified-tick ms-2"><i class="fa-solid fa-circle-check"></i> Verified</span>' : ''}`;

    // Avatar
    const img = document.getElementById('ppAvatar');
    const fb  = document.getElementById('ppAvatarFallback');
    if (u.avatar_url) {
        img.src = u.avatar_url + '?t=' + Date.now();
        img.classList.remove('d-none');
        fb.classList.add('d-none');
    } else {
        img.classList.add('d-none');
        fb.classList.remove('d-none');
        fb.querySelector('.pp-avatar-fallback').innerText = (u.name || 'U').trim().charAt(0).toUpperCase();
    }

    // Role chips
    const chips = (d.user.roles || []).filter(r => r !== 'user');
    const missing = d.suggested_roles || [];
    const wrap = document.getElementById('ppRoleChips');
    wrap.innerHTML = '';
    chips.forEach(r => {
        const s = document.createElement('span');
        s.className = 'role-chip';
        s.innerHTML = `<i class="fa-solid fa-circle-check"></i> ${r}`;
        wrap.appendChild(s);
    });
    missing.forEach(r => {
        const a = document.createElement('a');
        a.className = 'role-chip add';
        a.href = "{{ route('home') }}#registration-section";
        a.innerHTML = `<i class="fa-solid fa-plus"></i> Become a ${r}`;
        wrap.appendChild(a);
    });
}

function ppRenderActivity(a) {
    if (!a) return;
    document.getElementById('actDonations').innerText = a.donations_count ?? 0;
    document.getElementById('actResponses').innerText = a.responses_count ?? 0;
    document.getElementById('actRequests').innerText  = a.requests_count ?? 0;
    document.getElementById('actOpen').innerText      = a.open_requests ?? 0;
    document.getElementById('actLast').innerText      = a.last_donation
        ? `Last donation: ${a.last_donation}` : 'No donations recorded yet.';
}

function ppRenderForm(u) {
    document.getElementById('userName').value     = u.name || '';
    document.getElementById('userMobile').value   = u.mobile || '';
    document.getElementById('userEmail').value    = u.email || '';
    document.getElementById('userBlood').value    = u.blood_group || '';
    document.getElementById('userDOB').value      = u.dob || '';
    document.getElementById('userGender').value   = u.gender || '';
    document.getElementById('userAddress').value  = u.address || '';
    document.getElementById('userPinCode').value  = u.pin_code || '';
    document.getElementById('userWhatsapp').value = u.whatsapp_number || '';
    document.getElementById('userLat').value      = u.latitude || '';
    document.getElementById('userLng').value      = u.longitude || '';
}

function ppRenderRoleSection(d) {
    const wrap = document.getElementById('ppRoleSection');
    const rd = d.role_data || {};
    const parts = [];

    if (rd.donor) {
        parts.push(`
            <h5 class="border-bottom pb-2 mb-3">Donor Details</h5>
            <p><strong>Blood Group:</strong> ${rd.donor.blood_group || '—'}</p>
            <p><strong>Year of Birth:</strong> ${rd.donor.year_of_birth || '—'}</p>
            <p><strong>Last Donation:</strong> ${rd.donor.last_donation ? new Date(rd.donor.last_donation).toLocaleDateString() : '—'}</p>
            <p><strong>Pin Code:</strong> ${rd.donor.pin_code || '—'}</p>
            <p><strong>Address:</strong> ${rd.donor.address || '—'}</p>
        `);
    }

    if (rd.receiver) {
        parts.push(`
            <h5 class="border-bottom pb-2 mb-3 mt-4">Receiver Details</h5>
            <p><strong>Type:</strong> ${rd.receiver.receiver_type || '—'}</p>
            <p><strong>Blood Group Needed:</strong> ${rd.receiver.blood_group || '—'}</p>
            <p><strong>Hospital:</strong> ${rd.receiver.hospital || '—'}</p>
        `);
    }

    if (rd.volunteer) {
        const v = rd.volunteer;
        let html = `
            <h5 class="border-bottom pb-2 mb-3 mt-4">Volunteer Details</h5>
            <p><strong>Position:</strong> ${(v.position || '').replace(/^./, c=>c.toUpperCase())}</p>
        `;
        if (v.volunteer_type === 'individual') {
            html += `
                <p><strong>Blood Group:</strong> ${(v.extra && v.extra.blood_group) || '—'}</p>
                <p><strong>Year of Birth:</strong> ${(v.extra && v.extra.year_of_birth) || '—'}</p>
            `;
        } else {
            html += `
                <p><strong>Organization:</strong> ${v.organization.organization_name || '—'}</p>
                <p><strong>Registration No:</strong> ${v.organization.registration_number || '—'}</p>
            `;
        }
        parts.push(html);
    }

    if (parts.length === 0) {
        const home = "{{ route('home') }}#registration-section";
        parts.push(`
            <p class="muted">You don't have any role-specific profile yet.</p>
            <a href="${home}" class="btn btn-outline-danger btn-sm">
                <i class="fa-solid fa-plus me-1"></i> Register as a Donor / Receiver / Volunteer
            </a>
        `);
    }

    wrap.innerHTML = parts.join('');
}

/* ── PROFILE FORM SAVE ───────────────────────────────────────────── */
async function ppSaveProfile() {
    const payload = {
        name:            document.getElementById('userName').value,
        email:           document.getElementById('userEmail').value || null,
        blood_group:     document.getElementById('userBlood').value || null,
        dob:             document.getElementById('userDOB').value || null,
        gender:          document.getElementById('userGender').value || null,
        address:         document.getElementById('userAddress').value || null,
        pin_code:        document.getElementById('userPinCode').value || null,
        whatsapp_number: document.getElementById('userWhatsapp').value || null,
        latitude:        document.getElementById('userLat').value || null,
        longitude:       document.getElementById('userLng').value || null,
    };

    const btn = document.getElementById('ppSaveBtn');
    btn.disabled = true;
    showLoader && showLoader();

    try {
        const res  = await fetch(`${PROFILE_API_BASE}/profile`, {
            method:  'PATCH',
            headers: ppHeaders(),
            body:    JSON.stringify(payload),
        });
        const json = await res.json();
        if (!res.ok || !json.status) throw new Error(json.message || 'Update failed.');

        _profileData = json.data;
        ppRenderHeader(json.data);
        ppRenderActivity(json.data.activity);
        ppRenderRoleSection(json.data);
        ppFire('success', 'Saved', 'Your profile was updated successfully.');
    } catch (e) {
        ppFire('error', 'Update failed', e.message);
    } finally {
        btn.disabled = false;
        hideLoader && hideLoader();
    }
}

/* ── AVATAR ──────────────────────────────────────────────────────── */
document.getElementById('ppAvatarFile').addEventListener('change', async (ev) => {
    const file = ev.target.files[0];
    if (!file) return;
    const form = new FormData();
    form.append('avatar', file);

    showLoader && showLoader();
    try {
        const res  = await fetch(`${PROFILE_API_BASE}/profile/avatar`, {
            method:  'POST',
            headers: ppHeaders(false), // multipart — no Content-Type
            body:    form,
        });
        const json = await res.json();
        if (!res.ok || !json.status) throw new Error(json.message || 'Upload failed.');
        if (_profileData) {
            _profileData.user.avatar     = json.avatar;
            _profileData.user.avatar_url = json.avatar_url;
            ppRenderHeader(_profileData);
        }
        ppFire('success', 'Photo updated', '');
    } catch (e) {
        ppFire('error', 'Upload failed', e.message);
    } finally {
        ev.target.value = '';
        hideLoader && hideLoader();
    }
});

async function ppRemoveAvatar() {
    const confirm = await Swal.fire({
        title: 'Remove profile photo?',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#dc3545', confirmButtonText: 'Remove',
    });
    if (!confirm.isConfirmed) return;
    try {
        const res  = await fetch(`${PROFILE_API_BASE}/profile/avatar`, { method: 'DELETE', headers: ppHeaders() });
        const json = await res.json();
        if (!res.ok || !json.status) throw new Error(json.message || 'Failed');
        if (_profileData) {
            _profileData.user.avatar = null;
            _profileData.user.avatar_url = null;
            ppRenderHeader(_profileData);
        }
        ppFire('success', 'Removed', '');
    } catch (e) {
        ppFire('error', 'Failed', e.message);
    }
}

/* ── PASSWORD ────────────────────────────────────────────────────── */
async function ppChangePassword() {
    const cur = document.getElementById('curPwd').value;
    const np  = document.getElementById('newPwd').value;
    const cf  = document.getElementById('newPwdConfirm').value;

    if (np !== cf) { ppFire('error', 'Mismatch', 'New password and confirmation do not match.'); return; }

    showLoader && showLoader();
    try {
        const res = await fetch(`${PROFILE_API_BASE}/profile/change-password`, {
            method: 'POST', headers: ppHeaders(),
            body: JSON.stringify({
                current_password: cur,
                new_password: np,
                new_password_confirmation: cf,
            }),
        });
        const json = await res.json();
        if (!res.ok || !json.status) throw new Error(json.message || 'Failed');
        document.getElementById('passwordForm').reset();
        ppFire('success', 'Password updated', '');
    } catch (e) {
        ppFire('error', 'Could not update password', e.message);
    } finally {
        hideLoader && hideLoader();
    }
}

/* ── MOBILE-CHANGE FLOW ──────────────────────────────────────────── */
let _mcStep = 1;

document.getElementById('mobileChangeModal').addEventListener('hidden.bs.modal', () => {
    _mcStep = 1;
    document.getElementById('mcStep1').classList.remove('d-none');
    document.getElementById('mcStep2').classList.add('d-none');
    document.getElementById('mcNewMobile').value = '';
    document.getElementById('mcOtp').value = '';
    document.getElementById('mcPrimaryBtn').innerText = 'Send OTP';
    document.getElementById('mcPrimaryBtn').setAttribute('onclick', 'ppMobileSendOtp()');
});

async function ppMobileSendOtp() {
    const mobile = document.getElementById('mcNewMobile').value.trim();
    if (!/^[0-9]{10}$/.test(mobile)) {
        ppFire('error', 'Invalid number', 'Enter a 10-digit mobile number.'); return;
    }
    showLoader && showLoader();
    try {
        const res  = await fetch(`${PROFILE_API_BASE}/profile/mobile/send-otp`, {
            method: 'POST', headers: ppHeaders(), body: JSON.stringify({ mobile }),
        });
        const json = await res.json();
        if (!res.ok || !json.status) throw new Error(json.message || 'Failed');

        _mcStep = 2;
        document.getElementById('mcStep1').classList.add('d-none');
        document.getElementById('mcStep2').classList.remove('d-none');
        document.getElementById('mcOtpSentTo').innerText = 'OTP sent to +91 ' + mobile;
        const btn = document.getElementById('mcPrimaryBtn');
        btn.innerText = 'Verify & Update';
        btn.setAttribute('onclick', 'ppMobileVerifyOtp()');
    } catch (e) {
        ppFire('error', 'Could not send OTP', e.message);
    } finally {
        hideLoader && hideLoader();
    }
}

async function ppMobileVerifyOtp() {
    const mobile = document.getElementById('mcNewMobile').value.trim();
    const otp    = document.getElementById('mcOtp').value.trim();
    if (!/^[0-9]{6}$/.test(otp)) {
        ppFire('error', 'Invalid OTP', 'Enter the 6-digit OTP.'); return;
    }

    showLoader && showLoader();
    try {
        const res  = await fetch(`${PROFILE_API_BASE}/profile/mobile/verify-otp`, {
            method: 'POST', headers: ppHeaders(),
            body: JSON.stringify({ mobile, otp }),
        });
        const json = await res.json();
        if (!res.ok || !json.status) throw new Error(json.message || 'Failed');

        const modal = bootstrap.Modal.getInstance(document.getElementById('mobileChangeModal'));
        modal.hide();
        if (_profileData) {
            _profileData.user.mobile = json.mobile;
            ppRenderHeader(_profileData);
            ppRenderForm(_profileData.user);
        }
        ppFire('success', 'Mobile updated', 'Your mobile number was changed successfully.');
    } catch (e) {
        ppFire('error', 'Could not verify OTP', e.message);
    } finally {
        hideLoader && hideLoader();
    }
}

/* ── DEVICES ─────────────────────────────────────────────────────── */
async function ppLoadDevices() {
    const wrap = document.getElementById('ppDeviceList');
    wrap.innerHTML = '<div class="pp-skel" style="width:100%;height:60px;"></div>';
    try {
        const res  = await fetch(`${PROFILE_API_BASE}/profile/devices`, { headers: ppHeaders() });
        const json = await res.json();
        if (!res.ok || !json.status) throw new Error(json.message || 'Failed');

        const list = json.data || [];
        if (!list.length) {
            wrap.innerHTML = '<p class="muted mb-0">No devices registered for push notifications yet.</p>';
            return;
        }

        wrap.innerHTML = list.map(d => `
            <div class="device-row">
                <div class="device-info">
                    <div><strong>Device</strong> &nbsp; <code>${d.token_preview || '—'}</code></div>
                    <div class="device-meta">
                        Registered ${new Date(d.created_at).toLocaleString()} ·
                        Last seen ${new Date(d.updated_at).toLocaleString()}
                    </div>
                </div>
                <button class="btn btn-sm btn-outline-danger btn-revoke" onclick="ppRevokeDevice(${d.id})">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> Revoke
                </button>
            </div>
        `).join('');
    } catch (e) {
        wrap.innerHTML = `<p class="text-danger small">Could not load devices: ${e.message}</p>`;
    }
}

async function ppRevokeDevice(id) {
    const confirm = await Swal.fire({
        title: 'Revoke device?',
        text: "This device will stop receiving push notifications.",
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#dc3545', confirmButtonText: 'Revoke',
    });
    if (!confirm.isConfirmed) return;

    try {
        const res  = await fetch(`${PROFILE_API_BASE}/profile/devices/${id}`, {
            method: 'DELETE', headers: ppHeaders(),
        });
        const json = await res.json();
        if (!res.ok || !json.status) throw new Error(json.message || 'Failed');
        ppLoadDevices();
        ppFire('success', 'Device revoked', '');
    } catch (e) {
        ppFire('error', 'Failed', e.message);
    }
}

/* ── MISC ────────────────────────────────────────────────────────── */
function ppRefresh() { ppLoadProfile(); }

document.addEventListener('DOMContentLoaded', () => {
    ppLoadProfile();
    ppInitMapPicker();
});

/* ── Self-contained Google Maps picker for #locationModal ─────────
   Centers on the user's saved coordinates each time the modal opens,
   drops a draggable marker, supports Places autocomplete in the
   search box, and on "Confirm Location" writes the selected address
   + lat/lng back into the profile form's #userAddress / #userLat /
   #userLng inputs (matching the data-* attributes the existing
   button defines).                                                  */
let _ppMap = null, _ppMarker = null, _ppGeocoder = null, _ppAutocomplete = null;

function ppInitMapPicker() {
    const modalEl = document.getElementById('locationModal');
    if (!modalEl) return;

    modalEl.addEventListener('shown.bs.modal', () => {
        if (!window.google || !window.google.maps) return;

        const lat = parseFloat(document.getElementById('userLat').value) || 22.9868;  // India centroid fallback
        const lng = parseFloat(document.getElementById('userLng').value) || 87.8550;
        const center = new google.maps.LatLng(lat, lng);

        if (!_ppMap) {
            _ppMap = new google.maps.Map(document.getElementById('map'), {
                center, zoom: 15, streetViewControl: false, mapTypeControl: false,
            });
            _ppGeocoder = new google.maps.Geocoder();

            // Places autocomplete on the search input
            const input = document.getElementById('mapSearchInput');
            if (input) {
                _ppAutocomplete = new google.maps.places.Autocomplete(input, {
                    fields: ['geometry', 'formatted_address'],
                });
                _ppAutocomplete.addListener('place_changed', () => {
                    const p = _ppAutocomplete.getPlace();
                    if (!p?.geometry?.location) return;
                    _ppPlaceMarker(p.geometry.location);
                    if (p.formatted_address) input.value = p.formatted_address;
                });
            }

            // Click anywhere to move marker
            _ppMap.addListener('click', (e) => _ppPlaceMarker(e.latLng, true));

            // Confirm-button handler
            document.getElementById('confirmLocation').addEventListener('click', () => {
                if (!_ppMarker) return;
                const pos = _ppMarker.getPosition();
                document.getElementById('userLat').value     = pos.lat();
                document.getElementById('userLng').value     = pos.lng();
                const addrText = document.getElementById('mapSearchInput').value;
                if (addrText) document.getElementById('userAddress').value = addrText;
                bootstrap.Modal.getInstance(modalEl).hide();
            });
        } else {
            // Re-open: recenter & re-place marker
            _ppMap.setCenter(center);
            _ppMap.setZoom(15);
        }

        _ppPlaceMarker(center, true);
        // Pre-fill search field with current address text
        const ad = document.getElementById('userAddress').value;
        if (ad) document.getElementById('mapSearchInput').value = ad;
    });

    // Clear (×) button inside the search field
    const clearBtn = document.getElementById('clearLocationBtn');
    if (clearBtn) clearBtn.addEventListener('click', () => {
        document.getElementById('mapSearchInput').value = '';
    });
}

function _ppPlaceMarker(latLng, reverseGeocode = false) {
    if (!_ppMap) return;
    if (!_ppMarker) {
        _ppMarker = new google.maps.Marker({
            position: latLng, map: _ppMap, draggable: true,
        });
        _ppMarker.addListener('dragend', (e) => _ppPlaceMarker(e.latLng, true));
    } else {
        _ppMarker.setPosition(latLng);
    }
    _ppMap.panTo(latLng);
    document.getElementById('map_latitude').value  = latLng.lat();
    document.getElementById('map_longitude').value = latLng.lng();

    if (reverseGeocode && _ppGeocoder) {
        _ppGeocoder.geocode({ location: latLng }, (results, status) => {
            if (status === 'OK' && results?.[0]) {
                document.getElementById('mapSearchInput').value = results[0].formatted_address;
            }
        });
    }
}

/* Expose for the layout's logout link */
window.logout = window.logout || async function () {
    try {
        await fetch(`${PROFILE_API_BASE}/logout`, { method: 'POST', headers: ppHeaders() });
    } catch (_) {}
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = "{{ url('/') }}";
};
</script>
@endsection
