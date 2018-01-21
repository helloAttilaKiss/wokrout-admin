<?php 

class AdminView extends View
{
	public function __construct()
	{
		$this->setDir('view/admin/');
		$this->setHeaderStart('headStart.php');
		$this->setHeaderEnd('headEnd.php');
		$this->setFooter('footer.php');
        $this->setBodyEnd('bodyEnd.php');
		$this->addMenu('menu.php');
	}
}

?>