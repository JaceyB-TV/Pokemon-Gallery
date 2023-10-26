<?php

class Column
{
    public $dataIndex;
    public $displayField;
    public $text;
    public $hidden;

    public function __construct( $dataIndex, $text = null, $displayField = null, $hidden = false )
    {
        $this->dataIndex = $dataIndex;
        $this->displayField = isset( $displayField ) ? $displayField : $this->dataIndex;
        $this->text = isset( $text ) ? $text : $this->displayField;
        $this->hidden = $hidden;
    }
}
