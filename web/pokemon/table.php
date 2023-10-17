<?php
$offset = isset( $_GET['offset'] ) ? $_GET['offset'] : 0;
$limit = isset( $_GET['limit'] ) ? $_GET['limit'] : 25;
$weird_form = isset( $_GET['form'] ) && $_GET['form'] === "true";
$missing = isset( $_GET['missing'] ) && $_GET['missing'] === "true";

$first = 0;

$prev = $offset - $limit;
if ( $prev < 0 ) {
    $prev = 0;
}

$next = $offset + $limit;
if ( $next > $pokemon_count ) {
    $next = floor( $pokemon_count / $limit ) * $limit;
}

$last = floor( $pokemon_count / $limit ) * $limit;

?>

<table>
    <tr>
        <th>#</th>
        <th>Pokémon</th>
        <th>Gender</th>
        <th>Suffix</th>
        <th>Form</th>
        <th>Suffix</th>
        <th colspan="2">Type</th>
        <th colspan="2">Actions</th>
    </tr><?php

    echo "
    <tr>
        <td></td>
    </tr>";
    ?>

    <tr>
        <th colspan="20">
            <a href="javascript: void(0);" onclick="setSearchParam('offset', '<?php echo $first; ?>')"><< First</a>
            <a href="javascript: void(0);" onclick="setSearchParam('offset', '<?php echo $prev; ?>')">< Previous</a>
            <a href="javascript: void(0);" onclick="setSearchParam('offset', '<?php echo $next; ?>')">Next ></a>
            <a href="javascript: void(0);" onclick="setSearchParam('offset', '<?php echo $last; ?>')">Last >></a>
        </th>
    </tr>
</table>