<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/common/column.php';

class Table
{
    public $columns;
    public $records;
    public $count;
    public $loggedIn;

    public function __construct( $columns, $records, $count, $loggedIn = false )
    {
        $this->columns = $columns;
        $this->records = $records;
        $this->count = $count;
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
                        <button type='submit' name='submit'><i class='fa fa-pencil'></i></button>
                    </form>
                </td>
                <td style='width: 42px; '>
                    <form action='?delete=true&id={$record['id']}' method='post'>
                        <button type='submit' name='submit'><i class='fa fa-trash-o'></i></button>
                    </form>
                </td>";
            }

            $row .= "
            </tr>";

            $table .= $row;
        }

        $column_count = count( $this->columns );
        if ( $this->loggedIn ) {
            $column_count += 2;
        }

        $offset = isset( $_GET['offset'] ) ? $_GET['offset'] : 0;
        $limit = isset( $_GET['limit'] ) ? $_GET['limit'] : 10;

        $first = 0;
        $prev = $offset - $limit;
        if ( $prev < 0 ) {
            $prev = 0;
        }
        $next = $offset + $limit;
        if ( $next >= $this->count ) {
            $next = floor( ($this->count - 1) / $limit ) * $limit;
        }
        $last = floor( ($this->count - 1) / $limit ) * $limit;

        $paging = "
            <tr>
                <th colspan='$column_count'>
                    <a href=\"javascript: void(0);\" onclick=\"setSearchParam('offset', '$first')\"><< First</a>
                    <a href=\"javascript: void(0);\" onclick=\"setSearchParam('offset', '$prev')\">< Previous</a>
                    <a href=\"javascript: void(0);\" onclick=\"setSearchParam('offset', '$next')\">Next ></a>
                    <a href=\"javascript: void(0);\" onclick=\"setSearchParam('offset', '$last')\">Last >></a>
                </th>
            </tr>";

        $table .= $paging;

        $table .= "
        </table>
    </div>";

        echo $table;
    }
}
