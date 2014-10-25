
$(document).ready(function(){ 
	$('#transactionType').on('change',function() { 

		if ( this.value == 'W' || this.value == 'D'){ 
			 $("#toAccountDiv").hide();
			 $("#transactionNoDiv").hide(); 
		}else{ 
			 $("#toAccountDiv").show();
			 $("#transactionNoDiv").show(); 
		 }
		 }); 
	});
	
