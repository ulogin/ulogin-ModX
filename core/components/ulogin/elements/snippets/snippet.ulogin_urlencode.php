<?php
if (empty($url)) {return '';}

return str_replace("%26amp%3B", "%26", urlencode($url));