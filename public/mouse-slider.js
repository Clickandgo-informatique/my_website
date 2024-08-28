let galerie = document.querySelector(".galerie");

for (let i = 1; i <= 50; i++) {
  let div = document.createElement("div");
  div.className = "item";
  div.textContent = i;
  galerie.appendChild(div);
}
function moveNext() {
  let items = document.querySelectorAll(".item");
  galerie.appendChild(items[0]);
}
function movePrev() {
  let items = document.querySelectorAll(".item");
  galerie.prepend(items[items.length - 1]);
}
window.addEventListener("wheel", function (event) {
  if (event.deltaY > 0) {
    moveNext();
  } else {
    movePrev();
  }
});
