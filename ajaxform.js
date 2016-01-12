
// wait for the DOM to be loaded 
$(document).ready(function () {
    // bind 'myForm' and provide a simple callback function 
    $('form').ajaxForm(function () {
        alert("Thank you for your comment!");
    });
});
        