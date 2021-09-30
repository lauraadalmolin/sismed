var searchWrapper;
var inputBox;
var inputId;
var suggBox;
var linkTag;

window.onload = function(){
    searchWrapper = document.querySelector(".search-input");
    
    if (!searchWrapper) {
        return;
    }

    inputBox = searchWrapper.querySelector("#patient");
    inputId = searchWrapper.querySelector("#patientID");
    suggBox = searchWrapper.querySelector(".autocom-box");
    linkTag = searchWrapper.querySelector("a");
}

function lookup(userData, action){
    let webLink;
    let emptyArray = [];
    if(userData){
        $.post(action, {queryString: ""+userData+""}, function(data){
            if(JSON.parse(data).length > 0) {
                emptyArray = JSON.parse(data);

                emptyArray = emptyArray.map((data)=>{
                    // passing return data inside li tag
                    return data = `<li onclick="select(this, '${data.id}')">`+ data.name +'</li>';
                });
                searchWrapper.classList.add("active"); //show autocomplete box
                showSuggestions(emptyArray);
            } else {
                searchWrapper.classList.remove("active");
            }
        })
        
    }else{
        searchWrapper.classList.remove("active"); //hide autocomplete box
    }
}

function select(element, id){
    let selectData = element.textContent;
    inputBox.value = selectData;
    inputId.value = id;
    searchWrapper.classList.remove("active");
}

function showSuggestions(list){
    let listData;
    if(!list.length){
        userValue = inputBox.value;
        listData = '<li>'+ userValue +'</li>';
    }else{
        listData = list.join('');
    }
    suggBox.innerHTML = listData;
}