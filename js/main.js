jQuery( ($) => {
    
    const form = document.getElementById('formTags')
    //const url = '?page=gtags_menu';

    form.addEventListener('submit', (e) => {
        e.preventDefault()
        save();

        
    })

    function save() {
        //console.log('ejecutado');
        const data = $("#analy").val(); // toma los datos 
        const nonce = $("#gtag_nonce").val(); 
            
            $.ajax({
                type: "post",
                url: ajax_var.url,
                data: "g_tag=" + data + "&action=" + ajax_var.action + "&nonce=" + nonce,
                success: function(result){
                    $('.alert-success-gtag').fadeTo("fast", 1);
                    
                }
            });
            
                
        }

        
   



});