<?php

require_once 'dao.php';

class TwitchClipsDAO extends DAO
{
    function selectAll(): array
    {
        $query = "SELECT * FROM twitch_clips ORDER BY created_at DESC";

        return $this->execute( $query );
    }

    function save( $slug, $created_at, $is_featured, $embed_url )
    {
        if ( $this->exists( $slug ) ) {
            return $this->update( $slug, $created_at, $is_featured, $embed_url );
        }

        return $this->create( $slug, $created_at, $is_featured, $embed_url );
    }

    private function exists( $slug ): bool
    {
        $query = "SELECT * FROM twitch_clips WHERE slug = '$slug'";

        return sizeof( $this->execute( $query ) ) !== 0;
    }

    private function create( $slug, $created_at, $is_featured, $embed_url )
    {
        include '../secret/secret.php';

        $query = "INSERT INTO twitch_clips ( `slug`, `created_at`, `is_featured`, `embed_url` ) VALUES ( ?, ?, ?, ? )";

        $insertStatement = $connection->prepare( $query );
        $insertStatement->bind_param( "ssis", $slug, $created_at, $is_featured, $embed_url );
        $insertStatement->execute();

        return $insertStatement->error;
    }

    private function update( $slug, $created_at, $is_featured, $embed_url )
    {
        include '../secret/secret.php';

        $query = "UPDATE twitch_clips SET `created_at` = ?, `is_featured` = ?, `embed_url` = ? WHERE slug = ?";

        $updateStatement = $connection->prepare( $query );
        $updateStatement->bind_param( "siss", $created_at, $is_featured, $embed_url, $slug );
        $updateStatement->execute();

        return $updateStatement->error;

    }
}

$twitch_clips_dao = new TwitchClipsDAO();
