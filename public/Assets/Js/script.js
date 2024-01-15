const body = document.querySelector("body");

$(".sidebar ul li").on("click", function () {
  $(".sidebar ul li.active").removeClass("active");
  $(this).addClass("active");
});

$('.open-btn').on('click', function () {
  $('.sidebar').addClass('active');
});

$('.content-right').on('click', function () {
  $('.sidebar').removeClass('active');
});

var menuItems = document.querySelectorAll('.menu li');

for (var i = 0; i < menuItems.length; i++) {
  menuItems[i].addEventListener('mouseover', function () {
    var subMenu = this.querySelector('ul');
    if (subMenu) {
      subMenu.style.display = 'block';
    }
  });

  menuItems[i].addEventListener('mouseout', function () {
    var subMenu = this.querySelector('ul');
    if (subMenu) {
      subMenu.style.display = 'none';
    }
  });
}


$(document).ready(function () {

  let load = document.querySelector(".loading-wrapper");

  body.removeChild(load);

  document.querySelector('.wrapper').classList.remove('d-none');

  $('#example').DataTable({
    order: [[0, 'desc']]
  });

  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
  const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
});
