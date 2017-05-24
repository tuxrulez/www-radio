#!/bin/bash

teste=`ps axu | grep /var/www/log.sh | grep -v grep`;

# Testando se o IM log.sh está rodando
if [ "$teste" ];
then
    echo "log rodando"
else
    /bin/bash /var/www/log.sh > /dev/null &
fi

teste2=`ps axu | grep /var/www/log_evento.sh | grep -v grep`;

# Testando se o IM log_evento.sh está rodando
if [ "$teste2" ];
then
    echo "log_evento rodando"
else
    /bin/bash /var/www/log_evento.sh > /dev/null &
fi