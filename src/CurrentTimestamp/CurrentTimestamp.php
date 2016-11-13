<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

namespace ActiveCollab\Utils\CurrentTimestamp;

/**
 * @package ActiveCollab\Utils\CurrentTimestamp
 */
class CurrentTimestamp implements CurrentTimestampInterface
{
    /**
     * {@inheritdoc}
     */
    public function getCurrentTimestamp()
    {
        return time();
    }
}
