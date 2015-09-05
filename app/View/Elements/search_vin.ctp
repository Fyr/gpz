	<div class="searchShadow">
		<form class="searchBlock" action="<?=$this->Html->url(array('controller' => 'Autoxp', 'action' => 'search'))?>" method="get">
			<button type="submit" class="submit">поиск по VIN-коду</button>
			<div class="outerSearch" style="margin-right: 192px">
				<span class="icon search"></span>
				<input type="hidden" name="ses" value="<?=$searchID?>" />
				<input type="text" name="vin" value="<?=$this->request->query('vin')?>"  placeholder="Введите VIN-код, например WAUZZZ8DZTA198423" />
			</div>
		</form>
	</div>