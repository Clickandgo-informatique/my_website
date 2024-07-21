const btnClose = document.querySelector(".btn-close");
const btnOpen = document.querySelector(".btn-open");
const main = document.querySelector("#main");
const sidebar = document.querySelector(".sidebar");

btnClose.addEventListener("click", closeNav);
btnOpen.addEventListener("click", openNav);
function openNav() {
  sidebar.style.width = "300px";
  sidebar.style.opacity = 1;
  btnOpen.style.display = "none";
  btnClose.style.display = "block";
  main.style.marginLeft = "300px";
}

function closeNav() {
  sidebar.style.width = "0";
  sidebar.style.opacity = 0;
  btnClose.style.display = "none";
  btnOpen.style.display = "block";
  main.style.marginLeft = "0";
}
