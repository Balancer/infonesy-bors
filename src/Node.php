<?php

namespace Infonesy;

class Node extends \B2\Obj
{
	function infonesy_uuid()
	{
		return 'unknown.'.gethostname();
	}
}
