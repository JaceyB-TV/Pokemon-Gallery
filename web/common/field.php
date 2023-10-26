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
            case $this::LST:
            {
                echo "
            <div class='field'>
                <label for='$this->dataIndex'>$this->label</label>
                <select name='$this->dataIndex' id='$this->dataIndex' placeholder='$this->label' value='$value'/>
            </div>";
                break;
            }
        }
    }
}
