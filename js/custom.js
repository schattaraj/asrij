const swiper = new Swiper('.swiper', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop:true,
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
document.addEventListener("DOMContentLoaded", function() {
  AOS.init({
    duration: 800,
    delay: 200,
    once: false,
  });
});
function handleMenu(){
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