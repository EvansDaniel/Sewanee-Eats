/** this is the script to animate the homepage
 */

/**fade in according to group categories based on the fading time
 * @param i: individual html node
 * @param grp: the group category
 */

function fdin(i, grp){
    if(grp == 1){
        i.fadeIn(1500);
    }
    if (grp == 2){
        i.fadeIn(1700);
    }
    if(grp == 3){
        i.fadeIn(1200);
    }
    if(grp == 4){
        i.fadeIn(1000);
    }
    else
        i.fadeIn(grp);

}

// the big fade function
$(document).ready(function () {
    // header animation
    var subHeading = $(".subheading"),
        btn = $("#btn");
    subHeading.hide(0);
    btn.hide(0);
    fdin(subHeading,4);
    fdin(btn, 4);

    // section hw it works

    var pckImg = $(".pick img"),
        pckPgph = $(".pick p"),
        pckHd = $(".pick h6"),
        pyImg = $(".pay img"),
        pyPgph = $(".pay p"),
        pyHd = $(".pay h6"),
        hwItWrks = $(".hw-it-wrks h4"),
        dlrImg = $(".delivery img"),
        dlrHd = $(".delivery h6"),
        dlrPgph = $(".delivery p");

    hwItWrks.hide(0);
    pckImg.hide(0);
    pckHd.hide(0);
    pckPgph.hide(0);
    pyHd.hide(0);
    pyImg.hide(0);
    pyPgph.hide(0);
    dlrHd.hide(0);
    dlrImg.hide(0);
    dlrPgph.hide(0);

    fdin(hwItWrks, 3);
    fdin(pckImg, 3);
    fdin(pckHd, 3);
    fdin(pckPgph, 3);
    fdin(pyPgph, 3);
    fdin(pyImg, 3);
    fdin(pyHd, 3);
    fdin(dlrImg, 3);
    fdin(dlrPgph, 3);
    fdin(dlrHd, 3);
});