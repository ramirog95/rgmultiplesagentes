<?php
require_once _PS_MODULE_DIR_ . 'rgmultiplesagentes/classes/RgAgente.php';
class AdminRgAgentesController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'rg_agentes';
        $this->className = 'RgAgente';
        $this->identifier = 'id_agente';
        $this->lang = false;
        $this->bootstrap = true;

        parent::__construct();

        $this->fields_list = [
            'id_agente' => [
                'title' => $this->trans('ID', [], 'Modules.RgMultiplesAgentes.Admin'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
            'nombre' => [
                'title' => $this->trans('Nombre', [], 'Modules.RgMultiplesAgentes.Admin'),
            ],
            'telefono' => [
                'title' => $this->trans('Teléfono', [], 'Modules.RgMultiplesAgentes.Admin'),
            ],
            'categoria' => [
                'title' => $this->trans('Área de servicio', [], 'Modules.RgMultiplesAgentes.Admin'),
            ],
        ];

        // Botones de acción en la lista de agentes
        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }

    // Formulario para añadir/editar un agente
    public function renderForm()
    {
        $this->fields_form = [
            'legend' => [
                'title' => $this->trans('Agregar / Editar Agente', [], 'Modules.RgMultiplesAgentes.Admin'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->trans('Nombre', [], 'Modules.RgMultiplesAgentes.Admin'),
                    'name' => 'nombre',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Teléfono', [], 'Modules.RgMultiplesAgentes.Admin'),
                    'name' => 'telefono',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Área de servicio', [], 'Modules.RgMultiplesAgentes.Admin'),
                    'name' => 'categoria',
                    'required' => true,
                ],
            ],
            'submit' => [
                'title' => $this->trans('Guardar', [], 'Admin.Actions'),
            ],
        ];

        return parent::renderForm();
    }
}
