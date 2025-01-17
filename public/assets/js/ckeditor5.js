import {
    ClassicEditor,
    Essentials,
    Paragraph,
    Bold,
    Italic,
    Font,
    Link,
    PasteFromOffice,
    Clipboard
} from 'ckeditor5';

ClassicEditor
    .create( document.querySelector( '#editor' ), {
        plugins: [ Essentials, Paragraph, Bold, Italic, Font, Link, PasteFromOffice, Clipboard ],
        toolbar: [
            'undo', 'redo', '|', 'bold', 'italic', '|',
            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|', 'link'
        ],
        link: {
            // Configure how links should be handled
            decorators: {
                isExternal: {
                    mode: 'manual',
                    label: 'Open in new tab',
                    attributes: {
                        target: '_blank',
                        rel: 'noopener noreferrer'
                    }
                }
            }
        },
        pasteFromOffice: {
            // Customize paste handling if needed
        }
    } )
    .then( editor => {
        window.editor = editor;
    } )
    .catch( error => {
        console.error( error );
    } );