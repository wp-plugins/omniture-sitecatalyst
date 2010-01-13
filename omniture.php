<?php
/*
Plugin Name: Omniture - SiteCatalyst
Plugin URI: http://www.rudishumpert.com/projects/wp-omniture/
Description: Add Omniture - SiteCatalyst to your blog with settings controlled in the admin section.
Version: 0.0.1 ALPHA
Author: Rudi Shumpert
Author URI: http://www.rudishumpert.com/
*/

define('omni_version', '0.0.1 ALPHA', true);

$omni_options = get_option('omni_admin_options'); 

// set an  SiteCatalyst option in the options table of WordPress
function omni_set_option($option_name, $option_value) {
  $omni_options = get_option('omni_admin_options');
  $omni_options[$option_name] = $option_value;
  update_option('omni_admin_options', $omni_options);
}

function omni_get_option($option_name) {
  $omni_options = get_option('omni_admin_options'); 
  if (!$omni_options || !array_key_exists($option_name, $omni_options)) {
    $omni_default_options=array();

    $omni_default_options['omni_report_sid']               = 'Report Suite ID';  
    $omni_default_options['omni_s_code_path']              = 'http://www.yousite.com/s_code.js';  
    $omni_default_options['omni_img_script_path']          = 'http://a.analytics.yahoo.com';
    $omni_default_options['omni_domain_list']              = '.yourdomain.com,yourotherdomain.com';
    $omni_default_options['omni_track_loggedin']    	   = 'false' ;   
    $omni_default_options['omni_sProp_loggedin']           = 's.prop3';
    $omni_default_options['omni_eVar_loggedin']            = 's.eVar3';
	$omni_default_options['omni_track_internal_search']    = 'false' ; 
    $omni_default_options['omni_sProp_internal_search']    = 's.prop1';
    $omni_default_options['omni_eVar_internal_search']     = 's.eVar1';
	$omni_default_options['omni_track_internal_search_num']= 'false' ; 
    $omni_default_options['omni_sProp_internal_search_num']= 's.prop2';
    $omni_default_options['omni_eVar_internal_search_num'] = 's.eVar2';
    $omni_default_options['omni_track_admins']    	       = 'false' ;
    $omni_default_options['omni_url_campid']    	       = 'cid' ;      

    add_option('omni_admin_options', $omni_default_options, 
               'Settings for  SiteCatalyst plugin');

    $result = $omni_default_options[$option_name];
  } else {
    $result = $omni_options[$option_name];
  }
  
  return $result;
}

function omni_admin() {

  if (function_exists('add_options_page')) {
    add_options_page('Omniture - SiteCatalyst Settings' /* page title */, 
                     'Omniture' /* menu title */, 
                     8 /* min. user level */, 
                     basename(__FILE__) /* php file */ , 
                     'omni_options' /* function for subpanel */);
  }

}

function omni_options() {
  if (isset($_POST['advanced_options'])) {
    omni_set_option('advanced_config', true);
  }
  if (isset($_POST['simple_options'])) {
    omni_set_option('advanced_config', false);
  }
  if (isset($_POST['factory_settings'])) {
    $omni_factory_options = array();
    update_option('omni_admin_options', $omni_factory_options);
    ?><div class="updated"><p><strong><?php _e('Default settings restored, remember to set RSID ID', 'omni')?></strong></p></div><?php
  }
  if (isset($_POST['info_update'])) {
    ?><div class="updated"><p><strong><?php 
    // process options form
    $omni_options = get_option('omni_admin_options');
    $omni_options['omni_report_sid']           		 = $_POST['omni_report_sid'];
    $omni_options['omni_s_code_path']       		 = $_POST['omni_s_code_path'];
    $omni_options['omni_domain_list']          		 = $_POST['omni_domain_list'];
    $omni_options['omni_img_script_path']       	 = $_POST['omni_img_script_path'];
    $omni_options['omni_track_loggedin']    	     = $_POST['omni_track_loggedin'];  
    $omni_options['omni_sProp_loggedin']             = $_POST['omni_sProp_loggedin'];
    $omni_options['omni_eVar_loggedin']              = $_POST['omni_eVar_loggedin'];
	$omni_options['omni_track_internal_search']      = $_POST['omni_track_internal_search']; 
    $omni_options['omni_sProp_internal_search']      = $_POST['omni_sProp_internal_search'];
    $omni_options['omni_eVar_internal_search']       = $_POST['omni_eVar_internal_search'];
	$omni_options['omni_track_internal_search_num']  = $_POST['omni_track_internal_search_num']; 
    $omni_options['omni_sProp_internal_search_num']  = $_POST['omni_sProp_internal_search_num'];
    $omni_options['omni_eVar_internal_search_num']   = $_POST['omni_eVar_internal_search_num'];
    $omni_options['omni_track_admins']    	         = $_POST['omni_track_admins']; 
    $omni_options['omni_url_campid']    	         = $_POST['omni_url_campid'];   
    
    update_option('omni_admin_options', $omni_options);

    _e('Options saved', 'omni')
    ?></strong></p></div><?php
	} 
	// Admin Page Form
  
	?>
<div class=wrap>
  <form method="post">
    <h2>Omniture - SiteCatalyst</h2>
    <fieldset class="options" name="general">
      <legend><?php _e('General settings', 'omni') ?></legend>
      <table width="100%" cellspacing="2" cellpadding="5" class="editform">

        <tr>
          <th nowrap valign="top" width="33%" align="left"><?php _e('JS Script Path', 'omni') ?></th>
          <td><input name="omni_s_code_path" type="text" id="omni_s_code_path" value="<?php echo omni_get_option('omni_s_code_path'); ?>" size="100" />
            <br />Enter the path to your SiteCatalyst s_code.js file (ie. http://www.yoursite.com/s_code.js ).
          </td>
        </tr>
        <tr>
          <th nowrap valign="top" width="33%" align="left"><?php _e('URL Campaign Variable', 'omni') ?></th>
          <td><input name="omni_url_campid" type="text" id="omni_url_campid" value="<?php echo omni_get_option('omni_url_campid'); ?>" size="10" />
            <br />Enter the url variable that will indentify campaigns. ( site.com/?campid=mycampcode Only enter campid)
          </td>
        </tr>
        <tr>
          <th nowrap valign="top" width="33%" align="left"><?php _e('Track Admins', 'omni') ?></th>
          <td><input name="omni_track_admins" type="checkbox" id="omni_track_admins" value="true" <?php if (omni_get_option('omni_track_admins')) echo "checked"; ?>  />
            <br />Enable tracking of blog administrators.
          </td>
        </tr>
        <tr>
          <th nowrap valign="top" width="33%" align="left"><?php _e('Internal Search', 'omni') ?></th>
          <td><input name="omni_track_internal_search" type="checkbox" id="omni_track_internal_search" value="true" <?php if (omni_get_option('omni_track_internal_search')) echo "checked"; ?>  />
            <br />Enable tracking of internal seraches
          </td>
        </tr>
        <tr>
          <th nowrap valign="top" width="33%" align="left"><?php _e('Internal Search: s.propN', 'omni') ?></th>
          <td><input name="omni_sProp_internal_search" type="text" id="omni_sProp_internal_search" value="<?php echo omni_get_option('omni_sProp_internal_search'); ?>" size="3" />
            <br />Enter the s.prop # that will hold the Internal Search Terms (NOTE: only enter the # 1 or 2 or 3 etc.)
          </td>
        </tr> 
        <tr>
          <th nowrap valign="top" width="33%" align="left"><?php _e('Internal Search Results', 'omni') ?></th>
          <td><input name="omni_track_internal_search_num" type="checkbox" id="omni_track_internal_search_num" value="true" <?php if (omni_get_option('omni_track_internal_search_num')) echo "checked"; ?>  />
            <br />Enable tracking of internal seraches results
          </td>
        </tr>
        <tr>
          <th nowrap valign="top" width="33%" align="left"><?php _e('Internal Search Results: s.propN', 'omni') ?></th>
          <td><input name="omni_sProp_internal_search_num" type="text" id="omni_sProp_internal_search_num" value="<?php echo omni_get_option('omni_sProp_internal_search_num'); ?>" size="3" />
            <br />Enter the s.prop # that will hold the Internal Search Results count (NOTE: only enter the # 1 or 2 or 3 etc.)
          </td>
        </tr> 
        <tr>
          <th nowrap valign="top" width="33%" align="left"><?php _e('Logged In', 'omni') ?></th>
          <td><input name="omni_track_loggedin" type="checkbox" id="omni_track_loggedin" value="true" <?php if (omni_get_option('omni_track_loggedin')) echo "checked"; ?>  />
            <br />Enable tracking of logged in status of users
          </td>
        </tr>
        <tr>
          <th nowrap valign="top" width="33%" align="left"><?php _e('Logged In: s.propN', 'omni') ?></th>
          <td><input name="omni_sProp_loggedin" type="text" id="omni_sProp_loggedin" value="<?php echo omni_get_option('omni_sProp_loggedin'); ?>" size="3" />
            <br />Enter the s.prop # that will hold the Logged In Status (NOTE: only enter the # 1 or 2 or 3 etc.)
          </td>
        </tr> 
      </table>
    </fieldset>
  
    <div class="submit">
      <input type="submit" name="info_update" value="<?php _e('Update options', 'omni') ?>" />
	  </div>
  </form>
</div><?php
 
}

function omni_insert_html_once($location, $html) {
  global $omni_footer_hooked;
  global $omni_html_inserted;
    $omni_footer_hooked = true;
    if (!$omni_html_inserted) {
      echo $html;
      }
}


function omni_get_tracker() {
  
  $result='<!-- user not tracked-->';

  if(is_home()) { 
	  $pageName = $category = $pageType = 'Blog Home';
  } elseif (is_page()) {
      $pageName = $category = the_title('', '', false);
      $pageType = 'Static Page';
  } elseif (is_single()) { 
      $categories = get_the_category();
      $pageName =  the_title('', '', false);
              $category = $categories[0]->name;
              $pageType = 'Article';
  } elseif (is_category()) {
     $pageName = $category = single_cat_title('', false);
     $pageName = 'Category: ' . $pageName;
	 $pageType = 'Category';
  } elseif (is_tag()) { 
 	 $pageName = $category = single_tag_title('', false);
  	 $pageType = 'Tag';
  } elseif (is_month()) { 
     list($month, $year) = split(' ', the_date('F Y', '', '', false));
     $pageName = 'Month Archive: ' . $month . ' ' . $year;
     $category = $pageType = 'Month Archive';
  } elseif (is_404()) {
  	$pageName = '404:'.$_SERVER["REQUEST_URI"];
  	$category = '404';
  }
  
  
  global $omni_camp_id_var;
  global $omni_camp_id_value;
  $omni_camp_id_var  .=  omni_get_option('omni_url_campid');
  $omni_camp_id_value = $_GET[$omni_camp_id_var];
  if ( $omni_camp_id_value == '' )
  	 {
  	 	$omni_camp = '';
  	 	
  	 }
  else
	  {
	  	$omni_camp = 's.campaign= "'.$omni_camp_id_value.'"';
	  }

  global $internal_search_value;
  $internal_search_value  =  $_GET["s"];
  if ( $internal_search_value == '' )
  	 {
  	 	$internal_search = '';
  	 	$internal_search_count = ''; 
  	 }
  else
   { 
   	  if (omni_get_option('omni_track_internal_search')) 
   	  {
   	  	$s_prop_for_search = omni_get_option('omni_sProp_internal_search');
   	  	$internal_search = 's.prop'.$s_prop_for_search.'= "'.$internal_search_value.'"' ;
   	  } else {
   	  	$internal_search = '';
   	  }
   	  if (omni_get_option('omni_track_internal_search_num')) 
   	  {
	   	  global $wp_query;
		  $omni_count_total .= $wp_query->found_posts;
   	  	  $internal_search_count = 's.prop'.omni_get_option('omni_sProp_internal_search_num').'= "'.$omni_count_total.'"' ;
   	  } else {
   	  	$internal_search_count = ''; 
   	  }

   	  $pageName = 'Internal Search'; 
   	  $category = 'Internal Search';
   	  $pageType = 'Internal Search';         		
   }

 	if (omni_get_option('omni_track_loggedin')) 
   	  {
   	  	  if ( is_user_logged_in() ) {
		      $loggedin = 'Yes';
		  } else {
		      $loggedin = 'No';
		  }; 
   	  	$s_prop_for_loggedin = omni_get_option('omni_sProp_loggedin');
   	  	$omni_loggedin = 's.prop'.$s_prop_for_loggedin.'= "'.$loggedin.'"' ;
   	  } else {
   	  	$omni_loggedin = '';
   	  }



      // tracking code to be added to page
  
  if (!omni_get_option('omni_track_admins')) {
       $result='<!-- user not tracked by Omniture-SiteCatalyst plugin v'.omni_version.': http://www.rudishumpert.com/projects/-->';
    } else {
    	 	$result='' .
    	 			'' .
    	 			'' .
    	 			'
			<!-- tracker added by Omniture-SiteCatalyst plugin v'.omni_version.': http://www.rudishumpert.com/projects/ -->
			<script type="text/javascript" src="'.omni_get_option('omni_s_code_path').'"></script>
			<script type="text/javascript"><!--
			
			s.pageName = "'.$pageName.'"
			s.channel = "'.$category.'"
			s.pageType = "'.$pageType.'"
			'.$internal_search.'
			'.$internal_search_count.'
			'.$omni_camp.'
		    '.$omni_loggedin.'

			/************* DO NOT ALTER ANYTHING BELOW THIS LINE ! **************/
			var s_code=s.t();if(s_code)document.write(s_code) //omniture variable
			-->
			</script>

	
			';
    }

  return $result;
}

function omni_wp_footer_track($OMNISluggo) {
  omni_insert_html_once('footer', omni_get_tracker());
  return $OMNISluggo;
}
// **************
// initialization
global $omni_footer_hooked;
$omni_footer_hooked=false;
add_action('admin_menu', 'omni_admin');
add_action('wp_footer', 'omni_wp_footer_track');

?>