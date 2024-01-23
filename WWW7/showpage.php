<?php

function PokazPodstrone($id)
{
    $id_clear = $id;

    $query="SELECT * FROM page_list WHERE page_title='$id_clear' LIMIT 1";
    $link = $GLOBALS['link'];
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);

    if (empty($row['id'])){
        $web = '[nie_znaleziono_strony]';
    }
    else {
        $web = html_entity_decode($row['page_content'], ENT_HTML5 | ENT_QUOTES | ENT_SUBSTITUTE);
    }

    return $web;
}
?>