<html> 
    <head> 
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script> 
        <script src="http://malsup.github.com/jquery.form.js"></script> 

        <script>
//            $(function () {
//                var current = $('#counter').text();
//                var endvalue = 50;
//
//                function timeoutVersion() {
//                    if (current === endvalue) {
//                        return false;
//                    } else {
//                        current++;
//                        $('#counter').text(current);
//                    }
//                    setTimeout(timeoutVersion, 2000);
//                }
//
//                $('a').click(function () {
//                    timeoutVersion();
//                })
//            })

            $(function () {
//                var current = $('#counter').text();
//                alert(current);
//                var endvalue = 10
//                $('a').click(function () {
//                    var storedInterval = setInterval(function () {
//                        if (current === endvalue) {
//                            clearInterval(storedInterval);
//                        } else {
//                            current++;
//                            $('#counter').text(current)
//                        }
//                    }, 50)
//                })
                
                var checkForConfirmation = function () {
//                    $("#anchorLink").trigger('click');
                    $('#ElementId').click();
                    alert('aa');
                }
                var check = 0;
                var checkInterval = setInterval(function () {
                    if(check == 0){
                        checkForConfirmation()
                        
                    }else{
                        return false;
                    }
                    
                }, 3000);

            })
            
            
        </script>
    </head>

    <body>
        <div id="counter">0</div>
        <a href="#"  id="ElementId">Click</a>
    </body>
</html>

<!--$(function() {
    var current = $('#counter').text();
    var endvalue = 50;

    function increment() {
        if (current !== endvalue) {
            current++;
            $('#counter').text(current)
            setTimeout(increment, 50);
        }

    }

    $('a').click(function() {
        increment();
    })
})​;-->