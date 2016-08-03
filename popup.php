<a href="#" onclick="location.reload();">Load lại trang để xem</a>

<div class="dark-bg"></div>

<div class="row intro-bg">

    <div class="col-md-12">

    <div id="it-intro">

            <div class="it-test-panel">

            <i class="fa fa-times-circle closePopUp"></i>

                <h3>Simple Popup Box</h3>

                <p class="subheading">Easy to use simple popup box with call to action buttons</p>

                <p class="extensionText">See our products<a class="blue" href="" target="_blank" id="extension">Click Here</a></p>

                <p class="extensionText">See our products<a class="green" href="" target="_blank" id="extension">Click Here</a></p>

                <p class="extensionText">See our products<a class="red" href="" target="_blank" id="extension">Click Here</a></p>

            </div>

        </div>

    </div>

</div>
 



.dark-bg {
    display: none;
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,0.4);
    z-index: 9999;
}
.intro-bg {
    width: 540px;
    position: fixed;
    top: 50%;
    left: 50%;
    display: none;
    margin-top: -175px;
    margin-left: -270px;
    padding: 10px;
    text-align: center;
    border-radius: 3px;
    background: rgb(66, 66, 66);
    box-shadow: 0 5px 5px rgba(0, 0, 0, 0.5);
    border: 2px solid rgba(0, 0, 0, 0.7)
    border-radius: 3px;
    z-index: 999999;
    background-image: -webkit-gradient(linear, left top, left bottom, from(#FFFFFF), to(#E9E9E9));
    background-image: -webkit-linear-gradient(top, #FFFFFF, #E9E9E9);
    background-image: -moz-linear-gradient(top, #FFFFFF, #E9E9E9);
    background-image: -ms-linear-gradient(top, #FFFFFF, #E9E9E9);
    background-image: -o-linear-gradient(top, #FFFFFF, #E9E9E9);
    background-image: linear-gradient(top, #FFFFFF, #E9E9E9);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='#FFFFFF', EndColorStr='#E9E9E9');
}
#it-intro {
    padding: 15px 20px;
}
#it-intro h3 {
    font-family: 'proxima_novasemibold', sans-serif;
    color: #434343;
    margin-bottom: 15px;
    margin-top: 0;
    font-weight: bold;
    font-size: 20px;
}
#it-intro p {
    color: #797979;
}
p.extensionText {
    font-size: 15px;
    margin-top: 0;
}
p.extensionText a.green{
    background-color: #1E8C5E;
}
p.extensionText a.green:hover{
    background-color: #1A7750;
}

p.extensionText a.blue{
    background-color: rgb(81, 160, 195);
}
p.extensionText a.blue:hover{
    background-color: rgb(75, 150, 180);
}

p.extensionText a.red{
    background-color: rgb(221, 107, 85);
}
p.extensionText a.red:hover{
    background-color: rgb(212, 103, 82);
}
p.extensionText a#extension {
    margin-left: 65px;
    color: #E7E7E7;
    display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 15px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    text-decoration: none;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border-radius: 2px;
}
i.closePopUp {
    position: absolute;
    top: -3px;
    right: -3px;
    font-size: 20px;
    color: #000;
}
i.closePopUp:hover,p.extensionText a#extension:hover{
    cursor: pointer;
}
p.subheading {
    margin-bottom: 20px;
}



$(document).ready(function($) {
    setTimeout(function() {
        $('.dark-bg').fadeIn('300', function() {
            $('.intro-bg').slideDown('400');
        });
    },1000);
    $('.closePopUp').click(function(event) {
        $('.intro-bg').slideUp('300',function(){
            $(this).remove();
            $('.dark-bg').fadeOut('400', function() {
                $(this).remove();
            });
        });
    });
});
