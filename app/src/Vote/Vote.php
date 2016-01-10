<?php

namespace CR\Vote;

/**
* A controller for Votes. Handles upvotes and downvotes for Questions, Answers and Comments
*/
class Vote implements \Anax\DI\IInjectionAware {

	use \Anax\DI\TInjectable;

    /**
    * Checks whether a vote already has been cast by the logged in user. Also prevents
    * a user from voting on his/her own posts.
    *
    * @param object $object, the object to update
    * @param string $objecttype, string with type of object
    *
    * @return true or false, if false casting a vote is possible
    */
    public function checkVote($object, $objecttype) {

		if ($this->di->UserloginController->checkLoginSimple() && $object->getProperties()[$objecttype.'UserId'] !== $this->session->get('id')) {
			$this->db->select()
				->from('vote2'.$objecttype)
				->where("id".$objecttype." = ?")
				->andWhere("idUser = ?")
				->execute([$object->getProperties()['id'], $this->session->get('id')]);
			$vote = $this->db->fetchAll();
			return $vote;
		}
		return true;
	}

    /**
    * Update the upvotes or downvotes column in the database table for object
    *
    * @param object $object, type of object to vote for
    * @param string $objecttype, string with name of object type
    * @param string $votetype, upvote or downvote
    * @param int @questionId, questionId for redirecting to correct page
    *
    * @return void
    */
    public function castVote($object, $objecttype, $votetype, $questionId) {

		$vote = $this->checkVote($object, $objecttype);

		if (!$vote) {
			$this->db->update(
				$objecttype,
				[$votetype],
				[++$object->getProperties()[$votetype]],
				"id = ".$object->getProperties()['id']
			);
			$this->db->execute();
			$this->db->insert(
				'vote2'.$objecttype,
				['id'.ucfirst($objecttype), 'idUser'],
				[$object->getProperties()['id'], $this->session->get('id')]
			);
			$this->db->execute();
		}

		$url = $this->url->create('question/id/'.$questionId.'#'.$objecttype.'-'.$object->getProperties()['id']);
		$this->response->redirect($url);
	}

	/**
	* Get total number of votes for a user
	*
	* @param int $id, user ID
	*
	* @return int @votes, total number of votes for the user
	*/
	public function getTotalUserVotes($id = null) {

		$tablearray = array('answer', 'comment', 'question');
		$votes = 0;

		foreach ($tablearray as $table) {
			$this->db->select("COUNT(*) AS votes")
				->from('vote2'.$table)
				->where('idUser = ?')
				->execute([$id]);
			$res = $this->db->fetchAll();
			$votes += $res[0]->votes;
		}

		return $votes;
	}

}
