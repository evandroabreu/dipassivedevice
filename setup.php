<?php

/**
 * Plugin: dipassivedevice
 * Adds PassiveDCEquipment (Dispositivo Passivo) support to the DataInjection plugin
 * via the plugin_datainjection_populate hook.
 */

define('PLUGIN_DIPASSIVEDEVICE_VERSION', '1.0.0');
define('PLUGIN_DIPASSIVEDEVICE_MIN_GLPI',  '10.0.0');
define('PLUGIN_DIPASSIVEDEVICE_MAX_GLPI',  '10.1.99');

function plugin_dipassivedevice_check_prerequisites(): bool
{
    if (
        version_compare(GLPI_VERSION, PLUGIN_DIPASSIVEDEVICE_MIN_GLPI, 'lt')
        || version_compare(GLPI_VERSION, PLUGIN_DIPASSIVEDEVICE_MAX_GLPI, 'gt')
    ) {
        echo 'This plugin requires GLPI >= ' . PLUGIN_DIPASSIVEDEVICE_MIN_GLPI;
        return false;
    }

    if (!Plugin::isPluginActive('datainjection')) {
        echo 'This plugin requires the DataInjection plugin to be active.';
        return false;
    }

    return true;
}

function plugin_dipassivedevice_check_config(bool $verbose = false): bool
{
    return true;
}

function plugin_dipassivedevice_install(): bool
{
    return true;
}

function plugin_dipassivedevice_uninstall(): bool
{
    return true;
}
