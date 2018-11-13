/***** CSS *****/
import './../../../../node_modules/bootstrap/dist/css/bootstrap.css'; // Bootstrap CSS
import './../../../sass/backend/components/_common.scss';

/***** JS *****/
import 'bootstrap'; // Bootstrap JS
import tinymce from '../../../plugins/tinymce/tinymce.min';

tinymce.init({
    selector: '#registration-instruction',
    plugins: 'advlist colorpicker autoresize legacyoutput media pagebreak toc wordcount anchor hr link save textcolor autolink directionality fullscreen lists noneditable preview searchreplace table textpattern',
    toolbar: 'save undo redo visualaid bold italic underline strikethrough subscript superscript blockformats align formats link openlink preview hr pagebreak searchreplace inserttable tableprops deletetable cell row column toc',
    height: 550,
    branding: false,
    menubar: true,
    convert_fonts_to_spans: true,
    element_format: 'html',
    invelid_elements: 'script,embed,object,iframe,frameset,frame'
});