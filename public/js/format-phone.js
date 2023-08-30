function Validate_telefon_nummer_input(input_val) {
    
    limit = 11;

    if(input_val == '') {
        return;
    }

    var clean_val = Clear_val_only_numbers(input_val);
    clean_val = LimitVal(clean_val , limit);
    var res = getResTelefonFormat(clean_val);
    return res;

    function LimitVal(val , limit){
        if(val == '' || typeof val === "undefined"){
            return false;
        }
        return val.substring(0 , limit);
    }  

    function getResTelefonFormat(val) {
        var val_toReturn= '';
        for(var i = 0; i < val.length; i++) {
            if(i == 0){
                val_toReturn+= '( ';
            }
            if(i == 3){
                val_toReturn+= ' ) ';
            }
            if(i == 6 || i == 8){
                val_toReturn+= ' - ';
            }  
            val_toReturn+= val.charAt(i);   
        }   
        return val_toReturn;
    }

    function Clear_val_only_numbers(val) {

        if(typeof val !== "undefined") {
            var val_toReturn = val.replace(/[^\d]+/g,'');
            if(val_toReturn == '') return false;
            return val_toReturn;
        }
    }
}