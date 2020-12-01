import $ from 'jquery';
import 'bootstrap';



window.addEventListener('DOMContentLoaded', (event) => {
    $('#galaxy-admin-tab a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    console.log("DOM fully loaded and parsed");
});