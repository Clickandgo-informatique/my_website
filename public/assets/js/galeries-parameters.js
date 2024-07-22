//Changer la largeur de la galerie
const inputGalleryWidth = document.querySelector("#galeries_gallery_width");
inputGalleryWidth.addEventListener("change", () => {
  let width = inputGalleryWidth.value;
  document.documentElement.style.setProperty("--gallery-width", `${width}%`);
});

//Changer la couleur de fond d'une galerie d'images
const inputGalleryPrimaryBackgroundColor = document.querySelector(
  "#galeries_primary_background_color"
);
inputGalleryPrimaryBackgroundColor.addEventListener("input", () => {
  let color = inputGalleryPrimaryBackgroundColor.value;
  document.documentElement.style.setProperty(
    "--gallery-primary-background-color",
    color
  );
});

//Changer le border-radius des images
const inputImagesBorderRadius = document.querySelector(
  "#galeries_images_border_radius"
);
inputImagesBorderRadius.addEventListener("change", () => {
  let borderRadius = inputImagesBorderRadius.value;
  document.documentElement.style.setProperty(
    "--images-border-radius",
    `${borderRadius}%`
  );
});

//Changer l'espacement des images
const inputGalleryGap = document.querySelector("#galeries_gallery_gap");
inputGalleryGap.addEventListener("change", () => {
  let gap = inputGalleryGap.value;
  document.documentElement.style.setProperty("--gallery-gap", `${gap}px`);
});
//Changer l'épaisseur du bord des images
const inputImagesBorderWidth = document.querySelector(
  "#galeries_images_border_width"
);
inputImagesBorderWidth.addEventListener("change", () => {
  let borderWidth = inputImagesBorderWidth.value;
  document.documentElement.style.setProperty(
    "--images-border-width",
    `${borderWidth}px solid #fff`
  );
});
//Choisir le nombre de colonnes
const inputColumns = document.querySelector("#galeries_gallery_max_columns");
inputColumns.addEventListener("change", () => {
  let maxColumns = inputColumns.value;
  document.documentElement.style.setProperty(
    "--gallery-max-columns",
    `${maxColumns}`
  );
});
//Choisir la hauteur de la galerie
const inputGalleryHeight = document.querySelector("#galeries_gallery_height");
inputGalleryHeight.addEventListener("change", () => {
  let maxGalleryHeight = inputGalleryHeight.value;
  document.documentElement.style.setProperty(
    "--gallery-height",
    `${maxGalleryHeight}px`
  );
});
//Changer l'ombre de l'image
const inputImageShadow = document.querySelector("#galeries_images_shadow");
inputImageShadow.addEventListener("change", () => {
  let imgShadow = inputImageShadow.value;
  console.log(imgShadow);
  document.documentElement.style.setProperty(
    "--images-shadow",
    `${imgShadow}px ${imgShadow}px ${imgShadow}px darkgray`
  );
});