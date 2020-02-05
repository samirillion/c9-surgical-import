<?php

namespace IfmImport;

use Sse\Event;

class EventHandler implements Event
{
    public function update()
    {
        //Here's the place to send data
        return 'Hello, world!';
    }

    public function check()
    {
        //Here's the place to check when the data needs update
        return true;
    }
}
