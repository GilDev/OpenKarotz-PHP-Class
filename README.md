OpenKarotz PHP Class Documentation
==================================

Bonjour tout le monde, je suis [GilDev](http://twitter.com/GilDev), et voici la documentation de ma classe PHP qui vous permet de contrôler votre OpenKarotz facilement. Elle utilise l'API HTTP officielle d'OpenKarotz. Vous aurez besoin de PHP 5.4+ ainsi que la bibliothèque [cURL](http://php.net/curl) pour utiliser cette classe.

J'ai essayé de documenter du mieux que j'ai pu, notamment en essayant de me rapprocher au maximum de la syntaxe des synopsis des fonctions de la documentation PHP officielle.  
Par exemple, voici le synopsis de la fonction `pulse()` :

	$out = $karotz->pulse($primaryColor [, $secondaryColor = '000000' [, $speed = 700]]);

Cet exemple appelle la fonction `pulse()` sur l'objet `$karotz`. Le paramètre `$primaryColor` est indispensable, cependant, les paramètres `$secondaryColor` et `$speed` sont optionnels (entre crochets) et ont par défaut les valeurs respectives `'000000'` et `700` s'ils ne sont pas renseignés.

Si aucun exemple de retour n'est présent pour une fonction, c'est que la fonction retourne uniquement :

	Array
	(
		[return] => 0
	)

S'il y a une erreur dans un appel de fonction, l'index `return` vaudra `1` et l'index `msg` contiendra le message d'erreur.

Pour toute question, me contacter sur [Twitter](http://twitter.com/GilDev) ou par [mail](mailto:gildev@gmail.com).

[![WTFPL](http://www.wtfpl.net/wp-content/uploads/2012/12/wtfpl-badge-4.png)](http://www.wtfpl.net/txt/copying/)

----------

Sommaire des fonctions
----------------------

* [Informations](#Informations)
  * [getStatus()](#getstatus)
  * [getStorage()](#getstorage)
  * [getList()](#getlist)
* [État](#etat)
  * [wakeUp()](#wakeup)
  * [sleep()](#sleep)
* [Leds](#leds)
  * [led()](#led)
  * [pulse()](#pulse)
* [Oreilles](#oreilles)
  * [earsMode()](#earsmode)
  * [ears()](#ears)
  * [earsReset()](#earsreset)
  * [earsRandom()](#earsrandom)
* [RFID](#rfid)
  * [rfidStartRecord()](#rfidstartrecord)
  * [rfidStopRecord()](#rfidstoprecord)
  * [rfidDelete()](#rfiddelete)
  * [rfidUnassign()](#rfidunassign)
  * [rfidAssignURL()](#rfidassignurl)
* [TTS](#tts)
  * [getCache()](#getcache)
  * [clearCache()](#clearcache)
  * [say()](#say)
* [Photos](#photos)
  * [clearSnapshots()](#clearsnapshots)
  * [takeSnapshot()](#takesnapshot)
* [Sons](#sons)
  * [play()](#play)
  * [playStream()](#playstream)
  * [pause()](#pause)
  * [stop()](#stop)
  * [squeezeboxStart()](#squeezeboxstart)
  * [squeezeboxStop()](#squeezeboxstop)
* [Applications](#applications)
  * [playMood()](#playmood)
  * [playClock()](#playclock)
  
----------

Créer un nouveau object OpenKarotz
----------------------------------

Pour commencer, crééz un nouvel objet OpenKarotz comme ceci :

	require 'OpenKarotz.class.php';

	try {
		$karotz = new OpenKarotz('x.x.x.x');
	} catch (Exception $e) {
		echo 'Erreur : ' . $e->getMessage();
		die();
	}

Remplacez "x.x.x.x" par l'adresse IP de votre OpenKarotz.

Toutes les fonctions de la classe retournent un tableau associatif. En cas d'erreur, l'index "retour" du tableau sera égal à 1 et l'index "msg" contiendra le message d'erreur.

S'il n'y a pas d'erreur, le tableau associatif sera le retour de l'API officielle (qui est au format JSON), décodé grâce à la fonction `json_decode()`.

----------

Fonctions
---------

### [Informations](id:informations) ###

#### [getStatus()](id:getstatus) ####

Vous retourne le status du lapin.

    $out = $karotz->getStatus();

##### Exemple de retour : #####

	Array
	(
	    [version] => 200
    	[ears_disabled] => 0
    	[sleep] => 0
    	[sleep_time] => 0
    	[led_color] => 00FF00
    	[led_pulse] => 1
    	[tts_cache_size] => 2
    	[usb_free_space] => -1
    	[karotz_free_space] => 147.3M
    	[eth_mac] => 00:00:00:00:00:00
    	[wlan_mac] => 00:0E:8E:2C:D4:98
    	[nb_tags] => 1
    	[nb_moods] => 305
    	[nb_sounds] => 14
    	[nb_stories] => 0
    	[karotz_percent_used_space] => 37
    	[usb_percent_used_space] => 
    	[data_dir] => /usr/openkarotz
	)

#### [getStorage()](id:getstorage) ####

Vous retourne l'espace utilisé sur la mémoire interne du lapin et la mémoire USB.

	$out = $karotz->getStorage();

##### Exemple de retour : #####

	Array
	(
		[karotz_percent_used_space] => 37
    	[usb_percent_used_space] => -1
	)

#### [getList()](id:getlist) ####

Vous retourne la liste de données spécifié.

	$out = $karotz->getList($categorie);

La catégorie peut être "rfid", "rfidInfos" (contient une version lisible des valeurs `type` et `color`, voir l'*exemple de retour*, équivalent de `/cgi-bin/rfid_infos_ext` de l'API officielle), sound", "voice", "moods", "snapshot", "stories" ou "radio".

##### Exemple de retour : #####

	Array
	(
		[tags] => Array
		(
			[0] => Array
			(
				[id] => D0021A053B4A21CB
				[name] => 
				[type] => KAROTZ
				[type_name] => KEYRING
				[color] => 4
				[color_name] => YELLOW
			)
		)
		[return] => 0
	)

### [État](id:etat) ###

#### [wakeUp()](id:wakeup) ####

Réveille le Karotz.

	$out = $karotz->wakeUp([$silent = false]);

Si `$silent` vaut `true`, le Karotz se réveillera silencieusement.

##### Exemple de retour : #####

	Array
	(
		[return] => 0
		[silent] => 1
	)

#### [sleep()](id:sleep) ####

Endors le Karotz.

	$out = $karotz->sleep();

### [Leds](id:leds) ###

#### [led()](id:led) ####

Change la couleur de la led du Karotz.

	$out = $karotz->led([$color = '000000']);

La couleur doit être spécifié sous forme d'une chaîne hexadécimale.

##### Exemple de retour : #####

	Array
	(
		[color] => FF0066
		[secondary_color] => 000000
		[pulse] => 0
		[no_memory] => 0
		[speed] => 
		[return] => 0
	)

#### [pulse()](id:pulse) ####

Fait clignoter la led du Karotz.

	$out = $karotz->pulse($primaryColor [, $secondaryColor = '000000' [, $speed = 700]]);

Les couleurs doivent être spécifiés sous forme d'une chaîne hexadécimale. La vitesse doit être comprise entre 0 et 2000.

##### Exemple de retour : #####

	Array
	(
		[color] => FF0066
		[secondary_color] => 00FF00
		[pulse] => 1
		[no_memory] => 0
		[speed] => 1000
		[return] => 0
	)

### [Oreilles](id:oreilles) ###

#### [earsMode()](id:earsmode) ####

Active ou désactive le mouvement des oreilles du Karotz.

	$out = $karotz->earsMode([$disable = false]);

##### Exemple de retour : #####

	Array
	(
		[return] => 0
		[disabled] => 1
	)

#### [ears()](id:ears) ####

Modifie la position des oreilles du Karotz.

	$out = $karotz->ears([$left = 0 [, $right = 0 [, $reset = false]]]);

Les positions $left ou $right vont de 0 à 16, vous pouvez cependant dépasser ou mettre une valeur négative si vous souhaitez faire plusieurs tours ou changer le sens de rotation par exemple.

Si `$reset` vaut `true`, les oreilles se mettront d'abord en position initiale avant de se positionner.

##### Exemple de retour : #####

	Array
	(
		[left] => 13
		[right] => 18
		[return] => 0
	)

#### [earsReset()](id:earsreset) ####

Réinitialise la position des oreilles.

	$out = $karotz->earsReset();

#### [earsRandom()](id:earsrandom) ####

Positionne les oreilles de manière aléatoire.

	$out = $karotz->earsRandom([$reset = false]);

Si `$reset` vaut `true`, les oreilles se mettrong d'abord en position initiale avant de se positionner.

### [RFID](id:rfid) ###

#### [rfidStartRecord()](id:rfidstartrecord) ####

Lance l'enregistrement des tags RFID.

	$out = $karotz->rfidStartRecord();

**Attention** : Cette fonction ne retourne actuellement rien !

#### [rfidStopRecord()](id:rfidstoprecord) ####

Stoppe l'enregistrement des tags RFID.

	$out = $karotz->rfidStopRecord();

#### [rfidDelete()](id:rfiddelete) ####

Supprime un tag RFID enregistré.

	$out = $karotz->rfidDelete($id);

##### Exemple de retour : #####

	Array
	(
		[return] => 0
		[tag] => D0021A053B4A21CB
	)

#### [rfidUnassign()](id:rfidunassign) ####

Supprime l'action associée au tag RFID.

	$out = $karotz->rfidUnassign($id);

##### Exemple de retour : #####

	Array
	(
		[return] => 0
		[tag] => D0021A053B4A21CB
	)

#### [rfidAssignURL()](id:rfidassignurl) ####

Assigne le tag RFID à un appel d'URL.

	$out = $karotz->rfidAssignUrl($id, $url, $name)

### [TTS](id:tts) (Text To Speech = Synthèse vocale) ###

#### [getCache()](id:getcache) ####

Récupère le cache TTS du Karotz.

	$out = $karotz->getCache();

##### Exemple de retour : #####

	Array
	(
		[cache] => Array
		(
			[0] => Array
			(
				[id] => 4d509419511635e0fce55a929629fbbb
				[text] =>  Bonjour tout le monde ! 
				[voice] => claire
			)
		)
		[return] => 0
	)
	
#### [clearCache()](id:clearcache) ####

Efface le cache TTS du Karotz.

	$out = $karotz->clearCache();

##### Exemple de retour : #####

	Array
	(
		[return] => 0
		[msg] => Cache cleared
	)

#### [say()](id:say) ####

Fait parler le Karotz.

	$out = $karotz->say($text [, $voice='claire' [, nocache = false]]);

$voice peut être `alice` (FR), `claire` (FR), `julie` (FR), `margaux` (FR), `antoine` (FR), `bruno` (FR), `louise` (CA), `justine` (BE), `heater` (US), `ryan` (US), `lucy` (UK), `graham` (UK), `andreas` (DE), `julia` (DE), `chiara` (IT) ou `Vittorio` (IT).

Si `$nocache` vaut `true`, le fichier audio ne sera pas sauvegardé dans le cache.

##### Exemple de retour : #####

	Array
	(
		[id] => cfcb12bf12e8bccffe7e48c791e0b870
		[played] => 1
		[cache] => 0
		[return] => 0
		[voice] => alice
		[mute] => 0
	)

### [Photos](id:photos) ###

#### [clearSnapshots()](id:clearsnapshots) ####

Efface toutes les photos enregistrées.

	$out = $karotz->clearSnapshots();

#### [takeSnapshot()](id:takesnapshot) ####

Prends une photo depuis le Karotz.

	$out = $karotz->takeSnapshot([$silent = true [, $ftp = false, $server, $user, $password, $remoteDirectory]]);

Si `$silent` vaut `false`, le lapin fera du bruit lors de la prise de photo.

Si `$ftp` vaut `true`, les paramètres `$server`, `$user`, `$password` et `$remoteDirectory` doivent absolument être renseignés. Ces champs ne vérifient pas la connexion ni la validité des données, faites donc bien attention aux valeurs que vous entrez !

`$server` est l'adresse du serveur FTP, `$user` l'identifiant, `$password` le mot de passe et `$remoteDirectory` le répertoire de sauvegarde de l'image.

### [Sons](id:sons) ###

#### [play()](id:play) ####

Joue un fichier audio enregistré sur le Karotz (vous pouvez obtenir la liste des fichiers audio enregistrés via la commande `getList('sound')`).

	$out = play($id);

**Note** : `$id` est une chaîne de caractère, à savoir le nom du fichier audio, et non un entier !

#### [playStream()](id:playstream) ####

Joue un fichier audio ou un flux audio depuis internet.

	$out = $karotz->playStream($url);

#### [pause()](id:pause) ####

Met en pause le fichier ou flux audio en cours de lecture, ou continue le morceau s'il est déjà en pause.

	$out = $karotz->pause();

##### Exemple de retour : #####

	Array
	(
		[return] => 0
		[cmd] => pause
	)

**Note** : Pour le moment, la valeur de l'index `cmd` retourné sera toujours sur `pause`, on ne peut pas savoir si le morceau est déjà en pause.

#### [stop()](id:stop) ####

Quitte la lecture du fichier ou flux audio.

	$out = $karotz->stop();

##### Exemple de retour : #####

	Array
	(
		[return] => 0
		[cmd] => quit
	)

#### [squeezeboxStart()](id:squeezeboxstart) ####

Lance la Squeezebox.

	$out = $karotz->squeezeboxStart();

#### [squeezeboxStop()](id:squeezeboxstop) ####

Stoppe la Squeezebox.

	$out = $karotz->squeezeboxStop();

### [Applications](id:applications) ###

#### [playMood()](id:playmood) ####

Joue une humeur sur le Karotz.

	$out = $karotz->playMood([$id = NULL]);

Si `$id` n'est pas renseigné, une humeur aléatoire sera jouée.

##### Exemple de retour : #####

	Array
	(
		[moods] => 279
		[return] => 0
	)

#### [playClock()](id:playclock) ####

Joue une heure sur le Karotz.

	$out = $karotz->playClock([$hour = NULL]);

Si `$hour` n'est pas renseigné, l'heure actuelle sera jouée.

##### Exemple de retour : #####

	Array
	(
		[return] => 0
		[hour] => 20
	)
