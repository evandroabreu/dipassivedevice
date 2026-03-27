<?php

/**
 * Plugin: dipassivedevice
 * Injection class for PassiveDCEquipment (Dispositivo Passivo).
 *
 * Campos suportados na importação:
 *   - name              (Nome)
 *   - locations_id      (Localização)
 *   - users_id_tech     (Técnico encarregado do hardware)
 *   - groups_id_tech    (Grupo encarregado do hardware)
 *   - serial            (Número de série)
 *   - otherserial       (Número de inventário)
 *   - comment           (Comentários)
 *   - states_id         (Status)
 *   - passivedcequipmenttypes_id  (Tipo de dispositivo passivo)
 *   - manufacturers_id  (Fabricante)
 *   - passivedcequipmentmodels_id (Modelo)
 *   - is_recursive      (Entidades filhas — sempre gravado como 1)
 */
class PluginDipassivedevicePassiveDCEquipmentInjection extends PassiveDCEquipment implements
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
     * Search option IDs used (from PassiveDCEquipment::rawSearchOptions and
     * Location::rawSearchOptionsToAdd):
     *   1  = name
     *   3  = locations_id       (dropdown, from Location::rawSearchOptionsToAdd)
     *   4  = passivedcequipmenttypes_id  (dropdown)
     *   5  = serial
     *   6  = otherserial
     *  16  = comment            (added manually — not in PassiveDCEquipment rawSearchOptions)
     *  23  = manufacturers_id   (dropdown)
     *  24  = users_id_tech      (user)
     *  31  = states_id          (dropdown)
     *  40  = passivedcequipmentmodels_id (dropdown)
     *  49  = groups_id_tech     (dropdown)
     */
    public function getOptions($primary_type = ''): array
    {
        $tab = Search::getOptions(get_parent_class($this));

        // PassiveDCEquipment does not define a search option for 'comment'.
        // We add it manually so that DataInjection can map a CSV column to it.
        if (!isset($tab[16])) {
            $tab[16] = [
                'id'        => '16',
                'table'     => $this->getTable(),
                'field'     => 'comment',
                'name'      => __('Comments'),
                'datatype'  => 'text',
            ];
        }

        $blacklist = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(
            get_parent_class($this)
        );

        // Fields that are not meaningful to import
        $notimportable = [
            61,  // template_name
        ];

        $options['ignore_fields'] = array_merge($blacklist, $notimportable);

        $options['displaytype'] = [
            // Dropdowns resolved by name
            'dropdown'       => [3, 4, 23, 31, 40, 49],
            // User resolved by login/name
            'user'           => [24],
            // Multi-line text area
            'multiline_text' => [16],
        ];

        return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
    }

    /**
     * Delegates the actual DB insert/update to the standard library.
     */
    public function addOrUpdateObject($values = [], $options = []): array
    {
        $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
        $lib->processAddOrUpdate();
        return $lib->getInjectionResults();
    }

    /**
     * Always sets is_recursive = 1 so that child entities (Entidades filhas)
     * can see the imported records.
     */
    public function addSpecificNeededFields($primary_type, $values): array
    {
        return ['is_recursive' => 1];
    }
}
