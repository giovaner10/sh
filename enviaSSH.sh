#!/bin/bash
export user=servidor
export senha=showtecservidor
stty $bkpterminal
export porta=220
for ARG in $*; do
    export cmd="$cmd $ARG"
done
for i in $(cat rbs.txt); do
	export host=$i
	expect -c '
	spawn -noecho ssh -l servidor -i chave $env(user)@$env(host) -p $env(porta) /$env(cmd);
	expect "ssword:" { send "$env(senha)\r"}
	interact
	'
done
