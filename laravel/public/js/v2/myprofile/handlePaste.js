//From http://stackoverflow.com/questions/2176861/javascript-get-clipboard-data-on-paste-event-cross-browser/2177059
//Modified

function handlepaste(elem, e) {
    this.elem = elem;
    thie.e = e;

    this.target = undefined;

    this.main = function(this.elem, this.e) {
        elem = this.elem;
        e = this.e ;
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

            this.waitforpastedata(elem, savedcontent);
            if (e.preventDefault) {
                    e.stopPropagation();
                    e.preventDefault();
            }
            return false;
        }
        else {// Everything else - empty editdiv and allow browser to paste content into it, then cleanup
            elem.innerHTML = "";
            this.waitforpastedata(elem, savedcontent);
            return true;
        }
    }

    this.waitforpastedata = function (elem, savedcontent) {
        if (elem.childNodes && elem.childNodes.length > 0) {
            this.processpaste(elem, savedcontent);
        }
        else {
            that = {
                e: elem,
                s: savedcontent
            }
            that.callself = function () {
                this.waitforpastedata(that.e, that.s)
            }
            setTimeout(that.callself,20);
        }
    }

    this.processpaste = function(elem, savedcontent) {
        pasteddata = elem.innerHTML;
        //^^Alternatively loop through dom (elem.childNodes or elem.getElementsByTagName) here

        elem.innerHTML = savedcontent;
    }

}

function handlepaste (elem, e) {
    
}

function 

function 