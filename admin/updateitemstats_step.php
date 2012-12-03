<?php
/******************************
* EQdkp
* Copyright 2002-2007
* Licensed under the GNU GPL.  See COPYING for full terms.
* ------------------
*  
* some code from Charmanager by Wallenium
*
* Began: Juni 1 2007 Corgan
*
* $Id: updateitemstats_step.php 62 2007-05-15 18:42:34Z osr-corgan $
 ******************************/
 
define('EQDKP_INC', true);
define('IN_ADMIN', true);
$eqdkp_root_path = './../';
include_once($eqdkp_root_path . 'common.php');
include_once($eqdkp_root_path . 'itemstats/config.php');
include_once($eqdkp_root_path . 'itemstats/eqdkp_itemstats.php');
$user->check_auth('a_item_upd');

$output = "
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=".$user->lang['ENCODING']." />
<script language='JavaScript' type='text/javascript' src='".$eqdkp_root_path."pluskernel/javascript/include/javascripts/prototype.js'></script>
<script language='JavaScript' type='text/javascript' src='".$eqdkp_root_path."pluskernel/javascript/include/javascripts/effects.js'></script>
<script language='JavaScript' type='text/javascript' src='".$eqdkp_root_path."pluskernel/javascript/include/javascripts/window.js'></script>
<script>
function closeWindow()
{
		var myId = window.name.replace('_content', '');   // resolve the id I was given
		window.parent.Windows.close(myId);  // call parent's windows collection to close me
}
</script>";

	$step = $_GET['step']	;
	$items = array();
  $ii = 0;
    
	if($step=='items')
	{
		$sql = "SELECT DISTINCT item_name FROM " . ITEMS_TABLE ." order by item_name" ;
	}
	
  if($step=='trade')
	{
			if ($pm->check(PLUGIN_INSTALLED, 'ts')) 
			{ 
		  	if (!defined('RP_RECIPES_TABLE')) { define('RP_RECIPES_TABLE', $table_prefix . 'tradeskill_recipes'); }		
				$sql = ' SELECT DISTINCT recipe_name as item_name  FROM ' . RP_RECIPES_TABLE . ' GROUP BY recipe_name ';
			}	
	}	

 	if($step=='bank')
	{
		if ($pm->check(PLUGIN_INSTALLED, 'raidbanker')) 
		{ 
			if (!defined('RB_BANKS_TABLE')) { define('RB_BANKS_TABLE', $table_prefix . 'raidbanker_bank'); }		
			$sql = ' SELECT  DISTINCT rb_item_name as item_name FROM ' . RB_BANKS_TABLE . ' as item_name GROUP BY rb_item_name ';
		}			
	}			

 	if($step=='bad')
	{
	 		$sql = 'SELECT DISTINCT item_name FROM ' . item_cache_table . ' WHERE item_icon LIKE "%INV_Misc_QuestionMark%"'  ; 
	}	
	
 	if($step=='all')
	{
	 		$sql = 'SELECT DISTINCT item_name FROM ' . item_cache_table . ' ' . ITEMS_TABLE   ; 			
	}	

	if(isset($sql))
	{
		$result = $db->query($sql) ;
	  while($row = $db->fetch_record($result)) 
    {
      $items[$ii] = $row['item_name'];
      $ii++;
    }
    $db->free_result($result);
	}	

	$output .= '<span id="loadingtext" style="display:inline;"><table>';			
	$output .= '<tr><td><img src="'.$eqdkp_root_path.'images/glyphs/progress.gif" alt"Loading" />';
	$output .= ' Update '.$ii.' Items </td></tr>';
	$output .= '<tr><td><iframe src="updateitemstats_include.php?step='.$step.'&count='.$ii.'&actual=0" width="390" height="280" name="item_update" frameborder=0 scrolling="no"><td></tr>';
	$output .= '</table></span>';
	
 echo $output;

?>
