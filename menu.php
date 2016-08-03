<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="REFRESH" content="1800" />
        <link rel="shortcut icon" href="http://blogtamsu.vn/wp-content/themes/news/images/favicon.ico" type="image/x-icon" />
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function ()
            {
                $(".account").click(function ()
                {
                    var X = $(this).attr("id");
                    if (X == 1)
                    {
                        $(".submenu").hide();
                        $(this).attr("id", "0");
                    }
                    else
                    {
                        $(".submenu").show();
                        $(this).attr("id", "1");
                    }
                }); //Mouse click on sub menu
                $(".submenu").mouseup(function ()
                {
                    return false
                }); //Mouse click on my account link
                $(".account").mouseup(function ()
                {
                    return false
                }); //Document Click
                $(document).mouseup(function ()
                {
                    $(".submenu").hide();
                    $(".account").attr("id", "");
                });
            });
        </script>


        <style>
            .dropdown 
            {
                color: #555;
                margin: 3px -22px 0 0;
                width: 143px;
                position: relative;
                height: 17px;
                text-align:left;
            }
            .submenu
            {
                background: #fff;
                position: absolute;
                top: -12px;
                left: -20px;
                z-index: 100;
                width: 135px;
                display: none;
                margin-left: 10px;
                padding: 40px 0 5px;
                border-radius: 6px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.45);
            }
            .dropdown li a 
            {
                color: #555555;
                display: block;
                font-family: arial;
                font-weight: bold;
                padding: 6px 15px;
                cursor: pointer;
                text-decoration:none;
            }.dropdown li a:hover
            {
                background:#155FB0;
                color: #FFFFFF;
                text-decoration: none;
            }
            a.account 
            {
                font-size: 11px;
                line-height: 16px;
                color: #555;
                position: absolute;
                z-index: 110;
                display: block;
                padding: 11px 0 0 20px;
                height: 28px;
                width: 121px;
                margin: -11px 0 0 -10px;
                text-decoration: none;
                background: url(icons/arrow.png) 116px 17px no-repeat;
                cursor:pointer;
            }
            .root
            {
                list-style:none;
                margin:0px;
                padding:0px;
                font-size: 11px;
                padding: 11px 0 0 0px;
                border-top:1px solid #dedede;
            }
        </style>
    </head>
    <div class="dropdown">
        <a class="account">My Account</a>
        <div class="submenu">
            <ul class="root">
                <li ><a href="#Dashboard">Dashboard</a></li>
                <li ><a href="#Profile">Profile</a></li>
                <li ><a href="#settings">Settings</a></li>
                <li ><a href="#feedback">Send Feedback</a></li>
            </ul>
        </div>
    </div>


</html>