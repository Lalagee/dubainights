(function($){

  const uluru = { lat: 25.276987, lng: 55.296249};
        // The map, centered at Uluru
        const map = new google.maps.Map(document.getElementById("map"), {
          zoom: 6,
          center: uluru,
        });
        // The marker, positioned at Uluru
        // const marker = new google.maps.Marker({
        //   position: {lat: 25.2531745, lng: 55.3656728 },
        //   map: map,
        // });
          var locations = the_ajax_script.show_all_posts;
         console.log(locations);
         const markers = locations.map((location, i) => {

          // this.addListener("click", () => {
          //     map.setZoom(8);
          //     map.setCenter(this.getPosition());
          //   });

        return new google.maps.Marker({
         position: location,
           map: map,
        });
      });
          /* google.maps.event.addListener(this, 'click', function() {
                map.panTo(this.getPosition());
                map.setZoom(9);
        }); */

  // console.log();
  

	function run_waitMe(){

 $('.waitmeclass').waitMe({
     effect:'bounce',
     text:'',
     bg:'rgba(255,255,255,0.7)',
     color:'#c79317',
     maxSize:'',
     waitTime: -1,
     source:'',
     textPos:'vertical',
     fontSize:'',
     onClose:function() {}
 });
}
	$(".scheckbox,.sradio,.rdate").change(function(){
      run_waitMe();

    if ($(this).hasClass('rdate')) {
      $('input[name="when"]').prop('checked', false);

    }
    if ($(this).hasClass('sradio')) {
      $('.rdate').val('');

    }
		 var dataa = new Array();
        $(".scheckbox:checked").each(function (index, element) {
           	 	dataa[this.name] = $(this).val();
             
		}); 
		var date_selected = $('input[name="when"]:checked').val();
    var srdate = $('input[name="srdate"]').val();
    var erdate = $('input[name="erdate"]').val();

       // if (maps) {
       // 	maps = true;
       // }
       // console.log(typeof(dataa))
       var test = $(".scheckbox:checked").val();
       $.ajax({
				type:"POST",
				url: the_ajax_script.ajaxurl,
				data: {cat_ids : Object.assign({}, dataa), //cat_ids
              find_by_date :date_selected, //date single
              srdate :srdate, //start date
              erdate :erdate, //end date
              action: 'search_post_by_tax'
            },
				// data: serialize_form,
				dataType : 'json',
				success: function (response) {	
					$(".waitmeclass").waitMe("hide");
					var status = response.status;
                        console.log(response);
                        // if (status) {

                        	$(".events-pst.grid").html('');
                        	$(".events-pst.grid").html('<div class="events-row">'+response.html+'</div>');
                       
                        
                        // } else {
                        
                        // }
                    },
                    error: function (errorThrown) {
                    	console.log('error');
                    	console.log(errorThrown);
                    },
                });
		 // console.log("test",test);
		 // console.log("dataa",dataa);
})
	$(".map-btn").click(function(){
		// $(".googlemap").fadeIn();
		// $(".events-pst").fadeOut();
		$(".googlemap").show();
		$(".events-pst").hide();
	})
		

})(jQuery);