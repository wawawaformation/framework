<?php

namespace Core\View;

use Core\View\Trait\BlockTrait;
use Core\View\Trait\EscaperTrait;
use Core\View\Trait\MainTrait;
use Core\View\Trait\MetaTrait;
use Core\View\Trait\OgTrait;
use Core\View\Trait\ScriptTrait;
use Core\View\Trait\StyleTrait;
use Core\View\Trait\TitleTrait;

trait ViewTrait
{
    use TitleTrait;
    use MetaTrait;
    use StyleTrait;
    use ScriptTrait;
    use BlockTrait;
    use MainTrait;
    use OgTrait;
    use EscaperTrait;

}
