@extends('layouts.app')
@section('title', 'Registration')

@section('content')
<section class="registration-section">
  <div class="container">
    <h1>Register as a Donor / Receiver / Volunteer / Blood Bank</h1>

    <div class="registration-tabs">
      <button class="tab-btn active" data-tab="donor">Donor</button>
      <button class="tab-btn" data-tab="receiver">Receiver</button>
      <button class="tab-btn" data-tab="volunteer">Volunteer</button>
      <button class="tab-btn" data-tab="hospital">Blood Bank / Hospital</button>
    </div>

    <!-- Donor Registration -->
    <div id="donor" class="tab-content active">
      <form class="registration-form">
        <input type="text" name="name" placeholder="Full Name" required>
        <select name="blood_group" required>
          <option value="">Select Blood Group</option>
          <option>A+</option><option>A-</option>
          <option>B+</option><option>B-</option>
          <option>AB+</option><option>AB-</option>
          <option>O+</option><option>O-</option>
        </select>
        <input type="date" name="last_donation" placeholder="Last Date of Donation" required>
        <input type="text" name="contact" placeholder="Contact Number" required>
        <textarea name="address" placeholder="Address with Pin Code" required></textarea>
        <button type="submit" class="btn-primary">Submit Registration</button>
      </form>
    </div>

    <!-- Receiver Registration -->
    <div id="receiver" class="tab-content">
      <form class="registration-form">
        <select name="receiver_type" required>
          <option value="">Select Receiver Type</option>
          <option>Thalassemia Patient</option>
          <option>Emergency - Accident Case</option>
          <option>Admitted Patient</option>
          <option>Other</option>
        </select>
        <input type="text" name="name" placeholder="Patient Name" required>
        <input type="text" name="hospital" placeholder="Hospital Name" required>
        <input type="text" name="contact" placeholder="Contact Number" required>
        <textarea name="address" placeholder="Address with Pin Code" required></textarea>
        <button type="submit" class="btn-primary">Submit Registration</button>
      </form>
    </div>

    <!-- Volunteer Registration -->
    <div id="volunteer" class="tab-content">
      <form class="registration-form">
        <input type="text" name="organization" placeholder="Organization / Trust Name" required>
        <input type="text" name="contact" placeholder="Contact Number" required>
        <input type="email" name="email" placeholder="Email ID" required>
        <textarea name="address" placeholder="Address with Pin Code" required></textarea>
        <button type="submit" class="btn-primary">Submit Registration</button>
      </form>
    </div>

    <!-- Blood Bank / Hospital -->
    <div id="hospital" class="tab-content">
      <form class="registration-form">
        <input type="text" name="hospital_name" placeholder="Hospital / Blood Bank Name" required>
        <input type="text" name="license" placeholder="Registration / License Number" required>
        <input type="text" name="contact" placeholder="Contact Number" required>
        <input type="email" name="email" placeholder="Email ID" required>
        <textarea name="address" placeholder="Address with Pin Code" required></textarea>
        <button type="submit" class="btn-primary">Submit Registration</button>
      </form>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
  const tabBtns = document.querySelectorAll('.tab-btn');
  const tabContents = document.querySelectorAll('.tab-content');

  tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      tabBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      tabContents.forEach(content => content.classList.remove('active'));
      document.getElementById(btn.dataset.tab).classList.add('active');
    });
  });

  // Optional mock submission
  document.querySelectorAll('.registration-form').forEach(form => {
    form.addEventListener('submit', e => {
      e.preventDefault();
      alert('Registration submitted successfully!');
      form.reset();
    });
  });
</script>
@endsection
