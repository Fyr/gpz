<?
	if ($errMsg) {
		echo 'Ошибка обновления курсов: '.$errMsg.'<br />Рекомендуется обновить курсы вручную';
	} else {
?>

На <?=date('d.m.Y')?> установлены следующие курсы ЦБ РФ:<br/>
<?
		foreach($setKurs as $curr => $rate) {
			echo strtoupper($curr).': '.$rate.'<br/>';
		}
	}
?>
