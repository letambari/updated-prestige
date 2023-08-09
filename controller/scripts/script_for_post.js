$(document).ready(function (e) {
    /////////////////////////////////////////////////
    ////////// function to edit post
    $("#reply_status").on('submit',(function(e) {
        e.preventDefault();
        if($("#comment_name").val().length == 0 || $("#comment_data").val().length == 0){
		Msg("<i class='fa fa-warning'></i>&nbsp; fill all fields!!!.","alert-danger",1,"#status",6000);
	} else {
            $("#commentBtn").html('<i class="fa fa-spinner fa-spin"></i> submitting...');
            $.ajax({
                url: "../controller/post.php?action=status_reply", // Url to which the request is send
                type: "POST",             // Type of request to be send, called as method
                data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false,       // The content type used when sending data to the server.
                cache: false,             // To unable request pages to be cached
                processData:false,        // To send DOMDocument or non processed data file it is set to false
                success: function(data)   // A function to be called if request succeeds
                {
                    if(data == 1){
                        alert('Comment Updated Successfully');
                        window.location = "";
                    }else{
                        alert(data);
                    }
                     $("#commentBtn").html('Send Comment');
                }
            });
        }
        
    }));
});