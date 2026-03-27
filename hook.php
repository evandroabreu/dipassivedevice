<?php

/**
 * Plugin: dipassivedevice
 * hook.php — registers GLPI hooks and the datainjection populate callback.
 */

$PLUGIN_HOOKS['csrf_compliant']['dipassivedevice'] = true;

// Register our injection class with the DataInjection plugin
$PLUGIN_HOOKS['plugin_datainjection_populate']['dipassivedevice'] =
    'plugin_dipassivedevice_populate_datainjection';

/**
 * Called by DataInjection via Plugin::doHook('plugin_datainjection_populate').
 * Adds PassiveDCEquipment to the list of injectable types.
 */
function plugin_dipassivedevice_populate_datainjection(): void
{
    /** @var array $INJECTABLE_TYPES */
    global $INJECTABLE_TYPES;

    $INJECTABLE_TYPES['PluginDipassivedevicePassiveDCEquipmentInjection'] = 'dipassivedevice';
}
