<?php
/* For licensing terms, see /license.txt */

/**
 * BNPanel
 * 
 * @author Julio Montoya <gugli100@gmail.com> BeezNest
 *  
 */

if(THT != 1){die();}

class page {
	
	public function __construct() {
		$this->navtitle = "Invoice Sub Menu";
		$this->navlist[] = array("View all Invoices", "package_go.png", "all");
		//$this->navlist[] = array("Add Invoice", "package_add.png", "add");
		//$this->navlist[] = array("Edit Invoice", "package_go.png", "edit");		
		//$this->navlist[] = array("Delete Invoice", "package_delete.png", "delete");		
	}
	
	public function description() {
		return "<strong>Managing Invoices</strong><br />
		Welcome to the Invoice Management Area. Here you can add, edit and delete Invoices. <br />
		To get started, choose a link from the sidebar's SubMenu.";	
	}	
	
	public function content() {		
		global $style, $db, $main, $invoice,$addon, $package;
		
		/*if(isset($_GET['iid']) && isset($_GET['pay'])) {					
			$invoice->set_paid($_GET['iid']);
			echo "<span style='color:green'>Invoice #{$_GET['iid']} marked as paid. <a href='index.php?sub=all&page=invoices&iid={$_GET['iid']}&unpay=true'>Undo this action</a></span>";
		}
		if(isset($_GET['iid']) && isset($_GET['unpay'])){	
			$invoice->set_unpaid($_GET['iid']);
			echo "<span style='color:red'>Invoice {$_GET['iid']} marked as unpaid. <a href='index.php?sub=all&page=invoices&iid={$_GET['iid']}&pay=true'>Undo this action</a></span>";
		}*/
		
		switch($main->getvar['sub']) {					
			case 'add':
				echo $style->replaceVar("tpl/invoices/addinvoice.tpl");
			break;
			case 'edit':
				if(isset($main->getvar['do'])) {
					$query = $db->query("SELECT * FROM `<PRE>invoices` WHERE `id` = '{$main->getvar['do']}'");
					if($db->num_rows($query) == 0) {
						echo "That invoice doesn't exist!";	
					} else {						
						if($_POST) {
							foreach($main->postvar as $key => $value) {
								//if($value == "" && !$n && $key != "admin") {
								
								/*if($value == "" && !$n && $key != "admin" && substr($key,0,13) != "billing_cycle"  && substr($key,0,5) != "addon" ) {
									$main->errors("Please fill in all the fields!");
									$n++;
								}*/
							}							
							if(!$n) {
								if ($main->postvar['status'] == INVOICE_STATUS_PAID) {
									$invoice->set_paid($main->getvar['do']);
								} else {
									$invoice->set_unpaid($main->getvar['do']);
								}
								
								$addong_list = $addon->getAllAddonsByBillingId($main->postvar['billing_id']);
								
								$new_addon_list = array();																
								foreach($addong_list as $addon_id=>$addon_amount) {																								
									$variable_name = 'addon_'.$addon_id;
									//var_dump($variable_name);
									if (isset($main->postvar[$variable_name]) && ! empty($main->postvar[$variable_name]) ) {										
										$new_addon_list[$addon_id] = $main->postvar[$variable_name];				
									}															
								}
																	
								$new_addon_list_serialized = $addon->generateAddonFeeFromList($new_addon_list, $main->postvar['billing_id'], true);								
								$main->postvar['due'] 		= strtotime($main->postvar['due']);
								$main->postvar['addon_fee'] = $new_addon_list_serialized;
								//Editing the invoice
								
								$invoice->edit($main->getvar['do'], $main->postvar);
								$main->errors('Invoice has been edited!');
								
								if ($main->postvar['status'] == INVOICE_STATUS_DELETED) {
									$main->redirect('?page=invoices&sub=all');	
								}								
							}
						}						
					}					
					$return_array = $invoice->getInvoice($main->getvar['do']);
					$return_array['DUE'] = substr($return_array['DUE'], 0, 10);					
					echo $style->replaceVar("tpl/invoices/editinvoice.tpl", $return_array);
				}
			break;			
			case 'view':				
				if(isset($main->getvar['do'])) {					
					$return_array = $invoice->getInvoice($main->getvar['do'], true);									
					echo $style->replaceVar("tpl/invoices/viewinvoice.tpl", $return_array);					
				}
			break;
			case 'delete':			
				if (isset($main->getvar['do'])) { 
					$invoice->delete($main->getvar['do']);					
				} else {
					$main->redirect("?page=invoices&sub=all");										
				}
				if (isset($main->getvar['confirm']) && $main->getvar['confirm'] == 1) {
					$main->errors("The invoice #".$main->getvar['do']." has been  deleted!");
					$main->redirect("?page=invoices&sub=all");	
				}
			default :			
			case 'all':					
				$per_page = $db->config('rows_per_page');
				$count_sql = "SELECT count(*)  as count FROM ".$invoice->getTableName()." WHERE status <> '".INVOICE_STATUS_DELETED."'";
				$result_max = $db->query($count_sql);		
				$count = $db->fetch_array($result_max);
				
				$count = $count['count'];
				$quantity = ceil($count / $per_page);							
				$return_array['COUNT'] = $quantity;
				echo $style->replaceVar("tpl/invoices/admin-page.tpl", $return_array);				
			break;			
		}
	}
}