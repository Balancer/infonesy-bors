<?php

namespace B2\MyBB;

class DbIdStyle extends \Respect\Data\Styles\Standard
{
    public function identifier($name)
    {
        switch ($name)
        {
            case 'mybb_forums':
               return 'fid';
            case 'mybb_threads':
               return 'tid';
            case 'mybb_posts':
               return 'pid';
            case 'mybb_users':
               return 'uid';
            default:
				echo "find id field for $name\n";
                return $name;
        }
    }
}
