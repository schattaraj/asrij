<h5 class="border-bottom mt-4 pb-2">Volunteer Details</h5>

<p><strong>Volunteer Type:</strong> {{ ucfirst($volunteer->volunteer_type) }}</p>

@if ($volunteer->volunteer_type === 'individual')
    <p><strong>Blood Group:</strong> {{ $extra['blood_group'] ?? '-' }}</p>
    <p><strong>Year of Birth:</strong> {{ $extra['year_of_birth'] ?? '-' }}</p>
@else
    <p><strong>Organization:</strong> {{ $volunteer['organization'] ?? '-' }}</p>
    <p><strong>Registration No:</strong> {{ $volunteer['registration_number'] ?? '-' }}</p>
    <p><strong>Group Quantity:</strong> {{ $volunteer['group_quantity'] ?? '-' }}</p>

    @if (!empty($extra['members']))
        <h6 class="mt-3">Members</h6>
        <ul class="list-group">
            @foreach ($extra['members'] as $index => $member)
                <li class="list-group-item">
                    <div class="d-flex align-items-center justify-content-between">
                    <p>
                    {{ $member["name"] }} 
                    ({{ $member['member_position'][$index] ?? 'Member' }}) - 
                    {{ $member['contact'] ?? '-' }}
                </p>    
                    <a href="#" class="btn btn-primary">Register as user</a>
                </div>
                </li>
            @endforeach
        </ul>
    @endif
@endif
