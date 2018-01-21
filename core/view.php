<?php 

abstract class View
{
	private $dir = '';
	protected $headStart = '';
	protected $headEnd = '';
	protected $headJavascripts = array();
	protected $bodyJavascripts = array();
	protected $styles = array();
	protected $menus = array();
	protected $addonViews = array();
	protected $footers = array();
	protected $bodyEnd = '';
	protected $params = array();
	protected $file;

	protected function setDir($dir){
		$this->dir = $dir;
	}

	public function setView($file){
		$this->file = $file;
	}

	public function addMenu($menu)
	{
		$this->menus[] = $menu;
	}

	public function addHeadJavascript($script)
	{
		$this->headJavascripts[] = $script;
	}

	public function addBodyJavascript($script)
	{
		$this->bodyJavascripts[] = $script;
	}

	public function addStyle($style)
	{
		$this->styles[] = $style;
	}

	public function setHeaderStart($head)
	{
		$this->headStart = $head;
	}

	public function setHeaderEnd($head)
	{
		$this->headEnd = $head;
	}

	public function setAddonView($addonView)
	{
		$this->addonViews[] = $addonView;
	}

	public function setFooter($footer)
	{
		$this->footers[] = $footer;
	}

	public function setBodyEnd($bodyEnd)
	{
		$this->bodyEnd = $bodyEnd;
	}

	public function addParam($key, $value)
	{
		$this->params[$key] = $value;
	}

	public function setParam($key, $value)
	{
		$this->addParam($key, $value);
	}

	public function render($flush = true)
	{
		global $baseInfo;
		extract($this->params);
		ob_start();

		if (!empty($this->headStart) && file_exists($this->dir.$this->headStart))
			require $this->dir.$this->headStart;

		foreach ($this->headJavascripts as $js) { 
			?>
				<script src="/view/scripts/<?php echo $js; ?>"></script>
			<?php 
		}

		foreach ($this->styles as $style) { 
			?>
				<link rel="stylesheet" href="/view/style/<?php echo $style; ?>">
			<?php 
		}

		if (!empty($this->headEnd) && file_exists($this->dir.$this->headEnd))
			require $this->dir.$this->headEnd;

		foreach ($this->menus as $menu) {
			require $this->dir.$menu;
		}

		require $this->dir.$this->file;

		foreach ($this->footers as $footer) {
			require $this->dir.$footer;
		}

		foreach ($this->addonViews as $addonView) {
			require $this->dir.$addonView;
		}

		foreach ($this->bodyJavascripts as $js) { 
			?>
				<script src="/view/scripts/<?php echo $js; ?>"></script>
			<?php 
		}

		if (!empty($this->bodyEnd) && file_exists($this->dir.$this->bodyEnd)) 
			require $this->dir.$this->bodyEnd;

		if ($flush){
			ob_flush();
			exit();
		}else{
			return ob_get_clean();
		}
		
	}

}
