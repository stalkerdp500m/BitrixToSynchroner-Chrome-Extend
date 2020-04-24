

if (document.readyState === "complete") { // запускаю создание скрипта для вставки
    loudScript()
} else {
    window.onload = () => {
        loudScript()
    }
}


function loudScript() {
    let s = document.createElement('script');
    s.type = 'text/javascript';
    s.src = chrome.extension.getURL('./scriptBtr.js');



    s.onload = function () {
        this.parentNode.removeChild(this);
    };
    try {
        (document.head || document.documentElement).appendChild(s);
    } catch (e) {
        console.log(e);
    }
}

