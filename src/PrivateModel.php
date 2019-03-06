<?php
namespace Dev\Mugglequent;

use Illuminate\Database\Eloquent\Model;
use Dev\Mugglequent\Behaviors\DisablesMagicCallers;
use Dev\Mugglequent\Behaviors\DisablesMagicGetters;
use Dev\Mugglequent\Behaviors\DisablesMagicSetters;

class PrivateModel extends Model
{
    use DisablesMagicCallers;
    use DisablesMagicGetters;
    use DisablesMagicSetters;

}
