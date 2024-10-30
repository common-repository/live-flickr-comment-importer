$j = jQuery.noConflict();

$j(document).ready(function () {
	
    comment_images = $j(".comment-thumb");

    $j(comment_images).each(function () {

        $j(this).hover(function () {
        	
			this.width = "75";

        }, function () {
            this.width = "30";
        })
    })

})
