<?php

/**
 * Plugin: dipassivedevice
 * Adds PassiveDCEquipment (Dispositivo Passivo) support to the DataInjection plugin
 * via the plugin_datainjection_populate hook.
 */

define('PLUGIN_DIPASSIVEDEVICE_VERSION', '1.0.0');
define('PLUGIN_DIPASSIVEDEVICE_MIN_GLPI', '10.0.0');
define('PLUGIN_DIPASSIVEDEVICE_MAX_GLPI', '10.0.99');

/**
 * Required by GLPI to display the plugin in Setup > Plugins.
 */
function plugin_version_dipassivedevice(): array
{
    return [
        'name'         => 'DataInjection - Dispositivo Passivo',
        'version'      => PLUGIN_DIPASSIVEDEVICE_VERSION,
        'author'       => 'TRE-PI',
        'license'      => 'GPLv2+',
        'homepage'     => '',
        'requirements' => [
            'glpi' => [
                'min' => PLUGIN_DIPASSIVEDEVICE_MIN_GLPI,
                'max' => PLUGIN_DIPASSIVEDEVICE_MAX_GLPI,
            ],
        ],
    ];
}

/**
 * Called by GLPI on every page load when the plugin is active.
 * Registers the hook that adds PassiveDCEquipment to DataInjection.
 */
function plugin_init_dipassivedevice(): void
{
    global $PLUGIN_HOOKS;

    $PLUGIN_HOOKS['csrf_compliant']['dipassivedevice'] = true;

    $plugin = new Plugin();
    if ($plugin->isActivated('dipassivedevice')) {
        // The DataInjection plugin builds injection class names as:
        //   'PluginDatainjection' . ucfirst($itemtype) . 'Injection'
        // For the core type PassiveDCEquipment this resolves to
        // PluginDatainjectionPassiveDCEquipmentInjection, but that file lives
        // in OUR plugin directory, not in datainjection/inc/.
        // We register an SPL autoloader so PHP can find it regardless of
        // which plugin directory DataInjection is looking in.
        spl_autoload_register('plugin_dipassivedevice_autoload');

        $PLUGIN_HOOKS['plugin_datainjection_populate']['dipassivedevice'] =
            'plugin_dipassivedevice_populate_datainjection';
    }
}

/**
 * SPL autoloader: maps PluginDatainjectionPassiveDCEquipmentInjection to our
 * plugin's inc/ directory.
 */
function plugin_dipassivedevice_autoload(string $class): void
{
    if ($class === 'PluginDatainjectionPassiveDCEquipmentInjection') {
        include_once Plugin::getPhpDir('dipassivedevice')
            . '/inc/passivedcequipmentinjection.class.php';
    }
}

function plugin_dipassivedevice_check_prerequisites(): bool
{
    if (
        version_compare(GLPI_VERSION, PLUGIN_DIPASSIVEDEVICE_MIN_GLPI, 'lt')
        || version_compare(GLPI_VERSION, PLUGIN_DIPASSIVEDEVICE_MAX_GLPI, 'gt')
    ) {
        echo 'Este plugin requer GLPI >= ' . PLUGIN_DIPASSIVEDEVICE_MIN_GLPI
            . ' e <= ' . PLUGIN_DIPASSIVEDEVICE_MAX_GLPI . '.';
        return false;
    }

    if (!Plugin::isPluginActive('datainjection')) {
        echo 'Este plugin requer o plugin DataInjection ativo.';
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
