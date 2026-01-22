<h5 class="border-bottom mt-4 pb-2">Volunteer Details</h5>

<p><strong>Volunteer Type:</strong> {{ ucfirst($volunteer->volunteer_type) }}</p>

@if ($volunteer->volunteer_type === 'individual')
    <p><strong>Blood Group:</strong> {{ $extra['blood_group'] ?? '-' }}</p>
    <p><strong>Year of Birth:</strong> {{ $extra['year_of_birth'] ?? '-' }}</p>
@else
    <p><strong>Organization:</strong> {{ $extra['organization'] ?? '-' }}</p>
    <p><strong>Registration No:</strong> {{ $extra['registration_number'] ?? '-' }}</p>
    <p><strong>Group Quantity:</strong> {{ $extra['group_quantity'] ?? '-' }}</p>

    @if (!empty($extra['member_name']))
        <h6 class="mt-3">Members</h6>
        <ul class="list-group">
            @foreach ($extra['member_name'] as $index => $member)
                <li class="list-group-item">
                    {{ $member }} 
                    ({{ $extra['member_position'][$index] ?? 'Member' }}) - 
                    {{ $extra['member_contact_number'][$index] ?? '-' }}
                </li>
            @endforeach
        </ul>
    @endif
@endif
