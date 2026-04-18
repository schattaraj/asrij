@extends('layouts.profile')
@section('content')
    <style>
        .card p {
            margin-bottom: 6px;
        }
    </style>
    <div class="container mb-5">
        <div class="row">

            <!-- RIGHT CONTENT -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">My Blood Donations</h4>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Profile -->
                            <div class="tab-pane fade show active" id="profile">
                                <div style="overflow: auto">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Patient Name</th>
                                                <th>Blood Group</th>
                                                <th>Hospital</th>
                                                <th>Unit Required</th>
                                                <th>Distance</th>
                                                <th>Address</th>
                                                <th>Required Within</th>
                                                <th>Urgency</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                                <th>Google Map</th>
                                            </tr>
                                        </thead>
                                        <tbody id="myDonationsTableBody"></tbody>
                                    </table>
                                </div>
                            </div>
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
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                confirmButtonColor: '#0d6efd'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#dc3545'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `
                <ul style="text-align:left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
                confirmButtonColor: '#dc3545'
            });
        @endif
        document.addEventListener('DOMContentLoaded', function() {
            let activeTab = "{{ session('active_tab', 'profile') }}";

            let trigger = document.querySelector('#tab-' + activeTab);

            if (trigger) {
                new bootstrap.Tab(trigger).show();
            }
            fetchRequests();
        });

        function renderMyDonations(data) {
            const tbody = document.getElementById("myDonationsTableBody");
            tbody.innerHTML = "";

            data.forEach(item => {
                const req = item.request;
                const distance = calculateDistance(
                    userData?.latitude,
                    userData?.longitude,
                    parseFloat(req.patient_latitude),
                    parseFloat(req.patient_longitude)
                );
                tbody.innerHTML += `
            <tr>
                <td>${req.name}</td>
                <td><strong>${req.blood_group}</strong></td>
                <td>${req.hospital_name}</td>
                <td>${req.unit}</td>
                <td>${distance}</td>
                <td style="text-wrap:auto">${req.address}</td>
                <td>${req.required_before} ${req.required_before_unit}</td>
                <td>
                    <span class="badge ${req.urgency === 'urgent' ? 'bg-danger' : 'bg-info'}">
                        ${req.urgency}
                    </span>
                </td>
                <td>
                    <span class="badge 
                        ${item.status === 'accepted' ? 'bg-success' : 
                          item.status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark'}">
                        ${item.status}
                    </span>
                </td>
                <td>
                    <button 
                        class="btn btn-sm btn-info"
                        data-bs-toggle="modal"
                        data-bs-target="#donationDetailsModal"
                        onclick='viewDonationDetails(${JSON.stringify(item)}, "${distance}")'
                    >
                        View
                    </button>

                    ${item.status === 'pending' ? `
                                <button 
                                    class="btn btn-sm btn-danger"
                                    onclick="cancelResponse(${item.id})"
                                >
                                    Cancel
                                </button>
                            ` : ''}
                </td>
                <td>
                    <a href="https://www.google.com/maps?q=${req?.patient_latitude},${req?.patient_longitude}" target="_blank" class="btn btn-primary btn-sm">
    Open in Google Maps
</a>
                </td>
            </tr>
        `;
            });
        }
        const API_URL = "{{ route('myBloodDonations') }}";
        let allRequests = [];

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
                    }
                }
                const res = await fetch(API_URL, option);
                const data = await res.json();
                hideLoader();
                allRequests = data?.data;
                if (!allRequests || allRequests.length === 0) {
                    // document.getElementById("blood_requests_section").style.display = 'none';
                    return;
                }
                // Show only first 4
                renderMyDonations(allRequests);

            } catch (err) {
                console.error("Error loading requests", err);
            }
            hideLoader();
        }

        function viewDonationDetails(item) {
            const req = item.request;

            document.getElementById("m_name").innerText = req.name;
            document.getElementById("m_blood").innerText = req.blood_group;
            document.getElementById("m_type").innerText = req.patient_type;
            document.getElementById("m_unit").innerText = req.unit;

            document.getElementById("m_mobile").innerText = req.mobile;
            document.getElementById("m_whatsapp").innerText = req.whatsapp_number || '-';
            document.getElementById("m_address").innerText = req.address;
            document.getElementById("m_pin").innerText = req.pin_code;

            document.getElementById("m_hospital").innerText = req.hospital_name;
            document.getElementById("m_time").innerText =
                req.required_before + " " + req.required_before_unit;

            document.getElementById("m_urgency").innerText = req.urgency;
            document.getElementById("m_status").innerText = item.status;
            document.getElementById("m_contact").innerText = item.contact_number;

            // Buttons
            document.getElementById("callBtn").href = `tel:${req.mobile}`;
            document.getElementById("waBtn").href = `https://wa.me/${req.mobile}`;
            if (req.prescription) {
                document.getElementById("m_prescription").src = `{{url('/public')}}/storage/${req.prescription}`;
            } else {
                document.getElementById("m_prescription").src = '';
            }
        }

        function cancelResponse(responseId) {
            const token = localStorage.getItem("auth_token");

            if (!confirm("Are you sure you want to cancel?")) return;

            fetch(`/api/cancel-response/${responseId}`, {
                    method: "DELETE",
                    headers: {
                        "Authorization": `Bearer ${token}`
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        alert("Cancelled successfully");
                        location.reload();
                    }
                });
        }
    </script>
@endsection
@endsection
