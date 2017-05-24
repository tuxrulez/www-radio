#!/bin/bash

# ALterado para nao duplicar o log

mplayer_log=/tmp/playlist_out

# verifica se o arquivo existe
while [ ! -f "$mplayer_log" ]
do
	sleep 1
done

arquivo=``

# verifica se o nome do arquivo é diferente de vazio
while [ $? ]; do
	arquivo=`tac $mplayer_log | grep "Playing " | cut -c 9- | rev | cut -c 2- | rev | head -n1`

	if [ -n "$arquivo" ] && [ "$arquivo" != "/var/www/mudo.wav" ]; then
		break
	fi

	sleep 1
done

# verifica se o nome do arquivo trocou e adiciona no log
while [ $? ]; do
	temp=`tac $mplayer_log | grep "Playing " | cut -c 9- | rev | cut -c 2- | rev | head -n1`

	if [ "$arquivo" != "$temp" ] && [ "$temp" != "/var/www/mudo.wav" ]; then
		hora=`date +%d/%m/%Y-%H:%M:%S`
		
		echo $hora $arquivo
		echo $hora $arquivo >> /var/log/radio/radio.log
                tipo=`echo $arquivo | cut -d "/" -f8`
		data=`echo $hora | cut -d "-" -f1`
		hora2=`echo $hora | cut -d "-" -f2`
		rede=`cat /var/www/config.inc.php | grep "rede" | cut -d "'" -f2`
		loja=`cat /var/www/config.inc.php | grep "loja" | cut -d "'" -f2`
		arquivo2=`echo $arquivo | cut -d "/" -f6`
		genero=`echo $arquivo | cut -d "/" -f7`
                echo "$rede|$loja|$data|$hora2|$tipo|$arquivo2|$genero" >> /var/log/radio/executadas.txt
		arquivo=$temp
	fi
	
	sleep 1
done
