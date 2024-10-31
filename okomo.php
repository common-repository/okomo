<?php
/*
 * @package 		OKOMO
 * @version 		1.1.0
 * Plugin Name:		OKOMO
 * Plugin URI:		http://wordpress.org/plugins/okomo/
 * Description:		Personal all-in-one online-communication with customers, including video, voice and text chat, screensharing and appointment scheduling.
 * Version:			1.1.0
 * Author:			OKOMO Team
 * Text Domain: 	OKOMO
 * Author URI:   	https://okomo.com
*/

function okomo_custom_menu_page(){
    add_menu_page(
        'Okomo',
        'Okomo',
        'manage_options',
        'okomo','okomo_custom_menu_page_render',
        plugins_url('images/okomo.png', __FILE__),
        90
    );
}
add_action( 'admin_menu', 'okomo_custom_menu_page' );

function okomo_apply_widget()
{
	$get_okomo_user_acc_status=get_option('okomo_plugin_status');
    if($get_okomo_user_acc_status=='Active') {
        echo get_option('okomo_wp_widget');
    }
}
add_action( 'wp_footer', 'okomo_apply_widget' );

function okomo_plugin_update_message( $data, $response ) {
	if( isset( $data['upgrade_notice'] ) ) {
		printf(
			'<div class="update-message">%s</div>',
			wpautop( $data['upgrade_notice'] )
		);
	}
}
add_action( 'in_plugin_update_message-okomo/okomo.php', 'okomo_plugin_update_message', 10, 0 );

function okomo_custom_menu_page_render(){
    if (is_user_logged_in()) {
        if (sanitize_text_field($_POST['okomo_remove_wp_plugin'])!='') {
            delete_option('okomo_company_account_ID');
            delete_option('okomo_wp_widget');
            delete_option('okomo_plugin_status');
        }
        if (sanitize_text_field($_POST['okomo_company_login'])!='') {
            $okomo_company_account_ID = sanitize_text_field($_POST['textbox_okomo_company_ID']);
            add_option('okomo_company_account_ID', "$okomo_company_account_ID");
            add_option('okomo_plugin_status', 'Active');
        }
        $okomo_update_company_account_status = sanitize_text_field($_POST['okomo_company_account_status']);
        if ($okomo_update_company_account_status != '') {
            update_option('okomo_plugin_status', "$okomo_update_company_account_status");
        }
    }
    $okomo_company_account_id = get_option("okomo_company_account_ID");
    if (!empty($okomo_company_account_id) && is_user_logged_in()) {
        $code = '';
        $code .= '<script type="text/javascript">';
        $code .= '((function () { var load = function () {';
        $code .= 'var url = "https://portal.okomo.com/okomo/' . $okomo_company_account_id . '";';
        $code .= 'var e = document.createElement(\'script\');';
        $code .= 'e.src = url;';
		$code .= 'e.async = true;';
        $code .= 'var eX = document.getElementsByTagName(\'script\')[0];';
        $code .= 'eX.parentNode.insertBefore(e, eX);';
        $code .= '}; ';
		$code .= 'if (document.readyState === "complete") load();';
        $code .= 'else if (window.addEventListener) window.addEventListener(\'load\', load, false);';
        $code .= 'else if (window.attachEvent) {  window.attachEvent("onload", load); }';
        $code .= '})())</script>';
        $code .= '<noscript><a href="https://okomo.com" title="All-in-one customer-engagement">OKOMO</a></noscript>';
        add_option("okomo_wp_widget", htmlspecialchars_decode($code), "yes");
        ?>
        <div class="wrap">
            <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'okomo/images/okomo-logo.svg'; ?>" width="35" style="float: left; padding-right: 20px">
            <h1 style="font-weight: bold; line-height: 20px;">Okomo</h1>
            <br>
            <div class="postbox" style="padding: 15px; border-radius: 5px;">
                <div class="handlediv" title="Click to toggle">
                    <br>
                </div>
                <div class="inside">
                    <div class="main">
                        <h3 style="margin-top: 0;">OKOMO Company ID linked to your Website</h3>
                        <p><strong>Company ID</strong>: <?= $okomo_company_account_id ?></p>
                        <form action="" method="post">
                            <?php
                            $get_okomo_user_acc_status = get_option('okomo_plugin_status');
                            if ($get_okomo_user_acc_status == 'Active') {
                                echo '<p><button type="submit" name="okomo_company_account_status" value="Disabled" style="margin-right: 18px !important; background-color: #af4848; color: #fff; font-weight: 500; border: 1px solid #af4848;" class="button button-secondary">Disable OKOMO Button</button> to hide the OKOMO Button from your website</p>';
                            } else {
                                echo '<p><button type="submit" name="okomo_company_account_status" value="Active" style="margin-right: 18px !important; background-color: #5da95d; text-shadow: none; font-weight: 500; border: 1px solid #5da95d; color: #fff;" class="button button-secondary">Enable OKOMO Button</button> to show the OKOMO Button on your website</p>';
                            }
                            ?>
							<p><input type="submit" name="okomo_remove_wp_plugin" style="margin-right: 18px !important; background-color:#128d92; border: 1px solid #128d92; color: #fff; font-weight: 500;" value="Unlink OKOMO Company ID" class="button button-secondary"> to change your OKOMO Company Id</p>
							<br>
							<p><strong>OKOMO Button not showing up?</strong><br>Make sure that you entered the correct Company ID and you whitelisted the domain of your website under <i>Integration &gt; Website</i> in the <a href="https://admin.portal.okomo.com" target="_blank">OKOMO Admin Portal</a>.
							<br>
							<br>
							<p><strong>Next steps:</strong><br>Now you can start to configure your OKOMO widget and answer customer requests:</p>
							<p><a href="https://admin.portal.okomo.com" target="_blank" style="margin-right: 18px !important; background-color:#128d92; border: 1px solid #128d92; color: #fff; font-weight: 500;" class="button button-secondary">Open OKOMO Admin Portal</a> to configure the OKOMO widget, onboard your experts/agents, and manage your OKOMO subscription</p>
							<p><a href="https://expert.portal.okomo.com" target="_blank" style="margin-right: 18px !important; background-color:#128d92; border: 1px solid #128d92; color: #fff; font-weight: 500;" class="button button-secondary">Open OKOMO Expert Portal</a> to conveniently respond to customer requests or manage your expert profile</p>
                        </form>
                        <br>
						<br>
						<p style="font-size:x-small">Made with ❤ by OKOMO | <a href="https://okomo.com/faq" target="_blank">Help & Support</a> | <a href="https://okomo.com/contact" target="_blank">Contact us</a> | <a href="https://okomo.com/privacy" target="_blank">Privacy Policy</a> | <a href="https://okomo.com/terms" target="_blank">Terms of Use</a></p>
                    </div>
                </div>
            </div>
        </div>
    <?php }
    if(empty($okomo_company_account_id) && is_user_logged_in()){
        ?>
		<div class="wrap">
            <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'okomo/images/okomo-logo.svg'; ?>" width="35" style="float: left; padding-right: 20px">
            <h1 style="font-weight: bold; line-height: 20px;">OKOMO</h1>
            <br>
            <div class="postbox" style="padding: 15px; border-radius: 5px;">
                <div class="handlediv" title="Click to toggle">
                </div>
                <h3 class="hndle" style="margin-top: 0; padding-bottom: 10px;">
                    <span>Link your OKOMO Account to your Wordpress Website</span>
                </h3>
                <div class="inside">
                    <div class="main">
                        <form method="post" action="">
							<p>After entering your Company ID and whitelisting the domain of your website in the <a href="https://admin.portal.okomo.com" target="_blank">OKOMO Admin Portal</a>, the OKOMO Button will be displayed on your website.</p>
                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row" style="width: 90px;">Company ID</th>
                                    <td style="width: 150px;"><input type="text" name="textbox_okomo_company_ID" width="40" value="" required="required"/></td>
                                    <td><input type="submit" name="okomo_company_login" id="okomo_company_login" class="button button-secondary"
                                               style="float: left; margin-right: 18px !important; background-color:#128d92; border: 1px solid #128d92; color: #fff; font-weight: 500;" value="Activate OKOMO"></td>
                                </tr>
                            </table>
                            <p>To get your <strong>Company ID</strong>, navigate to <i>Integration &gt; Website</i> in the <a href="https://admin.portal.okomo.com" target="_blank">OKOMO Admin Portal</a>.</p>
                            <p>If you do not yet have an <strong>OKOMO account</strong>, please <a target="_blank" href="https://okomo.com/signup">register here</a>.</p>
							<p>For a step-by-step guide to integrate OKOMO into your Wordpress website, please <a target="_blank" href="https://okomo.com/wordpress">click here</a>.</p>
							<br>
							<br>
							<p style="font-size:x-small">Made with ❤ by OKOMO | <a href="https://okomo.com/faq" target="_blank">Help & Support</a> | <a href="https://okomo.com/contact" target="_blank">Contact us</a> | <a href="https://okomo.com/privacy" target="_blank">Privacy Policy</a> | <a href="https://okomo.com/terms" target="_blank">Terms of Use</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>
