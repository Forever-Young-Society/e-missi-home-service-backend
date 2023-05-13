<?php /**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
 

return [
	"service" => [
		"POST:create" => [
            'params' => [
		

			"Category[title]" => \Faker\Factory::create()->text(10),
			"Category[image_file]" => \Faker\Factory::create()->text(10),
			"Category[state_id]" => 0,
			"Category[type_id]" => 0,
			"Category[created_on]" => "2022-11-03 12:20:23",
			"Category[created_by_id]" => 1,
			]
],
		"POST:update/{id}"=>  [
            'params' => [		
		
			"Category[title]" => \Faker\Factory::create()->text(10),
			"Category[image_file]" => \Faker\Factory::create()->text(10),
			"Category[state_id]" => 0,
			"Category[type_id]" => 0,
			"Category[created_on]" => "2022-11-03 12:20:23",
			"Category[created_by_id]" => 1,
			]
],
		"GET:index" => [
            ],
		"GET:{id}" =>  [
            ],
		"DELETE:{id}" =>  [
            ],
	]
];
?>
