<?php

class ApiTest extends TestCase {

	/**
	 * is api alive ?
	 *
	 * @return void
	 * @author gaspard
	 */
	public function testApiAlive()
	{
		$response = $this->call('GET', '/api/v1');
		$this->assertTrue($response->isOk());
		$this->assertEquals('API v1 is alive', json_decode($response->getContent())->message );
	}

	/**
	 * test API Simple Requests
	 *
	 * @return void
	 */
	public function testApiSimpleRequests()
	{
		$this->seed();
		
//		Route::enableFilters();
//		$crawler = $this->client->request('GET', '/api/v1/book');
//		$this->assertTrue($this->client->getResponse()->isOk());
	}
	/**
	 * test API Book Read
	 * Can we read the API Book
	 * @return void
	 * @author gaspard
	 */
	public function testApiBookRead()
	{

		$response = $this->call('GET', '/api/v1/book');
		$j = json_decode($response->getContent());

		$this->assertEquals(1, $j->books[0]->id);
		$this->assertEquals('first-book', $j->books[0]->slug);
		$this->assertEquals('First Book', $j->books[0]->title);
		$this->assertEquals('This is the first book, alpha of litterature, a never ending book', $j->books[0]->description);


		$response = $this->call('GET', '/api/v1/book?page=2');
		$j = json_decode($response->getContent());

		$this->assertEquals(3, $j->books[0]->id);
		$this->assertEquals('dramatis-personae', $j->books[0]->slug);
		$this->assertEquals('Dramatis Personae', $j->books[0]->title);

		$response = $this->call('GET', '/api/v1/book?page=99');
		$j = json_decode($response->getContent());

		$this->assertEquals(0, count($j->books));

		$response = $this->call('GET', '/api/v1/book/1');
		$j = json_decode($response->getContent());

		$this->assertEquals(1, $j->book->id);
		$this->assertEquals('first-book', $j->book->slug);
		$this->assertEquals('First Book', $j->book->title);
		$this->assertEquals('This is the first book, alpha of litterature, a never ending book', $j->book->description);

	}

	/**
	 * test API Book Write
	 * 
	 * @return void
	 * @author gaspard
	 */
	public function testApiBookWrite()
	{
/*		$t = 'testuser'.uniqid();
		$user = new User(array('name' => $t, 'password' => $t, 'email'=> $t.'@mail.com'));
*/
		// filters are disactivated in laravel while testing
		// we have to simulate being some user
		$user = new User(array('id'=>1));
		$this->be($user);

		// insert
		$response = $this->call('POST', '/api/v1/book', 
			array('title' => 'My New book title', 'description' => 'This is my new book, you should enjoy it')
		);
		$this->assertTrue($response->getStatusCode() == 201);

		// this *might* be after a redirection
		$j = json_decode($response->getContent());
		$this->assertEquals('My New book title', $j->book->title);
		$this->assertEquals('This is my new book, you should enjoy it', $j->book->description);

		$this->assertGreaterThan(1, $j->book->id);

		$id = $j->book->id;

		// read
		$response = $this->call('GET', '/api/v1/book/'.$id);
		$j = json_decode($response->getContent());
		$this->assertEquals('My New book title', $j->book->title);
		$this->assertEquals('This is my new book, you should enjoy it', $j->book->description);

		// insert fail no description
		$response = $this->call('POST', '/api/v1/book', 
			array('title' => 'My failing book title')
		);
		$this->assertTrue($response->getStatusCode() == 404);

		// insert fail no title
		$response = $this->call('POST', '/api/v1/book', 
			array('description' => 'My failing book is about a book that fails, because some title is missing')
		);
		$this->assertTrue($response->getStatusCode() == 404);

		// insert fail decription too short
		$response = $this->call('POST', '/api/v1/book', 
			array('title' => 'My short book', 'description' => 'This is too short')
		);
		$this->assertTrue($response->getStatusCode() == 404);


		// update
		$response = $this->call('PUT', '/api/v1/book/'.$id, ['title'=> 'My New book title, updated']);
		$this->assertTrue($response->getStatusCode() == 200);
		$j = json_decode($response->getContent());
		$this->assertEquals('My New book title, updated', $j->modified->title);

		$response = $this->call('PUT', '/api/v1/book/'.$id, ['description'=> 'This is my new book, you should enjoy it very much']);
		$this->assertTrue($response->getStatusCode() == 200);
		$j = json_decode($response->getContent());
		$this->assertEquals('This is my new book, you should enjoy it very much', $j->modified->description);

		// read
		$response = $this->call('GET', '/api/v1/book/'.$id);
		$j = json_decode($response->getContent());
		$this->assertEquals('My New book title, updated', $j->book->title);
		$this->assertEquals('This is my new book, you should enjoy it very much', $j->book->description);

	}

	/**
	 * testApiBookDelete
	 *
	 * @return void
	 * @author gaspard
	 */
	public function testApiBookDelete()
	{
		$user1 = new User(array('id'=>1));
		$user2 = new User(array('id'=>2));
		$this->be($user1);

		// insert a new book
		$response = $this->call('POST', '/api/v1/book', 
			array('title' => 'The ghost book', 'description' => 'This book will be deleted quite soon')
		);
		$this->assertTrue($response->getStatusCode() == 201);
		$j = json_decode($response->getContent());
		$id = $j->book->id;

		// delete it
		$response = $this->call('DELETE', '/api/v1/book/'.$id);
		$this->assertTrue($response->getStatusCode() == 200);

		// consult it, it should not exist
		$response = $this->call('GET', '/api/v1/book/'.$id);
		$this->assertTrue($response->getStatusCode() == 404);

		// fail to delete it (already deleted)
		$response = $this->call('DELETE', '/api/v1/book/'.$id);
		$this->assertTrue($response->getStatusCode() == 404);

		// insert a new book
		$response = $this->call('POST', '/api/v1/book', 
			array('title' => 'The survival book', 'description' => 'This book will not be deleted because of controller restrictions')
		);


		$this->assertTrue($response->getStatusCode() == 201);
		$j = json_decode($response->getContent());
		$id2 = $j->book->id;

		// change user
		$this->be($user2);

		// fail to delete it (ownership)
		$response = $this->call('DELETE', '/api/v1/book/'.$id2);
		$this->assertTrue($response->getStatusCode() == 404);

	}

	/**
	 * Test API Book By User
	 *
	 * @author gaspard
	 */
	public function testApiBookByUser()
	{
		$response = $this->call('GET', '/api/v1/user/1/book');
		$j = json_decode($response->getContent());

		$this->assertEquals(1, $j->books[0]->id);
		$this->assertEquals('first-book', $j->books[0]->slug);
		$this->assertEquals('First Book', $j->books[0]->title);
		$this->assertEquals('This is the first book, alpha of litterature, a never ending book', $j->books[0]->description);
	}

	/**
	 * Test API User Read
	 *
	 * @author gaspard
	 */
	public function testApiUserRead()
	{
		$response = $this->call('GET', '/api/v1/user');
		$j = json_decode($response->getContent());
		$this->assertEquals(1, $j->users[0]->id);
		$this->assertEquals('firstuser', $j->users[0]->username);
		$this->assertTrue(isset($j->users[0]->username));
		$this->assertFalse(isset($j->users[0]->email));
		$this->assertFalse(isset($j->users[0]->password));

		// first user
		$response = $this->call('GET', '/api/v1/user/1');
		$j = json_decode($response->getContent());
		$this->assertEquals(1, $j->user->id);
		$this->assertEquals('firstuser', $j->user->username);
		$this->assertTrue(isset($j->user->username));
		$this->assertFalse(isset($j->user->email));
		$this->assertFalse(isset($j->user->password));

		// second user
		$response = $this->call('GET', '/api/v1/user/2');
		$j = json_decode($response->getContent());
		$this->assertEquals(2, $j->user->id);
		$this->assertEquals('seconduser', $j->user->username);

	}
}