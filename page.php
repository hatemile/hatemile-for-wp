<div class="wrap">
    <h1>HaTeMiLe settings</h1>
    <form method="post" action="options.php">
        <?php
        // This prints out all hidden setting fields
        settings_fields('hatemile_option_group');
        do_settings_sections('hatemile-settings');
        submit_button();
        ?>
    </form>
</div>