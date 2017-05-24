#!/bin/bash

mplayer_log=/tmp/locucao_out

# verifica se o arquivo existe
while [ ! -f "$mplayer_log" ]
do
	sleep 1
done

arquivo=``
ultimo=""
aux=0

# verifica se o nome do arquivo trocou e adiciona no log
while [ $? ]; do
	vazio=`tail -1 $mplayer_log`
	hora=`date +%d/%m/%Y-%H:%M:%S`
	temp=`tac $mplayer_log | grep "Playing " | cut -c 9- | rev | cut -c 2- | rev | head -n1`

	if [ -n "$temp" ] && [ "$vazio" == "" ]; then
		echo "vazio" $hora
		if [ "$aux" == 1 ]; then
			echo "gravou" $temp
			echo $hora $temp >> /var/log/radio/radio.log

			tipo=`echo $temp | cut -d "/" -f8`
            data=`echo $hora | cut -d "-" -f1`
            hora2=`echo $hora | cut -d "-" -f2`
            rede=`cat /var/www/config.inc.php | grep "rede" | cut -d "'" -f2`
            loja=`cat /var/www/config.inc.php | grep "loja" | cut -d "'" -f2`
            arquivo2=`echo $temp | cut -d "/" -f6`
            genero=`echo $temp | cut -d "/" -f7`
            echo "$rede|$loja|$data|$hora2|$tipo|$arquivo2|$genero" >> /var/log/radio/executadas.txt

			aux=0
		fi
	else
		echo "tocando" $hora $temp
		aux=1
	fi

	#echo $hora $vazio
	sleep 1
done