<?php

include_once '../secret/secret.php';
include_once '../dao/form.php';
include_once '../dao/form_suffix.php';
include_once '../dao/gender.php';
include_once '../dao/pokemon.php';
include_once '../dao/type.php';

if ( isset( $found_pokemon ) ) {
    $found_id = $found_pokemon['id'];
    $found_number = $found_pokemon['number'];
    $found_name = $found_pokemon['name'];
    $found_gender_id = $found_pokemon['gender_id'];
    $found_form_id = $found_pokemon['form_id'];
    $found_form_suffix_id = $found_pokemon['form_suffix_id'];
    $found_type1_id = $found_pokemon['type1'];
    $found_type2_id = $found_pokemon['type2'];
}
else {
    $found_id = '';
    $found_number = '';
    $found_name = '';
}

$forms = $form_dao->findAll( 0, $form_dao->countAll() );
$genders = $gender_dao->findAll( 0, $gender_dao->countAll() );
$types = $type_dao->findAll( 0, $type_dao->countAll() );
$form_suffixes = $form_suffix_dao->findAll( 0, $form_suffix_dao->countAll() );

$form_options = "";
$form_suffix_options = "";
$gender_options = "";
$type1_options = "";
$type2_options = "";

foreach ( $forms as $form ) {
    $value = $form['id'];
    $caption = $form['name'];

    if ( isset( $found_form_id ) && $value == $found_form_id ) {
        $form_options .= "
                <option value='$value' selected>$caption</option>";
    }
    else {
        $form_options .= "
                <option value='$value'>$caption</option>";
    }
}

foreach ( $form_suffixes as $form_suffix ) {
    $value = $form_suffix['id'];
    $caption = $form_suffix['name'];

    if ( isset( $found_form_suffix_id ) && $value == $found_form_suffix_id ) {
        $form_suffix_options .= "
                <option value='$value' selected>$caption</option>";
    }
    else {
        $form_suffix_options .= "
                <option value='$value'>$caption</option>";
    }
}

foreach ( $genders as $gender ) {
    $value = $gender['id'];
    $caption = $gender['name'];

    if ( isset( $found_gender_id ) && $value == $found_gender_id ) {
        $gender_options .= "
                <option value='$value' selected>$caption</option>";
    }
    else {
        $gender_options .= "
                <option value='$value'>$caption</option>";
    }
}

foreach ( $types as $type ) {
    $value = $type['id'];
    $caption = $type['name'];

    if ( isset( $found_type1_id ) && $value == $found_type1_id ) {
        $type1_options .= "
                <option value='$value' selected>$caption</option>";
    }
    else {
        $type1_options .= "
                <option value='$value'>$caption</option>";
    }
}

foreach ( $types as $type ) {
    $value = $type['id'];
    $caption = $type['name'];

    if ( isset( $found_type2_id ) && $value == $found_type2_id ) {
        $type2_options .= "
                <option value='$value' selected>$caption</option>";
    }
    else {
        $type2_options .= "
                <option value='$value'>$caption</option>";
    }
}

?>

<div class='upload'>
    <form action='' method='post'>
        <div class='field'>
            <label for='id'>ID</label>
            <input type='number' name='id' id='id' placeholder='ID' value="<?php echo $found_id; ?>"/>
        </div>
        <div class='field'>
            <label for='number'>#</label>
            <input type='number' name='number' id='number' placeholder='#' value="<?php echo $found_number; ?>"/>
        </div>
        <div class='field'>
            <label for='name'>Name</label>
            <input type='text' name='name' id='name' placeholder='Name' value="<?php echo $found_name; ?>"/>
        </div>
        <div class='field'>
            <label for='gender_id'>Gender</label>
            <select name='gender_id' id='gender_id'><?php
                echo $gender_options; ?>

            </select>
        </div>
        <div class='field'>
            <label for='form_id'>Form</label>
            <select name='form_id' id='form_id'><?php
                echo $form_options; ?>

            </select>
        </div>
        <div class='field'>
            <label for='form_suffix_id'>Form Suffix</label>
            <select name='form_suffix_id' id='form_suffix_id'>
                <option value>-- Please Select --</option><?php
                echo $form_suffix_options; ?>

            </select>
        </div>
        <div class='field'>
            <label for='type1'>Type 1</label>
            <select name='type1' id='type1'><?php
                echo $type1_options; ?>

            </select>
        </div>
        <div class='field'>
            <label for='type2'>Type 2</label>
            <select name='type2' id='type2'>
                <option value>-- Please Select --</option><?php
                echo $type2_options; ?>

            </select>
        </div>
        <div class='submit'>
            <button type='submit' name='submit'>SAVE</button>
        </div>
    </form>
</div>