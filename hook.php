<?php

/**
 * Plugin: dipassivedevice
 * hook.php — callback functions for registered hooks.
 */

/**
 * Called by DataInjection via Plugin::doHook('plugin_datainjection_populate').
 * Adds PassiveDCEquipment to the list of injectable types.
 */
function plugin_dipassivedevice_populate_datainjection(): void
{
    /** @var array $INJECTABLE_TYPES */
    global $INJECTABLE_TYPES;

    $INJECTABLE_TYPES['PluginDatainjectionPassiveDCEquipmentInjection'] = 'dipassivedevice';
}
