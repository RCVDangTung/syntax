
$(document).ready(function () {
    $(".new").click(function () {
        var parents_2 = $(this).parents('.cha').attr('id');// lay cha 1
        var parents = $(this).parents('.cha');// lay cha 2
//        alert(parents);
//        return;
        alert($(parents[parents.length - 1]).attr('id'));
    });
});
