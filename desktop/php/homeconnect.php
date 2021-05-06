<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('homeconnect');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i>	{{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoSecondary" id="bt_syncHomeConnect" >
				<i class="fas fa-sync-alt"></i>
				<br>
				<span>{{Synchronisation}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf" >
					<i class="fas fa-wrench"></i>
					<br>
					<span>{{Configuration}}</span>
			</div>
			<div class="cursor logoSecondary" id="bt_healthHomeConnect">
				<i class="fas fa-medkit"></i>
				<br />
				<span>{{Santé}}</span>
			</div>
		</div>

		<legend><i class="fas fa-table"></i> {{Mes appareils}}</legend>

		<div class="eqLogicThumbnailContainer">

			<?php
				foreach ($eqLogics as $eqLogic) {
					$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
					echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
					echo '<img src="' . $eqLogic->getImage() . '"/>';
					echo '<br>';
					echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
					echo '</div>';
				}
			?>

		</div>
	</div>

	<div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
        <div class="input-group pull-right" style="display:inline-flex">
            <a class="btn btn-success eqLogicAction roundedLeft" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
            <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
            <a class="btn btn-default eqLogicAction roundedRight" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}</a>
        </div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br/>

				<div class="col-xs-6">
					<form class="form-horizontal">
						<fieldset>

							<div class="form-group">
								<label class="col-sm-3 control-label">{{Nom de l'appareil}}</label>
								<div class="col-sm-5">
									<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
									<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'appareil}}"/>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label" >{{Objet parent}}</label>
								<div class="col-sm-5">
									<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
										<option value="">{{Aucun}}</option>
										<?php
										foreach ((jeeObject::buildTree(null, false)) as $object) {
											echo '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
										}
										?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">{{Catégorie}}</label>
								<div class="col-sm-8">
									<?php
										foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
											echo '<label class="checkbox-inline">';
											echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
											echo '</label>';
										}
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"></label>
								<div class="col-sm-9">
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
								</div>
							</div>

							<br />
							<div class="form-group">
								<label class="col-lg-3 control-label">{{Type : }}</label>
								<div class="col-sm-6">
									<span class="eqLogicAttr label label-info" style="font-size:1em;cursor: default;" data-l1key="configuration" data-l2key="type"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label">{{Marque : }}</label>
								<div class="col-sm-6">
									<span class="eqLogicAttr label label-info" style="font-size:1em;cursor: default;" data-l1key="configuration" data-l2key="brand"></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-3 control-label">{{Modèle : }}</label>
								<div class="col-sm-6">
									<span class="eqLogicAttr label label-info" style="font-size:1em;cursor: default;" data-l1key="configuration" data-l2key="vib"></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-3 control-label">{{Identifiant}}</label>
								<div class="col-sm-6">
									<span class="eqLogicAttr label label-info" style="font-size:1em;cursor: default;" data-l1key="configuration" data-l2key="haid"></span>
								</div>
							</div>

						</fieldset>
					</form>
				</div>
				<div class="col-sm-6">
					<form class="form-horizontal">
						<fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-8">
                                    <img src="core/img/no_image.gif" data-original=".png" id="img_device" class="img-responsive" style="max-height : 250px;" onerror="this.src='plugins/homeconnect/plugin_info/homeconnect_icon.png'"/>
                                </div>
                            </div>
					</fieldset>
				    </form>
				</div>

			</div>

			<div role="tabpanel" class="tab-pane" id="commandtab">
				<a class="btn btn-success btn-sm cmdAction pull-right" data-action="add" style="margin-top:5px;"><i class="fas fa-plus-circle"></i> {{Commandes}}</a><br/><br/>

				<table id="table_cmd" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>{{Nom}}</th><th>{{Options}}</th><th>{{Type}}</th><th>{{Paramètres}}</th><th>{{Action}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>

			</div>
		</div>
	</div>
</div>

<?php include_file('desktop', 'homeconnect', 'js', 'homeconnect');?>
<?php include_file('core', 'plugin.template', 'js');?>
