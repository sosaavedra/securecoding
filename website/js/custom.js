
$(document).ready(function(){ 
	$('#transactionType').on('change',function() { 

		if ( this.value == '1' || this.value == '2'){ 
			 $("#toAccountDiv").hide();
		}else{ 
			 $("#toAccountDiv").show();
		 }
		 }); 
	});
	
