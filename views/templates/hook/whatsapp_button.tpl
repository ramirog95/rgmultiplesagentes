<div id="whatsapp-floating-button">
    <button id="whatsapp-button" onclick="toggleWhatsappList()">
        <img src="{$module_dir}views/img/whatsapp-icon.png" alt="WhatsApp">
    </button>
    <div id="whatsapp-agentes-list" style="display: none;">
        <ul>
            {foreach from=$agentes item=agente}
                <li>
                    <a href="https://wa.me/+549{$agente.telefono|escape:'html':'UTF-8'}?text=Hola%20{$agente.nombre|escape:'html':'UTF-8'},%20tengo%20consulta%20relacionada%20al%20área%20de%20{$agente.categoria|escape:'html':'UTF-8'}" target="_blank">
                        {$agente.nombre|escape:'html':'UTF-8'} - {$agente.categoria|escape:'html':'UTF-8'}
                    </a>
                </li>
            {/foreach}
        </ul>
    </div>
</div>