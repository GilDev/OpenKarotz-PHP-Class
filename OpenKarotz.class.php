<?php
/*
OpenKarotz PHP Class
Version 1.0

- By GilDev -
http://twitter.com/GilDev
*/
class OpenKarotz
{
	private $_adress;
	private $_request;
	const HEXA_COLOR_REGEX = '#^[0-9a-fA-F]{6}$#';

	public function __construct($ip)
	{
		if (preg_match('#^([0-9]{1,3}\.){3}[0-9]{1,3}$#', $ip)) {	//Vérification de la forme de l'IP
			$this->_adress = 'http://' . $ip . '/cgi-bin/';	//On assigne l'adresse d'appel de l'API OpenKarotz dans une variable pour faciliter les appels de l'API

			if (!isset($this->getStatus()['version'])) {
				throw new Exception('IP doesn\'t point to an OpenKarotz or the OpenKarotz is off');
			}
		} else {
			throw new Exception('Invalid IP');
		}
	}

	private function request()
	{
		$request = curl_init($this->_request);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($request, CURLOPT_TIMEOUT, 10); 
		$out = curl_exec($request);

		return json_decode($out, true);
	}

	/* INFOS */
	public function getStatus()
	{
		$this->_request = $this->_adress . 'status';
		return $this->request();
	}

	public function getStorage()
	{
		$this->_request = $this->_adress . 'get_free_space';
		return $this->request();
	}

	public function getList($list)
	{
		$existingLists = array('rfid', 'rfidInfos', 'sound', 'voice', 'moods', 'snapshot', 'stories', 'radio');

		if (!in_array($list, $existingLists)) {
			return array('return' => '1', 'msg' => 'List doesn\'t exist');
		}

		if ($list == 'rfidInfos') {
			$this->_request = $this->_adress . 'rfid_list_ext';
		} else {
			$this->_request = $this->_adress . $list . '_list';

		}

		return $this->request();
	}

	/* STATE */
	public function wakeUp($silent = false)
	{
		if ($silent) {
			$silent = 1;
		} else {
			$silent = 0;
		}

		$this->_request = $this->_adress . 'wakeup?silent=' . $silent;
		return $this->request();
	}

	public function sleep()
	{
		$this->_request = $this->_adress . 'sleep';
		return $this->request();
	}

	/* LEDS */
	public function led($color = '000000')
	{
		if (!preg_match(self::HEXA_COLOR_REGEX, $color)) {
			return array('return' => '1', 'msg' => 'Incorrect color');
		}

		$this->_request = $this->_adress . 'leds?color=' . $color;
		return $this->request();
	}

	public function pulse($primaryColor, $secondaryColor = '000000', $speed = 700)
	{
		if (!preg_match(self::HEXA_COLOR_REGEX, $primaryColor) || !preg_match(self::HEXA_COLOR_REGEX, $secondaryColor)) {
			return array('return' => '1', 'msg' => 'Incorrect color');
		}

		$speed = (int) $speed;
		if ($speed > 2000 || $speed < 0) {
			return array('return' => '1', 'msg' => 'Speed out of range');
		}

		$this->_request = $this->_adress . 'leds?pulse=1&speed=' . $speed . '&color=' . $primaryColor . '&color2=' . $secondaryColor;
		return $this->request();
	}

	/* EARS */
	public function earsMode($disable = false)
	{
		if ($disable) {
			$disable = 1;
		} else {
			$disable = 0;
		}

		$this->_request = $this->_adress . 'ears_mode?disable=' . $disable;
		return $this->request();
	}

	public function ears($left = 0, $right = 0, $reset = false)
	{
		if ($reset) {
			$noreset = 0;
		} else {
			$noreset = 1;
		}

		$this->_request = $this->_adress . 'ears?noreset=' . $noreset . '&left=' . $left . '&right=' . $right;
		return $this->request();
	}

	public function earsReset()
	{
		$this->_request = $this->_adress . 'ears_reset';
		return $this->request();
	}

	public function earsRandom($reset = false)
	{
		if ($reset) {
			$noreset = 0;
		} else {
			$noreset = 1;
		}

		$this->_request = $this->_adress . 'ears_random?noreset=' . $noreset;
		return $this->request();
	}

	/* RFID */
	public function rfidStartRecord()
	{
		$this->_request = $this->_adress . 'rfid_start_record';
		return $this->request();	//Cette requête ne retourne rien
	}

	public function rfidStopRecord()
	{
		$this->_request = $this->_adress . 'rfid_stop_record';
		return $this->request();
	}

	public function rfidDelete($id)
	{
		$this->_request = $this->_adress . 'rfid_delete?tag=' . $id;
		return $this->request();
	}

	public function rfidUnassign($id)
	{
		$this->_request = $this->_adress . 'rfid_unassign?tag=' . $id;
		return $this->request();
	}

	public function rfidAssignURL($id, $url, $name)
	{
		$this->_request = $this->_adress . 'rfid_assign_url?tag=' . $id . '&url=' . $url . '&name=' . urlencode($name);
		return $this->request();
	}

	/* TTS */
	public function getCache()
	{
		$this->_request = $this->_adress . 'display_cache';
		return $this->request();
	}

	public function clearCache()
	{
		$this->_request = $this->_adress . 'clear_cache';
		return $this->request();
	}

	public function say($text, $voice = 'claire', $nocache = false)
	{
		if ($nocache) {
			$nocache = 1;
		} else {
			$nocache = 0;
		}

		$this->_request = $this->_adress . 'tts?nocache=' . $nocache . '&voice=' . urlencode($voice) . '&text=' . urlencode($text);
		return $this->request();
	}

	/* SNAPSHOT */
	public function clearSnapshots()
	{
		$this->_request = $this->_adress . 'clear_snapshots';
		return $this->request();
	}

	public function takeSnapshot($silent = true, $ftp = false, $server = NULL, $user = NULL, $password = NULL, $remoteDirectory = NULL)
	{
		if ($silent) {
			$silent = 1;
		} else {
			$silent = 0;
		}

		if ($ftp) {
			if ($server && $user && $password && $remoteDirectory) {
				$this->_request = $this->_adress . 'snapshot_ftp?server=' . $server . '&user=' . urlencode($user) . '&password=' . urlencode($password) . '&remote_dir=' . urlencode($remoteDirectory) . '&silent=' . $silent;
			} else {
				return array('return' => '1', 'msg' => 'Missing FTP parameters');
			}
		} else {
			$this->_request = $this->_adress . 'take_snapshot?silent=' . $silent;
			return $this->request();
		}
	}

	/* SOUNDS */
	public function play($id)
	{
		$this->_request = $this->_adress . 'sound?id=' . urlencode($id);
		return $this->request();
	}

	public function playStream($url)
	{
		$this->_request = $this->_adress . 'sound?url=' . $url;
		return $this->request();
	}

	public function pause()
	{
		$this->_request = $this->_adress . 'sound_control?cmd=pause';
		return $this->request();
	}

	public function stop()
	{
		$this->_request = $this->_adress . 'sound_control?cmd=quit';
		return $this->request();
	}

	public function squeezeboxStart()
	{
		$this->_request = $this->_adress . 'squeezebox?cmd=start';
		return $this->request();
	}

	public function squeezeboxStop()
	{
		$this->_request = $this->_adress . 'squeezebox?cmd=stop';
		return $this->request();
	}

	/* APPS */
	/* Moods */
	public function playMood($id = NULL)
	{
		$this->_request = $this->_adress . 'apps/moods';
		if (!empty($id)) {
			$id = (int) $id;
			$this->_request .= '?id=' . $id;
		}

		return $this->request();
	}

	/* Funny Clock */
	public function playClock($hour = NULL)
	{
		$this->_request = $this->_adress . 'apps/clock';
		if (isset($hour)) {
			$hour = (int) $hour;
			$this->_request .= '?hour=' . $hour;
		}

		return $this->request();
	}
}
?>