jQuery(function($) {

	$('#idealWeight').validate({
		rules: {
		    'info[age]':  {
				required: true,
				number: true,
				min: 15,
				max: 80
		    },
		    'info[height][feet]':  {
				required: true,
				number: true,
				min: 1
		    },
		    'info[height][inches]':  {
				number: true,
		    }
	  	},
	  	messages: {
	  		rsl_balance: {
		      required: 'Please enter the value',
		      number: 'Value is numeric'
		    },
		    rsl_monthly:  {
		    	required: 'Please enter the value',
		      number: 'Value is numeric'
		    },
		    rsl_interest:  {
				required: 'Please enter the value',
				number: 'Value is numeric'
		    }
	  	},
		  submitHandler: function(form) {
			var formData = $('#idealWeight').serializeArray();
			var jsonData = {};
            $('#spinner').show();
			$('.calories-box').css('background', "rgb(250 250 250 / 1)");
            $('.calories-box').css('opacity', "0.3");
			$.each(formData, function(i, field) {
				var parts = field.name.split('[');
				var currentObj = jsonData;

				for (var j = 0; j < parts.length; j++) {
					var key = parts[j].replace(']', '');

					if (j === parts.length - 1) {
					if(field.value)
					{
						currentObj[key] = field.value;
					}
					} else {
						currentObj[key] = currentObj[key] || {};
						currentObj = currentObj[key];
					}
				}
			});
			$.ajax({
			 url:'https://www.endomondo.com/',
			  type: 'GET', 
			  cache: false,
			  dataType: "json",
			  data: {
				  jsonData,
				  'get_ideal_weight_tool':true 
			  },
			  success: function(data) {
				  $('.content-bottom').addClass('bdbottom');
				  $('.content-bottom').html(data);
				  $('#spinner').hide();
				$('.calories-box').removeAttr('style');
			  }
		  });
		  return false;
		}
	});
})

