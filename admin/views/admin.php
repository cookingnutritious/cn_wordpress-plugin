<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   cn_wordpress
 * @author    Jesse Greathouse <jesse.greathouse@gmail.com>
 * @license   MIT
 * @link      http://cookingnutritious.com
 * @copyright 2014 Jesse Greathouse @cookingnutritious.com
 */
?>
<?php if (isset($_POST['do_cn_options']) && 'Y' == $_POST['do_cn_options']) { ?>
<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
<?php } ?>
<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <form name="cn_options" method="post" action="">
        <input type="hidden" name="do_cn_options" value="Y">
        <p><?php _e("API token:", 'menu-test' ); ?> 
        <input type="text" name="api_token" value="<?php echo $api_token; ?>" size="20">
        </p><hr />

        <p>
            <input type="checkbox" name="use_parent_category" 
            <?php if ($use_parent_category) echo "checked"; ?>
             value="true">Use parent category for all recipe posts<br>
        </p>

        <p><?php _e("Parent Category:", 'menu-test' ); ?> 
            <input type="text" name="parent_category" value="<?php echo $parent_category; ?>" size="20">
        </p><hr />

        <p>
            <input type="checkbox" name="category" 
            <?php if ($category) echo "checked"; ?>
             value="true">Automatically set the post category<br>
        </p></hr>

        <p>
            <input type="checkbox" name="tags" 
            <?php if ($tags) echo "checked"; ?>
             value="true">Automatically add tags to posts<br>
        </p></hr>

        <p class="submit">
        <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
        </p>
    </form>

</div>
