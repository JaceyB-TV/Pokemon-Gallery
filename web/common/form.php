<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/common/field.php';

class Form
{
    public $fields;
    public $record;

    public function __construct( $fields, $record = null )
    {
        $this->fields = $fields;
        $this->record = $record;
    }


    public function echo_me()
    {
        echo "
    <div class='upload'>
        <form action='' method='post'>";

        foreach ( $this->fields as $field ) {
            $field->echo_me( $this->record );
        }

        echo "
            <div class='submit'>
                <button type='submit' name='submit'>SAVE</button>
            </div>
        </form>
    </div>";
    }
}
