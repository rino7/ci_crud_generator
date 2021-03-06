<style>
    .cortar{
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

</style>
<?php
$alias_tabla = substr($this->input->get("tabla"), 0, 3);
?>
<form action="/crud_generator/generar" method="POST" class="form form-horizontal" >
    <fieldset>
        <div class="form-group">
            <label>Nombre Entidad (controlador)</label>
            <input class="form-control" type="text" name="entidad" value="<?php echo $this->input->get("tabla"); ?>" /><br/>
        </div>
        <div class="form-group">
            <div class="col-md-4">
                <label>Tabla</label>
                <input class="form-control" type="text" name="tabla" value="<?php echo $this->input->get("tabla"); ?>" readonly />
            </div>
            <div class="col-md-4">
                <label>Alias</label>
                <input class="form-control" type="text" name="alias_tabla" value="<?php echo $alias_tabla; ?>" />
            </div>

        </div>
        <div class="form-group">
            <h3>Sobrescribir archivos generados</h3>
            <div class="row">
                <div class="col-md-3">
                    <label>Listado:</label>
                    <br>
                    <select name="pisar_view_listado_anterior">
                        <?php echo option("N", "NO", array_item($saved, "pisar_view_listado_anterior", "S")); ?>
                        <?php echo option("S", "SI", array_item($saved, "pisar_view_listado_anterior", "S")); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Formulario:</label>
                    <br>
                    <select name="pisar_view_form_anterior">
                        <?php echo option("N", "NO", array_item($saved, "pisar_view_form_anterior", "S")); ?>
                        <?php echo option("S", "SI", array_item($saved, "pisar_view_form_anterior", "S")); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Controlador:</label>
                    <br>
                    <select name="pisar_controller_anterior">
                        <?php echo option("N", "NO", array_item($saved, "pisar_controller_anterior", "S")); ?>
                        <?php echo option("S", "SI", array_item($saved, "pisar_controller_anterior", "S")); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Modelo:</label>
                    <br>
                    <select name="pisar_model_anterior">
                        <?php echo option("N", "NO", array_item($saved, "pisar_model_anterior", "S")); ?>
                        <?php echo option("S", "SI", array_item($saved, "pisar_model_anterior", "S")); ?>
                    </select>
                </div>
            </div>


        </div>
        <hr>
        <h3>Formulario Nuevo / Editar</h3>
        <br>
        <br>
        <div class="form-group">
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <th>
                        <input type="checkbox" data-selector=".js-chk-generar-campo" class="js-select-all" />
                        <span class="js-tooltip" data-placement="center" title="&iquest;Generar?"><?php echo glyphicon("wrench"); ?></span>
                    </th>
                    <th>Columna</th>
                    <th>Label</th>
                    <th>Tipo</th>
                    <th>Configuraciones</th>
                    <th>PK</th>
                </tr>
                <?php
                foreach ($columnas as $oColumna):
                    $columna = (array) $oColumna;
                    $es_pk = (int) $columna["primary_key"] === 1;
                    $tipo_input_default = $es_pk ? "hidden" : array_item($relacion_input_tipo_columna, $columna["type"], "");
                    $destacar_tr = $es_pk ? " info " : "";
                    $generar_checked = (int) array_item($saved["campos"][$columna["name"]], "generar_input", 0) === 1 ? " checked " : "";
                    $saved_label = array_item($saved["campos"][$columna["name"]], "label", $columna["name"]);
                    ?>
                    <tr class="js-contenedor-columna <?php echo $destacar_tr; ?>" >
                        <td>
                            <input type="checkbox" class="js-chk-generar-campo" name='campos[<?php echo $columna["name"]; ?>][generar_input]' value='1' <?php echo $generar_checked; ?> <?php echo $es_pk ? "checked readonly" : ""; ?>  />
                        </td>
                        <td class="js-nombre-columna" ><?php echo $columna["name"]; ?></td>
                        <td><input type='text' name='campos[<?php echo $columna["name"]; ?>][label]' value='<?php echo $saved_label; ?>' /></td>
                        <td>
                            <select class="js-select-tipo-campo" name='campos[<?php echo $columna["name"]; ?>][tipo_campo]'>
                                <?php echo option("", "Seleccione...", $tipo_input_default); ?>
                                <?php foreach ($inputs_disponibles as $value => $text): ?>
                                    <?php echo option($value, $text, $tipo_input_default); ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <code class="cortar" id="config_mostrar_<?php echo $columna["name"]; ?>"></code>
                            <a class="btn btn-xs btn-default js-edit-config" style="display:none;" href="javascript:void(0);"><?php echo glyphicon("edit"); ?></a>
                            <a class="btn btn-xs btn-danger js-delete-config" style="display:none;" href="javascript:void(0);"><?php echo glyphicon("trash"); ?></a>
                            <textarea style="display:none;"name="campos[<?php echo $columna["name"]; ?>][config]" id="config_<?php echo $columna["name"]; ?>" ></textarea>
                        </td>
                        <td><input type="radio" value="<?php echo $columna["name"]; ?>" name="pk" <?php echo $es_pk ? "checked" : ""; ?> /></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <hr>
        <h3>Listado</h3>
        <div class="form-group">
            <label>Ordenar por default en sentido:</label>
            <select  name='en_sentido'>
                <option value='desc' selected>DESC</option>
                <option value='asc'>ASC</option>
            </select>
        </div>
        <div class="form-group">
            <label>Custom Where:</label><br>
            <textarea class="form-control" name="listado_custom_where" rows="4" cols="50" placeholder="activo = 1 AND eliminado = 0 AND alias.id > 1" ></textarea>
        </div>
        <div class="form-group">
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <th>
                        <input type="checkbox" data-selector=".js-chk-mostrar-campo" class="js-select-all" />
                        <span class="js-tooltip" data-placement="center" title="&iquest;Mostrar?"><?php echo glyphicon("eye-open"); ?></span>
                    </th>
                    <th>Columna</th>
                    <th>Label</th>
                    <th>Tabla Join</th>
                    <th>ON</th>
                    <th>Tipo Join</th>
                    <th>
                        <input type="checkbox" data-selector=".js-chk-ordenar-campo" class="js-select-all" />
                        <span class="js-tooltip" data-placement="center" title="&iquest;Puede Ordenar?"><?php echo glyphicon("sort-by-attributes"); ?></span>
                    </th>
                    <th>
                        <span class="js-tooltip" data-placement="center" title="Ordenado Por Default">
                            <?php echo glyphicon("star"); ?>
                            <?php echo glyphicon("sort-by-attributes"); ?>
                        </span>
                    </th>
                </tr>
                <?php
                foreach ($columnas as $oColumna):
                    $columna = (array) $oColumna;
                    $es_pk = (int) $columna["primary_key"] === 1;
                    $destacar_tr = $es_pk ? "class='info'" : "";
                    ?>
                    <tr <?php echo $destacar_tr; ?> id="tr-listado-<?php echo $columna["name"]; ?>">
                        <td><input type="checkbox" class="js-chk-mostrar-campo" name='campos[<?php echo $columna["name"]; ?>][mostrar_listado]' value='1' /></td>
                        <td id="td-columna-<?php echo $columna["name"]; ?>"><input type='text' name='campos[<?php echo $columna["name"]; ?>][columna]' value='<?php echo "{$alias_tabla}.{$columna["name"]}"; ?>' /></td>
                        <td><input type='text' name='campos[<?php echo $columna["name"]; ?>][label]' value='<?php echo $columna["name"]; ?>' /></td>
                        <td id="td-join-<?php echo $columna["name"]; ?>">
                            <select class="form-control js-slt-tabla-join" name='campos[<?php echo $columna["name"]; ?>][join][tabla]' disabled>
                                <?php echo option("", "N/A", ""); ?>
                                <?php foreach ($tablas as $tabla): ?>
                                    <?php echo option($tabla, $tabla, ""); ?>
                                <?php endforeach; ?>
                            </select>
                            <label>Alias</label>
                            <input class="form-control js-ipt-alias-tabla-join" type="text" name='campos[<?php echo $columna["name"]; ?>][join][alias_tabla]' value="" placeholder="AS alias" disabled />
                            <a href="javascript:void(0);" class="js-toggle-habilitar-join" data-columna="<?php echo $columna["name"]; ?>"><?php echo glyphicon("edit"); ?></a>
                        </td>

                        <td><input class="form-control" type="text" name='campos[<?php echo $columna["name"]; ?>][join][on]' value="" placeholder="alias.columna = <?php echo "{$alias_tabla}.{$columna["name"]}"; ?>" /><br/></td>
                        <td>
                            <select class="form-control" name='campos[<?php echo $columna["name"]; ?>][join][tipo_join]'>
                                <?php echo option("LEFT", "LEFT", "LEFT"); ?>
                                <?php echo option("RIGHT", "RIGHT", "LEFT"); ?>
                                <?php echo option("INNER", "INNER", "LEFT"); ?>

                            </select>
                        </td>
                        <td><input type="checkbox" class="js-chk-ordenar-campo"  name='campos[<?php echo $columna["name"]; ?>][puede_ordenar]' value='1' /></td>
                        <td><input type="radio" value="<?php echo $columna["name"]; ?>" name="ordenar_por" <?php echo $es_pk ? "checked" : ""; ?> /></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="form-group">
            <input type="submit" name="generar" class="btn btn-primary" value="Generar" >
        </div>
    </fieldset>
</form>
<?php $this->load->view("/crud_generator/templates/config_select_fk", array("tablas" => $tablas)); ?>
<?php $this->load->view("/crud_generator/templates/config_select"); ?>