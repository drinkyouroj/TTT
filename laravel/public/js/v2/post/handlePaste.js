//From http://stackoverflow.com/questions/2176861/javascript-get-clipboard-data-on-paste-event-cross-browser/2177059
function handlepaste (elem, e) {
    var savedcontent = elem.innerHTML;
    if (e && e.clipboardData && e.clipboardData.getData) {// Webkit - get data from clipboard, put into editdiv, cleanup, then cancel event
        if (/text\/html/.test(e.clipboardData.types)) {
            elem.innerHTML = e.clipboardData.getData('text/html');
        }
        else if (/text\/plain/.test(e.clipboardData.types)) {
            elem.innerHTML = e.clipboardData.getData('text/plain');
        }
        else {
            elem.innerHTML = "";
        }
        waitforpastedata(elem, savedcontent);
        if (e.preventDefault) {
                e.stopPropagation();
                e.preventDefault();
        }
        return false;
    }
    else {// Everything else - empty editdiv and allow browser to paste content into it, then cleanup
        elem.innerHTML = "";
        waitforpastedata(elem, savedcontent);
        return true;
    }
}

function waitforpastedata (elem, savedcontent) {
    if (elem.childNodes && elem.childNodes.length > 0) {
        processpaste(elem, savedcontent);
    }
    else {
        that = {
            e: elem,
            s: savedcontent
        }
        that.callself = function () {
            waitforpastedata(that.e, that.s)
        }
        setTimeout(that.callself,20);
    }
}

function processpaste (elem, savedcontent) {
    pasteddata = elem.innerHTML;
    //^^Alternatively loop through dom (elem.childNodes or elem.getElementsByTagName) here

    elem.innerHTML = savedcontent;
}


//http://stackoverflow.com/questions/1181700/set-cursor-position-on-contenteditable-div/3323835#3323835
//<div id="area" style="width:300px;height:300px;" onblur="onDivBlur();" onmousedown="return cancelEvent(event);" onclick="return cancelEvent(event);" contentEditable="true" onmouseup="saveSelection();" onkeyup="saveSelection();" onfocus="restoreSelection();"></div>
var savedRange,isInFocus;
function saveSelection()
{
    if(window.getSelection)//non IE Browsers
    {
        savedRange = window.getSelection().getRangeAt(0);
    }
    else if(document.selection)//IE
    { 
        savedRange = document.selection.createRange();  
    } 
}

function restoreSelection()
{
    isInFocus = true;
    document.getElementById("area").focus();
    if (savedRange != null) {
        if (window.getSelection)//non IE and there is already a selection
        {
            var s = window.getSelection();
            if (s.rangeCount > 0) 
                s.removeAllRanges();
            s.addRange(savedRange);
        }
        else if (document.createRange)//non IE and no selection
        {
            window.getSelection().addRange(savedRange);
        }
        else if (document.selection)//IE
        {
            savedRange.select();
        }
    }
}
//this part onwards is only needed if you want to restore selection onclick
var isInFocus = false;
function onDivBlur()
{
    isInFocus = false;
}

function cancelEvent(e)
{
    if (isInFocus == false && savedRange != null) {
        if (e && e.preventDefault) {
            //alert("FF");
            e.stopPropagation(); // DOM style (return false doesn't always work in FF)
            e.preventDefault();
        }
        else {
            window.event.cancelBubble = true;//IE stopPropagation
        }
        restoreSelection();
        return false; // false = IE style
    }
}