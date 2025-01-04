var dtoken = $('meta[name="csrf-token"]').attr('content');

	var blockedDates = [];
	//var blockedSelectedDates = [];
	var weekDays = ['MON','TUE','WED','THU','FRI', 'SAT', 'SUN'];
	//var weekDays = ['MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY', 'SATURDAY', 'SUNDAY'];
	var selectedWeekDays = [];
	//var availabletimings = ['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','01:00','01:30'];
	var availabletimings = [];
	var timeSlots = "";
	var blockeddays = "";
	$(function(){
		var options = {
			clearable : true
		};
		$('#start_time').wickedpicker();
		$('#end_time').wickedpicker();

	    $('#datepicker3').daterangepicker({
			opens: 'top',orientation: "top",
	    	locale: {
		      format: 'M-DD-Y'
		    }
	    	/*format: 'dd-mm-YYYY',
				
			*/
			
	    })

	    $('.addDate').on('click', function(){
	    	var blockDate = $('#datepicker3').val();
	    	if(blockDate){
	    		if(!blockedDates.includes(blockDate)){
		    		blockedDates.push(blockDate);
		    	}
		    	console.log(blockedDates);
		    	$('#datepicker3').val('');

		    	if(blockedDates.length > 0){
		    		$('#blockDates').html('');
		    		for (var i = 0; i < blockedDates.length; i++) {
		    			$('#blockDates').append('<p class="blockdte" id="del_'+i+'" dval="'+blockedDates[i]+'">'+blockedDates[i]+' <span class="btn btn-danger btn-xs deletebckDate" id="'+i+'_'+blockedDates[i]+'"> <i class="fa fa-close"></i></span></p>');
		    		}
		    	}
				
			$.ajax({
	    		type: 'POST',
	            //url: "{{ url('users/blockeddates') }}",
				url: baseurl + '/users/blockeddates',
	            data: {_token : dtoken, 'blockDate': blockDate},
	            //dataType: 'json',
              	success: function (res) {
					console.log("Dates "+res);
					$('#blockeddates').html(res);
					blockeddays = res;
				}
			});
				
	    	}else{
	    		toastr.error('Please Select Blocked Date');
	    	}
	    });

	    $('body').on('click', '.deletebckDate', function(){
	    	var id = $(this).attr('id').split('_')[0];
	    	var removeItem = $(this).attr('id').split('_')[1];
	    	blockedDates = jQuery.grep(blockedDates, function(value) {
			  return value != removeItem;
			});

			console.log(blockedDates);
	    	$("#del_"+id).remove();  
	    });

	    week_days();
	    time_slots();
	    fetchExistingData();

		$('body').on('click', '#updateProvider', function(){
	    	$('#result').html('');
			if(selectedWeekDays.length == 0){
				toastr.error("Please Select Available Days ");
				return false;				
			}
	    	var provider = $('#provider_id').val();
	    	var timeduration = $('#duration_time').val();
	    	var data = {
				'providerid' : provider,
	    		'provider' : provider,	    		
	    		'availabletimings' : availabletimings,
				'timeSlots' : timeSlots,
	    		'timeduration' : timeduration,
				'weekDays' : selectedWeekDays,
	    		'availableDays' : selectedWeekDays,
	    		'blockedDates' : blockedDates,
				'blockeddays' : blockeddays
	    	}

	    	$.ajax({
	    		type: 'POST',
	            //url: "{{ url('users/updateProviderJson') }}",
				url: baseurl + '/users/updateProviderJson',
	            data: {_token : dtoken, provider:provider, data: data },
	            dataType: 'json',
	            beforeSend: function()
              	{
                  	$("#result").html('<i class="fa fa-spinner"></i> Please wait...');
              	},
              	success: function (res) {
              		$("#result").html('');
              		if(res.status == 200){
              			toastr.success(res.message);
						$("#user_modal .btn-close-red").click();
              		}else{
              			toastr.error(res.message);
              		}
              	}
	    	})
	    	//$('#result').append('<pre>'+JSON.stringify(data)+'</pre>');	    	
	    })

	    $('.addTimeSlot').on('click', function(){
	    	var start_time = $('#start_time').val();
	    	var end_time = $('#end_time').val();

	    	var time = start_time+' - '+end_time;
	    	availabletimings.push(time);
	    	if(availabletimings.length > 0){
	    		$('#availabletimings').html('');
	    		for (var m = 0; m < availabletimings.length; m++) {
	    			$('#availabletimings').append('<p class="blockdte" id="deltime_'+m+'">'+availabletimings[m]+' <span class="btn btn-danger btn-xs deleteTime" id="'+m+'_'+availabletimings[m]+'"> <i class="fa fa-close"></i></span></p>');
	    		}
	    	}
	    	console.log(start_time+' - '+end_time);
	    });

	    $('body').on('click', '.deleteTime', function(){
	    	var id = $(this).attr('id').split('_')[0];
	    	var removeItem = $(this).attr('id').split('_')[1];
	    	availabletimings = jQuery.grep(availabletimings, function(value) {
			  return value != removeItem;
			});
			console.log(availabletimings);
	    	$("#deltime_"+id).remove();
			$('#timeSlots').html('');
			$('#duration_time').val('');	
	    });

	    $('body').on('click', '.selectDay', function(){
	    	var id = $(this).attr('id');
	    	if(!selectedWeekDays.includes(id)){
	    	 	selectedWeekDays.push(id);
	    	}
	    	renderSelDays();
	    });

	    $('body').on('click', '.delSelDay', function(){
	    	var id = $(this).attr('id').split('_')[0];
	    	var day = $(this).attr('id').split('_')[1];
	    	selectedWeekDays = jQuery.grep(selectedWeekDays, function(value) {
			  return value != day;
			});
			console.log(selectedWeekDays);
	    	$("#delday_"+id).remove();
	    })
		
	    $('body').on('change blur', '#duration_time', function(){
			if(availabletimings.length == 0){
				toastr.error("Please Enter Available Timings ");
				$(this).val('');
				return false;
			}
	    	var timeduration = $(this).val();
			//console.log(timeduration);
			$.ajax({
	    		type: 'POST',
	            //url: "{{ url('users/formattimeslots') }}",
				url: baseurl + '/users/formattimeslots',
	            data: {_token : dtoken, 'timeduration': timeduration, 'availabletimings' : availabletimings},
	            //dataType: 'json',
              	success: function (res) {
					console.log("SLOTS "+res);
					$('#timeSlots').html(res);
					timeSlots = res;
				}
			});
 
	    })

	});



	function renderSelDays() {
		$('#selweekdays').empty();
		if(selectedWeekDays.length > 0){
			for (var i = 0; i < selectedWeekDays.length; i++) {
				$('#selweekdays').append('<p class="blockdte2" id="delday_'+i+'">'+selectedWeekDays[i]+' <span class="btn btn-danger btn-xs delSelDay" id="'+i+'_'+selectedWeekDays[i]+'"> <i class="fa fa-close"></i></span></p>');
			}
		}
	}

	function week_days() {
		if(weekDays.length > 0){
			$('#weekdays').html('');
			for (var j = 0; j < weekDays.length; j++) {
				$('#weekdays').append('<span class="btn btn-brown selectDay" id="'+weekDays[j]+'" style="margin-right:5px">'+weekDays[j]+'</span>');
			}
		}
	}

	function time_slots() {
		if(availabletimings.length > 0){
			$('#availabletimings').html('');
			for (var k = 0; k < availabletimings.length; k++) {
				$('#availabletimings').append('<span class="btn btn-warning" id="'+availabletimings[k]+'" style="margin-right:5px">'+availabletimings[k]+'</span>');
			}
		}
	}

	function fetchExistingData() {
		console.log('testing');
		var providerId = $('#provider_id').val();
		$.ajax({
	    		type: 'POST',
	            //url: "{{ url('users/getProviderData') }}",
				url: baseurl + '/users/getProviderData',
	            data: {_token : dtoken, providerId: providerId},
	            dataType: 'json',
              	success: function (res) {
              		console.log(res.provider.config);
              		if(res.status == 200){
              			if( res.provider.config != null){
	              			if(res.provider.config.blockedDates !== 'undefined' && res.provider.config.blockedDates.length > 0){
	              				for (var i = 0; i < res.provider.config.blockedDates.length; i++) {
	              					blockedDates.push(res.provider.config.blockedDates[i]);
	              				}
	              			}

	              			if(res.provider.config.availabletimings !== 'undefined' && res.provider.config.availabletimings.length > 0){
	              				for (var j = 0; j < res.provider.config.availabletimings.length; j++) {
	              					availabletimings.push(res.provider.config.availabletimings[j]);
	              				}
	              			}

	              			if(res.provider.config.weekDays !== 'undefined' && res.provider.config.weekDays.length > 0){
	              				for (var k = 0; k < res.provider.config.weekDays.length; k++) {
	              					selectedWeekDays.push(res.provider.config.weekDays[k]);
	              				}
	              			}
							
							if(res.provider.config.timeduration !== 'undefined' && res.provider.config.timeduration != ''){
								$('#duration_time').val(res.provider.config.timeduration);
							}

	              			/* Rendering */
	              			renderSelDays();

	              			if(blockedDates.length > 0){
					    		$('#blockDates').html('');
					    		for (var i = 0; i < blockedDates.length; i++) {
					    			$('#blockDates').append('<p class="blockdte" id="del_'+i+'" dval="'+blockedDates[i]+'">'+blockedDates[i]+' <span class="btn btn-danger btn-xs deletebckDate" id="'+i+'_'+blockedDates[i]+'"> <i class="fa fa-close"></i></span></p>');
					    		}
					    	}
					    	
							if(availabletimings.length > 0){
					    		$('#availabletimings').html('');
					    		for (var m = 0; m < availabletimings.length; m++) {
					    			$('#availabletimings').append('<p class="blockdte" id="deltime_'+m+'">'+availabletimings[m]+' <span class="btn btn-danger btn-xs deleteTime" id="'+m+'_'+availabletimings[m]+'"> <i class="fa fa-close"></i></span></p>');
					    		}
					    	}

					    	console.log(res.provider.config.timeSlots);

					    	if(res.provider.config.timeSlots !== 'undefined' ){
					    		timeSlots= res.provider.config.timeSlots;
					    	}
					    	if(res.provider.config.blockeddays !== 'undefined' ){
					    		blockeddays= res.provider.config.blockeddays;
					    	}
					    }
              		}else{
              			toastr.error(res.message);
              		}
              	}
	    	})
	}