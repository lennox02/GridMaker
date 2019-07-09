<html lang="en">
<head>

    <meta charset="utf-8">

    <title>Grid Maker</title>
    <meta name="description" content=">Put My Name Down">
    <meta name="author" content="James Webb">

    <link rel="stylesheet" href="css/styles.css?v=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>

<body>

    <div class="gm-container">

        <div id="gm-save-box">Save</div>

    </div>

<script>

/***START BUILD GRID***/
let x;
let i;
let mTop = 35;
let mLeft = 35;
for(x=0;x<30;x++){
    for(i=0;i<50;i++){
        $(".gm-container").append("<div class='gm-grid-circle' id='box-" + x + "-" + i +"' style='top: " + mTop + "; left: " + mLeft + ";'></div>");
        mLeft = mLeft + 40;
    }
    mLeft = 35;
    mTop = mTop + 40;
}
/***END BUILD GRID***/

//Enlarge node on hover - need javascript instead of :hover for dynamic positioning
$('.gm-grid-circle').mouseover(function(){
    if(!$(this).hasClass("point")){
        let top = parseInt($(this).css('top')) - 3;
        let left = parseInt($(this).css('left')) - 3;
        $(this).css({
            "height":"17px",
            "width":"17px",
            "background-color":"#FF8C00",
            "top":top + "px",
            "left":left + "px"
        });
    }
});

//Make node smaller on mouseout
$('.gm-grid-circle').mouseout(function(){
    if(!$(this).hasClass("point")){
        let top = parseInt($(this).css('top')) + 3;
        let left = parseInt($(this).css('left')) + 3;
        $(this).css({
            "height":"12px",
            "width":"12px",
            "background-color":"#ccc",
            "top":top + "px",
            "left":left + "px"
        });
    }
});


/***START LINE BUILDING***/
var lastClicked = null;
var lastlastClicked = null;
var lineWidth = 0;
var lineTop = 0;
var lineLeft = 0;
var lineCount = 1;
var degrees = 0;
var xAngle = 0;
var yAngle = 0;
var ySide = 0;
var xSide = 0;

var positions = [];


function angleCalc(ax, ay) {
    var theta = Math.atan2(ay, ax); // range (-PI, PI]
    theta *= 180 / Math.PI; // rads to degs, range (-180, 180]
    //if (theta < 0) theta = 360 + theta; // range [0, 360)
    return theta;
}


$('.gm-grid-circle').click(function(){

    if(lastClicked !== null && ((lastClicked.top !== $(this).offset().top) || (lastClicked.left !== $(this).offset().left))){

        if(!$(this).hasClass("point")){
            $(this).toggleClass("point");
        }


        //get xy values from left/top.  Last clicked left/top are conisdered x,y 0,0
        xAngle = $(this).offset().left - lastClicked.left;
        yAngle = lastClicked.top - $(this).offset().top;
        //console.log(xAngle);
        //console.log(yAngle);

        //get length of line
        ySide = yAngle;
        if(ySide < 0){
            ySide = ySide * (-1);
        }
        xSide = xAngle;
        if(xSide < 0){
            xSide = xSide * (-1);
        }
        //a squared + b squared = c squared
        lineWidth = Math.round(Math.sqrt((xSide*xSide) + (ySide*ySide)));
        degrees = angleCalc(xAngle, yAngle) * (-1);

        //get positions
        lineTop = lastClicked.top + 5;
        lineLeft = lastClicked.left + 8;

        //add line
        $(".gm-container").append("<div id='line" + lineCount + "' style='position: absolute; width: " + lineWidth + "px; height: 6px; background-color: #FF8C00; top: " + lineTop + "px; left: " + lineLeft + "px; z-index: 1;'></div>");

        //rotate line
        $("#line" + lineCount).css({'transform' : 'rotate('+ degrees +'deg)', 'transform-origin' : '0% 50%'});
        lineCount++;
        positions.push(lastClicked);
        lastClicked = $(this).offset();

    //first click
    } else if(lastClicked === null){

        if(!$(this).hasClass("point")){
            $(this).toggleClass("point");
        }
        lastClicked = $(this).offset();

    //if we're undoing a node
    } else if((lastClicked.top === $(this).offset().top) && (lastClicked.left === $(this).offset().left)){
        $(this).toggleClass("point");
        $("#line" + (lineCount-1)).remove();
        lastClicked = positions[lineCount-2];
        lineCount--;
    }
});

$('#gm-save-box').click(function(){

    var that = $(this);
    that.css({'border-color' : 'darkorange'});

    //replace with Ajax call to save positions in a db
    console.log(positions);

    setTimeout(function(){
        that.css({'border-color' : 'RoyalBlue'});
    }, 500);

});

</script>

</body>
</html>
