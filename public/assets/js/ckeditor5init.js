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
  Table,
} from "/ckeditor5/ckeditor5.js";

ClassicEditor.create(document.querySelector(".ckeditor"), {
  plugins: [Essentials, Bold, Italic, Font, Paragraph,Autoformat,Strikethrough,Heading,Link,Highlight,Table,List],
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
  .then((editor) => {   
      editor.sourceElement.parentElement.addEventListener("submit", function (e) {
        e.preventDefault();        
         editor.updateSourceElement()
        this.submit();
      });
  })
  .catch((error) => {
    console.error(error);
  });
