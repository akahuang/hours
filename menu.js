function addOrder(id) {
    var button = document.getElementById("button" + id);
    button.className = "order";

    var sendOrder = document.getElementById("sendOrder");
    if (sendOrder.orderList.value == "") {
        sendOrder.orderList.value = id;
    } else {
        sendOrder.orderList.value += (":" + id);
    }

    var currentOrder = document.getElementById("currentOrder");
    currentOrder.innerHTML += (button.value + "<br>\n");
}

function delOrder() {
    var buttons = document.getElementsByClassName("order");
    while (buttons.length > 0) {
        buttons[0].className = "unorder";
    }

    var sendOrder = document.getElementById("sendOrder");
    sendOrder.orderList.value = "";

    var currentOrder = document.getElementById("currentOrder");
    currentOrder.innerHTML = "";
}

function getElementsByClassName(classname) {
    var node = document.getElementsByTagName("body")[0];
    var a = [];
    var re = new RegExp('\\b' + classname + '\\b');
    var els = node.getElementsByTagName("*");
    for(var i=0,j=els.length; i<j; i++)
        if(re.test(els[i].className))a.push(els[i]);
    return a;
}
