document.addEventListener("DOMContentLoaded", () => {
    console.log('coucou')
  const customSelects = document.querySelectorAll(".custom-select");
  const updateSelectedOptions = (customSelect) => {
    const selectedOptions = Array.from(
      customSelect.querySelectorAll(".option.active")
    )
      .filter(
        (option) => option !== customSelect.querySelector(".option.all-tags")
      )
      .map((option) => {
        return {
          value: option.getAttribute("data-value"),
          text: option.textContent.trim(),
        };
      });
    const selectedValues = selectedOptions.map((option) => {
      return option.value;
    });
    customSelect.querySelector(".tags_input").value = selectedValues.join(", ");

    let tagsHTML = "";

    if (selectedOptions.length === 0) {
      tagsHTML = '<span class="placeholder">Choisissez des tags</span>';
    }
  };
});
