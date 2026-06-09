@extends('layouts.app')
@section('title', 'All Blood Requests')

@section('content')
    <section id="all_blood_requests_section">
        {{-- Page Header --}}
        <div class="all-requests-header text-white py-5"
            style="background: linear-gradient(135deg, var(--primary-color), #8b0027);">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"
                                class="text-white-50 text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Blood Requests</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h1 class="fw-bold mb-1"><i class="fa-solid fa-droplet"></i> All Blood Requests</h1>
                        <p class="mb-0 opacity-75">Help save lives by responding to requests near you</p>
                    </div>
                    <span class="badge bg-white text-danger px-3 py-2 fs-6">
                        <i class="fa-solid fa-circle text-danger me-1" style="font-size:8px;vertical-align:middle"></i> Live
                    </span>
                </div>
            </div>
        </div>

        <div class="container my-5">

            {{-- Filters --}}
            <div class="filter-bar mb-4 p-3 rounded shadow-sm bg-white">
                <div class="row g-2 align-items-center">

                    <div class="col-md-3">
                        <select class="form-select" id="bloodFilter">
                            <option value="">All Blood Groups</option>
                            @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                                <option value="{{ $group }}">{{ $group }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select class="form-select" id="urgencyFilter">
                            <option value="">All Urgency</option>
                            <option value="urgent">Urgent</option>
                            <option value="normal">Normal</option>
                        </select>
                    </div>

                    <div class="col-md-5">
                        <input type="text" id="searchInput" class="form-control"
                            placeholder="Search location or hospital...">
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-danger w-100" onclick="resetFilters()">
                            <i class="fa-solid fa-rotate-left me-1"></i> Reset
                        </button>
                    </div>

                </div>
            </div>

            {{-- Result count --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <small class="text-muted" id="resultCount"></small>
            </div>

            {{-- Requests Grid --}}
            <div class="row g-3" id="requestContainer"></div>

            {{-- Empty State --}}
            <div id="emptyState" class="text-center py-5" style="display:none;">
                <i class="fa-solid fa-droplet-slash text-muted" style="font-size:48px;"></i>
                <h5 class="mt-3 text-muted">No blood requests found</h5>
                <p class="text-muted mb-0">Try adjusting your filters or check back later.</p>
            </div>
        </div>

        {{-- Confirm Donation Modal --}}
        <div class="modal fade" id="confirmDonationModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title">Confirm Your Response</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="donationForm">
                            <input type="hidden" id="request_id" name="request_id">

                            <div class="mb-3">
                                <label class="form-label text-muted small">Your Name</label>
                                <input type="text" id="donor_name" class="form-control bg-light" readonly>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Contact Number</label>
                                <input type="text" id="donor_phone" class="form-control" required>
                                <div class="form-text text-info">
                                    <i class="fas fa-info-circle me-1"></i> Is this number currently reachable? Update it
                                    if needed.
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary w-100"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger w-100">Confirm & Respond</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        const container = document.getElementById("requestContainer");
        const API_URL = "{{ route('blood-requests.index') }}";
        let allRequests = [];

        function render(data) {
            container.innerHTML = "";

            const emptyState = document.getElementById("emptyState");
            const resultCount = document.getElementById("resultCount");

            if (!data || data.length === 0) {
                emptyState.style.display = "block";
                resultCount.innerText = "";
                return;
            }

            emptyState.style.display = "none";
            resultCount.innerText = `Showing ${data.length} request${data.length > 1 ? 's' : ''}`;

            data.forEach(req => {
                const createdDate = req.created_at ?
                    new Date(req.created_at).toLocaleString('en-IN', {
                        day: '2-digit',
                        month: 'short',
                        year: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    }) :
                    "N/A";
                const required_within = `${req.required_before} ${req.required_before_unit}`;
                container.innerHTML += `
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="request-card ${req.urgency === 'urgent' ? 'urgent-card' : ''}">
                            <div class="d-flex justify-content-between">
                                <div class="blood-group">${req.blood_group}</div>
                                ${req.urgency === 'urgent'
                                    ? '<span class="badge bg-danger urgent-tag"><i class="fa-solid fa-exclamation"></i> URGENT</span>'
                                    : ''}
                            </div>
                            <div class="meta"><strong>Hospital Name</strong> : ${req.hospital_name}</div>
                            <div class="meta mb-1"><strong>Address</strong> : ${req.address}</div>
                            <div class="meta mb-2"><strong>Units</strong> : ${req.unit}</div>
                            <div class="meta mb-1"><strong>Token</strong> : ${req.token || 'N/A'}</div>
                            <div class="meta mb-1"><strong>Requested On</strong> : ${createdDate}</div>
                            <div class="meta mb-2"><strong>Required within ${required_within}</strong></div>
                            ${req?.distance ? `<div class="meta mb-3"><strong>Distance</strong> : ${req.distance_text} from your registered address</div>` : ''}
                            <button
                                class="btn btn-sm ${req.has_responded ? 'btn-secondary' : 'btn-danger'} w-100 donate-btn"
                                data-request_id="${req.id}"
                                data-blood_group="${req?.blood_group}"
                                ${req.has_responded ? 'disabled' : ''}
                            >
                                ${req.has_responded ? 'Already Responded' : 'Donate'}
                            </button>
                        </div>
                    </div>
                `;
            });

            attachDonateButtons();
        }

        function attachDonateButtons() {
            document.querySelectorAll(".donate-btn").forEach(btn => {
                btn.addEventListener("click", () => {
                    if (btn.disabled) return;
                    let token = localStorage.getItem("token");

                    // 1. Auth & Validation
                    if (!token) {
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('loginModal')).show();
                        return;
                    }

                    if (userData && userData.blood_group && userData.blood_group !== btn.dataset.blood_group) {
                        showAlert("warning", "Blood group mismatch. Please check the request requirements.");
                        return;
                    }

                    // 2. Populate Modal Data
                    const modalEl = document.getElementById('confirmDonationModal');
                    modalEl.querySelector('#request_id').value = btn.dataset.request_id;
                    modalEl.querySelector('#donor_name').value = (userData && userData.name) || "User";
                    modalEl.querySelector('#donor_phone').value = (userData && userData.mobile) || "";

                    // 3. Show the Modal
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                });
            });
        }

        // Submit response handling
        document.getElementById('donationForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const submitBtn = e.target.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Processing...`;

            const payload = {
                request_id: document.getElementById('request_id').value,
                contact_number: document.getElementById('donor_phone').value
            };

            try {
                const response = await fetch('{{ route('blood-requests.respond') }}', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem("token")}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.status) {
                    showAlert("success", "Thank you! Your response has been sent to the requester.");
                    bootstrap.Modal.getInstance(document.getElementById('confirmDonationModal')).hide();
                    // Refresh list so the responded request reflects its new state
                    fetchRequests();
                } else {
                    showAlert("error", result.message || "Something went wrong.");
                }
            } catch (error) {
                showAlert("danger", "Connection error. Please try again.");
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = "Confirm & Respond";
            }
        });

        function filterData() {
            const blood = document.getElementById("bloodFilter").value;
            const urgency = document.getElementById("urgencyFilter").value;
            const search = document.getElementById("searchInput").value.toLowerCase();

            const filtered = allRequests.filter(r => {
                return (
                    (!blood || r.blood_group === blood) &&
                    (!urgency || r.urgency === urgency) &&
                    (
                        (r.address || '').toLowerCase().includes(search) ||
                        (r.hospital_name || '').toLowerCase().includes(search)
                    )
                );
            });

            render(filtered);
        }

        function resetFilters() {
            document.getElementById("bloodFilter").value = "";
            document.getElementById("urgencyFilter").value = "";
            document.getElementById("searchInput").value = "";
            render(allRequests);
        }

        document.getElementById("bloodFilter").addEventListener("change", filterData);
        document.getElementById("urgencyFilter").addEventListener("change", filterData);
        document.getElementById("searchInput").addEventListener("input", filterData);

        async function fetchRequests() {
            showLoader();
            try {
                const token = localStorage.getItem("token");
                let option = {
                    headers: {
                        'Accept': 'application/json'
                    }
                };
                if (token) {
                    option = {
                        headers: {
                            'Accept': 'application/json',
                            Authorization: 'Bearer ' + token
                        }
                    };
                }
                const res = await fetch(API_URL, option);
                const data = await res.json();

                allRequests = data?.data || [];
                // Re-apply any active filters when rendering (handles refresh after responding)
                filterData();
            } catch (err) {
                console.error("Error loading requests", err);
                showAlert("error", "Unable to load blood requests. Please try again.");
            }
            hideLoader();
        }

        document.addEventListener('DOMContentLoaded', fetchRequests);
    </script>
@endsection
