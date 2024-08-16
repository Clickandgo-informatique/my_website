import {ClassicEditor} from '../../ckeditor5/ckeditor5/ckeditor5.js'

//On initialise ckeditor5
console.log('Initialisation ckeditor5')
ClassicEditor.create(document.querySelector("#editor")).catch((error) => {
    console.error(error);
  });;
