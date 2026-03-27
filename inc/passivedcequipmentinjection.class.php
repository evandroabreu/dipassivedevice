<?php

/**
 * Plugin: dipassivedevice
 * Injection class for PassiveDCEquipment (Dispositivo Passivo).
 *
 * NAMING NOTE:
 * The DataInjection plugin builds the injection class name via:
 *   'PluginDatainjection' . ucfirst($itemtype) . 'Injection'
 * For PassiveDCEquipment (a core GLPI type) this always produces
 * "PluginDatainjectionPassiveDCEquipmentInjection", regardless of which
 * plugin registers it. Our plugin registers an SPL autoloader in
 * plugin_init_dipassivedevice() so PHP finds this class here instead of
 * looking (and failing) inside the datainjection plugin directory.
 *
 * Campos suportados na importação:
 *   - name                       (Nome)
 *   - locations_id               (Localização)
 *   - users_id_tech              (Técnico encarregado do hardware)
 *   - groups_id_tech             (Grupo encarregado do hardware)
 *   - serial                     (Número de série)
 *   - otherserial                (Número de inventário)
 *   - comment                    (Comentários)
 *   - states_id                  (Status)
 *   - passivedcequipmenttypes_id (Tipo de dispositivo passivo)
 *   - manufacturers_id           (Fabricante)
 *   - passivedcequipmentmodels_id (Modelo)
 *   - is_recursive               (Entidades filhas — sempre gravado como 1)
 */
class PluginDatainjectionPassiveDCEquipmentInjection extends PassiveDCEquipment implements
    PluginDatainjectionInjectionInterface
{
    public static function getTable($classname = null): string
    {
        return PassiveDCEquipment::getTable();
    }

    public function isPrimaryType(): bool
    {
        return true;
    }

    public function connectedTo(): array
    {
        return [];
    }

    public function isNullable($field): bool
    {
        return true;
    }

    /**
     * Returns the list of importable fields, their display types, and which to ignore.
     *
     * Search option IDs (from PassiveDCEquipment::rawSearchOptions and
     * Location::rawSearchOptionsToAdd):
     *   1  = name
     *   3  = locations_id          (dropdown — linkfield added manually)
     *   4  = passivedcequipmenttypes_id  (dropdown — linkfield added manually)
     *   5  = serial
     *   6  = otherserial
     *  16  = comment               (option added manually — absent from rawSearchOptions)
     *  23  = manufacturers_id      (dropdown — linkfield added manually)
     *  24  = users_id_tech         (user — linkfield already in rawSearchOptions)
     *  31  = states_id             (dropdown — linkfield added manually)
     *  40  = passivedcequipmentmodels_id (dropdown — linkfield added manually)
     *  49  = groups_id_tech        (dropdown — linkfield already in rawSearchOptions)
     */
    public function getOptions($primary_type = ''): array
    {
        $tab = Search::getOptions(get_parent_class($this));

        // addToSearchOptions() silently removes every option that has no 'linkfield'.
        // PassiveDCEquipment::rawSearchOptions() omits linkfield for all foreign-key
        // dropdowns, so we must set them explicitly here.
        $tab[3]['linkfield']  = 'locations_id';               // Localização
        $tab[4]['linkfield']  = 'passivedcequipmenttypes_id'; // Tipo de dispositivo passivo
        $tab[23]['linkfield'] = 'manufacturers_id';            // Fabricante
        $tab[31]['linkfield'] = 'states_id';                   // Status
        $tab[40]['linkfield'] = 'passivedcequipmentmodels_id'; // Modelo

        // 'comment' has no search option in PassiveDCEquipment at all — add it manually.
        if (!isset($tab[16])) {
            $tab[16] = [
                'id'        => '16',
                'table'     => $this->getTable(),
                'field'     => 'comment',
                'linkfield' => 'comment',
                'name'      => __('Comments'),
                'datatype'  => 'text',
            ];
        }

        $blacklist = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(
            get_parent_class($this)
        );

        $notimportable = [61]; // template_name

        $options['ignore_fields'] = array_merge($blacklist, $notimportable);

        $options['displaytype'] = [
            'dropdown'       => [3, 4, 23, 31, 40, 49],
            'user'           => [24],
            'multiline_text' => [16],
        ];

        return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
    }

    public function addOrUpdateObject($values = [], $options = []): array
    {
        $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
        $lib->processAddOrUpdate();
        return $lib->getInjectionResults();
    }

    /**
     * Always sets is_recursive = 1 so child entities can see the imported records.
     */
    public function addSpecificNeededFields($primary_type, $values): array
    {
        return ['is_recursive' => 1];
    }
}
