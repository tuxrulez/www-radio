<?php

$app->get('/hora', function()
{
    print date('H:i');
});