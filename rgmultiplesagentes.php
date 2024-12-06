<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class RgMultiplesAgentes extends Module
{
    public function __construct()
    {
        $this->name = 'rgmultiplesagentes';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Ramiro Grabinski';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => '8.99.99',
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('Múltiples Agentes de WhatsApp', [], 'Modules.RgMultiplesAgentes.Admin');
        $this->description = $this->trans('Añade un botón de WhatsApp flotante con múltiples agentes.', [], 'Modules.RgMultiplesAgentes.Admin');

        $this->confirmUninstall = $this->trans('¿Estás seguro de que deseas desinstalar este módulo?', [], 'Modules.RgMultiplesAgentes.Admin');

        if (!Configuration::get('RGMULTIPLESAGENTES_ACTIVE')) {
            $this->warning = $this->trans('El módulo no está configurado correctamente.', [], 'Modules.RgMultiplesAgentes.Admin');
        }
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        return (
            parent::install()
            && Configuration::updateValue('RGMULTIPLESAGENTES_ACTIVE', true)
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayFooter')
            && $this->installDb()
            && $this->installTab()
        );
    }

    public function hookDisplayFooter()
    {
        $agentes = $this->getAgentes();
        $this->context->smarty->assign([
            'agentes' => $agentes,
        ]);
        return $this->display(__FILE__, 'views/templates/hook/whatsapp_button.tpl');
    }
    public function uninstall()
    {
        return (
            parent::uninstall()
            && Configuration::deleteByName('RGMULTIPLESAGENTES_ACTIVE')
            && $this->uninstallDb()
            && $this->uninstallTab()
        );
    }

    private function installDb()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rg_agentes` (
            `id_agente` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `nombre` VARCHAR(255) NOT NULL,
            `telefono` VARCHAR(20) NOT NULL,
            `categoria` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id_agente`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        return Db::getInstance()->execute($sql);
    }

    private function uninstallDb()
    {
        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'rg_agentes`;';
        return Db::getInstance()->execute($sql);
    }
    public function getAgentes()
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('rg_agentes');
        return Db::getInstance()->executeS($sql);
    }   

    private function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Gestión de Agentes WhatsApp';
        }
        $tab->class_name = 'AdminRgAgentes';
        $tab->id_parent = (int) Tab::getIdFromClassName('IMPROVE');
        $tab->module = $this->name;

        return $tab->add();
    }

    private function uninstallTab()
    {
        $id_tab = (int) Tab::getIdFromClassName('AdminRgAgentes');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }

        return true;
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path . 'views/css/rgmultiplesagentes.css');
        $this->context->controller->addJS($this->_path . 'views/js/rgmultiplesagentes.js');
    }
}
