<?php
// simple wordpress back end page to display the settings of the user

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// register a page in the backend for the plugin
function phpLiteAdmin_register_settings_page() {
	add_menu_page(
		'phpLiteAdmin', // page title
		'phpLiteAdmin', // menu title
		'manage_options', // capability
		'phpLiteAdmin', // menu slug
		'phpLiteAdmin_render_settings_page', // callback function
		'dashicons-database', // icon
		100 // position
	);
}
add_action( 'admin_menu', 'phpLiteAdmin_register_settings_page' );

// render the settings page
function phpLiteAdmin_render_settings_page() {

	// check if current user has administrator role
	if ( ! current_user_can( 'manage_options' ) ) {
		?>
			<!-- HTML -->
			<div class="wrap">
				<!-- Titolo -->
				<h1>phpLiteAdmin</h1>
				<!-- Sezione Wiki -->
				<h2>You are not authorized</h2>
				<p>Request permissions from your administrator</p>
			</div>
		<?php
		return;
	}

	// show phpMyAdmin page 
	?>
		<style>
			#wpcontent {
				padding-left: 0px !important;
			}
		</style>
		<div id="phpMyAdmin">
			<iframe src="/wp-content/plugins/phpLiteAdmin-for-wp/php/phpliteadmin.php" frameborder="0" style="width:100%;height:calc( 100vh - 110px );"></iframe>
		</div>
	<?php
}

// register a new page in the backend for the plugin as a submenu of the previous one
function phpLiteAdmin_register_settings_page_2() {
	add_submenu_page(
		'phpLiteAdmin', // parent slug
		'Settings', // page title
		'Settings', // menu title
		'manage_options', // capability
		'phpLiteAdmin-settings', // menu slug
		'phpLiteAdmin_render_settings_page_2' // callback function
	);
}
add_action( 'admin_menu', 'phpLiteAdmin_register_settings_page_2' );

// render the settings page
function phpLiteAdmin_render_settings_page_2() {

	// check if current user has administrator role
	if ( ! current_user_can( 'manage_options' ) ) {
		?>
			<!-- HTML -->
			<div class="wrap">
				<!-- Titolo -->
				<h1>phpLiteAdmin</h1>
				<!-- Sezione Wiki -->
				<h2>You are not authorized</h2>
				<p>Request permissions from your administrator</p>
			</div>
		<?php
		return;
	}

	// show phpMyAdmin page 
	?>
		<!-- HTML -->
		<div class="wrap">

			<!-- Info -->
			<h2>Info</h2>
			<p>To have access to all the pages of this plugin you need to have administrator roles.</p>
			<br>

			<!-- Sezione Wiki -->
			<h2>Database path</h2>
			<p>The default path is that of the <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/sqlite-database-integration/">SQLite Database Integration</a> plugin.<br><b>Default path: /wp-content/database/.ht.sqlite</b></p>
			<br>
			
			<!-- Sezione Impostazioni -->
			<form action="options.php" method="post">
				<?php
					settings_fields( 'phpLiteAdmin__options' );
					do_settings_sections( 'phpLiteAdmin-settings' );
					submit_button();
				?>
			</form>
		</div>
	<?php
}

// register the settings
function phpLiteAdmin__register_settings() {
	// aggiunta dei singoli setting in database e nella pagina
	register_setting(
		'phpLiteAdmin__options', // option group
		'phpLiteAdmin__options', // option name
		'phpLiteAdmin__callback' // sanitize callback
	);

	add_settings_section(
		'phpLiteAdmin__section', // id
		'Settings', // title
		'', // callback
		'phpLiteAdmin-settings' // page
	);

	// theme setting
	add_settings_field(
		'phpLiteAdmin__theme', // id
		'Theme', // title
		'phpLiteAdmin__theme_function', // callback
		'phpLiteAdmin-settings', // page
		'phpLiteAdmin__section' // section
	);

	// databases positions setting
	add_settings_field(
		'phpLiteAdmin__databases', // id
		'Databases', // title
		'phpLiteAdmin__databases_function', // callback
		'phpLiteAdmin-settings', // page
		'phpLiteAdmin__section' // section
	);
	
}
add_action( 'admin_init', 'phpLiteAdmin__register_settings' );

// sanitize the input
function phpLiteAdmin__callback( $options ) {

    if ( ! is_array( $options ) ) {
        $options = [];
    }

	if ( isset( $_POST['phpLiteAdmin__theme'] ) ) {
		$options['phpLiteAdmin__theme'] = sanitize_text_field( $_POST['phpLiteAdmin__theme'] );
	} else {
		$options['phpLiteAdmin__theme'] = 0;
	}

	if ( isset( $_POST['phpLiteAdmin__databases'] ) ) {
		$options['phpLiteAdmin__databases'] = $_POST['phpLiteAdmin__databases'];
	}

    return $options;
}

// render the fields
function phpLiteAdmin__theme_function() {
	$options = get_option( 'phpLiteAdmin__options' );
	?>
	<div style="display:flex;gap:2rem;align-items:center">
		<select name="phpLiteAdmin__theme" id="phpLiteAdmin__theme" style="width:300px;">
			<?php
			$dir = ABSPATH . "/wp-content/plugins/phpLiteAdmin-for-wp/themes/";
			$themes = scandir($dir);

			foreach ($themes as $theme) {
				if (is_dir($dir . $theme) && $theme != "." && $theme != "..") {
					if ($options['phpLiteAdmin__theme'] == $theme)
						echo "<option value='$theme' selected>$theme</option>";
					else
						echo "<option value='$theme'>$theme</option>";
				}
			}
			?>
		</select>
		<p style="margin:0;">Choose your phpLiteAdmin style.</p>
	</div>
	<?php
}

function phpLiteAdmin__databases_function() {
	$options = get_option( 'phpLiteAdmin__options' );
	?>
	<div style="display:flex;gap:2rem;align-items:center;">
		<input style="width:300px;" type="text" name="phpLiteAdmin__databases" id="phpLiteAdmin__databases" value="<?php echo $options['phpLiteAdmin__databases']; ?>">
		<p style="margin:0;">Enter your databases folder. (Separate the different paths with a comma)</p>
	</div>
	<?php
}
?>