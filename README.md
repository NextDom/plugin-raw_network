# Plugin Raw Network
Plugin NextDom/Jeedom qui permet d'envoyer des données brutes par le réseau au formation hexadécimal.
Il devient ainsi possible de renvoyer des paquets capturés et contrôler des périphériques n'ayant pas de possibilité d'interfacage.

# Exemple d'utilisation
![Remote IR](https://images-na.ssl-images-amazon.com/images/I/51-Gzav28IL._SY355_.jpg)

Pour les périphériques de la marque Tuya, il existe une application pour mobile mais pas de plugin. Une méthode existe mais reste complexe.
Pour le contrôler, l'application envoie un paquet qui correspond à une commande.
Il est possible de capturer ce paquet avec un outil approprié (pour l'exemple, [Packet Catpure](https://play.google.com/store/apps/details?id=app.greyshirts.sslcapture&hl=fr)).
## Démarche
Lancer l'application qui permet de contrôler le périphérique : 

<img src="./documentation/images/tuya_command.jpg" alt="Tuya command" width="25%"/>

Aller sous l'application Packet Capture : 

<img src="./documentation/images/capture_start.jpg" alt="Start capture" width="25%"/>

Lancer la capture et choisir l'application : 

<img src="./documentation/images/app_choice.jpg" alt="Start capture" width="25%"/>

Retourner sur l'application et lancer la commande : 

<img src="./documentation/images/tuya_command.jpg" alt="Tuya command" width="25%"/>

Une fois lancé, le paquet est capturé. Ils doivent être exportés au format __pcap__ pour l'analyser plus simplement : 

<img src="./documentation/images/export.jpg" alt="Tuya command" width="25%"/>

Ce fichier peut être lu par des applications comme [WireShark](https://www.wireshark.org/).
Une fois ouvert, il faut retrouver le paquet qui lance la commande : 

<img src="./documentation/images/wireshark_paquet.jpg" alt="Tuya command" width="25%"/>

Il faut ensuite copier les données envoyées (et ne pas prendre les entêtes) : 

<img src="./documentation/images/wireshark_value.jpg" alt="Tuya command" width="25%"/>

Dans le plugin ajouter un équipement puis une commande, il est possible de tester directement si le paquet fonctionne : 

<img src="./documentation/images/plugin_paste.jpg" alt="Tuya command" width="25%"/>

