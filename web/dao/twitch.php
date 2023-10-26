<?php

require_once 'dao.php';

class TwitchDAO extends DAO
{
    protected $db_name = "twitch";

    function set( $key, $value )
    {
        if ( $this->exists( $key ) ) {
            return $this->update( $key, $value );
        }

        return $this->create( $key, $value );
    }

    function get( $key )
    {
        if ( !$this->exists( $key ) ) {
            return null;
        }

        $query = "SELECT * FROM twitch WHERE param_key = '$key'";

        return $this->execute( $query )[0]['param_value'];
    }

    function remove( $key )
    {
        include '../secret/secret.php';

        $query = "DELETE FROM twitch WHERE param_key = ?";

        $insertStatement = $connection->prepare( $query );
        $insertStatement->bind_param( "s", $key );
        $insertStatement->execute();

        return $insertStatement->error;
    }

    public function exists( $key )
    {
        $query = "SELECT * FROM twitch WHERE param_key = '$key'";

        return sizeof( $this->execute( $query ) ) !== 0;
    }

    public function create( $key, $value ): string
    {
        include '../secret/secret.php';

        $query = "INSERT INTO twitch ( param_key, param_value ) VALUES (?, ?)";

        $insertStatement = $connection->prepare( $query );
        $insertStatement->bind_param( "ss", $key, $value );
        $insertStatement->execute();

        return $insertStatement->error;
    }

    public function update( $key, $value ): string
    {
        include '../secret/secret.php';

        $query = "UPDATE twitch SET param_value = ? WHERE param_key = ?";

        $updateStatement = $connection->prepare( $query );
        $updateStatement->bind_param( "ss", $value, $key );
        $updateStatement->execute();

        return $updateStatement->error;
    }

}

$twitch_dao = new TwitchDAO();
