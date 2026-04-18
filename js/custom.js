const swiper = new Swiper('.swiper', {
  slidesPerView: 1,
  spaceBetween: 20,
  loop: true,
  // If we need pagination
  pagination: {
    el: '.swiper-pagination',
  },

  // Navigation arrows
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  breakpoints: {
    768: {
      slidesPerView: 3,
    },
  },
  // And if we need scrollbar
  // scrollbar: {
  //     el: '.swiper-scrollbar',
  // },
});
var swiper2 = new Swiper(".mySwiper", {
  slidesPerView: 1,
  spaceBetween: 30,
  loop: true,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  autoplay: {
    delay: 3500,
    disableOnInteraction: false,
  },
  // breakpoints: {
  //   768: {
  //     slidesPerView: 2,
  //   },
  // },
});
document.addEventListener("DOMContentLoaded", function () {
  if (typeof AOS != "undefined") {
    AOS.init({
      duration: 800,
      delay: 200,
      once: false,
    });
  }
});
function handleMenu() {
  const body = document.querySelector('body');
  const menuToggle = document.querySelector('.navbar-toggler');
  body.classList.toggle('show-menu');
  menuToggle.classList.toggle('active');
}


// ✅ SweetAlert helper
function showAlert(type, message) {
  Swal.fire({
    icon: type, // success | error | warning | info
    text: message,
    confirmButtonColor: '#3085d6'
  });
}

async function validatePincode(googleAddress, userPincode) {
  console.log(googleAddress, userPincode);

  if (!googleAddress || typeof googleAddress !== "string") {
    await Swal.fire({
      icon: 'error',
      title: 'Invalid Address',
      text: 'Please select a valid address from Google Maps.'
    });
    return false;
  }

  const match = googleAddress.match(/\b\d{6}\b/);
  const googlePincode = match ? match[0] : null;

  if (!googlePincode) {
    const result = await Swal.fire({
      icon: 'warning',
      title: 'PIN Not Detected',
      text: 'Could not detect a PIN code from the selected address. Do you want to continue?',
      showCancelButton: true,
      confirmButtonText: 'Yes, continue',
      cancelButtonText: 'No, check PIN'
    });

    return result.isConfirmed; // ✅ now this works correctly
  }

  if (googlePincode !== userPincode) {
    await Swal.fire({
      icon: 'warning',
      title: 'PIN Code Mismatch',
      text: 'Please enter correct details. The PIN code does not match the selected address.'
    });
    return false;
  }

  return true;
}

const loader = document.getElementById("loader");

function showLoader() {
  loader.style.display = "flex";
}

function hideLoader() {
  loader.style.display = "none";
}
let userData;

function logout() {

  const token = localStorage.getItem("token");

  fetch("{{ url('/') }}/api/v1/logout", {
    method: "POST",
    headers: {
      "Authorization": "Bearer " + token
    }
  })
    .then(() => {
      localStorage.removeItem("token");
      location.reload();
    })
    .catch(err => {
      showAlert("error", err);
    });
}
function calculateDistance(lat1, lon1, lat2, lon2) {
  const R = 6371; // km
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLon = (lon2 - lon1) * Math.PI / 180;

  const a =
    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(lat1 * Math.PI / 180) *
    Math.cos(lat2 * Math.PI / 180) *
    Math.sin(dLon / 2) *
    Math.sin(dLon / 2);

  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return (R * c).toFixed(2) + " km";
}

// ALl Map Related Codes..........................Start__________________________________
let map, marker;
let selectedLocation = {};
let activeTrigger = null;

const modalEl = document.getElementById("locationModal");
const clearBtn = document.getElementById("clearLocationBtn");

/* =========================
   TRACK WHICH BUTTON OPENS MODAL
========================= */
document.querySelectorAll(".open-location-modal").forEach(btn => {
  btn.addEventListener("click", function () {
    activeTrigger = this;
  });
});

/* =========================
   AUTO FETCH LOCATION ON PAGE LOAD
========================= */
function autoDetectLocation({
  locationInputId,
  latInputId,
  lngInputId
}) {

  const locationInput = document.getElementById(locationInputId);
  const latInput = document.getElementById(latInputId);
  const lngInput = document.getElementById(lngInputId);

  if (!locationInput || !navigator.geolocation) return;

  navigator.geolocation.getCurrentPosition(
    position => {
      const loc = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };

      if (latInput) latInput.value = loc.lat;
      if (lngInput) lngInput.value = loc.lng;

      reverseGeocodeToInput(loc, locationInput);

      // keep global state in sync
      selectedLocation = loc;
    },
    () => {
      locationInput.value = "Unable to fetch location";
    }
  );
}

        /* =========================
           MODAL OPEN → INIT MAP
        ========================= */
        modalEl.addEventListener("shown.bs.modal", () => {
          initMap();

          if (selectedLocation.lat) {
              map.setCenter(selectedLocation);
              marker.setPosition(selectedLocation);
          }
      });

      /* =========================
         INIT MAP
      ========================= */
      function initMap() {
          if (map) {
              google.maps.event.trigger(map, "resize");
              return;
          }

          const defaultLocation = {
              lat: 20.5937,
              lng: 78.9629
          };

          map = new google.maps.Map(document.getElementById("map"), {
              center: defaultLocation,
              zoom: 15,
          });

          marker = new google.maps.Marker({
              map,
              draggable: true,
              position: defaultLocation,
          });

          selectedLocation = defaultLocation;

          // Autocomplete
          const input = document.getElementById("mapSearchInput");
          const autocomplete = new google.maps.places.Autocomplete(input);

          autocomplete.addListener("place_changed", () => {
              const place = autocomplete.getPlace();
              if (!place.geometry) return;

              const loc = {
                  lat: place.geometry.location.lat(),
                  lng: place.geometry.location.lng()
              };

              map.setCenter(loc);
              marker.setPosition(loc);
              updateSelected(loc, false);
          });

          // Marker drag
          marker.addListener("dragend", () => {
              const pos = marker.getPosition();
              const loc = {
                  lat: pos.lat(),
                  lng: pos.lng()
              };

              updateSelected(loc, false);
              reverseGeocode(loc);
          });

          // Map click
          map.addListener("click", (event) => {
              const loc = {
                  lat: event.latLng.lat(),
                  lng: event.latLng.lng()
              };

              marker.setPosition(loc);
              updateSelected(loc, false);
              reverseGeocode(loc);
          });

          // Detect current location inside modal
          if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(position => {
                  const loc = {
                      lat: position.coords.latitude,
                      lng: position.coords.longitude
                  };

                  map.setCenter(loc);
                  marker.setPosition(loc);
                  updateSelected(loc);
                  reverseGeocode(loc);
              });
          }
      }

      /* =========================
         UPDATE SELECTED LOCATION
      ========================= */
      function updateSelected(loc, select = true) {
          selectedLocation = loc;

          if (!activeTrigger) return;

          const latInput = document.getElementById(activeTrigger.dataset.lat);
          const lngInput = document.getElementById(activeTrigger.dataset.lng);
          const map_lat = document.getElementById("map_latitude");
          const map_long = document.getElementById("map_longitude");
          if (map_lat) map_lat.value = loc.lat;
          if (map_long) map_long.value = loc.lng;
          if (select) {
              if (latInput) latInput.value = loc.lat;
              if (lngInput) lngInput.value = loc.lng;
          }

          clearBtn.style.display = "block";
      }

      /* =========================
         REVERSE GEOCODE (MODAL)
      ========================= */
      function reverseGeocode(loc) {
          const geocoder = new google.maps.Geocoder();

          geocoder.geocode({
              location: loc
          }, (results, status) => {
              if (status === "OK" && results[0]) {
                  document.getElementById("mapSearchInput").value =
                      results[0].formatted_address;

                  clearBtn.style.display = "block";
              }
          });
      }

      /* =========================
         REVERSE GEOCODE (PAGE LOAD)
      ========================= */
      function reverseGeocodeToInput(loc, inputElement) {
          const geocoder = new google.maps.Geocoder();

          geocoder.geocode({
              location: loc
          }, (results, status) => {
              if (status === "OK" && results[0]) {
                  inputElement.value = results[0].formatted_address;
              }
          });
      }

      /* =========================
         CONFIRM LOCATION BUTTON
      ========================= */
      document.getElementById("confirmLocation").addEventListener("click", () => {

          if (!activeTrigger) return;

          const address = document.getElementById("mapSearchInput").value;
          const map_lat = document.getElementById("map_latitude").value;
          const map_long = document.getElementById("map_longitude").value;
          const locationInput = document.getElementById(
              activeTrigger.dataset.locationInput
          );
          const latInput = document.getElementById(activeTrigger.dataset.lat);
          if (locationInput) {
              locationInput.value = address;
          }
          if (latInput) {
              latInput.value = map_lat;
          }
          const lngInput = document.getElementById(activeTrigger.dataset.lng);
          if (lngInput) {
              lngInput.value = map_long;
          }
          const modal = bootstrap.Modal.getInstance(modalEl);
          modal.hide();
      });

      /* =========================
         CLEAR BUTTON
      ========================= */
      clearBtn.addEventListener("click", () => {

          document.getElementById("mapSearchInput").value = "";

          if (marker) {
              marker.setPosition(null);
          }

          selectedLocation = {};
          clearBtn.style.display = "none";
      });
// ALl Map Related Codes..........................Ends__________________________________

// Function to check if message contains "OTP" and show modal
function checkMessageForOTP(message) {
  if (message.toLowerCase().includes('otp')) {
    return true;
  }
  return false;
}