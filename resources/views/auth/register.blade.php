<form method="POST" action="{{ route('register') }}">
    @csrf

    <label>Name:</label>
    <input type="text" name="name" required>

    <label>Email:</label>
    <input type="email" name="email">

    <label>Mobile:</label>
    <input type="text" name="mobile">

    <label>Password:</label>
    <input type="password" name="password">

    <label>Confirm Password:</label>
    <input type="password" name="password_confirmation">

    <label>Role:</label>
    <select name="role" id="role" required>
        <option value="donor">Donor</option>
        <option value="receiver">Receiver</option>
        <option value="volunteer">Volunteer</option>
        <option value="blood_bank">Blood Bank/Hospital</option>
    </select>

    <!-- Additional dynamic fields for each role (JS toggled) -->
    <div id="donor-fields">
        <input type="text" name="blood_group" placeholder="Blood Group">
        <input type="date" name="last_donation_date">
        <input type="text" name="address" placeholder="Address">
        <input type="text" name="pin_code" placeholder="Pin Code">
    </div>

    <button type="submit">Register</button>
</form>
