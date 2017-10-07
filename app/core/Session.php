<?php

namespace Core;

/**
 * Class Session
 * @package Core
 */
class Session extends ArrayList {

    /**
     * @var bool
     */
    protected $sessionStarted = false;

    /**
     * Session constructor.
     * @throws RuntimeException
     */
    public function __construct() {
        if (session_status() !== \PHP_SESSION_ACTIVE) {
            if (!session_start()) {
                throw new RuntimeException("Session: Failed to start session.");
            }
            parent::__construct($_SESSION);
            $this->sessionStarted = true;
        }
    }

    /**
     * Destroy session
     */
    public function __destruct() {
        if ($this->sessionStarted) {
            $this->save();
        }
    }

    /**
     * Write session data
     */
    public function save() {
        $_SESSION = $this->data;
        session_write_close();
        $this->data = null;
    }

}
