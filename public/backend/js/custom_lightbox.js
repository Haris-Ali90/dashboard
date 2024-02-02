
function ShowLightBox(el) {

    // get current  img src
    let img_src = el.src;
    // create html
    let html = '<div id="custom-light-box-model" class="modal">';
    html+=' <span class="custom-light-box-model-close-btn" onClick="CloseLightBox()">X</span>';
    html+=' <img src="'+img_src+ '" class="custom-light-box-model-img" id=""> ';
    html+=' </div> ';

    // appending html
    document.getElementsByTagName("body")[0].insertAdjacentHTML("beforeend",
        html );
}

function CloseLightBox() {
    document.getElementById("custom-light-box-model").remove();
}
