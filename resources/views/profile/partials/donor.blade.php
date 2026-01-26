<h5 class="border-bottom mt-4 pb-2">Donor Details</h5>

<p><strong>Type:</strong> {{ $donor->type ?? '' }}</p>
<p><strong>Blood Group:</strong> {{ $donor->blood_group }}</p>
<p><strong>Year of Birth:</strong> {{ $donor->year_of_birth }}</p>
<p><strong>Last Donation Date:</strong> {{ $donor->last_donation && \Carbon\Carbon::parse($donor->last_donation)->format('d M Y') }}</p>
