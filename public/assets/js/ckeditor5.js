import {
    ClassicEditor,
    Essentials,
    Paragraph,
    Bold,
    Italic,
    Font
} from 'ckeditor5';
ClassicEditor
    .create( document.querySelector( '#editor' ), {
        plugins: [ Essentials, Paragraph, Bold, Italic, Font ],
        toolbar: [
            'undo', 'redo', '|', 'bold', 'italic', '|',
            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
        ]
    } )
    .then( editor => {
        window.editor = editor;
    } )
    .catch( error => {
        console.error( error );
    } );