<?php 

class BaseModel extends Model
{
	/* Exercises */

	//Get all exercises
	public function getExercises()
	{
		$query = $this->db->prepare("SELECT * FROM `exercise` WHERE `status` = 1 AND `deleted` = 0");
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	//Get exercise by code
	public function getExerciseByCode($code)
	{
		$query = $this->db->prepare("SELECT * FROM `exercise` WHERE `code` = :code AND `status` = 1 AND `deleted` = 0");
		$query->bindValue(':code', $code, PDO::PARAM_STR);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetch(PDO::FETCH_ASSOC);
	}

	/* Exercise instances */
	//Get exercises on a day
	public function getExercisesByDayId($dayId)
	{
		$query = $this->db->prepare("SELECT ei.*, e.`name` FROM `exercise_instances` ei INNER JOIN `exercise` e ON ei.`exercise_id` = e.`id`  WHERE ei.`day_id` = :dayid AND ei.`status` = 1 AND ei.`deleted` = 0 AND e.`status` = 1 AND e.`deleted` = 0 ORDER BY ei.`order`");
		$query->bindValue(':dayid', $dayId, PDO::PARAM_INT);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	//Get exercise instance by code
	public function getDayExerciseByCode($code)
	{
		$query = $this->db->prepare("SELECT * FROM `exercise_instances` WHERE `code` = :code AND `status` = 1 AND `deleted` = 0");
		$query->bindValue(':code', $code, PDO::PARAM_STR);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetch(PDO::FETCH_ASSOC);
	}

	//Get exercises not selected by day id
	public function getNotSelectedExercisesByDayId($dayId)
	{
		$query = $this->db->prepare("SELECT * FROM `exercise` e WHERE e.`status` = 1 AND e.`deleted` = 0 AND NOT EXISTS ( SELECT null FROM `exercise_instances` ei WHERE e.`id` = ei.`exercise_id` AND  ei.`day_id` = :dayid AND ei.`status` = 1 AND ei.`deleted` = 0 )");
		$query->bindValue(':dayid', $dayId, PDO::PARAM_INT);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	//Get last exercise order for day id
	public function getLastExerciseOrderByDayId($dayId)
	{
		$query = $this->db->prepare("SELECT `order` FROM `exercise_instances` WHERE `day_id` = :dayid AND `status` = 1 AND `deleted` = 0 ORDER BY `order` DESC LIMIT 1");
		$query->bindValue(':dayid', $dayId, PDO::PARAM_STR);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetch(PDO::FETCH_COLUMN, 0);
	}

	//Add exercise to day
	public function addExerciseToDay($data = array())
	{
		$query = $this->db->prepare("INSERT INTO `exercise_instances` (`exercise_id`, `day_id`, `exercise_duration`, `order`, `code`, `status`, `created_ts`) VALUES (:exerciseid, :dayid, :exerciseduration, :order, :code, :status, :createdts)");
		$query->bindValue(':exerciseid', $data['exerciseId'], PDO::PARAM_INT);
		$query->bindValue(':dayid', $data['dayId'], PDO::PARAM_INT);
		$query->bindValue(':exerciseduration', $data['exerciseDuration'], PDO::PARAM_INT);
		$query->bindValue(':order', $data['order'], PDO::PARAM_INT);
		$query->bindValue(':code', $data['code'], PDO::PARAM_STR);
		$query->bindValue(':status', $data['status'], PDO::PARAM_INT);
		$query->bindValue(':createdts', $data['createdTS'], PDO::PARAM_STR);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->rowCount() ? true : false;
	}

	//Delete day exercise
	public function deleteDayExercise($data = array())
	{
		$query = $this->db->prepare("UPDATE `exercise_instances` SET `deleted` = 1, `deleted_ts` = :deletedts WHERE `id` = :id AND `deleted` = 0");
		$query->bindValue(':deletedts', $data['deletedTS'], PDO::PARAM_STR);
		$query->bindValue(':id', $data['id'], PDO::PARAM_INT);
		$query->execute();
		return $query->rowCount() ? true : false;
	}

	//Change 2 days order
	public function changeDayExercisesOrder($data = array())
	{	
		if ($data['order1'] > $data['order2']) {
			$query = $this->db->prepare("UPDATE `exercise_instances` SET `order` = `order` + 1 WHERE `order` < :order1 AND `order` >= :order2 AND `day_id` = :dayid AND `deleted` = 0");
			$query->bindValue(':dayid', $data['dayId'], PDO::PARAM_INT);
			$query->bindValue(':order1', $data['order1'], PDO::PARAM_INT);
			$query->bindValue(':order2', $data['order2'], PDO::PARAM_INT);
			$query->execute();

			$update1 = $query->rowCount();
			if (!$update1) return false;
			else {
				$query = $this->db->prepare("UPDATE `exercise_instances` SET `order` = :order WHERE `id` = :id AND `deleted` = 0");
				$query->bindValue(':id', $data['id1'], PDO::PARAM_INT);
				$query->bindValue(':order', $data['order2'], PDO::PARAM_INT);
				$query->execute();
				$update2 = $query->rowCount();

				if (!$update2) {
					$query = $this->db->prepare("UPDATE `exercise_instances` SET `order` = `order` - 1 WHERE `order` <= :order AND `order` > :order2 AND `id` <> :id, `day_id` = :dayid AND `deleted` = 0");
					$query->bindValue(':dayid', $data['dayId'], PDO::PARAM_INT);
					$query->bindValue(':order1', $data['order1'], PDO::PARAM_INT);
					$query->bindValue(':order2', $data['order2'], PDO::PARAM_INT);
					$query->bindValue(':id', $data['id1'], PDO::PARAM_INT);
					$query->execute();
					return false;
				}
			}
		}else{
			$query = $this->db->prepare("UPDATE `exercise_instances` SET `order` = `order` - 1 WHERE `order` > :order1 AND `order` <= :order2 AND `day_id` = :dayid AND `deleted` = 0");
			$query->bindValue(':dayid', $data['dayId'], PDO::PARAM_INT);
			$query->bindValue(':order1', $data['order1'], PDO::PARAM_INT);
			$query->bindValue(':order2', $data['order2'], PDO::PARAM_INT);
			$query->execute();

			$update1 = $query->rowCount();
			if (!$update1) return false;
			else {
				$query = $this->db->prepare("UPDATE `exercise_instances` SET `order` = :order WHERE `id` = :id AND `deleted` = 0");
				$query->bindValue(':id', $data['id1'], PDO::PARAM_INT);
				$query->bindValue(':order', $data['order2'], PDO::PARAM_INT);
				$query->execute();
				$update2 = $query->rowCount();

				if (!$update2) {
					$query = $this->db->prepare("UPDATE `exercise_instances` SET `order` = `order` + 1 WHERE `order` >= :order AND `order` < :order2 AND `id` <> :id, `day_id` = :dayid AND `deleted` = 0");
					$query->bindValue(':dayid', $data['dayId'], PDO::PARAM_INT);
					$query->bindValue(':order1', $data['order1'], PDO::PARAM_INT);
					$query->bindValue(':order2', $data['order2'], PDO::PARAM_INT);
					$query->bindValue(':id', $data['id1'], PDO::PARAM_INT);
					$query->execute();
					return false;
				}
			}
		}
		

		return true;
		
	}

	/* User */

	//Get user by id
	public function getUser($id)
	{
		$query = $this->db->prepare("SELECT * FROM `user` WHERE `id` = :id AND `status` = 1 AND `deleted` = 0");
		$query->bindValue(':id', $id, PDO::PARAM_INT);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetch(PDO::FETCH_ASSOC);
	}

	//Get users
	public function getUsers()
	{
		$query = $this->db->prepare("SELECT * FROM `user` WHERE `status` = 1 AND `deleted` = 0");
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	//Get user by code
	public function getUserByCode($code)
	{
		$query = $this->db->prepare("SELECT * FROM `user` WHERE `code` = :code AND `status` = 1 AND `deleted` = 0");
		$query->bindValue(':code', $code, PDO::PARAM_STR);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetch(PDO::FETCH_ASSOC);
	}

	//Get user by email
	public function getUserByEmail($email)
	{
		$query = $this->db->prepare("SELECT * FROM `user` WHERE `email` = :email AND `deleted` = 0");
		$query->bindValue(':email', $email, PDO::PARAM_STR);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetch(PDO::FETCH_ASSOC);
	}

	//Add user
	public function addUser($data = array())
	{
		$query = $this->db->prepare("INSERT INTO `user` (`first_name`, `last_name`, `email`, `code`, `status`, `created_ts`) VALUES (:firstname, :lastname, :email, :code, :status, :createdts)");
		$query->bindValue(':firstname', $data['firstName'], PDO::PARAM_STR);
		$query->bindValue(':lastname', $data['lastName'], PDO::PARAM_STR);
		$query->bindValue(':email', $data['email'], PDO::PARAM_STR);
		$query->bindValue(':code', $data['code'], PDO::PARAM_STR);
		$query->bindValue(':status', $data['status'], PDO::PARAM_INT);
		$query->bindValue(':createdts', $data['createdTS'], PDO::PARAM_STR);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->rowCount() ? true : false;
	}

	//Edit user
	public function editUser($data = array())
	{
		$query = $this->db->prepare("UPDATE `user` SET `first_name` = :firstname, `last_name` = :lastname, `email` = :email WHERE `id` = :id AND `deleted` = 0");
		$query->bindValue(':firstname', $data['firstName'], PDO::PARAM_STR);
		$query->bindValue(':lastname', $data['lastName'], PDO::PARAM_STR);
		$query->bindValue(':email', $data['email'], PDO::PARAM_STR);
		$query->bindValue(':id', $data['id'], PDO::PARAM_INT);
		$query->execute();
		return $query->rowCount() ? true : false;
	}

	//Delete user
	public function deleteUser($data = array())
	{
		$query = $this->db->prepare("UPDATE `user` SET `deleted` = 1, `deleted_ts` = :deletedts WHERE `id` = :id AND `deleted` = 0");
		$query->bindValue(':deletedts', $data['deletedTS'], PDO::PARAM_STR);
		$query->bindValue(':id', $data['id'], PDO::PARAM_INT);
		$query->execute();
		return $query->rowCount() ? true : false;
	}


	/* Plan */

	//Get plan by id
	public function getPlan($id)
	{
		$query = $this->db->prepare("SELECT * FROM `plan` WHERE `id` = :id AND `status` = 1 AND `deleted` = 0");
		$query->bindValue(':id', $id, PDO::PARAM_INT);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetch(PDO::FETCH_ASSOC);
	}

	//Get plans
	public function getPlans()
	{
		$query = $this->db->prepare("SELECT * FROM `plan` WHERE `status` = 1 AND `deleted` = 0");
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	//Get plan by code
	public function getPlanByCode($code)
	{
		$query = $this->db->prepare("SELECT * FROM `plan` WHERE `code` = :code AND `status` = 1 AND `deleted` = 0");
		$query->bindValue(':code', $code, PDO::PARAM_STR);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetch(PDO::FETCH_ASSOC);
	}

	//Add plan
	public function addPlan($data = array())
	{
		$query = $this->db->prepare("INSERT INTO `plan` (`plan_name`, `plan_description`, `plan_difficulty`, `code`, `status`, `created_ts`) VALUES (:planname, :plandesc, :plandiff, :code, :status, :createdts)");
		$query->bindValue(':planname', $data['name'], PDO::PARAM_STR);
		$query->bindValue(':plandesc', $data['description'], PDO::PARAM_STR);
		$query->bindValue(':plandiff', $data['difficulty'], PDO::PARAM_STR);
		$query->bindValue(':code', $data['code'], PDO::PARAM_STR);
		$query->bindValue(':status', $data['status'], PDO::PARAM_INT);
		$query->bindValue(':createdts', $data['createdTS'], PDO::PARAM_STR);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->rowCount() ? true : false;
	}

	//Edit plan
	public function editPlan($data = array())
	{
		$query = $this->db->prepare("UPDATE `plan` SET `plan_name` = :planname, `plan_description` = :plandesc, `plan_difficulty` = :plandiff WHERE `id` = :id AND `deleted` = 0");
		$query->bindValue(':planname', $data['name'], PDO::PARAM_STR);
		$query->bindValue(':plandesc', $data['description'], PDO::PARAM_STR);
		$query->bindValue(':plandiff', $data['difficulty'], PDO::PARAM_STR);
		$query->bindValue(':id', $data['id'], PDO::PARAM_INT);
		$query->execute();
		return $query->rowCount() ? true : false;
	}

	//Delete plan
	public function deletePlan($data = array())
	{
		$query = $this->db->prepare("UPDATE `plan` SET `deleted` = 1, `deleted_ts` = :deletedts WHERE `id` = :id AND `deleted` = 0");
		$query->bindValue(':deletedts', $data['deletedTS'], PDO::PARAM_STR);
		$query->bindValue(':id', $data['id'], PDO::PARAM_INT);
		$query->execute();
		return $query->rowCount() ? true : false;
	}

	/* User - Plan */

	//Get user plan by id
	public function getUserPlan($id)
	{
		$query = $this->db->prepare("SELECT * FROM `user_plans` WHERE `id` = :id AND `status` = 1 AND `deleted` = 0");
		$query->bindValue(':id', $id, PDO::PARAM_INT);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	//Get user plans
	public function getUserPlans()
	{
		$query = $this->db->prepare("SELECT * FROM `user_plans` WHERE `status` = 1 AND `deleted` = 0");
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	//Get user plan by code
	public function getUserPlanByCode($code)
	{
		$query = $this->db->prepare("SELECT * FROM `user_plans` WHERE `code` = :code AND `status` = 1 AND `deleted` = 0");
		$query->bindValue(':code', $code, PDO::PARAM_STR);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetch(PDO::FETCH_ASSOC);
	}

	//Get user plans by user id
	public function getUserPlansByUserId($userId)
	{
		$query = $this->db->prepare("SELECT p.*, up.`code` as up_code FROM `user_plans` up join `plan` p ON up.`plan_id` = p.`id` WHERE up.`user_id` = :userid AND up.`status` = 1 AND up.`deleted` = 0 AND p.`status` = 1 AND p.`deleted` = 0");
		$query->bindValue(':userid', $userId, PDO::PARAM_INT);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	//Get user plans by plan id
	public function getUsersByPlanId($planId)
	{
		$query = $this->db->prepare("SELECT u.* FROM `user_plans` up INNER JOIN `user` u ON up.`user_id` = u.`id` WHERE up.`plan_id` = :planid AND up.`status` = 1 AND up.`deleted` = 0 AND u.`status` = 1 AND u.`deleted` = 0;");
		$query->bindValue(':planid', $planId, PDO::PARAM_INT);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	//Get plans not selected by user id
	public function getNotSelectedPlansByUserId($userId)
	{
		$query = $this->db->prepare("SELECT * FROM `plan` p WHERE p.`status` = 1 AND p.`deleted` = 0 AND NOT EXISTS ( SELECT null FROM `user_plans` up WHERE p.`id` = up.`plan_id` AND  up.`user_id` = :userid AND up.`status` = 1 AND up.`deleted` = 0 )");
		$query->bindValue(':userid', $userId, PDO::PARAM_INT);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	//Add plan to user
	public function addPlanToUser($data = array())
	{
		$query = $this->db->prepare("INSERT INTO `user_plans` (`user_id`, `plan_id`, `code`, `status`, `created_ts`) VALUES (:userid, :planid, :code, :status, :createdts)");
		$query->bindValue(':userid', $data['userId'], PDO::PARAM_INT);
		$query->bindValue(':planid', $data['planId'], PDO::PARAM_INT);
		$query->bindValue(':code', $data['code'], PDO::PARAM_STR);
		$query->bindValue(':status', $data['status'], PDO::PARAM_INT);
		$query->bindValue(':createdts', $data['createdTS'], PDO::PARAM_STR);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->rowCount() ? true : false;
	}

	//Remove plan from user
	public function deletePlanFromUser($data = array())
	{
		$query = $this->db->prepare("UPDATE `user_plans` SET `deleted` = 1, `deleted_ts` = :deletedts WHERE `id` = :id AND `deleted` = 0");
		$query->bindValue(':deletedts', $data['deletedTS'], PDO::PARAM_STR);
		$query->bindValue(':id', $data['Id'], PDO::PARAM_INT);
		$query->execute();
		return $query->rowCount() ? true : false;
	}


	/* Plan days */

	//Get all plan days
	public function getPlanDays()
	{
		$query = $this->db->prepare("SELECT * FROM `plan_days` WHERE `status` = 1 AND `deleted` = 0");
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	//Get plan day by code
	public function getPlanDayByCode($code)
	{
		$query = $this->db->prepare("SELECT * FROM `plan_days` WHERE `code` = :code AND `status` = 1 AND `deleted` = 0");
		$query->bindValue(':code', $code, PDO::PARAM_STR);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetch(PDO::FETCH_ASSOC);
	}

	//Get plan days for plan id
	public function getPlanDaysByPlanId($planId)
	{
		$query = $this->db->prepare("SELECT * FROM `plan_days` WHERE `plan_id` = :planid AND `status` = 1 AND `deleted` = 0 ORDER BY `order`");
		$query->bindValue(':planid', $planId, PDO::PARAM_STR);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	//Get plan days for plan id
	public function getLastDayOrderByPlanId($planId)
	{
		$query = $this->db->prepare("SELECT `order` FROM `plan_days` WHERE `plan_id` = :planid AND `status` = 1 AND `deleted` = 0 ORDER BY `order` DESC LIMIT 1");
		$query->bindValue(':planid', $planId, PDO::PARAM_STR);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->fetch(PDO::FETCH_COLUMN, 0);
	}

	//Add plan day
	public function addPlanDay($data = array())
	{
		$query = $this->db->prepare("INSERT INTO `plan_days` (`plan_id`, `day_name`, `order`, `code`, `status`, `created_ts`) VALUES (:planid, :dayname, :order, :code, :status, :createdts)");
		$query->bindValue(':planid', $data['planId'], PDO::PARAM_INT);
		$query->bindValue(':dayname', $data['dayName'], PDO::PARAM_STR);
		$query->bindValue(':order', $data['order'], PDO::PARAM_INT);
		$query->bindValue(':code', $data['code'], PDO::PARAM_STR);
		$query->bindValue(':status', $data['status'], PDO::PARAM_INT);
		$query->bindValue(':createdts', $data['createdTS'], PDO::PARAM_STR);
		$query->execute();

		$queryError = $query->errorInfo();
		if ($queryError[0] != '00000')
		{
			if (DEV) {
				var_dump($queryError);
				exit();
			}
			return false;
		}

		return $query->rowCount() ? true : false;
	}

	//Edit plan day
	public function editPlanDay($data = array())
	{
		$query = $this->db->prepare("UPDATE `plan_days` SET `day_name` = :dayname WHERE `id` = :id AND `deleted` = 0");
		$query->bindValue(':dayname', $data['name'], PDO::PARAM_STR);
		$query->bindValue(':id', $data['id'], PDO::PARAM_INT);
		$query->execute();
		return $query->rowCount() ? true : false;
	}

	//Change 2 days order
	public function changePlanDaysOrder($data = array())
	{	
		if ($data['order1'] > $data['order2']) {
			$query = $this->db->prepare("UPDATE `plan_days` SET `order` = `order` + 1 WHERE `order` < :order1 AND `order` >= :order2 AND  `plan_id` = :planid AND `deleted` = 0");
			$query->bindValue(':planid', $data['planId'], PDO::PARAM_INT);
			$query->bindValue(':order1', $data['order1'], PDO::PARAM_INT);
			$query->bindValue(':order2', $data['order2'], PDO::PARAM_INT);
			$query->execute();

			$update1 = $query->rowCount();
			if (!$update1) return false;
			else {
				$query = $this->db->prepare("UPDATE `plan_days` SET `order` = :order WHERE `id` = :id AND `deleted` = 0");
				$query->bindValue(':id', $data['id1'], PDO::PARAM_INT);
				$query->bindValue(':order', $data['order2'], PDO::PARAM_INT);
				$query->execute();
				$update2 = $query->rowCount();

				if (!$update2) {
					$query = $this->db->prepare("UPDATE `plan_days` SET `order` = `order` 1 1 WHERE `order` <= :order AND `order` > :order2 AND `id` <> :id, `plan_id` = :planid AND `deleted` = 0");
					$query->bindValue(':planid', $data['planId'], PDO::PARAM_INT);
					$query->bindValue(':order1', $data['order1'], PDO::PARAM_INT);
					$query->bindValue(':order2', $data['order2'], PDO::PARAM_INT);
					$query->bindValue(':id', $data['id1'], PDO::PARAM_INT);
					$query->execute();
					return false;
				}
			}
		}else{
			$query = $this->db->prepare("UPDATE `plan_days` SET `order` = `order` - 1 WHERE `order` > :order1 AND `order` <= :order2 AND  `plan_id` = :planid AND `deleted` = 0");
			$query->bindValue(':planid', $data['planId'], PDO::PARAM_INT);
			$query->bindValue(':order1', $data['order1'], PDO::PARAM_INT);
			$query->bindValue(':order2', $data['order2'], PDO::PARAM_INT);
			$query->execute();

			$update1 = $query->rowCount();
			if (!$update1) return false;
			else {
				$query = $this->db->prepare("UPDATE `plan_days` SET `order` = :order WHERE `id` = :id AND `deleted` = 0");
				$query->bindValue(':id', $data['id1'], PDO::PARAM_INT);
				$query->bindValue(':order', $data['order2'], PDO::PARAM_INT);
				$query->execute();
				$update2 = $query->rowCount();

				if (!$update2) {
					$query = $this->db->prepare("UPDATE `plan_days` SET `order` = `order` + 1 WHERE `order` >= :order AND `order` < :order2 AND `id` <> :id, `plan_id` = :planid AND `deleted` = 0");
					$query->bindValue(':planid', $data['planId'], PDO::PARAM_INT);
					$query->bindValue(':order1', $data['order1'], PDO::PARAM_INT);
					$query->bindValue(':order2', $data['order2'], PDO::PARAM_INT);
					$query->bindValue(':id', $data['id1'], PDO::PARAM_INT);
					$query->execute();
					return false;
				}
			}
		}
		
		return true;
		
	}

	//Delete plan day
	public function deletePlanDay($data = array())
	{
		$query = $this->db->prepare("UPDATE `plan_days` SET `deleted` = 1, `deleted_ts` = :deletedts WHERE `id` = :id AND `deleted` = 0");
		$query->bindValue(':deletedts', $data['deletedTS'], PDO::PARAM_STR);
		$query->bindValue(':id', $data['id'], PDO::PARAM_INT);
		$query->execute();
		return $query->rowCount() ? true : false;
	}

}

?>