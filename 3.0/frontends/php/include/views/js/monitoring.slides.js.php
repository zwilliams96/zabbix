<script type="text/javascript">
	jQuery(function() {
		initPMaster(
			'slideshows',
			<?php
				echo CJs::encodeJson([
					WIDGET_SLIDESHOW => [
						'frequency' => $this->data['element']['delay'] * $this->data['refreshMultiplier'],
						'url' => 'slides.php?output=html&elementid='.$this->data['elementId'].
							(isset($this->data['groupid']) ? '&groupid='.$this->data['groupid'] : '').
							(isset($this->data['hostid']) ? '&hostid='.$this->data['hostid'] : ''),
						'counter' => 0,
						'darken' => 0,
						'params' => [
							'widgetRefresh' => WIDGET_SLIDESHOW,
							'lastupdate' => time()
						]
					]
				])
			?>
		);
	});
</script>
