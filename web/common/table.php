<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/common/column.php';

class Table
{
    public $columns;
    public $records;
    public $loggedIn;

    public function __construct( $columns, $records, $loggedIn = false )
    {
        $this->columns = $columns;
        $this->records = $records;
        $this->loggedIn = $loggedIn;
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

        if ( $this->loggedIn ) {
            $header .= "
                <th colspan='2'>Actions</th>";
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

            if ( $this->loggedIn ) {
                $row .= "
                <td style='width: 42px; '>
                    <form action='?id={$record['id']}' method='post'>
                        <button type='submit' name='submit'>&#xe065;</button>
                    </form>
                </td>
                <td style='width: 42px; '>
                    <form action='?delete=true&id={$record['id']}' method='post'>
                        <button type='submit' name='submit'>&times;</button>
                    </form>
                </td>";
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
