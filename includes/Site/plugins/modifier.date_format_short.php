<?php

function smarty_modifier_date_format_short($date)
{
    return strftime('%m/%d/%Y', strtotime($date));
}

