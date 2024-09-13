const listeTags = document.querySelector(".liste-tags");
const parentSelector = document.querySelectorAll(".parent-option");

let parent;

parentSelector.forEach((item) => {
  item.addEventListener("change", (e) => {
    parent = e.target.value;    
    showTags(parent);
  });
});
const showTags = async (parent) => {

  const response = await fetch("/admin/tags/gestion-tags", {
    method: "POST",
    headers: {
      "Content-Type": "Application/json",
      "X-Requested-with": "XMLHttpRequest",
    },
    body: JSON.stringify(parent),
  });
  const data = await response.json();

  listeTags.innerHTML=""
  listeTags.insertAdjacentHTML("afterbegin", data.content);
};

//Amorçage des tags
showTags(parentSelector[0].value);
