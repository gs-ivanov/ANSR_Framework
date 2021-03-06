<?php

namespace ANSR\Core\Http\Response;

/**
 * @author Ivan Yonkov <ivanynkv@gmail.com>
 */
class RedirectResponse implements ResponseInterface
{
    private $location;

    public function __construct($location)
    {
        $this->location = $location;
    }

    public function send()
    {
        header("Location: " . $this->location);
    }
}