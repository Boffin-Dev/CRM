const navMore = document.getElementById("navMore");
const navItemsMore = document.getElementById("navItemsMore");

navMore.addEventListener("click", (event) => {
  navItemsMore.style.display = "block";
  navMore.focus();
});

navMore.addEventListener("focusout", (event) => {
  navItemsMore.style.display = "none";
});


//Resize Table Column
const handles = document.getElementsByClassName('resizable__handle');
var x_start = 0;
var x_end = 0;
var startWidth = 0;
var pressed = false;
var div;
var th;

if(handles.length>0) {
    for(i=0;i<handles.length;i++){
            
        handles[i].addEventListener("mousedown", function(event){
            div = event.target.parentElement.parentElement.parentElement;
            th = div.parentElement;
            startWidth = div.offsetWidth;
            x_start =  event.clientX;
            pressed = true;

        });

    }
}


document.addEventListener("mousemove", function(event){
    if(pressed){
        x_end = event.clientX;
        div.style.width = startWidth + x_end - x_start +'px';
        th.style.width = startWidth + x_end - x_start +'px';
    }
});

document.addEventListener("mouseup", function(event){
    pressed = false;
});


//Close Modal
var btn_model__close = document.getElementsByClassName('slds-modal__close')[0];
var model =  document.getElementsByClassName('slds-modal')[0]
if(btn_model__close && model) {
    btn_model__close.addEventListener("click", function(event){
        model.style.display = "none";

    });
}

//Display Modal
var btn_edit = document.getElementsByName('edit')[0];
if(btn_edit && model) {
    btn_edit.addEventListener("click", function(event){
        model.style.display = "flex";
    });
}