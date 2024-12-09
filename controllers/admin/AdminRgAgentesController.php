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
            'imagen' => [
                'title' => $this->trans('Foto de perfil', [], 'Modules.RgMultiplesAgentes.Admin'),
                'callback' => 'displayImage',
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
                [
                    'type' => 'file',
                    'label' => $this->trans('Foto de perfil', [], 'Modules.RgMultiplesAgentes.Admin'),
                    'name' => 'imagen',
                    'display_image' => true,
                ],
            ],
            'submit' => [
                'title' => $this->trans('Guardar', [], 'Admin.Actions'),
            ],
        ];

        return parent::renderForm();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAdd' . $this->table)) {
            $id = (int) Tools::getValue('id_agente');

            // Manejo de subida de imagen
            if (isset($_FILES['imagen']) && !empty($_FILES['imagen']['tmp_name'])) {
                $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                $allowed_extensions = ['jpg', 'jpeg', 'png'];

                $allowed_mime_types = ['image/jpeg', 'image/png'];
                $mime_type = mime_content_type($_FILES['imagen']['tmp_name']);

                if (!in_array($mime_type, $allowed_mime_types) || !in_array(strtolower($extension), $allowed_extensions)) {
                    $this->errors[] = $this->trans('Solo se permiten archivos JPG y PNG.', [], 'Modules.RgMultiplesAgentes.Admin');
                } else {
                    $file_name = Tools::str2url(pathinfo($_FILES['imagen']['name'], PATHINFO_FILENAME)) . '.' . $extension;
                    $path = _PS_IMG_DIR_ . $file_name;

                    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $path)) {
                        $this->errors[] = $this->trans('Error al subir la imagen.', [], 'Modules.RgMultiplesAgentes.Admin');
                    } else {
                        $_POST['imagen'] = $file_name;
                    }
                }
            } elseif ($id) {
                $agente = new RgAgente($id);
                $_POST['imagen'] = $agente->imagen;
            }
        }

        return parent::postProcess();
    }

    // Mostrar la imagen en la lista
    public function displayImage($value, $row)
    {
        $img_path = _PS_IMG_ . $value;
        if ($value && file_exists(_PS_IMG_DIR_ . $value)) {
            return '<img src="' . $img_path . '" alt="" class="img-thumbnail" width="50">';
        }
        return '';
    }
    public function renderView()
    {
        // Obtener todos los agentes
        $agentes = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'rg_agentes');

        // Construir la URL completa de la imagen para cada agente
        foreach ($agentes as &$agente) {
            // Verificar si la imagen existe
            if (!empty($agente['imagen']) && file_exists(_PS_IMG_DIR_ . $agente['imagen'])) {
                $agente['imagen'] = _PS_IMG_ . $agente['imagen']; // Construir URL completa
            } else {
                $agente['imagen'] = _MODULE_DIR_ . 'rgmultiplesagentes/views/img/default-avatar.png'; // Imagen predeterminada
            }
        }

        // Pasar los datos a Smarty
        $this->context->smarty->assign('agentes', $agentes);

        // Renderizar el TPL
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'rgmultiplesagentes/views/templates/front/agentes-list.tpl');
    }
}
