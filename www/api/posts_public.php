<?php
// Implements the del.icio.us API request for all a user's posts, optionally filtered by tag.

// del.icio.us behavior:
// - doesn't include the filtered tag as an attribute on the root element (we do)

// Scuttle behavior:
// - returns privacy status of each bookmark.

// Force HTTP authentication first!
//require_once('httpauth.inc.php');
$httpContentType = 'text/xml';
require_once '../www-header.php';

/* Service creation: only useful services are created */
$bookmarkservice =SemanticScuttle_Service_Factory::get('Bookmark');


// Check to see if a tag was specified.
if (isset($_REQUEST['tag']) && (trim($_REQUEST['tag']) != ''))
    $tag = trim($_REQUEST['tag']);
else
    $tag = NULL;

// Get the posts relevant to the passed-in variables.
$bookmarks = $bookmarkservice->getBookmarks(0, NULL, NULL, $tag);

// Set up the XML file and output all the posts.
echo '<?xml version="1.0" standalone="yes" ?'.">\r\n";
echo '<posts update="'. gmdate('Y-m-d\TH:i:s\Z') .'" '. (is_null($tag) ? '' : ' tag="'. htmlspecialchars($tag) .'"') .">\r\n";

foreach($bookmarks['bookmarks'] as $row) {
    if (is_null($row['bDescription']) || (trim($row['bDescription']) == ''))
        $description = '';
    else
        $description = 'extended="'. filter($row['bDescription'], 'xml') .'" ';

    $taglist = '';
    if (count($row['tags']) > 0) {
        foreach($row['tags'] as $tag)
            $taglist .= convertTag($tag) .' ';
        $taglist = substr($taglist, 0, -1);
    } else {
        $taglist = 'system:unfiled';
    }

    echo "\t<post href=\"". filter($row['bAddress'], 'xml') .'" description="'. filter($row['bTitle'], 'xml') .'" '. $description .'hash="'. md5($row['bAddress']) .'" tag="'. filter($taglist, 'xml') .'" time="'. gmdate('Y-m-d\TH:i:s\Z', strtotime($row['bDatetime'])) . '" status="'. filter($row['bStatus'], 'xml') ."\" />\r\n";
}

echo '</posts>';
?>
