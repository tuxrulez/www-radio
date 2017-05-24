<?php

$app->get('/arquivo/:genero/:arquivo', function($genero,$arquivo) use ($app)
{
    $env = $app->environment();

    $file = $env['path_mp3']."comercial/".$genero."/".$arquivo;
    //$file = "_assets/locucoes/".$genero."/".$arquivo;

    if(file_exists($file))
    {
        $fh = fopen($file, 'rb');

        print(fread($fh, filesize($file)));
    }
});

$app->get('/arquivos/:genero', function($genero) use ($app)
{
	//echo "<pre>";
	//echo json_encode(lista_arquivos($genero));
	//echo "</pre>";
	$response = $app->response();
	$response['Content-Type'] = 'application/json';
	$response->status(200);
	// etc.

	$response->body(json_encode(lista_arquivos($genero)));


});