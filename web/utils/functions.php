<?php

function log_msg( $msg )
{
    echo "<p>$msg</p>";
}

/**
 * getRandomWeightedElement()
 * Utility function for getting random values with weighting.
 * Pass in an associative array, such as array('A'=>5, 'B'=>45, 'C'=>50)
 * An array like this means that "A" has a 5% chance of being selected, "B" 45%, and "C" 50%.
 * The return value is the array key, A, B, or C in this case.  Note that the values assigned
 * do not have to be percentages.  The values are simply relative to each other.  If one value
 * weight was 2, and the other weight of 1, the value with the weight of 2 has about a 66%
 * chance of being selected.  Also note that weights should be integers.
 *
 * @param array $weightedValues
 */
function getRandomWeightedElement( array $weightedValues, $column_key = 'weight' )
{
    $rand = mt_rand( 1, (int)array_sum( array_column( $weightedValues, $column_key ) ) );

    foreach ( $weightedValues as $key => $value ) {
        $rand -= $value[$column_key];
        if ( $rand <= 0 ) {
            return $key;
        }
    }

    return $weightedValues[0];
}
