$(function() { 
});
// input tag value filter
function setInputFilter(textbox, inputFilter) {
  ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
    textbox.addEventListener(event, function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  });
}

// popup open in one window
function myOpenWindow(winURL, winName, winFeatures, winObj)
{
    var theWin;

    if (winObj != null)
    {   
        if (!winObj.closed) {
            winObj.focus();
            winObj.location = winURL;
        return winObj;
        } 
    }
    theWin = window.open(winURL, winName, winFeatures);
    return theWin;
}

function return_number(value){
    if(value)
        return value;
    else
        return 0;
}

function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0]=parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

function finance_number(x){
    x = numberWithCommas(x);
    x = x.replaceAll(",", " ");
    x = x.replaceAll(".", ",");
    x = x.replaceAll(" ", ".");
    return x;
}