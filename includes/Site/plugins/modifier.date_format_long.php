<?php

function smarty_modifier_date_format_long($date)
{
    return strftime('%A, %B %e, %Y', strtotime($date));
}

