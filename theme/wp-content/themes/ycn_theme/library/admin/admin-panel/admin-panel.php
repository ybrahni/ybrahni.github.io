<?php

function al_add_adminpanel() {
	
	global $theme_name, $shortname, $options, $wpdb;
	$upload_tracking = array();
	$options_array = array();
	
	
	if (isset($_REQUEST['caction']) && $_REQUEST['caction'] == 'save') {
		foreach($options as $value) {
			if (isset($value['id']) && isset($_REQUEST[$value['id']])) {
				if (!preg_match("/^[\s]{1,1000}$/D", $_REQUEST[$value['id']])) {
					$options_array[$value['id']] = stripslashes($_REQUEST[$value['id']]);
				}
			}

			// Upload
			if ($value['type'] == 'upload') {
				$id = $value['id'];
				$override['test_form'] = false;
				$override['action'] = 'save';
				if (!empty($_FILES['attachment_' . $id]['name'])) {
					$file = wp_handle_upload($_FILES['attachment_' . $id], $override);
					$file['upload_name'] = $value['name'];
					$upload_tracking[] = $file;

					// Check if not error
					if (!isset($file['error'])) {

						// Update option
						$options_array[$id] = $file['url'];
						// Add attachment
						add_attachment($file);
					}
				}
				elseif (empty($_FILES['attachement_' . $id]['name']) && $_REQUEST[$id] != '') {
					$options_array[$value['id']] = $_REQUEST[$value['id']];
				}
			}

			update_option('al_tracking', $upload_tracking);
		}
		update_option('al_general_settings', $options_array);
		redirect('adminpanel');
	}

	// Load admin menu
	add_theme_page($theme_name, $theme_name, 'administrator', 'adminpanel', 'adminpanel');

}
// Load modules
///gs

function al_adminpanel_scripts($hook) {
	
	if (isset($_REQUEST['page']) && $_REQUEST['page'] == 'adminpanel') {
		// JAVASCRIPT
		wp_enqueue_script('r-colorpicker', get_template_directory_uri() . '/library/admin/js/colorpicker.js', array('jquery'), '1.0.0');
	}
}

add_action('admin_enqueue_scripts', 'al_adminpanel_scripts');

function adminpanel() {
	
	global $theme_name, $shortname, $options;
?>

<div id="header">
	<div id="logo-container">
		<h1 class="option_title">Vertica Wp</h1>		
	</div>	
    <div id="admin-panel">
    	<p>Administration Panel</p>
       
    </div>       
    <div class="clear"></div>
</div>
<div class="wrap al_wrap">
	<div class="al_opts">
		<div id="messages">
			<?php
            $upload_tracking = get_option('al_tracking');
            if (!empty($upload_tracking)) {
                foreach($upload_tracking as $array) {
                    if (array_key_exists('error', $array)) {
                        echo '<div class="error-message"><p><strong>' . $array['upload_name'] . '</strong> - ' . $array['error'] . '</p></div>';
                    }
                }
            }
            delete_option('al_tracking');
            if (isset($_REQUEST['saved'])) echo '<div class="success-message"><p>Settings successfully saved</p></div>'; 
            ?>
		</div>
		<form method="post" id="post" enctype="multipart/form-data">
			<div id="vtabs1">
				<div>
					<ul>
			  		<?php
						foreach($options as $value) 
						{
							if (isset($value['tab_name'])) 
							{
								echo '<li><a href="#'.$value['tab_id'].'" id="'.$value['tab_id'].'"><span>' . $value['tab_name'] . '</span></a></li>' . "\n";
							}
						} 
					?>
					</ul>
				</div>
				<div>
					<?php adminpanel_contructor($options); ?>
				</div>	  	
			</div>
		
			<div id="footer-block">
            	<input name="save" class="footer_input"type="submit" value="Save changes"  />
            	<input type="hidden" name="caction" value="save" />
        	</div>
        </form>
	</div>
</div>
<?php } 

///
//include($panelPath . 'sidebars.php');


function redirect($page) { ?>
 
<?php
}

/** Attachments **/
function add_attachment($file) {
	
	$url = $file['url'];
	$type = $file['type'];
	$file = $file['file'];
	$filename = basename($file);

	// Construct the attachment array
	$attachment = array(
		'post_title' => $filename,
		//'post_content' => $descr,
		'post_type' => 'attachment',
		//'post_parent' => $post,
		'post_mime_type' => $type,
		'guid' => $url
	);

	// Save the data
	$id = wp_insert_attachment($attachment, $file, $post='');
	if (preg_match('!^image/!', $attachment['post_mime_type'])) {
		$imagesize = getimagesize($file);
		$imagedata['width'] = $imagesize['0'];
		$imagedata['height'] = $imagesize['1'];
		list($uwidth, $uheight) = get_udims($imagedata['width'], $imagedata['height']);
		$imagedata['hwstring_small'] = "height='$uheight' width='$uwidth'";
		$imagedata['file'] = $file;
		add_post_meta($id, '_wp_attachment_metadata', $imagedata);
	}
}

/*** CONSTRUCTOR ***/

function adminpanel_contructor($options) {
	
	$get_options = get_option('al_general_settings');
	foreach($options as $value) {
		$id = isset($value['id']) ? $value['id'] : '';
		switch ($value['type']) {
				case 'open' : ?>
		<div id="<?php echo $value['tab_id']; ?>">
		<?php 
			break; 
			case 'close' :
		?>
		</div>
<!-- ////////////////////////// UPLOAD ////////////////////////// -->
<?php
	break;
	case 'toggle':
?>	
	<h2 class="toggle-trigger"><a href="#" class="tr"><?php echo $value['item_name']; ?></a></h2> 
			<div class="toggle-container"> 
				<div class="block"> 
<?php	
	break;
	case 'toggle_close':
?>	
		</div> 
	</div> 
<?php	
	break;
	case 'upload':
?>
<div>
	<div class="al_input">
		<div class="r-upload">
			<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
			<input type="file" name="attachment_<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"/>
		</div>
		<div>
		<label for="<?php echo $value['id']; ?>">&nbsp;</label>
		<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php if (isset($get_options[$id]) && $get_options[$id] != '') echo stripslashes($get_options[$id]); else echo $value['std'] ?>" class="inputs"/>
		<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
		</div>
		<div style="margin-top:20px">
		<?php if (isset($get_options[$id]) && !empty($get_options[$id])) : ?>
			<label for="">Preview</label>
			<a href="<?php echo $get_options[$id]; ?>"><img src="<?php echo  $get_options[$id] ?>" alt="" style="max-width:250px" /></a>
			<div class="clearfix"></div>
		<?php endif; ?>
		
		</div>
	</div>
</div>

<!-- Textboxes -->
<?php
	break;
	case 'text':
?>
<div class="al_input al_text">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
	<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php if (isset($get_options[$id]) && $get_options[$id] != '') echo ($get_options[$id]); else echo (isset ($value['std']) ? $value['std'] : ''); ?>" class="inputs"/>
	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
</div>

<!-- ////////////////////////// COLOR ////////////////////////// -->
<?php
	break;
	case 'color':
?>
<div class="al_input">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
	<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php if (isset($get_options[$id]) && $get_options[$id] != '') echo stripslashes($get_options[$id]); else echo $value['std'] ?>" class="inputs color-picker"/>
	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
</div>

<!-- checkboxes -->
<?php
	break;
	case 'checkbox':
?>
<div class="al_input al_checkbox">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
	
  	<?php
	if ($get_options[$id]) {
		$checked = 'checked="checked"';
	}
	else {
		$checked = '';
	} ?>
	<div class="check-box">
    	<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
  	</div>
	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
</div>

<!-- Select Box -->
<?php
	break;
	case 'select':
?>
<div class="al_input al_select">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
	<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" size="1" >
	    <?php
		foreach($value['options'] as $option) {
		if ($get_options[$id] == $option['value']) $selected = 'selected="selected"';
		else $selected = '';
		echo "<option $selected value='" . $option['value'] . "'>" . $option['text'] . "</option>";
		} ?>
	</select>
  	<small><?php echo isset ($value['desc']) ? $value['desc'] : ''; ?></small><div class="clearfix"></div>
</div>

<?php
	break;
	case 'background':
?>
            
    <div class="al_input al_select">
        <label for="<?php echo $value['id']; ?>" style="width:100%; float:none; margin-bottom:10px"><?php echo $value['name']; ?></label>
        <p style="margin-bottom:20px"><?php echo $value['desc']; ?></p>
        <div class="backgrounds">                	
		    <?php foreach ($value['options'] as $option) :
				if ($get_options[$id] == $option) $checked = 'checked="checked"';
				else $checked = '';
		 	?>
		       	<div class="skin-background">
        	   		<img src="<?php echo get_template_directory_uri() ?>/img/backgrounds/<?php echo $option?>"  />
        	   		<table>
	        	   		<tr>
		        	   		<td><label for="<?php echo $option; ?>"><?php echo $option; ?></label></td>
		        	   		<td>
		        	   			<input type="radio" <?php echo $checked?> name="<?php echo $value['id']; ?>" value="<?php echo $option; ?>" id="<?php echo $value['id']; ; ?>" <?php if (get_option( $value['id'] ) == $option) { echo 'checked="checked"'; } ?> />
		        	   		</td>
	        	   		</tr>
        	   		</table>
        	   </div>
			
    		<?php endforeach; ?>
        </div>
        <div class="clear"></div>
     
    </div>


<!-- ////////////////////////// SELECT CATEGORY ////////////////////////// -->
<?php
	break;
	case 'select_category':
?>
<div class="al_input al_select">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
	<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" size="1" class="r-select">
    <option value="_all"<?php if ($get_options[$id] == '_all'): ?> selected="selected" <?php endif; ?>>All</option>
		<?php
		foreach((get_categories()) as $category) {
			if ($category->term_id == $get_options[$id]) {
				$selected = "selected=\"selected\"";
			} else { 
				$selected = "";
			}
		echo "<option $selected value=\"" . $category->term_id . "\">" . $category->cat_name . "</option>" . "\n";
		} ?>
	</select>
  	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
</div>

<!-- TextAreas -->
<?php
	break;
	case 'textarea':
?>
<div class="al_input">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
	<textarea name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if (isset($get_options[$id]) && $get_options[$id] != '') echo stripslashes($get_options[$id]); else echo $value['std'] ?></textarea>
	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
</div>
<?php
	break;
		}
	}
}
// Add admin menu
 add_action('admin_menu', 'al_add_adminpanel'); 
?>
