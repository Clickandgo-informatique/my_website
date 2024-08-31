import {
  ClassicEditor,
  Essentials,
  Bold,
  Italic,
  Font,
  Paragraph,
  Autoformat,
  Strikethrough,
  Heading,
  Link,
  List,
  Highlight,
  CKFinder,
  Table,
} from "/ckeditor5/ckeditor5.js";
console.log("coucou depuis ckeditor 5");

ClassicEditor.create(document.querySelector("#editor"), {
  plugins: [Essentials, Bold, Italic, Font, Paragraph],
  toolbar: {
    items: [
      "undo",
      "redo",
      "|",
      "bold",
      "italic",
      "|",
      "fontSize",
      "fontFamily",
      "fontColor",
      "fontBackgroundColor",
    ],
  },
})
  // .then((editor) => {
  //   editor.sourceElement.parentElement.addEventListener("submit", function (e) {
  //     e.preventDefault();
  //     editor.updateSourceElement();
  //     this.submit();
  //   });
  // })
  .catch((error) => {
    console.error(error);
  });
