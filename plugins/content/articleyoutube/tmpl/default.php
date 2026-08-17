<?php
/**
 * @version		4.7.0
 * @package		AllVideos (plugin)
 * @author    	JoomlaWorks - http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2015 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


?>
<script src="https://www.youtube.com/iframe_api"></script>
<div class="youtube-player" data-id="<?php	echo $videoID; ?>">
    <div data-id="<?php echo $videoID; ?>">
        <img src="https://i.ytimg.com/vi/<?php  echo $videoID; ?>/hqdefault.jpg" alt="Click to Play Video" class="youtube-thumb"/>    
        <div class="play"></div>
    </div>
</div>
