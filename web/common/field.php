<?php

class Field
{
    const TEXT = 1;
    const NUMBER = 2;
    const LST = 3;

    public $type;
    public $dataIndex;
    public $label;
    public $lookup;
    public $hidden;
    public $required;

    public function __construct( $type, $dataIndex, $label = null, $lookup = null, $hidden = false, $required = false )
    {
        $this->type = $type;
        $this->dataIndex = $dataIndex;
        $this->label = isset( $label ) ? $label : $this->dataIndex;
        $this->lookup = $lookup;
        $this->hidden = $hidden;
        $this->required = $required;
    }

    public function echo_me( $record )
    {
        $value = isset( $record ) && array_key_exists( $this->dataIndex, $record ) ? $record[$this->dataIndex] : null;

        switch ( $this->type ) {
            case $this::TEXT:
            {
                if ( $this->hidden ) {
                    echo "
            <div class='field' style='display: none; '>
                <label for='$this->dataIndex'>$this->label</label>
                <input type='hidden' name='$this->dataIndex' id='$this->dataIndex' placeholder='$this->label' value='$value'/>
            </div>";
                }
                else {
                    echo "
            <div class='field'>
                <label for='$this->dataIndex'>$this->label</label>
                <input type='text' name='$this->dataIndex' id='$this->dataIndex' placeholder='$this->label' value='$value'/>
            </div>";
                }
                break;
            }
            case $this::NUMBER:
            {
                if ( $this->hidden ) {
                    echo "
            <div class='field' style='display: none; '>
                <label for='$this->dataIndex'>$this->label</label>
                <input type='hidden' name='$this->dataIndex' id='$this->dataIndex' placeholder='$this->label' value='$value'/>
            </div>";
                }
                else {
                    echo "
            <div class='field'>
                <label for='$this->dataIndex'>$this->label</label>
                <input type='number' name='$this->dataIndex' id='$this->dataIndex' placeholder='$this->label' value='$value'/>
            </div>";

                }
                break;
            }
            case $this::LST:
            {
                $options = $this->get_options( $this->lookup, $value );

                // TODO: Add first "empty" option config (--please select)--

                echo "
            <div class='field'>
                <label for='$this->dataIndex'>$this->label</label>
                <select name='$this->dataIndex' id='$this->dataIndex'>$options
                </select>
            </div>";
                break;
            }
        }
    }

    public function get_options( $table, $value )
    {
        $dao = $table . '_dao';

        include_once $_SERVER['DOCUMENT_ROOT'] . "/dao/$table.php";

        $records = $$dao->findAll();

        $options = '';

        foreach ( $records as $record ) {
            $selected = '';

            if ( $record['id'] === $value ) {
                $selected = 'selected';
            }

            $options .= "
                    <option value='{$record["name"]}' $selected>{$record["name"]}</option>";
        }

        return $options;
    }
}
