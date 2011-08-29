<?php


/* Load javascript */
$this->regClientStartupScript('libs/jquery.galleriffic.js');
$this->regClientStartupScript('libs/jquery.history.js');
$this->regClientStartupScript('libs/jquery.opacityrollover.js');

/* Launch script before body closing tag */
$script = $this->getChunk('script');
$modx->regClientHTMLBlock('<script type="text/javascript">'.$script.'</script>');