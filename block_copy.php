

<!--//http://jsfiddle.net/dannylane/9pRsx/4/-->

<!--Code chống right click-->

<!--$(document).bind("contextmenu",function(e){ e.preventDefault(); });-->

<!--Code chống copy, paste-->
<script>
// We also check for a text selection if ctrl/command are pressed along w/certain keys 
    $(document).keydown(function (ev) {
        // capture the event for a variety of browsers 
        ev = ev || window.event;
        // catpure the keyCode for a variety of browsers 
        kc = ev.keyCode || ev.which;
// check to see that either ctrl or command are being pressed along w/any other keys 
        if ((ev.ctrlKey || ev.metaKey) && kc) {
// these are the naughty keys in question. 'x', 'c', and 'c' 
// (some browsers return a key code, some return an ASCII value) 
            if (kc == 99 || kc == 67 || kc == 88) {
                return false;
            }
        }
    });
</script>
<!--https://jsfiddle.net/lesson8/ZxKdp/-->
<input type="text" id="mytext"/>
<script>
    $(document).ready(function () {
        $(document).keydown(function (event) {
            if (event.ctrlKey == true && (event.which == '118' || event.which == '86' || event.which == '67' || event.which == '99')) {
                alert('thou. shalt. not. PASTE!');
                event.preventDefault();
            }
        });
    });
</script>