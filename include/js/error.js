shp.error = {
    aError: null
};

shp.error.init = function (){
    if (shp.error.aError[0]!=null){$(shp.error.aError[0]).focus();}
    for (id in shp.error.aError){
        $(shp.error.aError[id]).addClass('form-error');
    }
}

$(document).ready(function (){
    shp.error.init();
})