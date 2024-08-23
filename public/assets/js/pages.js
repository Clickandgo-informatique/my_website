//Activer/desactiver une page en tant que page d'accueil

const checkActivation = document.querySelector("#pages_is_homepage");
let etatActivation;

checkActivation.addEventListener("change", (e) => {
  if (e.target.checked) {
    etatActivation = true;
  } else {
    etatActivation = false;
  }
  activerHomepage();
});
const activerHomepage = async () => {
  const formData = new FormData();
  formData.append("etatActivation", etatActivation);
  try {
    const response = await fetch("/admin/pages/choisir-page-d-accueil", {
      method: "POST",
      headers: {
        "content-type": "application/json ;charset=UTF-8",
        "X-Requested-with": "XMLHttpRequest",
      },
      body: formData,
    });
    const data = await response.json();
    console.log(data);
  } catch (error) {
    console.error(error);
  }
};
