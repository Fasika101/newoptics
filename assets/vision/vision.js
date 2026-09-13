
	jQuery(function(){
	//main_prescription
	//progressive_pd
    jQuery('#cancel_some').click(function(){
		jQuery('.main_target').hide();

	});
	jQuery('#cancel').click(function(){
		jQuery('.main_target').hide();
	});
	jQuery('.main_btn').click(function(){
		jQuery('.main_target').hide();
		jQuery('#div'+$(this).attr('main_target')).show();
	});

	jQuery('#hideall').click(function(){
		jQuery('.target').hide();
	});

	jQuery('.Single').click(function(){
		jQuery('.target').hide();
		jQuery('#div'+$(this).attr('target')).show();
	});
	//single_pd
	jQuery('#hideallpd').click(function(){
		jQuery('.pdtarget').hide();
	});
	jQuery('.pd').click(function(){
		jQuery('.pdtarget').hide();
		jQuery('#div'+$(this).attr('pdtarget')).show();
	});
	//progressive_pd
	jQuery('#hideall_p_pd').click(function(){
		jQuery('.prog_pd_target').hide();
	});
	jQuery('.progressive_pd').click(function(){
		jQuery('.prog_pd_target').hide();
		jQuery('#div'+$(this).attr('prog_pd_target')).show();
	});


	});

    //name and price value passing method
     var inputF = document.getElementById("cost_lens");
     var inputG = document.getElementById("name_lens");
     var show_lens_name = document.getElementById("disp_lens_name");
    

      
        function myFunction() {
        //   document.getElementById("selected_type").innerHTML = "Frame Only";
        inputF.value = "0";
         global_c.value = "0";
        inputG.value = "Frame Only";
                            show_lens_name.innerHTML = "Selected Type: "+ inputG.value;
                            type_price = Number(inputF.value);
                    sum_price();
        }

        // var el_down = document.getElementById("GFG_DOWN");

        function antiblue() {
  // document.getElementById("selected_type").innerHTML = "Frame with Anti Blue Light Lens";
  // document.getElementById("type_price").innerHTML = "150";
  inputF.value = "900";
  inputG.value = "Anhhhhhhe";
  show_lens_name.innerHTML = "Selected Type: " + inputG.value;
  type_price = Number(inputF.value);
  sum_price();
}
function tinted() {
  //   document.getElementById("selected_type").innerHTML = "Frame with Tinted Lens";
  //   document.getElementById("type_price").innerHTML = "250";
  inputF.value = "900";
  inputG.value = "Tinted";
  show_lens_name.innerHTML = "Selected Type: " + inputG.value;

  type_price = Number(inputF.value);
  sum_price();
}
function polarized() {
  //   document.getElementById("selected_type").innerHTML = "Frame with Polarized Lens";
  //   document.getElementById("type_price").innerHTML = "350";
  inputF.value = "1900";
  inputG.value = "Polarized";
  show_lens_name.innerHTML = "Selected Type: " + inputG.value;
  type_price = Number(inputF.value);
  sum_price();
}
function photochromic() {
  //   document.getElementById("selected_type").innerHTML = "Frame with Photochromic Lens";
  //   document.getElementById("type_price").innerHTML = "450";
  inputF.value = "1550";
  inputG.value = "Photochromic";
  show_lens_name.innerHTML = "Selected Type: " + inputG.value;
  type_price = Number(inputF.value);
  sum_price();
}
function nightvision() {
  //   document.getElementById("selected_type").innerHTML = "Frame with Night Vision(Yellow) Lens";
  //   document.getElementById("type_price").innerHTML = "500";
  inputF.value = "1400";
  inputG.value = "Night Vision";
  show_lens_name.innerHTML = "Selected Type: " + inputG.value;
  type_price = Number(inputF.value);
  sum_price();
}
        function single_vision() {
       // document.getElementById("vision_type").innerHTML = "Single  Vision";
        }
        function progressive_vision() {
        //document.getElementById("vision_type").innerHTML = "Progressive Vision";
        
        }


        function cart_button() {
        var popup = document.getElementById("Item added to cart successfully.");
         popup.classList.toggle("show");
        }

       //single vision price out put box
    var ssphright = document.getElementById("single_sph_right");
    var ssphleft = document.getElementById("single_sph_left");
	var scylright = document.getElementById("single_cyl_right");
	var scylleft = document.getElementById("single_cyl_left");
	var saxisright = document.getElementById("single_axis_right");
	var saxisleft = document.getElementById("single_axis_left");
        //progressive vision price out put box
    var psphright = document.getElementById("progressive_sph_right");
    var psphleft = document.getElementById("progressive_sph_left");
	var pcylright = document.getElementById("progressive_cyl_right");
	var pcylleft = document.getElementById("progressive_cyl_left");
	var paxisright = document.getElementById("progressive_axis_right");
	var paxisleft = document.getElementById("progressive_axis_left");
    var paddright = document.getElementById("progressive_add_right");
	var paddleft = document.getElementById("progressive_add_left");

    //pd number out put boxes

	var pd_one = document.getElementById("one_pd_data");
	var pd_two_right = document.getElementById("two_pd_data_right");
	var pd_two_left = document.getElementById("two_pd_data_left");

    


       //Lens type price

    //  var price_of_lens = 0;
    //   //  price_of_lens = document.getElementById("cost_lens").value;

    //     function one(){
    //           var bb = document.getElementById("cost_lens").value;
    //             price_of_lens = bb;
    //             sum_price();
    //     }

    
    //price variables
    var global_p = document.getElementById("disp_vision_price");
        var ssr = 0; //single sph right
        var ssl = 0; //single sph left
        var scr = 0; //single cyl right
        var scl = 0; //single cyl left
        var sal = 0; //single axis left
        var sar = 0; //single axis right

        var psr = 0; //progressive sph right
        var psl = 0; //progressive sph left
        var pcr = 0; //progressive cyl right
        var pcl = 0; //progressive cyl left
        var pal = 0; //progressive axis left
        var par = 0; //progressive axis right
        var padl = 0; //progressive add left
        var padr = 0; //progressive add right



    // PD Numbers
    function one_pd() {
      var details = document.getElementById("ssph-right").value;
      var allData = details.split("||");
      //inputF.value = allData[0];
      pd_one.value = details;
      //ssr = Number(ssphright.value);
      //sum_price();
    }
    function two_pd_right() {
      var details = document.getElementById("ssph-right").value;
      var allData = details.split("||");
      //inputF.value = allData[0];
      ssphright.value = allData[1];
      ssr = Number(ssphright.value);
      sum_price();
    }
    function two_pd_left() {
      var details = document.getElementById("ssph-right").value;
      var allData = details.split("||");
      //inputF.value = allData[0];
      ssphright.value = allData[1];
      ssr = Number(ssphright.value);
      sum_price();
    }

    //single vision
        function set_sph_right(){
            var details = document.getElementById("ssph-right").value;
            var allData = details.split("||");
            //inputF.value = allData[0];
            ssphright.value = allData[1];
            ssr = Number(ssphright.value);
            sum_price();

        }
        function set_sph_left(){
            var details = document.getElementById("ssph-left").value;
            var allData = details.split("||");
            //ssphright.value = allData[0];
            ssphleft.value = allData[1];
            ssl = Number(ssphleft.value);
            sum_price();
        }
        function set_cyl_right(){
            var details = document.getElementById("scyl-right").value;
            var allData = details.split("||");
            //inputF.value = allData[0];
            scylright.value = allData[1];
            scr = Number(scylright.value);
            sum_price();
        }
        function set_cyl_left(){
            var details = document.getElementById("scyl-left").value;
            var allData = details.split("||");
            //  inputF.value = allData[0];
            scylleft.value = allData[1];
            scl = Number(scylleft.value);
            sum_price();
            
        }

	    function set_axis_right(){
            var details = document.getElementById("saxis-right").value;
            var allData = details.split("||");
            //inputF.value = allData[0];
            saxisright.value = allData[1];
            sar = Number(saxisright.value);
            sum_price();
	    }

	    function set_axis_left(){
            var details = document.getElementById("saxis-left").value;
            var allData = details.split("||");
            // inputF.value = allData[0];
            saxisleft.value = allData[1];
            sal = Number(saxisleft.value);
        
            sum_price();
	    }

        //progressive vision
        function p_set_sph_right(){
            var details = document.getElementById("psph-right").value;
            var allData = details.split("||");
            // inputF.value = allData[0];
            psphright.value = allData[1];
            psr = Number(psphright.value);
            
            sum_price();
	    }
        function p_set_sph_left(){
            var details = document.getElementById("psph-left").value;
            var allData = details.split("||");
            // inputF.value = allData[0];
            psphleft.value = allData[1];
            psl = Number(psphleft.value);
           
            sum_price();
	    }
        function p_set_cyl_right(){
            var details = document.getElementById("pcyl-right").value;
            var allData = details.split("||");
            // inputF.value = allData[0];
            pcylright.value = allData[1];
            pcr = Number(pcylright.value);
        
            sum_price();
	    }
        function p_set_cyl_left(){
            var details = document.getElementById("pcyl-left").value;
            var allData = details.split("||");
            // inputF.value = allData[0];
            pcylleft.value = allData[1];
            pcl = Number(pcylleft.value);
        
            sum_price();
	    }
        function p_set_axis_right(){
            var details = document.getElementById("paxis-right").value;
            var allData = details.split("||");
            // inputF.value = allData[0];
            paxisright.value = allData[1];
            par = Number(paxisright.value);
        
            sum_price();
	    }
        function p_set_axis_left(){
            var details = document.getElementById("paxis-left").value;
            var allData = details.split("||");
            // inputF.value = allData[0];
            paxisleft.value = allData[1];
            pal = Number(paxisleft.value);
        
            sum_price();
	    }
        function p_set_add_right(){
            var details = document.getElementById("padd-right").value;
            var allData = details.split("||");
            // inputF.value = allData[0];
            paddright.value = allData[1];
            padr = Number(paddright.value);
        
            sum_price();
	    }
        function p_set_add_left(){
            var details = document.getElementById("padd-left").value;
            var allData = details.split("||");
            // inputF.value = allData[0];
            paddleft.value = allData[1];
            padl = Number(paddleft.value);
        
            sum_price();
	    }

   var main_price = 0;
   var type_price = 0;
   var cart_price ;
   var global_c = document.getElementById("calculated_price");
  // var cancel_id_hoo = document.getElementById("calculated_price");



		function sum_price() {
              //main price value
            main_price = Number(document.getElementById("p_current_price").value);
            var local_main_price = main_price;
              //lens type price
            type_price = Number(document.getElementById("cost_lens").value);
            var local_type_price = type_price;
            
            //single vision                 //progressive vision
            var local_ssr = ssr;            var local_psr = psr;
            var local_ssl = ssl;            var local_psl = psl;
            var local_scr = scr;            var local_pcr = pcr;
            var local_scl = scl;            var local_pcl = pcl;
            var local_sar = sar;            var local_par = par;
            var local_sal = sal;            var local_pal = pal;
                                            var local_padr = padr;
                                            var local_padl = padl;

            var single_sph = local_ssr + local_ssl;
            var single_cyl = local_scr + local_scl 

            var progressive_sph = local_psr + local_psl;
            var progressive_cyl = local_pcr + local_pcl;
            var progressive_axis = local_par + local_pal;
            var progressive_add = local_padr + local_padl;
            
            var single_vision_price = single_sph + single_cyl + local_sar + local_sal  ; 
            var progressive_vision_price = progressive_sph + progressive_cyl + progressive_axis + progressive_add ;
           
            // global_p.value = local_price_of_lens;
            global_p.value = single_vision_price + local_main_price + progressive_vision_price + local_type_price ;
            global_c.value = single_vision_price + progressive_vision_price + local_type_price ;

	        document.getElementById("new_new").innerHTML = global_p.value ;
            document.getElementById("etb").innerHTML = "ETB ";
          //  document.getElementById("calculated_price")
            

		}

        //refresh page
        // function refreshPage() {
        //   window.location.reload();
        // } 

      




