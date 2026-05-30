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
                        <h4 class="mb-0">My Blood Requests</h4>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Profile -->
                            <div class="tab-pane fade show active" id="profile">
                                <h5 class="border-bottom pb-2">Reponses</h5>
                                {{-- <div class="table-reponsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Donor name</th>
                                                <th>Blood Group</th>
                                                <th>Mobile</th>
                                                <th>Address</th>
                                                <th>Distance</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="responsesTableBody"></tbody>
                                    </table>
                                </div> --}}
                                <div class="row" id="responsesCardContainer"></div>
                            </div>
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
                    <button class="btn btn-success" id="modalAcceptBtn">Accept Donor</button>
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
        //get token
        const token = localStorage.getItem("token");

        // function renderTable(data) {
        //     const tbody = document.getElementById("responsesTableBody");
        //     tbody.innerHTML = "";

        //     data.forEach(request => {
        //         const patientLat = parseFloat(request.patient_latitude);
        //         const patientLng = parseFloat(request.patient_longitude);

        //         request.donors.forEach(donor => {
        //             const donorLat = parseFloat(donor.latitude);
        //             const donorLng = parseFloat(donor.longitude);

        //             const distance = calculateDistance(
        //                 patientLat,
        //                 patientLng,
        //                 donorLat,
        //                 donorLng
        //             );

        //             tbody.innerHTML += `
        //         <tr>
        //             <td><strong>${donor.name || '-'}</strong></td>
        //             <td>${donor.blood_group}</td>
        //             <td>${donor.mobile}</td>
        //             <td style="text-wrap:auto">${donor.address || '-'}</td>
        //             <td>${distance}</td>
        //             <td>
        //                 <span class="badge bg-warning text-dark">
        //                     ${donor.pivot.status}
        //                 </span>
        //             </td>
        //             <td>
        //                 <button 
        //                     class="btn btn-sm btn-info"
        //                     onclick='viewDetails(${JSON.stringify(donor)}, ${JSON.stringify(request)}, "${distance}")'
        //                     data-bs-toggle="modal" 
        //                     data-bs-target="#donorDetailsModal"
        //                 >
        //                     View Details
        //                 </button>
        //                 <button 
        //                     class="btn btn-sm btn-success"
        //                     onclick="acceptDonor(${request.id}, ${donor.id})"
        //                 >
        //                     Accept
        //                 </button>
        //             </td>
        //         </tr>
        //     `;
        //         });
        //     });
        // }
        function renderTable(data) {
    const container = document.getElementById("responsesCardContainer");
    container.innerHTML = "";

    data.forEach(request => {
        const patientLat = parseFloat(request.patient_latitude);
        const patientLng = parseFloat(request.patient_longitude);

        request.donors.forEach(donor => {
            const donorLat = parseFloat(donor.latitude);
            const donorLng = parseFloat(donor.longitude);

            const distance = calculateDistance(
                patientLat,
                patientLng,
                donorLat,
                donorLng
            );

            let statusClass = "bg-warning text-dark";

            if (donor.pivot.status === "accepted") {
                statusClass = "bg-success";
            } else if (donor.pivot.status === "rejected") {
                statusClass = "bg-danger";
            }

            container.innerHTML += `
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm h-100 border-start border-4 border-danger">

                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">${donor.name || '-'}</h5>
                                <small class="text-muted">${donor.blood_group}</small>
                            </div>

                            <span class="badge ${statusClass}">
                                ${donor.pivot.status}
                            </span>
                        </div>

                        <div class="card-body">

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <strong>Blood Group</strong><br>
                                    ${donor.blood_group}
                                </div>

                                <div class="col-6">
                                    <strong>Distance</strong><br>
                                    ${distance}
                                </div>
                            </div>

                            <div class="mb-3">
                                <strong>Mobile</strong><br>
                                <a href="tel:${donor.mobile}">
                                    ${donor.mobile}
                                </a>
                            </div>

                            <div>
                                <strong>Address</strong>
                                <p class="mb-0 text-muted">
                                    ${donor.address || '-'}
                                </p>
                            </div>

                        </div>

                        <div class="card-footer d-flex flex-wrap gap-2">

                            <button
                                class="btn btn-info btn-sm"
                                onclick='viewDetails(${JSON.stringify(donor)}, ${JSON.stringify(request)}, "${distance}")'
                                data-bs-toggle="modal"
                                data-bs-target="#donorDetailsModal">
                                View Details
                            </button>

                            ${
                                donor.pivot.status === 'pending'
                                    ? `
                                <button
                                    class="btn btn-success btn-sm"
                                    onclick="acceptDonor(${request.id}, ${donor.id})">
                                    Accept
                                </button>
                            `
                                    : ''
                            }

                            <a
                                href="https://www.google.com/maps?q=${donor.latitude},${donor.longitude}"
                                target="_blank"
                                class="btn btn-primary btn-sm">
                                Open Map
                            </a>

                        </div>

                    </div>
                </div>
            `;
        });
    });
}
        const API_URL = "{{ route('fetchResponses') }}";
        let allRequests = [];

        async function fetchRequests() {
            showLoader();
            try {
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

                allRequests = data?.data;
                console.log("allRequests", allRequests);
                if (!allRequests || allRequests.length === 0) {
                    // document.getElementById("blood_requests_section").style.display = 'none';
                    hideLoader();
                    return;
                }
                // Show only first 4
                renderTable(allRequests);

            } catch (err) {
                console.error("Error loading requests", err);
            }
            hideLoader();
        }
        async function acceptDonor(requestId, donorId) {
            console.log("Accepting donor:", donorId, "for request:", requestId);

            // Example AJAX
            const acceptApi = await fetch(`{{ route('updateResponse') }}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${token}`
                },
                body: JSON.stringify({
                    request_id: requestId,
                    donor_id: donorId,
                    action:'accepted'
                })
            });
            const acceptApiRes = await acceptApi.json();
            if (!acceptApi.ok) {
                showAlert('error',acceptApiRes.message || "Something went wrong");
            }

        }

        function viewDetails(donor, request, distance) {
            document.getElementById("d_name").innerText = donor.name || '-';
            document.getElementById("d_blood").innerText = donor.blood_group;
            document.getElementById("d_mobile").innerText = donor.mobile;
            document.getElementById("d_whatsapp").innerText = donor.whatsapp_number || '-';
            document.getElementById("d_address").innerText = donor.address || '-';
            document.getElementById("d_pin").innerText = donor.pin_code || '-';
            document.getElementById("d_status").innerText = donor.pivot.status;
            document.getElementById("d_distance").innerText = distance;

            document.getElementById("r_hospital").innerText = request.hospital_name;
            document.getElementById("r_unit").innerText = request.unit;
            document.getElementById("r_time").innerText =
                request.required_before + " " + request.required_before_unit;

            // Bind accept button inside modal
            document.getElementById("modalAcceptBtn").onclick = function() {
                acceptDonor(request.id, donor.id);
            };
        }
    </script>
@endsection
@endsection
