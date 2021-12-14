jQuery(document).ready( function() {

    jQuery("#linkbutton").click( function(e) {
       e.preventDefault();
       nonce = jQuery(this).attr("data-nonce");
       post_title = jQuery(this).attr('post-title');
       user = jQuery(this).attr('user')

       jQuery.ajax({
          type : "post",
          dataType : "json",
          url : myAjax.ajaxurl,
          data : { action: "show_authors", 
                  nonce: nonce, 
                  post_title: post_title, 
                  user: user },
          success: function( response ) {
             console.log( response );
             const list = document.getElementById('list')
             const item = document.createElement('li')
             item.innerText = ` You have clicked ${response['click_count']} times.
             This click was at ${response['date']}.
             You are currently on the ${response['page_title']} page. `
            list.appendChild(item)
          }
 
    })
 
 })

})