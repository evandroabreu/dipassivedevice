# DataInjection - Dispositivo Passivo

GLPI plugin that adds **Passive Device** (`PassiveDCEquipment`) support to the [DataInjection](https://github.com/pluginsGLPI/datainjection) plugin, enabling bulk import of passive devices from CSV files.

## Requirements

| Dependency | Version |
|---|---|
| GLPI | 10.0.0 – 10.0.x |
| DataInjection plugin | any compatible version |

## Supported fields

The following fields can be mapped to CSV columns when creating an import model:

| Field | Description |
|---|---|
| Name | Device name |
| Location | Physical location |
| Status | Device status |
| Passive device type | Type classification |
| Manufacturer | Device manufacturer |
| Model | Device model |
| Serial number | Serial number |
| Inventory number | Internal inventory number |
| Technician in charge | Responsible technician (resolved by login) |
| Group in charge | Responsible group |
| Comments | Free-text comments |

> **Child entities (`is_recursive`)** is always set to `1` on every imported record, so all sub-entities automatically have access to the imported data.

## Installation

1. Copy the `dipassivedevice` folder into your GLPI `plugins/` directory.
2. Go to **Setup → Plugins** in the GLPI administration panel.
3. Find **DataInjection - Dispositivo Passivo** and click **Install**, then **Activate**.

> Both this plugin and the DataInjection plugin must be active at the same time.

## Usage

1. Go to **Tools → Data injection**.
2. Create a new **Model** and select **Passive devices** as the item type.
3. Map each column of your CSV file to the corresponding field.
4. Mark at least one field as the **Link field** (e.g. *Name* or *Serial number*) — this field is used to detect whether a record already exists and should be updated instead of inserted.
5. Upload your CSV file and run the injection.

### CSV example

```csv
Nome,Status,Localizacao,Tipo,Fabricante,Modelo,Numero de serie,Numero inventario,Tecnico Encarregado,Grupo Encarregado,Comentario
Patch Panel 1A,Em uso,Sala de Servidores,Patch Panel,Furukawa,FX-24P,SN-00123,INV-2024-001,joao.silva,TI - Infraestrutura,Rack A fila 3
```

## How it works

The DataInjection plugin exposes a `plugin_datainjection_populate` hook that allows third-party plugins to register new injectable types without modifying the DataInjection source code.

This plugin uses that hook to register `PluginDatainjectionPassiveDCEquipmentInjection`, which extends GLPI's core `PassiveDCEquipment` class and implements `PluginDatainjectionInjectionInterface`.

Because DataInjection derives injection class names with a fixed convention (`PluginDatainjection{Itemtype}Injection`), a custom SPL autoloader is registered during plugin initialization to map the expected class name to the correct file inside this plugin's directory.

## License

GPLv2+

## Author

Evandro Abreu
