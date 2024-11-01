jQuery(function($){

    var image_custom_uploader;
    $('#add_slides').click(function(e) {
        e.preventDefault();

        //If the uploader object has already been created, reopen the dialog
        if (image_custom_uploader) {
            image_custom_uploader.open();
            return;
        }

        //Extend the wp.media object
        image_custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Images',
            button: {
                text: 'Choose Images'
            },
            multiple: true
        });

        //When a file is selected, grab the URL and set it as the text field's value
        image_custom_uploader.on('select', function() {
            attachment = image_custom_uploader.state().get('selection').toJSON();    
            // Loop through each attachment
            var tr  = '';
            var count = $("#slides tr").length;
            console.log(count);
            $( attachment ).each( function() {
                if ( this.type && this.type === 'image' ) {
                    count ++;
                    tr  = '<tr>';
                    tr += '<td>' + count + '</td>';
                    tr += '<td><img src="' + this.url + '"/></td>';
                    tr += '<td>';
                    tr += '<input type="button"  value="Delete"  class="del-button button button-large">';
                    tr += '<input type="hidden" value="' + this.id + '" name="data[]">';
                    tr += '</td>';
                    tr += '</tr>';                 
                } 
                
                $('#slides').append(tr);
            });
        });

        //Open the uploader dialog
        image_custom_uploader.open();
    });
    
    
    $( '.del-button' ).live( 'click', function() {  
        $(this).parent().parent().remove(); 
 
        $( '#slides tr').each(function( index ) {
            $('td:first',this).html(index + 1);
        });
    })
 
    
});

 