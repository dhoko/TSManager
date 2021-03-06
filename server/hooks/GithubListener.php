<?php
class GithubListener extends HookListener {

	private $json = null;
	private $post = [];

	/**
	 * Init a hook with the datas send by Github
	 * @param Array $payload Gihub JSON from $_POST['payload']
	 */
	public function __construct($payload) {
		$this->base = 'https://raw.'.URL_GIT;
		$this->json = $payload;
	}

	/**
	 * Get new updates from github
	 * @return Array [status,msg] status = success||error
	 */
	public function get() {
		
		try {

			return [
				'status' => 'success',
				'msg'    => 'New elements from github',
				'files'  => array(
					'added'    => $this->addedFiles(),
					'modified' => $this->modifiedFiles(),
					'removed'  => $this->removedFiles()
					),
				'timestamp' => $this->timestamp
			];

		} catch (Exception $e) {
			TSlog($e->getMessage(),'error');
			return [
				'status'    => 'error',
				'files'     => array(),
				'timestamp' => '',
				'msg'       => $e->getMessage()
			];
		}
	}

	/**
	 * Read each files from a hook to build them in DRAFT and find the post
	 * @param String $status 
	 * @return Array [post,pict,total]
	 */
	protected function grabFiles($status = 'added') {

		$db = [];
		$pict = [];
		$this->timestamp = (new DateTime($this->json['head_commit']['timestamp']))->format('Y-m-d H:i:s');

		foreach ($this->json['head_commit'][$status] as $file) {

			if (in_array(pathinfo($file, PATHINFO_EXTENSION), $this->postFilesExt)) {

				$db[] = [$file,$this->timestamp];
				TSlog('HOOK - Post content found');
			}

			if (in_array(pathinfo($file, PATHINFO_EXTENSION), $this->pictFilesExt)) {

				$pict[] = $file;
				TSlog('HOOK - Picture found');
			}
		}
		return [
			'post' => $db,
			'pict' => $pict,
			'total' => count($db),
			'date' => $this->timestamp
		];
	}
}