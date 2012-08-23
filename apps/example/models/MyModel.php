<?php

namespace example;

/**
 * A model is just a class that encapsulates business
 * logic. It doesn't need to inherit from anything.
 */
class MyModel {
	/**
	 * Get some data from this model.
	 */
	public function get_some_data ($id) {
		return array (
			'id' => $id,
			'value' => 'Some data'
		);
	}
}