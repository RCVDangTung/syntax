<html> 
    <head> 
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script> 
        <script src="http://malsup.github.com/jquery.form.js"></script> 

        <script>
            $(document).ready(function () {
                $('input[name="mybutton"]').click(function () {
//                    alert('ok');
//                    return;
                    var $hidden = $(this).parent('form').find('input[name="myhidden"]').val();
                    var email = $(this).parent('form').find('input[name="email"]').val();
                    $(this).parent().css('border','1px solid red');
                    $(this).parent().attr('sub' , 1);
                    alert(email);
//                    console.log($hidden.val());
//                    $hidden.val($(this).attr('rel'));
//                    console.log($hidden.val());
                });
            });
            
//            var values = [];
//$("input[name='items[]']").each(function() {
//    values.push($(this).val());
//});
        </script>
    </head>
    <body>
        <form>
            <input type="text" name="myhidden" value="[placeholder1]"/>
            <input class="form-control pre-copy" name="email" placeholder="Email" type="email">
            <input type="button" name="mybutton" value="Click me!" rel="the value to copy 1"/>
        </form>
        <form>
            <input type="text" name="myhidden" value="[placeholder2]"/>
            <input class="form-control pre-copy" name="email" placeholder="Email" type="email">
            <input type="button" name="mybutton" value="Click me!" rel="the value to copy 2"/>
        </form>
        <form>
            <input type="text" name="myhidden" value="[placeholder3]"/>
            <input class="form-control pre-copy" name="email" placeholder="Email" type="email">
            <input type="button" name="mybutton" value="Click me!" rel="the value to copy 3"/>
        </form>
    </body>
</html>
