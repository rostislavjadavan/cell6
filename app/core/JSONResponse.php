<?php

/**
 * JSON Response
 *
 * @package Http
 * @author spool
 */

namespace Core;

class JSONResponse extends Response {

    /**
     * Send HTTP headers
     */
    public function sendHeaders() {
        header($this->protocol . ' ' . $this->code . ' ' . $this->text);
        header('Content-Type: application/json');
    }

    /**
     * Send output. Transform to JSON format.
     */
    public function sendContent() {
        $json = new JSON();
        echo $json->encode($this->content);
    }

    /**
     * Get output. Transform to JSON format.
     */
    public function getContent() {
        $json = new JSON();
        return $json->encode($this->content);
    }
}
