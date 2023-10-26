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

class Table
{
    public $columns;
    public $records;

    public function __construct( $columns, $records )
    {
        $this->columns = $columns;
        $this->records = $records;
    }

    function echo_me()
    {
        $table = "
    <div class='table'>
        <table>";

        $header = "
            <tr>";

        foreach ( $this->columns as $column ) {
            if ( $column->hidden ) {
                continue;
            }

            $header .= "
                <th>$column->text</th>";
        }

        $header .= "
            </tr>";

        $table .= $header;

        foreach ( $this->records as $record ) {
            $row = "
            <tr>";

            foreach ( $this->columns as $column ) {
                if ( $column->hidden ) {
                    continue;
                }

                $row .= "
                <td>{$record[$column->displayField]}</td>";
            }

            $row .= "
            </tr>";

            $table .= $row;
        }

        $table .= "
        </table>
    </div>";

        echo $table;
    }
}
