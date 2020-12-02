(function($){

  const uluru = { lat: 25.276987, lng: 55.296249};
        // The map, centered at Uluru
        const map = new google.maps.Map(document.getElementById("map"), {
          zoom: 12,
          center: uluru,
        });
        // The marker, positioned at Uluru
        // const marker = new google.maps.Marker({
        //   position: {lat: 25.2531745, lng: 55.3656728 },
        //   map: map,
        // });
          var locations = the_ajax_script.show_all_posts; //for all locations
         console.log(locations);
         var markers = locations.map((location, i) => {
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
  $(".map-btn").click(function(){
    $(".googlemap").show();
    $(".events-pst").hide();
  })

	$(".scheckbox,.sradio,.rdate").change(function(){
      run_waitMe();
       maps = false;


    if ($(this).hasClass('rdate')) {
      $('input[name="when"]').prop('checked', false);

    }
    if ($(this).hasClass('sradio')) {
      $('.rdate').val('');

    }
    if (!$(".googlemap").is(":hidden")) {
         maps = true;
    }
		 var dataa = new Array();
        $(".scheckbox:checked").each(function (index, element) {
           	 	dataa[this.name] = $(this).val();
             
		}); 
        console.log("data",dataa);
		var date_selected = $('input[name="when"]:checked').val();
    var srdate = $('input[name="srdate"]').val();
    var erdate = $('input[name="erdate"]').val();

  
       var test = $(".scheckbox:checked").val();
       $.ajax({
				type:"POST",
				url: the_ajax_script.ajaxurl,
				data: {cat_ids : Object.assign({}, dataa), //cat_ids
              find_by_date :date_selected, //date single
              srdate :srdate, //start date
              erdate :erdate, //end date
              map    :maps, //flag for maps(for adjustment of maps marker on search option or filters)
              action: 'search_post_by_tax'
            },
				// data: serialize_form,
				dataType : 'json',
				success: function (response) {	
					$(".waitmeclass").waitMe("hide");
					var status = response.status;
              console.log(response);
              // if (status) {
                if (response.maps) {
                  const uluru = { lat: 25.276987, lng: 55.296249};
        // The map, centered at Uluru
                  const mapx = new google.maps.Map(document.getElementById("map"), {
                    zoom: 10,
                    center: uluru,
                  });
                  var locationx = response.locations;
                  var markers = locationx.map((location, i) => {
                      return new google.maps.Marker({
                       position: location,
                       map: mapx, 
                      });
                    });
                    }
                else{
                    $(".events-pst.grid").html('');
                    $(".events-pst.grid").html('<div class="events-row">'+response.html+'</div>');  
                }
// 
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


})(jQuery);