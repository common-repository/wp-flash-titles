<?php
/*
Plugin Name: WP Flash Titles
Plugin URI: http://www.samburdge.co.uk
Description: Allows you to display your post titles in any font, colour and background colour. Comes with a selection of fonts to get you started.
Version: 2.0
Author: Sam Burdge
Author URI: http://www.samburdge.co.uk
*/

//database creation
$wp_ft_db_version = "2.0";

function wp_ft_install () {
   global $wpdb;
   global $wp_ft_db_version;

   $table_name = $wpdb->prefix . "flashtitles";
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      
      $sql = "CREATE TABLE " . $table_name . " (
	  fcolour text NOT NULL,
	  hcolour text NOT NULL,
	  bgcolour text NOT NULL,
	  ftwidth int(3) NOT NULL,
	  ftheight int(3) NOT NULL,
	  ftsize int(3) NOT NULL,
	  ftfile text NOT NULL
	);";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);

      $fcolour = "#999900";
      $hcolour = "#999999";
      $bgcolour = "#000000";
      $ftwidth = "500";
      $ftheight = "70";
      $ftsize = "20";
      $ftfile = "00sonix.swf";

      $insert = "INSERT INTO " . $table_name .
            " (fcolour, hcolour, bgcolour, ftwidth, ftheight, ftsize, ftfile) " .
            "VALUES ('" . $wpdb->escape($fcolour) . "','" . $wpdb->escape($hcolour) . "','" . $wpdb->escape($bgcolour) . "','" . $wpdb->escape($ftwidth) . "','" . $wpdb->escape($ftheight) . "','" . $wpdb->escape($ftsize) . "','" . $wpdb->escape($ftfile) . "')";

      $results = $wpdb->query( $insert );
 
      add_option("wp_ft_db_version", $wp_ft_db_version);

   }
}

register_activation_hook(__FILE__,'wp_ft_install');


//add the javascript
function flash_titles_js(){
	
	echo '<script type="text/javascript" src="'.get_settings('siteurl').'/wp-content/plugins/wp_flash_titles/swfobject.js"></script>
';
}

add_action('wp_head', 'flash_titles_js');

//Function: Add Color Picker Javascript to admin head
add_action('admin_head', 'flash_title_cp_js');
function flash_title_cp_js() { 
echo '<script src="'.get_bloginfo('wpurl').'/wp-content/plugins/wp_flash_titles/201a.js" type="text/javascript"></script>'; }

global $wpdb;

//options page
function flash_titles_options_page(){

//Get Flash files

$wpft_dir = ABSPATH.'/wp-content/plugins/wp_flash_titles/flash/'; // the directory, where your images are stored
  $allowed_types = array('swf'); // list of filetypes you want to show
  
  $wpft_img = opendir($wpft_dir);
  while($wpft_file = readdir($wpft_img))
  {
   if(in_array(strtolower(substr($wpft_file,-3)),$allowed_types))
   {
    $wpft_swf[] = $wpft_file;
    sort($wpft_swf);
    reset ($wpft_swf);
   }
  }

$tot_swf = count($wpft_swf); // total image number

$flash_titles_updated = $_POST['flash_titles_updated'];

global $wpdb;

$table_name = $wpdb->prefix . "flashtitles";


//update options

if($flash_titles_updated=="true"){
$updated_wpft = '<div class="updated"><p><strong>Options saved.</strong></p></div>';

$insert = "UPDATE " . $table_name . " SET fcolour = '" . $wpdb->escape($_POST['titlefontcolor']) . "' , hcolour = '" . $wpdb->escape($_POST['titlehovercolor']) . "' , bgcolour = '" . $wpdb->escape($_POST['titlebgcolor']) . "' , ftwidth = '" . $wpdb->escape($_POST['titlewidth']) . "' , ftheight = '" . $wpdb->escape($_POST['titleheight']) . "' , ftsize = '" . $wpdb->escape($_POST['titlefontsize']) . "' , ftfile = '" . $wpdb->escape($_POST['titlefont']) . "'";

      $results = $wpdb->query( $insert );

}

//database queries

$wpft_flashfile = $wpdb->get_var("SELECT ftfile FROM $table_name;");
$wpft_textcolor = $wpdb->get_var("SELECT fcolour FROM $table_name;");
$wpft_bgcolor = $wpdb->get_var("SELECT bgcolour FROM $table_name;");
$wpft_hovercolor = $wpdb->get_var("SELECT hcolour FROM $table_name;");
$wpft_width = $wpdb->get_var("SELECT ftwidth FROM $table_name;");
$wpft_height = $wpdb->get_var("SELECT ftheight FROM $table_name;");
$wpft_fontsize = $wpdb->get_var("SELECT ftsize FROM $table_name;");


print $updated_wpft.'<div class="wrap">
<div id="colorpicker201" class="colorpicker201"></div>

	<h2>WP Flash Titles Options</h2>


<p><b>Choose the colour scheme, font and size for your titles:</b></p>

<form method="post" action="">
<table>
<tr><td align="right">
Font colour: </td><td><input type="text" value="'.$wpft_textcolor.'" name="titlefontcolor" id="titlefontcolor" /></td><td>
<input type="text" ID="sample_1" size="1" value="" style="background: '.$wpft_textcolor.';" />
</td><td>
<input type="button" onclick="showColorGrid2(\'titlefontcolor\',\'sample_1\');" value="Select Colour" />
</td></tr>
<tr><td align="right">
Hover colour: </td><td><input type="text" value="'.$wpft_hovercolor.'" name="titlehovercolor" id="titlehovercolor" /></td><td>
<input type="text" ID="sample_2" size="1" value="" style="background: '.$wpft_hovercolor.';" />
</td><td>
<input type="button" onclick="showColorGrid2(\'titlehovercolor\',\'sample_2\');" value="Select Colour" /></td></tr>
<tr><td align="right">
Background colour: </td><td><input type="text" value="'.$wpft_bgcolor.'" name="titlebgcolor" id="titlebgcolor" /></td><td>
<input type="text" ID="sample_3" size="1" value="" style="background: '.$wpft_bgcolor.';" />
</td><td>
<input type="button" onclick="showColorGrid2(\'titlebgcolor\',\'sample_3\');" value="Select Colour" /></td></tr>
<tr><td align="right">
Width: </td><td><input type="text" value="'.$wpft_width.'" name="titlewidth" /></td></tr>
<tr><td align="right">
Height: </td><td><input type="text" value="'.$wpft_height.'" name="titleheight" /></td></tr>
<tr><td align="right">
Font size: </td><td><input type="text" value="'.$wpft_fontsize.'" name="titlefontsize" /></td></tr>
<tr><td align="right">
Font: </td><td>
<select name="titlefont">';

$xxx = '0';
foreach($wpft_swf as $wpft_swf_file){
if($wpft_swf_file == $wpft_flashfile){$sel = 'selected="selected" ';}
print '<option '.$sel.' value="'.$wpft_swf[$xxx].'">'.$wpft_swf[$xxx].'</option>';
$sel = '';
$xxx++;
}

print '</select>
</td></tr>

<tr><td align="right" colspan="2">
<input type="hidden" value="true" name="flash_titles_updated" />
<input type="submit" value="'.__('Update Options »').'" />
</td></tr></table>
</form>

<h2>Example</h2>
<script type="text/javascript" src="'.get_settings('siteurl').'/wp-content/plugins/wp_flash_titles/swfobject.js"></script>
<div id="flash_title_example"></div><script type="text/javascript">
var so = new SWFObject("'.get_settings('siteurl').'/wp-content/plugins/wp_flash_titles/flash/'.$wpft_flashfile.'?font_size='.$wpft_fontsize.'&the_title=This is a test title with 39 characters&fontcolour='.$wpft_textcolor.'&hovercolour='.$wpft_hovercolor.'&the_permalink=#", "mymovie", "'.$wpft_width.'", "'.$wpft_height.'", "8", "'. $wpft_bgcolor .'");
so.write("flash_title_example");
</script>


<p>&nbsp;</p>
<h2>Instructions:</h2>
<h3>To embed the plugin in your page template:</h3>
<ol>
<li>Go to \'Presentation\' -> \'Theme Editor\' and select the template file (Main index, Page, etc.)</li>
<li>Find the part in the code that calls the title, typically something like:<br />

<b>&lt;h3 class="storytitle">&lt;a href="&lt;?php the_permalink() ?>" rel="bookmark">&lt;?php the_title(); ?>&lt;/a>&lt;/h3></b></li>

<li>Add this code in the line before the title code occurs:<br />
<b>&lt;?php wp_flash_titles($post->ID,\'start\') ?></b>
</li>

<li>Add this code in the line after the title code occurs:<br />
<b>&lt;?php wp_flash_titles($post->ID,\'end\') ?></b>
</li>

<li>The full code should now be like:<br />
<b>&lt;?php wp_flash_titles($post->ID,\'start\') ?><br />
&lt;h3 class="storytitle">&lt;a href="&lt;?php the_permalink() ?>" rel="bookmark">&lt;?php the_title(); ?>&lt;/a>&lt;/h3><br />
&lt;?php wp_flash_titles($post->ID,\'end\') ?></b></li>
</ol>

<h3>To use any true type font (ttf):</h3>
<ol>
<li>Open the wp_flash_title.fla file in Flash CS3.</li>
<li>Change the font of the text box to any font you like.</li>
<li>Save as and publish the swf.</li>
<li>Upload the swf to wp-content/plugins/wp_flash_titles/flash/</li>
</ol>

<h3>Hints &amp; Tips:</h3>
<ol>
<li>Make sure the height and font size are set so that your longest titles fit within the box</li>
<li>Don\'t make the font size too small</li>

<li><br />
<form method="post" action="https://www.paypal.com/cgi-bin/webscr">
<input type="hidden" value="_s-xclick" name="cmd"/>
<input type="image" border="0" alt="Make payments with PayPal - it\'s fast, free and secure!" name="submit" src="http://www.samburdge.co.uk/images/samburdge-donate-button-str.jpg"/>
<img width="1" height="1" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" alt=""/>
<input type="hidden" value="-----BEGIN PKCS7-----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-----END PKCS7----- " name="encrypted"/>
</form>

</li>
</ol>



<div style="width: 100%; text-align: left; clear: both;">
<p>WP Flash Titles Plugin by <a href="http://www.samburdge.co.uk" target="_blank">Sam Burdge</a> 2008</p>
</div>

</div>';

}

function flash_titles_admin_page(){
	add_submenu_page('options-general.php', 'WP Flash Titles', 'WP Flash Titles', 5, 'wp_flash_titles.php', 'flash_titles_options_page');
}

add_action('admin_menu', 'flash_titles_admin_page');





//The titles
function wp_flash_titles($flash_title_id,$part){

global $post;
global $wpdb;

$table_name = $wpdb->prefix . "flashtitles";

$wpft_flashfile = $wpdb->get_var("SELECT ftfile FROM $table_name;");
$wpft_textcolor = $wpdb->get_var("SELECT fcolour FROM $table_name;");
$wpft_bgcolor = $wpdb->get_var("SELECT bgcolour FROM $table_name;");
$wpft_hovercolor = $wpdb->get_var("SELECT hcolour FROM $table_name;");
$wpft_width = $wpdb->get_var("SELECT ftwidth FROM $table_name;");
$wpft_height = $wpdb->get_var("SELECT ftheight FROM $table_name;");
$wpft_fontsize = $wpdb->get_var("SELECT ftsize FROM $table_name;");

if($part=='start'){$flash_title_content = '<div id="flash_title_'.$flash_title_id.'">';}

if($part=='end'){$flash_title_content = '</div>
<script type="text/javascript">
var so = new SWFObject("'.get_bloginfo('wpurl').'/wp-content/plugins/wp_flash_titles/flash/'. $wpft_flashfile. '?fontcolour=' .$wpft_textcolor. '&hovercolour=' .$wpft_hovercolor. '&font_size=' .$wpft_fontsize. '&the_title=' .get_the_title($flash_title_id). '&the_permalink='. get_permalink($flash_title_id). '", "mymovie", "'.$wpft_width.'", "'.$wpft_height.'", "8", "'. $wpft_bgcolor .'");
so.write("flash_title_'.$flash_title_id.'");
</script>
<div style="width: 100%; height: 3px;"></div>
';}

echo $flash_title_content;

}



?>