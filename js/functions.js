
// maak een bootstrap melding
var messageCount = 0;
function message(type, title, content) {
    // type = success/info/warning/danger
    document.getElementById("message").insertAdjacentHTML('beforeend', '<div id="messagebox' + messageCount + '" class="alert alert-' + type + ' alert-dismissable fade in message"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>' + title + '</strong> ' + content + '</div>');

    var messageBoxName = "messagebox" + messageCount;
    var delay = 100 + 500 * messageCount;

    setTimeout(function () {
        document.getElementById(messageBoxName).style.opacity = "1"
    }, delay);
    // vloeiend binnenkomen en zichtbaar maken        
    messageCount++;
}

// maak een laadicoon in verstuurbutton en wijzig button tekst
function sendButton(buttonText, loading, buttonid) {
    if (loading) {
        buttonText = '<i class="fa fa-circle-o-notch fa-spin"></i> ' + buttonText;
    }
    document.getElementById(buttonid).innerHTML = buttonText;
}

function edit (element,name) {
    document.getElementById(name).style.display = "block";
    element.style.display = "none";
    // als er op de wijzigen knop word geklik knop onzichtbaar maken en bestands input zichtbaar maken
}