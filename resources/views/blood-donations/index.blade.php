@extends('layouts.profile')
@section('content')
    <style>
        .card p {
            margin-bottom: 6px;
        }

        /* ── Donation flow styling (mirrors the mobile app) ── */
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
        }
        .flow {
            background: #f9fafb;
            border: 1px solid #eef2f7;
            border-radius: 12px;
            padding: 12px 14px;
        }
        .flow-step {
            display: flex;
            gap: 10px;
        }
        .flow-marker {
            width: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .flow-dot {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid #d1d5db;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .flow-dot.complete {
            border-color: #16a34a;
            background: #dcfce7;
        }
        .flow-line {
            width: 2px;
            flex: 1;
            min-height: 16px;
            background: #e5e7eb;
            margin: 2px 0;
        }
        .flow-line.complete {
            background: #86efac;
        }
        .flow-label {
            font-size: 13px;
            color: #6b7280;
            padding-bottom: 12px;
            padding-left: 2px;
        }
        .flow-label.complete {
            color: #166534;
            font-weight: 700;
        }
        .btn-action {
            background: var(--primary-color, #c70039);
            color: #fff;
            font-weight: 700;
        }
        .btn-action:hover {
            background: #a5002f;
            color: #fff;
        }
        .waiting-confirm {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
            border-radius: 10px;
            padding: 9px 12px;
            font-size: 12.5px;
            font-weight: 600;
        }
        .pending-strip {
            background: #fffbeb;
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 12px;
            color: #92400e;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .donation-footer {
            border-top: 1px solid #f3f4f6;
            padding-top: 8px;
            margin-top: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
        }
        .req-status-chip {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 6px;
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
                        <h4 class="mb-0">My Blood Donations</h4>
                        <span class="badge bg-light text-danger" id="donationCountBadge"></span>
                    </div>

                    <div class="card-body">
                        <div id="pendingSummary" class="pending-strip mb-3" style="display:none;"></div>
                        <div class="row" id="myDonationsCardContainer"></div>
                        <div id="emptyState" class="empty-wrap" style="display:none;">
                            <i class="fa-solid fa-heart fa-3x mb-3" style="color:#fecaca;"></i>
                            <h5 class="fw-bold text-secondary">No Donations Yet</h5>
                            <p class="mb-0">When you respond to blood requests, your donation history will appear here.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="donationDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Donation Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Patient Info</h6>
                            <p><strong>Name:</strong> <span id="m_name"></span></p>
                            <p><strong>Blood Group:</strong> <span id="m_blood"></span></p>
                            <p><strong>Patient Type:</strong> <span id="m_type"></span></p>
                            <p><strong>Units:</strong> <span id="m_unit"></span></p>
                        </div>

                        <div class="col-md-6">
                            <h6>Contact Info</h6>
                            <p><strong>Mobile:</strong> <span id="m_mobile"></span></p>
                            <p><strong>WhatsApp:</strong> <span id="m_whatsapp"></span></p>
                            <p><strong>Address:</strong> <span id="m_address"></span></p>
                            <p><strong>Pincode:</strong> <span id="m_pin"></span></p>
                        </div>

                        <hr>

                        <div class="col-md-6">
                            <h6>Hospital Info</h6>
                            <p><strong>Hospital:</strong> <span id="m_hospital"></span></p>
                            <p><strong>Required Within:</strong> <span id="m_time"></span></p>
                            <p><strong>Urgency:</strong> <span id="m_urgency"></span></p>
                        </div>

                        <div class="col-md-6">
                            <h6>Your Response</h6>
                            <p><strong>Status:</strong> <span id="m_status"></span></p>
                            <p><strong>Your Contact:</strong> <span id="m_contact"></span></p>
                        </div>
                        <div class="col-md-12 mt-3">
                            <h6>Prescription</h6>
                            <img id="m_prescription" src="" class="img-fluid rounded border" />
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a id="callBtn" class="btn btn-success">Call</a>
                    <a id="waBtn" target="_blank" class="btn btn-success">WhatsApp</a>
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

        const API_URL = "{{ route('myBloodDonations') }}";
        const UPDATE_URL = "{{ route('updateResponse') }}";
        const CANCEL_RESPONSE_URL = "{{ route('cancelResponse', ['id' => '__RESPONSE_ID__']) }}";
        let allRequests = [];

        /* ── Status config (mirrors the mobile app) ── */
        function normalizeStatus(status) {
            return String(status || '').toLowerCase().trim().replace(/[\s-]+/g, '_');
        }
        const STATUS_CONFIG = {
            pending:           { bg: '#fef9c3', text: '#854d0e', dot: '#f59e0b', label: 'Pending' },
            accepted:          { bg: '#dcfce7', text: '#166534', dot: '#16a34a', label: 'Donation Accepted' },
            reached_hospital:  { bg: '#dbeafe', text: '#1d4ed8', dot: '#2563eb', label: 'Reached Hospital' },
            donated:           { bg: '#ede9fe', text: '#6d28d9', dot: '#7c3aed', label: 'Donated' },
            completed:         { bg: '#ede9fe', text: '#6d28d9', dot: '#7c3aed', label: 'Donated' },
            patient_confirmed: { bg: '#dcfce7', text: '#166534', dot: '#16a34a', label: 'Patient Confirmed' },
            rejected:          { bg: '#fee2e2', text: '#991b1b', dot: '#dc2626', label: 'Rejected' },
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

        /* ── Donation flow (donor's progress) ── */
        const DONATION_FLOW = [
            { key: 'accepted',          label: 'Patient accepted your response' },
            { key: 'reached_hospital',  label: 'Donor Reached Hospital' },
            { key: 'donated',           label: 'Donor Donated' },
            { key: 'patient_confirmed', label: 'Patient Confirmed' },
        ];
        const DONATION_FLOW_STATUS_INDEX = {
            accepted: 0,
            reached_hospital: 1,
            donated: 2,
            completed: 2,
            patient_confirmed: 3,
            confirmed: 3,
        };

        function renderFlow(item) {
            const status = normalizeStatus(item.status);
            const activeIndex = DONATION_FLOW_STATUS_INDEX[status] ?? -1;
            if (activeIndex < 0) return '';

            let steps = '';
            DONATION_FLOW.forEach((step, index) => {
                const isComplete = index <= activeIndex;
                const isLast = index === DONATION_FLOW.length - 1;
                steps += `
                    <div class="flow-step">
                        <div class="flow-marker">
                            <div class="flow-dot ${isComplete ? 'complete' : ''}">
                                ${isComplete ? '<i class="fa-solid fa-check" style="font-size:9px;color:#16a34a"></i>' : ''}
                            </div>
                            ${!isLast ? `<div class="flow-line ${index < activeIndex ? 'complete' : ''}"></div>` : ''}
                        </div>
                        <div class="flow-label ${isComplete ? 'complete' : ''}">${step.label}</div>
                    </div>`;
            });

            let action = '';
            if (activeIndex === 0) {
                action = `<button class="btn btn-sm btn-action w-100 mt-2"
                            onclick="donationAction(${item.request.id}, ${item.donor_id}, 'reached_hospital')">
                            <i class="fa-solid fa-location-arrow me-1"></i> Mark Reached Hospital
                          </button>`;
            } else if (activeIndex === 1) {
                action = `<button class="btn btn-sm btn-action w-100 mt-2"
                            onclick="donationAction(${item.request.id}, ${item.donor_id}, 'donated')">
                            <i class="fa-solid fa-heart me-1"></i> Mark Donated
                          </button>`;
            } else if (activeIndex === 2) {
                action = `<div class="waiting-confirm mt-2">
                            <i class="fa-regular fa-clock me-1"></i> Waiting for patient confirmation
                          </div>`;
            }

            return `<div class="flow mt-2 mb-2">${steps}${action}</div>`;
        }

        document.addEventListener('DOMContentLoaded', fetchRequests);

        function renderMyDonations(data) {
            const container = document.getElementById("myDonationsCardContainer");
            const empty = document.getElementById("emptyState");
            container.innerHTML = "";

            document.getElementById("donationCountBadge").innerText =
                `${data.length} donation${data.length !== 1 ? 's' : ''}`;

            const pendingCount = data.filter(d => normalizeStatus(d.status) === 'pending').length;
            const pendingSummary = document.getElementById("pendingSummary");
            if (pendingCount > 0) {
                pendingSummary.style.display = 'flex';
                pendingSummary.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${pendingCount} donation${pendingCount !== 1 ? 's' : ''} awaiting confirmation`;
            } else {
                pendingSummary.style.display = 'none';
            }

            if (!data.length) {
                empty.style.display = "block";
                return;
            }
            empty.style.display = "none";

            // Pending donations first
            const sorted = [...data].sort((a, b) => {
                const order = { pending: 0, accepted: 1, rejected: 2 };
                return (order[normalizeStatus(a.status)] ?? 1) - (order[normalizeStatus(b.status)] ?? 1);
            });

            sorted.forEach(item => {
                const req = item.request || {};
                const distance = calculateDistance(
                    userData?.latitude, userData?.longitude,
                    parseFloat(req.patient_latitude), parseFloat(req.patient_longitude)
                );
                const isPending = normalizeStatus(item.status) === 'pending';
                const respondedOn = item.created_at
                    ? new Date(item.created_at).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' })
                    : '—';

                let reqStatusChip = '';
                if (req.status) {
                    const open = req.status === 'open';
                    reqStatusChip = `<span class="req-status-chip" style="background:${open ? '#dcfce7' : '#f3f4f6'};color:${open ? '#166534' : '#6b7280'}">${open ? 'Open' : 'Closed'}</span>`;
                }

                container.innerHTML += `
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm h-100 border-start border-4 border-danger">
                            <div class="card-body d-flex flex-column">

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="blood-circle"><i class="fa-solid fa-droplet"></i> ${esc(req.blood_group || '-')}</span>
                                    ${statusBadge(item.status)}
                                </div>

                                <h5 class="mb-1">${esc(req.hospital_name || 'Unknown Hospital')}</h5>

                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    <span class="meta-chip"><i class="fa-solid fa-droplet text-danger"></i> ${esc(req.unit ?? '-')} units</span>
                                    <span class="meta-chip"><i class="fa-regular fa-clock"></i> ${esc(req.required_before)} ${esc(req.required_before_unit)}</span>
                                    <span class="meta-chip"><i class="fa-solid fa-location-arrow"></i> ${esc(distance)}</span>
                                </div>

                                <div class="text-muted small mb-2">
                                    <i class="fa-solid fa-location-dot"></i> ${esc(req.address || '-')}
                                </div>

                                ${renderFlow(item)}

                                <div class="donation-footer">
                                    <span class="text-muted small"><i class="fa-regular fa-calendar"></i> Responded on ${esc(respondedOn)}</span>
                                    ${reqStatusChip}
                                </div>

                                ${isPending ? `<div class="pending-strip mt-2"><i class="fa-solid fa-circle-exclamation"></i> Awaiting confirmation from the requester</div>` : ''}

                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    <button class="btn btn-outline-secondary btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#donationDetailsModal"
                                        onclick='viewDonationDetails(${JSON.stringify(item)})'>
                                        View
                                    </button>
                                    ${isPending ? `<button class="btn btn-danger btn-sm" onclick="cancelResponse(${item.id})">Cancel</button>` : ''}
                                    <a href="https://www.google.com/maps?q=${req.patient_latitude},${req.patient_longitude}"
                                        target="_blank" class="btn btn-primary btn-sm">
                                        <i class="fa-solid fa-map-location-dot"></i> Open Map
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>`;
            });
        }

        async function fetchRequests() {
            showLoader();
            try {
                const token = localStorage.getItem("token");
                const option = { headers: { 'Accept': 'application/json', ...(token ? { Authorization: 'Bearer ' + token } : {}) } };
                const res = await fetch(API_URL, option);
                const data = await res.json();
                allRequests = data?.data || [];
                renderMyDonations(allRequests);
            } catch (err) {
                console.error("Error loading donations", err);
                showAlert('error', 'Could not load your donation history. Please try again.');
            }
            hideLoader();
        }

        async function donationAction(requestId, donorId, action) {
            const successMsg = action === 'reached_hospital'
                ? 'Hospital arrival marked successfully.'
                : 'Donation marked successfully.';
            const token = localStorage.getItem("token");
            showLoader();
            try {
                const res = await fetch(UPDATE_URL, {
                    method: "PUT",
                    headers: { "Content-Type": "application/json", "Authorization": `Bearer ${token}` },
                    body: JSON.stringify({ request_id: requestId, donor_id: donorId, action })
                });
                const data = await res.json();
                if (!res.ok || !data.status) {
                    throw new Error(data.message || 'Unable to update status.');
                }
                hideLoader();
                showAlert('success', successMsg);
                await fetchRequests();
            } catch (err) {
                hideLoader();
                showAlert('error', err.message || 'Unable to update status.');
            }
        }

        function viewDonationDetails(item) {
            const req = item.request || {};

            document.getElementById("m_name").innerText = req.name || '-';
            document.getElementById("m_blood").innerText = req.blood_group || '-';
            document.getElementById("m_type").innerText = req.patient_type || '-';
            document.getElementById("m_unit").innerText = req.unit ?? '-';

            document.getElementById("m_mobile").innerText = req.mobile || '-';
            document.getElementById("m_whatsapp").innerText = req.whatsapp_number || '-';
            document.getElementById("m_address").innerText = req.address || '-';
            document.getElementById("m_pin").innerText = req.pin_code || '-';

            document.getElementById("m_hospital").innerText = req.hospital_name || '-';
            document.getElementById("m_time").innerText = `${req.required_before || ''} ${req.required_before_unit || ''}`;
            document.getElementById("m_urgency").innerText = req.urgency || '-';
            document.getElementById("m_status").innerHTML = statusBadge(item.status);
            document.getElementById("m_contact").innerText = item.contact_number || '-';

            document.getElementById("callBtn").href = `tel:${req.mobile || ''}`;
            document.getElementById("waBtn").href = `https://wa.me/${req.mobile || ''}`;
            document.getElementById("m_prescription").src = req.prescription
                ? `{{ url('/') }}/storage/app/public/${req.prescription}`
                : '';
        }

        function cancelResponse(responseId) {
            const token = localStorage.getItem("token") || localStorage.getItem("auth_token");

            Swal.fire({
                icon: 'warning',
                title: 'Cancel response?',
                text: 'Only pending responses can be cancelled.',
                showCancelButton: true,
                confirmButtonText: 'Yes, cancel',
                confirmButtonColor: '#dc3545',
            }).then(result => {
                if (!result.isConfirmed) return;
                showLoader();
                fetch(CANCEL_RESPONSE_URL.replace('__RESPONSE_ID__', responseId), {
                    method: "DELETE",
                    headers: { "Accept": "application/json", "Authorization": `Bearer ${token}` }
                })
                    .then(res => res.json())
                    .then(data => {
                        hideLoader();
                        if (data.status) {
                            showAlert('success', 'Response cancelled');
                            fetchRequests();
                        } else {
                            showAlert('error', data.message || 'Could not cancel response.');
                        }
                    })
                    .catch(() => { hideLoader(); showAlert('error', 'Could not cancel response.'); });
            });
        }
    </script>
@endsection
@endsection
