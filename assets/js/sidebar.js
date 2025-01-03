// Function to toggle sidebar and adjust body padding
const showMenu = (toggleId, navbarId, bodyId) => {
  const toggle = document.getElementById(toggleId),
    navbar = document.getElementById(navbarId),
    bodypadding = document.getElementById(bodyId);

  if (toggle && navbar) {
    toggle.addEventListener("click", () => {
      // Toggle sidebar width
      navbar.classList.toggle("show");
      // Rotate the toggle button
      toggle.classList.toggle("rotate");
      // Add padding to the body
      bodypadding.classList.toggle("expander");
    });
  }
};

showMenu("nav-toggle", "navbar", "body");

// Handle link active state
const linkColor = document.querySelectorAll(".nav__link");

function colorLink() {
  linkColor.forEach((l) => l.classList.remove("active"));
  this.classList.add("active");
}

linkColor.forEach((l) => l.addEventListener("click", colorLink));

// Dropdown functionality
document.querySelectorAll(".nav__item").forEach((item) => {
  item.addEventListener("click", (e) => {
    // Check if the item has a dropdown menu
    if (item.querySelector(".dropdown-menu")) {
      // Prevent default only if clicking on the main link, not on the dropdown items
      if (!e.target.classList.contains("dropdown-item")) {
        e.preventDefault(); // Prevent default action for the parent link
        const parent = item;

        // Toggle active class for dropdown
        parent.classList.toggle("active");

        // Adjust divider based on dropdown state
        const divider = parent.querySelector(".divider");
        if (parent.classList.contains("active")) {
          divider.style.height = "2px"; // Adjust height for extended effect
          divider.style.margin = "0.5rem 0";
        } else {
          divider.style.height = "1px";
          divider.style.margin = "1rem 0";
        }
      }
    }
  });
});
