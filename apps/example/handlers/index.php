<?php

/**
 * This is a sample handler.
 */

// Verify the input
$id = (isset ($_GET['id']) && is_numeric ($_GET['id']))
	? $_GET['id']
	: 1;

// Fetch some data from the model
$model = new example\MyModel;
$some_data = $model->get_some_data ($id);

// Send the results to a view
return $this->view->render ('apps/example/views/index.php', $some_data);