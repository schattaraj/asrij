@extends('layouts.profile')
@section('content')
    <style>
        .card p {
            margin-bottom: 6px;
        }

        /* ── Activity flow styling (mirrors the mobile app) ── */
        .req-card {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
        }
        .token-row {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 12px 16px 0;
        }
        .token-label {
            font-size: 12px;
            font-weight: 700;
            color: #991b1b;
        }
        .token-text {
            font-size: 12px;
            font-weight: 800;
            color: #dc2626;
            letter-spacing: .5px;
        }
        .blood-circle {
            background: #fee2e2;
            color: #dc2626;
            font-weight: 800;
            border-radius: 20px;
            padding: 6px 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
        }
        .urgent-tag {
            background: #dc2626;
            color: #fff;
            font-size: 10px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
        .meta-chip {
            background: #f9fafb;
            border: 1px solid #eef2f7;
            border-radius: 8px;
            padding: 4px 9px;
            font-size: 12px;
            color: #374151;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            max-width: 100%;
        }
        .meta-chip .chip-ellipsis {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 220px;
        }
        .donor-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #fee2e2;
            color: #dc2626;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .donor-item {
            border: 1px solid #f1f1f1;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 10px;
            background: #fff;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }
        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            display: inline-block;
        }
        .context-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: #7c3aed;
            font-weight: 600;
        }
        .empty-wrap {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }
    </style>
    <div class="container mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">My Blood Requests</h4>
                        <span class="badge bg-light text-danger" id="requestCountBadge"></span>
                    </div>

                    <div class="card-body">
                        <div id="responsesCardContainer"></div>
                        <div id="emptyState" class="empty-wrap" style="display:none;">
                            <i class="fa-solid fa-droplet fa-3x mb-3" style="color:#fecaca;"></i>
                            <h5 class="fw-bold text-secondary">No Requests Yet</h5>
                            <p class="mb-0">When you post blood requests, donor responses will appear here.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="donorDetailsModal" tabindex="-1" aria-labelledby="donorDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="donorDetailsLabel">Donor Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> <span id="d_name"></span></p>
                            <p><strong>Blood Group:</strong> <span id="d_blood"></span></p>
                            <p><strong>Mobile:</strong> <span id="d_mobile"></span></p>
                            <p><strong>WhatsApp:</strong> <span id="d_whatsapp"></span></p>
                        </div>

                        <div class="col-md-6">
                            <p><strong>Address:</strong> <span id="d_address"></span></p>
                            <p><strong>Pincode:</strong> <span id="d_pin"></span></p>
                            <p><strong>Status:</strong> <span id="d_status"></span></p>
                            <p><strong>Distance:</strong> <span id="d_distance"></span></p>
                        </div>

                        <hr>

                        <div class="col-md-12">
                            <h6>Request Info</h6>
                            <p><strong>Hospital:</strong> <span id="r_hospital"></span></p>
                            <p><strong>Units Needed:</strong> <span id="r_unit"></span></p>
                            <p><strong>Required Within:</strong> <span id="r_time"></span></p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a class="btn btn-primary" id="modalCallBtn"><i class="fa-solid fa-phone me-1"></i> Call</a>
                    <button class="btn btn-success" id="modalAcceptBtn">Accept Donor</button>
                    <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@section('scripts')
    <script>
        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Success', text: "{{ session('success') }}", confirmButtonColor: '#0d6efd' });
        @endif
        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Oops!', text: "{{ session('error') }}", confirmButtonColor: '#dc3545' });
        @endif
        @if ($errors->any())
            Swal.fire({
                icon: 'error', title: 'Validation Error',
                html: `<ul style="text-align:left;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>`,
                confirmButtonColor: '#dc3545'
            });
        @endif

        const token = localStorage.getItem("token");
        const API_URL = "{{ route('fetchResponses') }}";
        const UPDATE_URL = "{{ route('updateResponse') }}";
        let allRequests = [];

        /* ── Status config (mirrors the mobile app) ── */
        function normalizeStatus(status) {
            return String(status || '').toLowerCase().trim().replace(/[\s-]+/g, '_');
        }
        const STATUS_CONFIG = {
            pending:                 { bg: '#fef9c3', text: '#854d0e', dot: '#f59e0b', label: 'Pending' },
            accepted:                { bg: '#dcfce7', text: '#166534', dot: '#16a34a', label: 'Donation Accepted' },
            reached_hospital:        { bg: '#dbeafe', text: '#1d4ed8', dot: '#2563eb', label: 'Reached Hospital' },
            donated:                 { bg: '#ede9fe', text: '#6d28d9', dot: '#7c3aed', label: 'Donated' },
            completed:               { bg: '#ede9fe', text: '#6d28d9', dot: '#7c3aed', label: 'Donated' },
            patient_confirmed:       { bg: '#dcfce7', text: '#166534', dot: '#16a34a', label: 'Patient Confirmed' },
            rejected:                { bg: '#fee2e2', text: '#991b1b', dot: '#dc2626', label: 'Rejected' },
        };
        function statusBadge(status) {
            const c = STATUS_CONFIG[normalizeStatus(status)] || STATUS_CONFIG.pending;
            return `<span class="status-badge" style="background:${c.bg};color:${c.text}">
                        <span class="status-dot" style="background:${c.dot}"></span>${c.label}
                    </span>`;
        }
        function esc(v) {
            return String(v ?? '').replace(/[&<>"']/g, (ch) => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
            }[ch]));
        }

        document.addEventListener('DOMContentLoaded', fetchRequests);

        function renderTable(data) {
            const container = document.getElementById("responsesCardContainer");
            const empty = document.getElementById("emptyState");
            container.innerHTML = "";

            document.getElementById("requestCountBadge").innerText =
                `${data.length} request${data.length !== 1 ? 's' : ''}`;

            if (!data.length) {
                empty.style.display = "block";
                return;
            }
            empty.style.display = "none";

            data.forEach(request => {
                const patientLat = parseFloat(request.patient_latitude);
                const patientLng = parseFloat(request.patient_longitude);
                const donors = request.donors || [];
                const acceptedCount = donors.filter(d => normalizeStatus(d.pivot?.status) === 'accepted').length;
                const awaitingConfirmation = request.status === 'awaiting_patient_confirmation';

                const contextChip = request.user_id
                    ? `<span class="context-chip"><i class="fa-solid fa-user-group"></i>${
                        request.user_id === request.submitted_by
                            ? 'Self Request'
                            : 'Requested for ' + esc(request.patient?.name || '-')}</span>`
                    : '';

                let donorRows = '';
                if (!donors.length) {
                    donorRows = `<div class="text-center text-muted py-3">No responses yet</div>`;
                } else {
                    donors.forEach(donor => {
                        const distance = calculateDistance(
                            patientLat, patientLng,
                            parseFloat(donor.latitude), parseFloat(donor.longitude)
                        );
                        const status = normalizeStatus(donor.pivot?.status);
                        const isPending = status === 'pending';

                        donorRows += `
                            <div class="donor-item">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                    <div class="d-flex gap-2">
                                        <div class="donor-avatar">${esc((donor.name || '?').charAt(0).toUpperCase())}</div>
                                        <div>
                                            <div class="fw-bold">${esc(donor.name || '-')}</div>
                                            <div class="text-muted small">
                                                <i class="fa-solid fa-droplet text-danger"></i> ${esc(donor.blood_group || '-')}
                                                &middot; <i class="fa-solid fa-location-arrow"></i> ${esc(distance)}
                                            </div>
                                            <a href="tel:${esc(donor.mobile)}" class="small text-primary text-decoration-none">
                                                <i class="fa-solid fa-phone"></i> ${esc(donor.mobile || '-')}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        ${statusBadge(donor.pivot?.status)}
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    <button class="btn btn-outline-secondary btn-sm"
                                        onclick='viewDetails(${JSON.stringify(donor)}, ${JSON.stringify(request)}, "${distance}")'
                                        data-bs-toggle="modal" data-bs-target="#donorDetailsModal">
                                        Details
                                    </button>
                                    ${isPending ? `
                                        <button class="btn btn-success btn-sm" onclick="acceptDonor(${request.id}, ${donor.id})">
                                            <i class="fa-solid fa-check"></i> Accept
                                        </button>` : ''}
                                    <a href="https://www.google.com/maps?q=${donor.latitude},${donor.longitude}"
                                        target="_blank" class="btn btn-primary btn-sm">
                                        <i class="fa-solid fa-map-location-dot"></i> Open Map
                                    </a>
                                    ${awaitingConfirmation ? `
                                        <button class="btn btn-success btn-sm w-100 mt-1"
                                            onclick="confirmReceived(${request.id}, ${donor.id})">
                                            <i class="fa-solid fa-circle-check"></i> Confirm Blood Received
                                        </button>` : ''}
                                </div>
                            </div>`;
                    });
                }

                const collapseId = `donors-${request.id}`;
                container.innerHTML += `
                    <div class="card req-card shadow-sm mb-3 border-start border-4 border-danger">
                        <div class="token-row">
                            <span class="token-label">Token :</span>
                            <span class="token-text">${esc(request.token || 'N/A')}</span>
                        </div>
                        <div class="card-header bg-white d-flex justify-content-between align-items-center"
                            role="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}">
                            <div class="d-flex align-items-center gap-2">
                                <span class="blood-circle"><i class="fa-solid fa-droplet"></i> ${esc(request.blood_group)}</span>
                                <div>
                                    <div class="fw-bold">${esc(request.hospital_name || '-')}</div>
                                    <div class="text-muted small">
                                        ${donors.length} response${donors.length !== 1 ? 's' : ''}${acceptedCount ? ' &middot; ' + acceptedCount + ' accepted' : ''}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                ${request.urgency === 'urgent' ? `<span class="urgent-tag"><i class="fa-solid fa-triangle-exclamation"></i> URGENT</span>` : ''}
                                <i class="fa-solid fa-chevron-down text-muted"></i>
                            </div>
                        </div>

                        <div class="px-3 py-2 d-flex flex-wrap gap-2 border-bottom">
                            <span class="meta-chip"><strong>Hospital:</strong> ${esc(request.hospital_name || '-')}</span>
                            <span class="meta-chip"><i class="fa-solid fa-droplet text-danger"></i> ${esc(request.unit)} units</span>
                            <span class="meta-chip"><i class="fa-regular fa-clock"></i> ${esc(request.required_before)} ${esc(request.required_before_unit)}</span>
                            ${contextChip ? `<span class="meta-chip">${contextChip}</span>` : ''}
                            <span class="meta-chip"><i class="fa-solid fa-location-dot"></i> <span class="chip-ellipsis">${esc(request.address || '-')}</span></span>
                        </div>

                        <div class="collapse show" id="${collapseId}">
                            <div class="card-body">
                                ${donorRows}
                            </div>
                        </div>
                    </div>`;
            });
        }

        async function fetchRequests() {
            showLoader();
            try {
                const option = { headers: { 'Accept': 'application/json', ...(token ? { Authorization: 'Bearer ' + token } : {}) } };
                const res = await fetch(API_URL, option);
                const data = await res.json();
                allRequests = data?.data || [];
                renderTable(allRequests);
            } catch (err) {
                console.error("Error loading requests", err);
                showAlert('error', 'Could not load your requests. Please try again.');
            }
            hideLoader();
        }

        async function updateResponseAction(requestId, donorId, action, successMsg) {
            showLoader();
            try {
                const res = await fetch(UPDATE_URL, {
                    method: "PUT",
                    headers: { "Content-Type": "application/json", "Authorization": `Bearer ${token}` },
                    body: JSON.stringify({ request_id: requestId, donor_id: donorId, action })
                });
                const data = await res.json();
                if (!res.ok || !data.status) {
                    throw new Error(data.message || "Something went wrong");
                }
                hideLoader();
                showAlert('success', successMsg);
                await fetchRequests();
            } catch (err) {
                hideLoader();
                showAlert('error', err.message || "Something went wrong");
            }
        }

        function acceptDonor(requestId, donorId) {
            Swal.fire({
                icon: 'question',
                title: 'Accept this donor?',
                text: 'Other pending donors for this request will be declined.',
                showCancelButton: true,
                confirmButtonText: 'Accept',
                confirmButtonColor: '#16a34a',
            }).then(result => {
                if (result.isConfirmed) {
                    updateResponseAction(requestId, donorId, 'accepted', 'Donor accepted successfully!');
                    const modalEl = document.getElementById('donorDetailsModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                }
            });
        }

        function confirmReceived(requestId, donorId) {
            Swal.fire({
                icon: 'question',
                title: 'Confirm blood received?',
                text: 'This will mark the donation as completed.',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                confirmButtonColor: '#16a34a',
            }).then(result => {
                if (result.isConfirmed) {
                    updateResponseAction(requestId, donorId, 'patient_confirmed', 'Donation confirmed successfully.');
                }
            });
        }

        function viewDetails(donor, request, distance) {
            document.getElementById("d_name").innerText = donor.name || '-';
            document.getElementById("d_blood").innerText = donor.blood_group || '-';
            document.getElementById("d_mobile").innerText = donor.mobile || '-';
            document.getElementById("d_whatsapp").innerText = donor.whatsapp_number || '-';
            document.getElementById("d_address").innerText = donor.address || '-';
            document.getElementById("d_pin").innerText = donor.pin_code || '-';
            document.getElementById("d_status").innerHTML = statusBadge(donor.pivot?.status);
            document.getElementById("d_distance").innerText = distance;

            document.getElementById("r_hospital").innerText = request.hospital_name || '-';
            document.getElementById("r_unit").innerText = request.unit ?? '-';
            document.getElementById("r_time").innerText =
                `${request.required_before || ''} ${request.required_before_unit || ''}`;

            document.getElementById("modalCallBtn").href = `tel:${donor.mobile || ''}`;

            const acceptBtn = document.getElementById("modalAcceptBtn");
            if (normalizeStatus(donor.pivot?.status) === 'pending') {
                acceptBtn.style.display = '';
                acceptBtn.onclick = () => acceptDonor(request.id, donor.id);
            } else {
                acceptBtn.style.display = 'none';
            }
        }
    </script>
@endsection
@endsection
