<?php
/***************************************************************************
 Copyright (C) 2005 - 2006 Scuttle project
 http://sourceforge.net/projects/scuttle/
 http://scuttle.org/

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ***************************************************************************/

header("Last-Modified: ". gmdate("D, d M Y H:i:s") ." GMT");
header("Cache-Control: no-cache, must-revalidate");

$httpContentType = 'text/xml';
require_once 'www-header.php';

$us = SemanticScuttle_Service_Factory::get('User');

/* Managing all possible inputs */
isset($_GET['url']) ? define('GET_URL', $_GET['url']): define('GET_URL', '');

/**
 * Generates a new private key and confirms it isn't being used
 *
 * @return string the new key value
 */
function getNewPrivateKey()
{
    global $us;

    // Generate a 32 char lowercase+numeric unique value
    $newKey = md5(uniqid('SemanticScuttle',True));
    // Check uniqueness in user table
    while ($us->PrivateKeyExists($newKey)) {
        $newKey = md5(uniqid('SemanticScuttle',True));
    }
    return $newKey;
}

echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<response>
<method>
getNewPrivateKey
</method>
<result>
<?php echo getNewPrivateKey(); ?>
</result>
</response>
