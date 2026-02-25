document.addEventListener('DOMContentLoaded', () => {

  /* ===============================
     1. MOBILE MENU
  =============================== */

  const toggle = document.querySelector('.menu-toggle');
  const menu = document.querySelector('.mobile-menu');
  const closeBtn = document.querySelector('.menu-close');

  if (toggle && menu) {
    toggle.addEventListener('click', () => {
      menu.classList.add('active');
    });
  }

  if (closeBtn && menu) {
    closeBtn.addEventListener('click', () => {
      menu.classList.remove('active');
    });
  }

  // Close when clicking outside
  document.addEventListener('click', (e) => {
    if (
      menu &&
      toggle &&
      menu.classList.contains('active') &&
      !menu.contains(e.target) &&
      !toggle.contains(e.target)
    ) {
      menu.classList.remove('active');
    }
  });


  /* ===============================
     2. TAB SWITCHING
  =============================== */

  const tabs = document.querySelectorAll('.tab');
  const tabContents = document.querySelectorAll('.tab-content > div');

  tabs.forEach((tab, index) => {
    tab.addEventListener('click', () => {

      // Remove active from all tabs
      tabs.forEach(t => t.classList.remove('active'));

      // Hide all contents
      tabContents.forEach(c => c.style.display = 'none');

      // Activate clicked tab
      tab.classList.add('active');

      if (tabContents[index]) {
        tabContents[index].style.display = 'block';
      }
    });
  });

  // Show first tab by default
  if (tabs.length > 0 && tabContents.length > 0) {
    tabs[0].classList.add('active');
    tabContents[0].style.display = 'block';
  }


  /* ===============================
     3. SMOOTH SCROLL
  =============================== */

  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      
      if (target) {
        e.preventDefault();
        target.scrollIntoView({
          behavior: 'smooth'
        });
      }
    });
  });


  /* ===============================
     4. ANNOUNCEMENT SLIDER
  =============================== */

  const announcements = document.querySelectorAll('.announcement');
  let index = 0;

  if (announcements.length > 0) {
    announcements[0].classList.add('active');

    setInterval(() => {
      announcements[index].classList.remove('active');

      index = (index + 1) % announcements.length;

      announcements[index].classList.add('active');
    }, 3000);
  }

});

// Get all tabs
const tabs = document.querySelectorAll(".tab");

// Get content container
const tabContent = document.getElementById("tabContent");

// Fake product data (you can later move this to external JSON)
const products = {
  new: [
    { name: "Floral Dress", price: "$45" },
    { name: "Summer Heels", price: "$60" },
    { name: "Luxury Watch", price: "$120" },
    { name: "Luxury Watch", price: "$120" },
    { name: "Luxury Watch", price: "$120" },
    { name: "Luxury Watch", price: "$120" },
    { name: "Luxury Watch", price: "$120" },
    { name: "Luxury Watch", price: "$120" },
    { name: "Luxury Watch", price: "$120" },
    { name: "Luxury Watch", price: "$120" },
    { name: "Luxury Watch", price: "$120" }
  ],
  best: [
    { name: "Classic Handbag", price: "$80" },
    { name: "Luxury Watch", price: "$120" },
    { name: "Luxury Watch", price: "$120" },
    { name: "Luxury Watch", price: "$120" },
    { name: "Luxury Watch", price: "$120" }
  ],
  beyond: [
    { name: "Limited Edition Gown", price: "$200" },
    { name: "Luxury Watch", price: "$120" },
    { name: "Luxury Watch", price: "$120" },
    { name: "Luxury Watch", price: "$120" }
  ],
  fragrance: [
    { name: "Rose Perfume", price: "$70" },
    { name: "Vanilla Mist", price: "$50" },
    { name: "Luxury Watch", price: "$120" },
    { name: "Luxury Watch", price: "$120" }
  ]
};

// Function to display products
function loadProducts(category) {
  const carouselInner = tabContent.querySelector(".carousel-inner");
  carouselInner.innerHTML = ""; // clear old slides

  const items = products[category];
  const productsPerSlide = 3; // 3 items per slide

  for (let i = 0; i < items.length; i += productsPerSlide) {
    const slideItems = items.slice(i, i + productsPerSlide);

    // Create slide
    const slideDiv = document.createElement("div");
    slideDiv.classList.add("carousel-item");
    if (i === 0) slideDiv.classList.add("active"); // first slide active

    // Row for products
    const rowDiv = document.createElement("div");
    rowDiv.classList.add("d-flex", "justify-content-center", "gap-3");

    slideItems.forEach(product => {
      const productDiv = document.createElement("div");
      productDiv.classList.add("card");
      productDiv.style.width = "180px"; // adjust size

      productDiv.innerHTML = `
        <div class="card-body text-center">
          <h6 class="card-title">${product.name}</h6>
          <p class="card-text text-danger">${product.price}</p>
        </div>
      `;

      rowDiv.appendChild(productDiv);
    });

    slideDiv.appendChild(rowDiv);
    carouselInner.appendChild(slideDiv);
  }
}


// Add click event to each tab
tabs.forEach(tab => {
  tab.addEventListener("click", function () {

    // Remove active from all
    tabs.forEach(btn => btn.classList.remove("active"));

    // Add active to clicked
    this.classList.add("active");

    // Get category
    const category = this.dataset.tab;

    // Load products
    loadProducts(category);
  });
});

// Load default tab on page load
loadProducts("new");


