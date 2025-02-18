import './bootstrap';
import Swal from 'sweetalert2';
import 'select2-tailwindcss-theme/dist/select2-tailwindcss-theme.css';



window.Swal = Swal;
$(document).ready(function() {
    $('#shipper, #consignee','#notify').select2();
});