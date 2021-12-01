console.log("Töötab!");

let fileSize = 1024 * 1024;

window.onload = function(){
    document.querySelector("#photo_submit").disabled = true;
    document.querySelector("#photo_input").addEventListener("change", checkSize);
}

function checkSize(){
    if(document.querySelector("#photo_input").files[0].size <= fileSize){
        document.querySelector("#photo_submit").disabled = false;
        document.querySelector("#notice").innerHTML = "";
    } else {
        document.querySelector("#photo_submit").disabled = true;
        document.querySelector("#notice").innerHTML = "Valitud fail on <strong>liiga</strong> suur!";
    }
}