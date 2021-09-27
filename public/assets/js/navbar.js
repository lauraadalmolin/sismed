document.addEventListener('DOMContentLoaded', function (event) {
  const showNavbar = (toggleId, navId, bodyId, headerId) => {
    const toggle = document.getElementById(toggleId),
      nav = document.getElementById(navId),
      bodypd = document.getElementById(bodyId),
      headerpd = document.getElementById(headerId);

    // Validate that all variables exist
    if (toggle && nav && bodypd && headerpd) {
      toggle.addEventListener('click', () => {
        // show navbar
        nav.classList.toggle('show');
        // change icon
        toggle.classList.toggle('bx-x');
        // add padding to body
        bodypd.classList.toggle('body-pd');
        // add padding to header
        headerpd.classList.toggle('body-pd');
      });
    }
  };

  showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header');

  /*===== LINK ACTIVE =====*/
  const options = document.querySelectorAll('.nav_link');

  const colorLink = () => {
    if (!options) return;

    const url = window.location.origin +  window.location.pathname;

    options.forEach((op) => {
      op.classList.remove('active');

      if (op.href === url) {
        op.classList.add('active');
      }

    });
  };
  
  colorLink();
});
