<?php

namespace Core;

/**
 * Class JSON
 * @package Core
 */
class JSON {

    /**
     * Encode data to JSON format
     *
     * @param string Data
     * @return string JSON encoded data
     * @throws \Exception
     */
    public function encode($data) {
        $result = json_encode($data);

        if ($result == FALSE) {
            throw new \Exception('JSON Encode Error:' . $this->getErrorMessage());
        }

        return $result;
    }

    /**
     * Decode JSON formated data
     *
     * @param string JSON encoded data
     * @param bool TRUE if convert to associative array
     * @return mixed Output data
     * @throws \Exception
     */
    public function decode($data, $objectsToAssocArray = FALSE) {
        $result = json_decode($data, $objectsToAssocArray);

        if ($result == FALSE || $result == NULL) throw new \Exception('JSON Decode Error:' . $this->getErrorMessage());

        return $result;
    }

    /**
     * Return last JSON error message
     *
     * @return string Error message
     */
    private function getErrorMessage() {
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = 'No error has occurred';
                break;
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error';
                break;
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                $error = 'Unknown error';
        }
    }

}
