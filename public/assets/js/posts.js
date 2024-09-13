//Affiche une preview d'image dans le formulaire des posts (articles)

document
  .querySelector("#posts_featuredImage")
  .addEventListener("change", checkFile);

function checkFile() {
  let preview = document.querySelector(".image-preview");
  let image = preview.querySelector("img");
  let file = this.files[0];
  const types = ["image/jpeg", "image/gif", "image/webp", "image/png"];
  let reader = new FileReader();
  reader.onloadend = function () {
    image.src = reader.result;
    preview.style.display = "block";
  };
  if (file) {
    if (types.includes(file.type)) reader.readAsDataURL(file);
  } else {
    image.src = "";
    preview.style.display = "none";
  }
}
