<?php
    /**************************************************************************/
    /***********************ASSIGNMENTS****************************************/
    /**************************************************************************/
    // get all todos
    $app->get('/assignments', function ($request, $response, $args) {
         $sth = $this->db->prepare('select SHA1(a.id) as id, c.name, a.`schedule_`, a.service_description,c.latitude,c.longitude, CASE WHEN a.state = 0 THEN "Pendiente" WHEN a.state = 1 THEN "Atendido" ELSE "Observado" END as state_des from company c inner join assignment a on c.id=a.company_id where c.state=1 order by a.id');
        $sth->execute();
        $todos = $sth->fetchAll();
        $todos = (!$todos) ? []:$todos;
        $rpta = [];
        $rpta['status'] = 'ok';
        $rpta['totalResults'] = count($todos);
        $rpta['data'] = $todos;
        return $this->response->withJson($rpta);
    });
 
    // Retrieve todo with id 
    $app->get('/assignments/[{id}]', function ($request, $response, $args) {
         $sth = $this->db->prepare('select SHA1(a.id) as id, c.name, a.`schedule_` as schedule, a.service_description as serviceDescription,c.latitude,c.longitude, CASE WHEN a.state = 0 THEN "Pendiente" WHEN a.state = 1 THEN "Atendido" ELSE "Observado" END as stateDescription,a.state as stateId from company c inner join assignment a on c.id=a.company_id where c.state=1 and a.user_id=:id');
        $sth->bindParam("id", $args['id']);
        $sth->execute();
        $todos = $sth->fetchObject();
        $todos = (!$todos) ? []:$todos;
        $rpta = [];
        $rpta['status'] = 'ok';
        $rpta['totalResults'] = count($todos);
        $rpta['data'] = $todos;
        
        return $this->response->withJson($rpta);
    });
 
    // Add a new todo
    $app->post('/assignments', function ($request, $response) {
        $input = $request->getParsedBody();
        $sql = "INSERT INTO assignment (user_id, company_id,schedule_,service_description) VALUES (:user_id,:company_id,:service_description,:schedule)";
         $sth = $this->db->prepare($sql);
        $sth->bindParam("user_id",              $input['user_id']);
        $sth->bindParam("company_id",           $input['company_id']);
        $sth->bindParam("service_description",  $input['service_description']);
        $sth->bindParam("schedule",             $input['schedule']);
        $sth->execute();
        
        $input['status'] = 'ok';
        $input['id'] = $this->db->lastInsertId();
        return $this->response->withJson($input);
    });
        
    /*
    // DELETE a todo with given id
    $app->delete('/todo/[{id}]', function ($request, $response, $args) {
         $sth = $this->db->prepare("DELETE FROM tasks WHERE id=:id");
        $sth->bindParam("id", $args['id']);
        $sth->execute();
        $todos = $sth->fetchAll();
        return $this->response->withJson($todos);
    });
 
    // Update todo with given id
    $app->put('/todo/[{id}]', function ($request, $response, $args) {
        $input = $request->getParsedBody();
        $sql = "UPDATE tasks SET task=:task WHERE id=:id";
         $sth = $this->db->prepare($sql);
        $sth->bindParam("id", $args['id']);
        $sth->bindParam("task", $input['task']);
        $sth->execute();
        $input['id'] = $args['id'];
        return $this->response->withJson($input);
    });
    
     */
     
    /**************************************************************************/
    /***********************ASSIGNMENTS****************************************/
    /**************************************************************************/