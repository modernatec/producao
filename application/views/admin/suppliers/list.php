<div class="topo" >
    <div class="tabs_panel">
        <ul class="tabs">
            <li><a class="aba ajax" id="supplier_1" href='<?=URL::base();?>admin/suppliers/getSuppliers'>fornecedores</a></li>
            <?
            if (strpos($current_auth,'assistente') === false) {?>
            <li><a class="aba ajax" id="supplier_2" href='<?=URL::base();?>admin/contatos/getContatos'>contatos</a></li>
            <li><a class="aba ajax" id="supplier_3" href='<?=URL::base();?>admin/services/getListServices'>serviços</a></li>
            <?}?>
        </ul>  
    </div>
    <div class="clear" id="filtros"></div>
</div>
<div id="esquerda">
    <div id="tabs_content">
        
    </div>
</div>
<div id="direita"></div>