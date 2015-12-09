#!/usr/bin/php
<?php
if (count($_SERVER['argv']) < 2) {
	echo "Error: A class name must be provided as argument".PHP_EOL."Exiting".PHP_EOL;
	exit(1);
}
echo PHP_EOL;

$logfile = fopen("anax-scaffold.log", 'a');
if (!is_resource($logfile)) {
	exit(1);
}
fwrite($logfile, 'The following files were created ' . date('Y:m:d H:i:s') .':'.PHP_EOL.PHP_EOL);

for ($i=1; $i < $_SERVER['argc'] ; $i++) { 
	
	$class_name = $_SERVER['argv'][$i];

	// Create model file
	$boilerplate = modelFileBoilerplate($class_name);
	createSourceFile($class_name, $boilerplate, 'Model', $logfile);

	// Create controller file
	$boilerplate = controllerFileBoilerplate($class_name);
	createSourceFile($class_name, $boilerplate, 'Controller', $logfile);
	echo PHP_EOL;

	// Create views
	createViews(strtolower($class_name), 'index', $logfile);
	createViews(strtolower($class_name), 'view', $logfile);
	createViews(strtolower($class_name), 'add', $logfile);	
	echo PHP_EOL;
}
createCDatabaseModel($logfile);

fwrite($logfile, PHP_EOL);
fclose($logfile);
echo PHP_EOL . "--> Log written to anax-scaffold.log".PHP_EOL;
echo PHP_EOL;

exit(0);

/**
 * Create source files
 *
 * @param string $class_name, name of class
 * @param string $boilerplate, code
 * @param string $type, type of file
 *
 * @return void
 */
function createSourceFile($class_name, $boilerplate, $type, $logfile) {

	if (!is_dir('app')) {
		mkdir('app');
	}
	chdir('app');

	if (!is_dir('src')) {
		mkdir('src');
	}
	chdir('src');

	if (!is_dir($class_name)) {
		mkdir($class_name);
	}
	chdir($class_name);
	$dir_name = $class_name;
	
	if ($type == 'Controller') {
		$class_name = $class_name.'Controller';
	}
	
	$fp = fopen("{$class_name}.php", 'w');
	if (!is_resource($fp)) {
		return false;
	}
	fwrite($fp, $boilerplate);
	fclose($fp);
	echo "--> " . $class_name . ".php created in directory /app/src/" . $dir_name . PHP_EOL;
	fwrite($logfile, '/app/src/' . $dir_name .'/'.$class_name.".php".PHP_EOL);
	chdir('../../../');
}

/**
 * Create view files
 *
 * @param string $class_name, name of class
 * @param string $boilerplate, code
 *
 * @return void
 */
function createViews($class_name, $type, $logfile) {

	if (!is_dir('app')) {
		mkdir('app');
	}
	chdir('app');
	if (!is_dir('view')) {
		mkdir('view');
	}
	chdir('view');

	if (!is_dir($class_name)) {
		mkdir($class_name);
	}
	chdir($class_name);
	$fp = fopen($type . ".tpl.php", 'w');
	if (!is_resource($fp)) {
		return false;
	}

	// fwrite file here
	fwrite($fp, "<article class='article1'>\n\t<h2><?=\$title?></h2>\n\t<?=\$content?>\n</article>");
	fclose($fp);
	echo "--> " . $type . ".tpl.php created in directory /app/view/" . $class_name . PHP_EOL;
	fwrite($logfile, '/app/view/' . $class_name .'/'.$type.".tpl.php".PHP_EOL);
	chdir('../../../');
}

/**
* Create CDatabaseModel.php file if not exists
*
* @param 
*
* @return void
*/
function createCDatabaseModel($logfile) {

	if (!is_dir('src')) {
		mkdir('src');
	}
	chdir('src');
	if (!is_dir('MVC')) {
		mkdir('MVC');
	}
	chdir('MVC');
	if (file_exists('CDatabaseModel.php')) {
		echo "--> CDatabaseModel.php already exists.".PHP_EOL;
	} else {
		$fp = fopen("CDatabaseModel.php", 'w');
		if (!is_resource($fp)) {
			return false;
		}
		$code = cdatabasemodelBoilerplate();
		fwrite($fp, $code);
		fclose($fp);
		echo "--> CDatabaseModel.php created in directory /src/MVC".PHP_EOL;
		fwrite($logfile, '/src/MVC/CDatabaseModel.php'.PHP_EOL);
	}
	chdir('../../../');
}

/**
* Boilerplate for model file
*
* @param string $class_name, name of class
*
* @return string with boilerplate code
*/
function modelFileBoilerplate($class_name) {
	return "<?php

	namespace CR\\".$class_name.";

	/**
 	 * Model for {$class_name}.
 	 *
 	 */
	class {$class_name} extends \Anax\MVC\CDatabaseModel {

	}";
}

/**
* Boilerplate for controller file
*
* @param string $class_name, name of class
*
* @return string with boilerplate code
*/
function controllerFileBoilerplate($class_name) {
	return "<?php

namespace CR\\".$class_name.";

/**
* A controller for {$class_name} and CRUD related events.
*/
class {$class_name}Controller implements \Anax\DI\IInjectionAware {

	use \Anax\DI\TInjectable;

	/**
	 * Initialize the controller.
	 *
	 * @return void
	 */
	public function initialize() {
		\$this->{$class_name} = new \CR\\{$class_name}\\{$class_name}();
		\$this->{$class_name}->setDI(\$this->di);
	}

	/**
	 * List all
	 *
	 * @return void
	 */
	public function indexAction() {

		\$all = null;
		//\$all = \$this->{$class_name}->findAll();

		\$this->theme->setTitle('$class_name');
		\$this->views->add('$class_name/index', [
			'content' => \$all,
			'title' => '$class_name',
			], 'main');
	}

	/**
	 * Setup database
	 *
	 * @return void
	 */
	public function setupAction() {
		//\$this->db->setVerbose();

		\$this->db->dropTableIfExists('$class_name')->execute();

		\$this->db->createTable(
			'$class_name',
			[
			'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
			'created' => ['datetime'],
			'updated' => ['datetime'],
			'deleted' => ['datetime'],
			]
			)->execute();
	}
	/**
	 * Find with id.
	 *
	 * @param int \$id
	 *
	 * @return void
	 */
	public function idAction(\$id = null) {

		\$res = \$this->{$class_name}->find(\$id);

		if (\$res) {
			\$this->theme->setTitle('$class_name');
			\$this->views->add('$class_name/view', [
				'content' => [\$res],
				'title' => '$class_name Detail view',
				], 'main');
		} else {
			\$url = \$this->url->create('$class_name-');
			\$this->response->redirect(\$url);
		}
	}

	/**
	 * Add new
	 *
	 * @return void
	 */
	public function addAction() {
/*
		\$form = new \Anax\HTMLForm\CFormAdd$class_name();
		\$form->setDI(\$this->di);
		\$form->check();

		\$this->di->theme->setTitle('New');
		\$this->views->add('$class_name/add', [
			'title' => 'New $class_name',
			'content' => \$form->getHTML()
			], 'main');
*/
	}

	/**
	 * Delete
	 *
	 * @param integer \$id
	 *
	 * @return void
	 */
	public function deleteAction(\$id = null) {
		if (!isset(\$id)) {
			die('Missing id');
		}

		\$res = \$this->{$class_name}->delete(\$id);
	}
}";
}

/**
* Code for CDatabaseModel file
*
* @param 
*
* @return string with code
*/
function cdatabasemodelBoilerplate() {
		return "<?php

namespace Anax\MVC;

/**
* Base class for database models
*/
class CDatabaseModel implements \Anax\DI\IInjectionAware {

	use \Anax\DI\TInjectable;
	
	/**
	 * Get the table name
	 *
	 * @return string with the table name
	 */
	public function getSource() {
		return strtolower(implode('', array_slice(explode('\\\', get_class(\$this)), -1)));
	}

	/**
	 * Find and return all
	 *
	 * @return array
	 */
	public function findAll() {
		\$this->db->select()
			->from(\$this->getSource());

		\$this->db->execute();
		\$this->db->setFetchModeClass(__CLASS__);
		return \$this->db->fetchAll();
	}

	/**
	 * Find and return specific.
	 *
	 * @return \$this
	 */
	public function find(\$id) {
		\$this->db->select()
			->from(\$this->getSource())
			->where(\"id = ?\");

		\$this->db->execute([\$id]);
		return \$this->db->fetchInto(\$this);
	}

	/**
	 * Get object properties.
	 *
	 * @return array with object properties.
	 */
	public function getProperties() {
		\$properties = get_object_vars(\$this);
		unset(\$properties['di']);
		unset(\$properties['db']);

		return \$properties;
	}

	/**
	 * Set object properties.
	 *
	 * @param array \$properties with propreties to set.
	 *
	 * @return void
	 */
	public function setProperties(\$properties) {
		// Update object with incoming values, if any
		if (!empty(\$properties)) {
			foreach (\$properties as \$key => \$value) {
				\$this->\$key = \$value;
			}
		}
	}

	/**
	 * Save current object/row.
	 *
	 * @param array @values key/values to save or empty to use object properties.
	 *
	 * @return boolean true or false if saving went ok.
	 */
	public function save(\$values = []) {
		\$this->setProperties(\$values);
		\$values = \$this->getProperties();

		if (isset(\$values['id'])) {
			return \$this->update(\$values);
		} else {
			return \$this->create(\$values);
		}
	}

	/**
	 * Create new row.
	 *
	 * @param array \$values key/values to save.
	 *
	 * @return boolean true or false if saving went ok.
	 */
	public function create(\$values) {
		\$keys = array_keys(\$values);
		\$values = array_values(\$values);

		\$this->db->insert(
			\$this->getSource(),
			\$keys
		);

		\$res = \$this->db->execute(\$values);

		\$this->id = \$this->db->lastInsertID();

		return \$res;
	}

	/**
	 * Update row.
	 *
	 * @param array \$values key/values to save.
	 *
	 * @return boolean true or false if saving went ok.
	 */
	public function update(\$values) {
		\$keys = array_keys(\$values);
		\$values = array_values(\$values);

		// Update, remove id and use as where-clause
		unset(\$keys['id']);
		\$values[] = \$this->id;

		\$this->db->update(
			\$this->getSource(),
			\$keys,
			\"id = ?\"
		);

		return \$this->db->execute(\$values);
	}

	/**
	 * Delete row.
	 *
	 * @param integer \$id to delete.
	 *
	 * @return boolean true or false if deleting went ok.
	 */
	public function delete(\$id) {
		\$this->db->delete(
			\$this->getSource(),
			'id = ?'
		);

		return \$this->db->execute([\$id]);
	}

	/**
	 * Build a select-query.
	 *
	 * @param string \$columns which columns to select.
	 *
	 * @return \$this
	 */
	public function query(\$columns = '*') {
		\$this->db->select(\$columns)
			->from(\$this->getSource());

		return \$this;
	}

	/**
	 * Build the where part.
	 *
	 * @param string \$condition for building the where part of the query.
 	 *
 	 * @return \$this
 	 */
	public function where(\$condition) {
		\$this->db->where(\$condition);

		return \$this;
	}

	/**
	 * Build the andWhere part.
	 *
	 * @param string \$condition for building the andWhere part of the query.
 	 *
 	 * @return \$this
 	 */
	public function andWhere(\$condition) {
		\$this->db->andWhere(\$condition);

		return \$this;
	}

	/**
 	 * Execute the query built.
 	 *
 	 * @param string \$query custom query.
 	 *
 	 * @return \$this
 	 */
	public function execute(\$params = []) {
		\$this->db->execute(\$this->db->getSQL(), \$params);
		\$this->db->setFetchModeClass(__CLASS__);

		return \$this->db->fetchAll();
	}

	/**
	 * Order-by
	 *
	 * @param string \$condition for building the orderBy part of the query.
 	 *
 	 * @return \$this
	 */
	public function orderBy(\$condition) {
		\$this->db->orderBy(\$condition);

		return \$this;
	}

	/**
	 * Group-by
	 *
	 * @param string \$condition for building the groupBy part of the query.
 	 *
 	 * @return \$this
	 */
	public function groupBy(\$condition) {
		\$this->db->groupBy(\$condition);

		return \$this;
	}

	/**
	 * Limit
	 *
	 * @param string \$condition for building the limit part of the query.
 	 *
 	 * @return \$this
	 */
	public function limit(\$condition) {
		\$this->db->limit(\$condition);

		return \$this;
	}

	/**
	 * Offset
	 *
	 * @param string \$condition for building the offset part of the query.
 	 *
 	 * @return \$this
	 */
	public function offset(\$condition) {
		\$this->db->offset(\$condition);

		return \$this;
	}
}";
}
?>