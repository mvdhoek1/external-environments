<?php

namespace MVDH\Environments\Admin;

use MVDH\Environments\Foundation\ServiceProvider;
use WP_Admin_Bar;

class AdminBarServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        \add_action('admin_bar_menu', [$this, 'addEnvironments']);
        \add_action('admin_init', [$this, 'addSettingsPage']);
        \add_action('admin_menu', [$this, 'addOptionsPage']);
    }

    public function addOptionsPage()
    {
        add_menu_page(
            'WPOrg',
            'WPOrg Options',
            'manage_options',
            'wporg',
            [$this, 'optionsPageCallback']
        );
    }

    public function optionsPageCallback()
    {
        // check user capabilities
        if (! current_user_can('manage_options')) {
            return;
        }

        // add error/update messages

        // check if the user have submitted the settings
        // WordPress will add the "settings-updated" $_GET parameter to the url
        if (isset($_GET['settings-updated'])) {
            // add settings saved message with the class of "updated"
            add_settings_error('wporg_messages', 'wporg_message', __('Settings Saved', 'wporg'), 'updated');
        }

        // show error/update messages
        settings_errors('wporg_messages');
        ?>
	<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
		<form action="options.php" method="post">
			<?php
                // output security fields for the registered setting "wporg"
                settings_fields('wporg');
        // output setting sections and their fields
        // (sections are registered for "wporg", each field is registered to a specific section)
        do_settings_sections('wporg');
        // output save settings button
        submit_button('Save Settings');
        ?>
		</form>
	</div>
	<?php
    }

    public function wporg_field_pill_cb($args)
    {
        // Get the value of the setting we've registered with register_setting()
        $options = get_option('wporg_options');
        ?>
        <select
                id="<?php echo esc_attr($args['label_for']); ?>"
                data-custom="<?php echo esc_attr($args['wporg_custom_data']); ?>"
                name="wporg_options[<?php echo esc_attr($args['label_for']); ?>]">
            <option value="red" <?php echo isset($options[ $args['label_for'] ]) ? (selected($options[ $args['label_for'] ], 'red', false)) : (''); ?>>
                <?php esc_html_e('red pill', 'wporg'); ?>
            </option>
             <option value="blue" <?php echo isset($options[ $args['label_for'] ]) ? (selected($options[ $args['label_for'] ], 'blue', false)) : (''); ?>>
                <?php esc_html_e('blue pill', 'wporg'); ?>
            </option>
        </select>
        <p class="description">
            <?php esc_html_e('You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'wporg'); ?>
        </p>
        <p class="description">
            <?php esc_html_e('You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'wporg'); ?>
        </p>
        <?php
    }

    public function addSettingsPage()
    {
        register_setting('wporg', 'wporg_options');

        add_settings_section(
            'wporg_section_developers',
            __('The Matrix has you.', 'wporg'),
            [$this, 'wporg_section_developers_callback'],
            'wporg'
        );

        add_settings_field(
            'wporg_field_pill', // As of WP 4.6 this value is used only internally.
            // Use $args' label_for to populate the id inside the callback.
            __('Pill', 'wporg'),
            [$this, 'wporg_field_pill_cb'],
            'wporg',
            'wporg_section_developers',
            array(
                'label_for'         => 'wporg_field_pill',
                'class'             => 'wporg_row',
                'wporg_custom_data' => 'custom',
            )
        );
    }

    public function wporg_section_developers_callback($args)
    {
        ?>
        <p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Follow the white rabbit.', 'wporg'); ?></p>
        <?php
    }

    public function addEnvironments(WP_Admin_BAR $adminBar): void
    {
        // $groups = $this->getGroups();

        // if (empty($groups)) {
        //     return;
        // }

        $adminBar->add_menu([
            'id'    => 'external-environments',
            'parent' => 'site-name',
            'group'  => null,
            'title' => __('My environments', 'mvdh-environments'),
            'meta' => [
                'title' => __('My environments', 'mvdh-environments'),
            ]
        ]);

        // foreach ($groups as $group) {
        //     $adminBar->add_menu([
        //         'id'    => \sanitize_title($group->getTitle()),
        //         'parent' => 'external-environments',
        //         'group'  => null,
        //         'title' => $group->getTitle(),
        //         'href'  => $group->getURL(),
        //         'meta' => [
        //             'title' => $group->getTitle(),
        //         ]
        //     ]);
        // }
    }

    // protected function getGroups(): array
    // {
    //     try {
    //         $groups = \get_field('environment', 'option');

    //         if (! is_array($groups)) {
    //             throw new \Exception;
    //         }
    //     } catch (\Exception $e) {
    //         $groups = [];
    //     }

    //     if (empty($groups)) {
    //         return [];
    //     }

    //     $converted = $this->convertToModels($groups);

    //     return $this->validateGroups($converted);
    // }

    // protected function convertToModels(array $groups): array
    // {
    //     return array_map(function ($group) {
    //         return new EnvironmentSettingGroup(is_array($group) ? $group : []);
    //     }, $groups);
    // }

    // protected function validateGroups($groups): array
    // {
    //     return array_filter($groups, function ($group) {
    //         return $group->isValid();
    //     });
    // }
}
