<script type="text/javascript">
	tinyMCE.init({
	mode : "textareas",
	skin : "o2k7",
	theme : "simple"
	});
	
	$(function() {
		$( "#due" ).datepicker({ 
			dateFormat: 'yy-mm-dd',
			showOn: 'button',
			buttonImage: '<URL>themes/icons/calendar_add.png'			 
			});
	});
	
	function loadAddons(obj) {
		var id=obj.options[obj.selectedIndex].value;
		/*
		var billing_obj = document.getElementById("billing_cycle_id");
		var billing_id=billing_obj.options[billing_obj.selectedIndex].value;*/
		var billing_id = document.getElementById("billing_id").value;
		$.get("<AJAX>function=loadaddons&package_id="+id+"&billing_id="+billing_id+"&order_id="+document.getElementById("order_id").value, function(data) {
			document.getElementById("showaddons").innerHTML = data;
		});
	}
</script>
<div class="contextual">
	<a href="?page=invoices&sub=view&do=%ID%"> <img src="<URL>themes/icons/arrow_rotate_clockwise.png"> Return to Invoice</a> 
</div>
<h2>Invoice #%ID%</h2>
<ERRORS>

<form class="content"  id="addpackage" name="addpackage" method="post" action="">
<input name="package_id" type="hidden" id="package_id" value="%PACKAGE_ID%" />
<table width="100%" border="0" cellspacing="2" cellpadding="0"> 
    <tr>
    <td width="20%">Order id:</td>
    <td><a href="?page=orders&sub=view&do=%ORDER_ID%">#%ORDER_ID%</a></td>
  </tr>
     <tr>
    <td valign="top">User</td>
    <td>
    %USER%
    </td>
  </tr>
  
     <tr>
    <td valign="top">Domain</td>
    <td>    
    <a target="_blank" href="http://%REAL_DOMAIN%">%REAL_DOMAIN%</a>
    </td>
  </tr>
  
    <tr>
    <td valign="top">Description:</td>
    <td><textarea name="notes" id="notes" cols="45" rows="5">%NOTES%</textarea></td>
  </tr>  
  
      <tr>
    <td valign="top">Billing cycles</td>
    <td>
    %BILLING_CYCLES%
    </td>
  </tr>
    
	<tr>
    <td valign="top">Package</td>
    <td>
    %PACKAGE_NAME%
    </td>
  </tr>
  
  	<tr>
    <td valign="top">Package amount</td>
    <td>
    
    <input name="amount" type="text" id="amount" value="%PACKAGE_AMOUNT%" />
    </td>
  </tr>
 
       <tr>
    <td valign="top">Addons</td>
    <td>
 	 %ADDON% 
    </td>
  </tr>
  
    	<tr>
    <td valign="top">Status</td>
    <td>
    %STATUS%
    </td>
  </tr> 
    
  <!-- <tr>
    	<td valign="top">Package amount:</td>
    	<td><input name="amount" type="text" id="amount" value="%AMOUNT%" /></td>
	</tr>  
  
	<tr>
    <td valign="top">Total</td>
    <td>
    %TOTAL%
    </td>
  </tr>
  -->  


  	<tr>
    <td valign="top">Creation date</td>
    <td>  		
  		%CREATED%
    </td>
  </tr>  
  
  	<tr>
    <td valign="top">Due date</td>
    <td>  		
  		<input name="due" type="text" id="due" value="%DUE%"/>
    </td>
  </tr>  
 
 
  <tr>
    <td valign="top">Email center</td>
    <td>  	
    	<ul>    		
  		<li><a target="_blank" href="?page=email&sub=templates&do=25">Edit Invoice Paid email</a>		<a target="_blank" href="?page=email&sub=templates&do=25"><img src="<URL>themes/icons/pencil.png"></a></li>
  		<li><a target="_blank" href="?page=email&sub=templates&do=26">Edit Invoice Pending email</a>	<a target="_blank" href="?page=email&sub=templates&do=26"><img src="<URL>themes/icons/pencil.png"></a></li>
  		<li><a target="_blank" href="?page=email&sub=templates&do=27">Edit Invoice Cancelled email</a>	<a target="_blank" href="?page=email&sub=templates&do=27"><img src="<URL>themes/icons/pencil.png"></a></li>		
  		</ul>
    </td>    
  </tr>    
</table>

<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td colspan="2" align="center"><input type="submit" name="add" id="add" value="Edit invoice" /></td>
  </tr>
</table>
</form>
