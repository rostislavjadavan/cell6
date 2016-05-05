<div class="grid" data-uid="<?php echo $uid ?>" data-url="<?php echo $backendUrl ?>" data-columns="<?php echo htmlentities($columnNames) ?>">
<div class="panel panel-default">
	<div class="panel-body">
		<div id="custom-search-input">
                <div class="input-group col-md-12">
                    <input type="text" class="form-control input-lg grid-search-input" placeholder="Hledat..." />
                    <span class="input-group-btn">
                        <button class="btn btn-info btn-lg grid-search-button" type="button">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
						<button class="btn btn-info btn-lg grid-remove-button" type="button">
                            <i class="glyphicon glyphicon-remove"></i>
                        </button>
                    </span>
                </div>
            </div>
		<div class="grid-content">
			<div class="loading-large center-block"></div>
		</div>
	</div>
</div>
</div>

