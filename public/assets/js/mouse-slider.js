let galerie = document.querySelector(".galerie");
console.log("coucou depuis mouse-slider")

function moveNext() {
  let items = document.querySelectorAll(".item");
  galerie.appendChild(items[0]);
}
function movePrev() {
  let items = document.querySelectorAll(".item");
  galerie.prepend(items[items.length - 1]);
}
galerie.addEventListener("wheel", function (event) {
  event.preventDefault();
  event.stopPropagation();
  if (event.deltaY > 0) {
    moveNext();
  } else {
    movePrev();
  }
});
