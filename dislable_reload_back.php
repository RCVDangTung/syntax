
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title>Administrator TOPICA</title>

        <meta name="description" content="overview &amp; stats" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <script type="text/javascript" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $.fn.myFunction = function () {
                    alert('con ga');
                };
                $(".call-btn").click(function () {
                    $.fn.myFunction();
                });

                function disableF5(e) {
                    if ((e.which || e.keyCode) == 116 || (e.which || e.keyCode) == 82) {
                        e.preventDefault();
                    }

                }

                $(document).on("keydown", disableF5);
            });
        </script>
    </head>
    <body>
        <button type="button" class="call-btn">Click Me</button>
    </body>
</html>

