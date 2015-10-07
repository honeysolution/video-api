$('.AddToPlayList').click(function(event) {
         event.preventDefault();
         // get the form data
      var id = $(this).attr('href');
    var opts = {
             lines: 13, // The number of lines to draw
             length: 20, // The length of each line
             width: 10, // The line thickness
             radius: 30, // The radius of the inner circle
             corners: 1, // Corner roundness (0..1)
             rotate: 0, // The rotation offset
             direction: 1, // 1: clockwise, -1: counterclockwise
             color: '#000', // #rgb or #rrggbb or array of colors
             speed: 1, // Rounds per second
             trail: 60, // Afterglow percentage
             shadow: false, // Whether to render a shadow
             hwaccel: false, // Whether to use hardware acceleration
             className: 'spinner', // The CSS class to assign to the spinner
             zIndex: 2e9, // The z-index (defaults to 2000000000)
             top: 0, // Top position relative to parent in px
             left: 0 // Left position relative to parent in px
         };
         var target = document.getElementById('modal_spinner_center');
         var spinner = new Spinner(opts).spin(target);
     
         
         $.ajax({
             type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
             url         : 'index.php/home/addToPlay', // the url where we want to POST
             data        : { id: id},
 // our data object
             dataType    : 'json', // what type of data do we expect back from the server
             encode          : true
         }).done(function(data) {
             //console.log(data);
            $(".Player").html('<div class="info" style="padding:5px;">Name:'+data.songname+'</div><audio controls autoplay><source src='+data.songpath+' type="audio/mpeg"></audio>');
             // here we will handle errors and validation messages
         });
     spinner.stop();
        
     });
    $('.category').change(function(event) {
         event.preventDefault();
         // get the form data      
      var category_list = [];
            $('#SongCategory :input:checked').each(function()
            {
            var category = $(this).val();
            category_list.push(category);
            //Push each check item's value into an array
            });
                // console.log(category_list)
            if(category_list.length == 0)
                $('.data').fadeIn();
            else 
            {
                $('.data').each(function()
                {
                   
                    var item = $(this).attr('data-tag');                    
                    if(jQuery.inArray(item,category_list) > -1) //Check if data-tag's value is in array
                      $(this).fadeIn('slow');
                    else
                      $(this).hide();
                });
            }
        
     });
$('.singer').change(function(event) {
         event.preventDefault();
         // get the form data      
      var category_list = [];
            $('#SongSinger :input:checked').each(function()
            {
            var category = $(this).val();
            category_list.push(category);
            //Push each check item's value into an array
            });
                // console.log(category_list)
            if(category_list.length == 0)
                $('.data').fadeIn();
            else 
            {
                $('.data').each(function()
                {
                   
                    var item = $(this).attr('id');                    
                    if(jQuery.inArray(item,category_list) > -1) //Check if data-tag's value is in array
                      $(this).fadeIn('slow');
                    else
                      $(this).hide();
                });
            }
        
     });
function changealbum(id)
{
    var category_list = [];
    category_list.push(id);
    if(category_list.length == 0)
                $('.data').fadeIn();
            else 
            {
                $('.data').each(function()
                {
                   
                    var item = $(this).attr('get');                    
                    if(jQuery.inArray(item,category_list) > -1) //Check if data-tag's value is in array
                      $(this).fadeIn('slow');
                    else
                      $(this).hide();
                });
            }
    
}
function filter()
{
    inp = $('#searchsong').val()
    // This should ignore first row with th inside
    $("tr:not(:has(>th))").each(function() {
        if (~$(this).text().toLowerCase().indexOf( inp.toLowerCase() ) ) {
            // Show the row (in case it was previously hidden)
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}
