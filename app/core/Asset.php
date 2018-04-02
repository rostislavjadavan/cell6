<?php

namespace Core;

class Asset {

    private $request = null;

    /**
     * Asset constructor.
     * @param Request|null $request
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Path to public directory
     *
     * @param $filename
     * @return string
     * @internal param $uri
     */
    public function assetUri($filename) {
        return PUBDIR."/".$filename;
    }

    /**
     * Url to public directory
     *
     * @param $uri
     * @return string
     */
    public function assetUrl($uri) {
        return $this->request->getBaseUrl().$this->assetUri($uri);
    }

    /**
     * @param $filename
     * @return bool
     */
    public function fileExists($filename) {
        return file_exists(PUBDIR."/".$filename);
    }

    /**
     * @param $filename
     * @return mixed
     */
    public function pathInfo($filename) {
        return pathinfo(PUBDIR."/".$filename);
    }
}