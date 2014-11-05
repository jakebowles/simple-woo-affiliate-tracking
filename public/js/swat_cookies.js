(function($) {

    $(document).ready(function() {
        addRefId();
        hideRefIdForm();
    })

    var addRefId = function() {

        if ($("#refid")){
            var cke = swatReadCookie("refid");
            if(cke != null){
                $('#refid').attr('value', cke);  
            }
        }

    }

    function hideRefIdForm() {
        if( $(".hidden") ) {
            $(".hidden").attr('style', 'display: none;');
        }
    }

    function swatReadCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
    }

}(jQuery));

